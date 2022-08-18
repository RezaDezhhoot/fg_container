<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\LicenseEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendLicenseRequest;
use App\Models\Container;
use App\Models\ContainerHistory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use JetBrains\PhpStorm\ArrayShape;

class SendLicenseController extends Controller
{
    private ?SendLicenseRequest $sendLicenseRequest;
    private ?int $product_id = 0 , $order_id = 0;

    public function __invoke(SendLicenseRequest $request): Response|Application|ResponseFactory
    {
        $this->sendLicenseRequest = $request;
        if (app()->environment('production'))
            if ($this->rateLimiter(phone:$request['phone']))
                return response([
                    'data' => [
                        'message' => 'زیادی تلاش کردی لطفا پس از مدتی دوباره سعی کنید.'
                    ],
                    'status' => 'error'
                ],429);

        $ValidCode = $this->ValidCode($request['code']);
        if ($ValidCode['status'] == 200) {
            try {
                DB::beginTransaction();
                $licenses = Container::isNotUsed($this->product_id)
                    ->with('enter_form')
                    ->take($request['count']);

                $codes = $licenses->get();
                $enter_price = $codes->map(function ($item){
                    return $item['enter_form']['enter_price'];
                })->sum()/$request['count'];
                $history = ContainerHistory::create([
                    'action' => LicenseEnum::EXIT,
                    'count' => $request['count'],
                    'enter_price' => $enter_price,
                    'exit_price' => $request['exit_price'],
                    'order_id' => $request['base_id'],
                    'user_id' => null,
                    'description' => 'درخواست از طرف api',
                    'product_title' => $request['product_title'],
                    'product_id' => $this->product_id,
                ]);

                $codes = implode(",",array_value_recursive('license',$codes->toArray()));
                $licenses->update([
                    'status' => LicenseEnum::IS_USED,
                    'form_exit_id' => $history->id
                ]);
                $this->insertRequest([
                    'status' => 200,
                    'message' => $codes
                ]);
                DB::commit();
                return \response([
                    'data' => [
                        'licenses' => base64_encode($codes)
                    ],'status' => 'success'
                ],200);
            } catch (\Exception $e) {
                DB::rollBack();
                $this->insertRequest([
                    'status' => 500,
                    'message' => $e->getMessage()
                ]);
                return \response([
                    'data' => [
                        'message' => $e->getMessage()
                    ],
                    'status' => 'error'
                ],500);
            }

        } else {
            return \response([
                'data' => [
                    'message' => $ValidCode['message']
                ],
                'status' => 'error'
            ],$ValidCode['status']);
        }
    }

    #[ArrayShape(['status' => "int", 'message' => "string"])]
    private function ValidCode($code): array
    {
        $salt = '12$#dAe)O@c$5*2Cn#g/sV^55!wX';
        try {
            $decrypt = explode('-',base64_decode($code));
            if (
                !\App\Models\Request::where('code',$code)->exists()
            ) {
                if (
                    $decrypt[1] && is_numeric($decrypt[1]) && sizeof($decrypt) == 4 && $decrypt[0] == md5($salt.$decrypt[1].$salt)
                ) {
                    if (Container::where('product_id',$decrypt[1])->exists()) {
                        if (Container::isNotUsed($decrypt[1])->take($this->sendLicenseRequest['count'])->count() >= $this->sendLicenseRequest['count']) {
                            $this->product_id = $decrypt[1];
                            return ['status' => 200 ,'message' => 'درخواست معتبر'];
                        } else {
                            $data = ['status'=>404,'message'=>'موجودی به پایان رسیده است'];
                            $this->insertRequest($data);
                            return $data;
                        }
                    } else {
                        $data=  ['status'=>404,'message'=>'product not found'];
                        $this->insertRequest($data);
                        return $data;
                    }
                } else {
                    $data = ['status'=>404,'message'=>'product key required'];
                    $this->insertRequest($data);
                    return $data;
                }
            } else {
                $data = ['status'=>404,'message'=>'invalid code'];
                $this->insertRequest($data);
                return $data;
            }
        } catch (\Exception $e) {
            $data = ['status'=>500,'message' =>$e->getMessage()];
            $this->insertRequest($data);
            return $data;
        }
    }

    private function insertRequest($data)
    {
        \App\Models\Request::create([
            'code' => $this->sendLicenseRequest['code'],
            'phone' => $this->sendLicenseRequest['phone'],
            'order_id' => $this->sendLicenseRequest['base_id'],
            'ip' => $this->sendLicenseRequest->ip(),
            'status' => $data['status'] ?? '',
            'sms' => $data['message'] ?? '',
            'http_referer' => $this->sendLicenseRequest->headers->get('referer') ?? '1',
        ]);
    }

    private function rateLimiter($phone): bool
    {
        $rateKey = 'verify-attempt:' . $phone;
        if (RateLimiter::tooManyAttempts($rateKey, 10)) {
            return true;
        }
        RateLimiter::hit($rateKey, 3 * 60 * 60);
        return false;
    }
}

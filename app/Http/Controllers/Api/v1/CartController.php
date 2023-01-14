<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\CartEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendCartRequest;
use App\Models\Cart;
use App\Models\Container;
use App\Models\Panel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;

class CartController extends Controller
{
    private ?SendCartRequest $sendCartRequest;
    private $category_id = 0;

    public function __invoke(SendCartRequest $request): Response|Application|ResponseFactory
    {
        $this->sendCartRequest = $request;
//        if (app()->environment('production'))
//            if ($this->rateLimiter(phone: $request['phone']))
//                return response([
//                    'data' => [
//                        'message' => 'زیادی تلاش کردی لطفا پس از مدتی دوباره سعی کنید.'
//                    ],
//                    'status' => 'error'
//                ], 429);

        if (!$request->filled('category_id')) {
            $cart = Cart::query()->firstOrFail($request->get('cart_id'));
            $cart->update([
                'is_charged' => true
            ]);
            return \response([
                'data' => [
                    'message' => 'ok'
                ],
                'status' => 'success'
            ], 200);
        }

        if (!\App\Models\Request::where('code', $request['code'])->exists() || app()->environment('local')) {
            $ValidCode = $this->ValidCode($request['code']);
            if ($ValidCode['status'] == 200) {
                return $this->send($request);
            } else {
                return \response([
                    'data' => [
                        'message' => $ValidCode['message']
                    ],
                    'status' => 'error'
                ], $ValidCode['status']);
            }
        } else {
            $data = ['status' => 422, 'message' => 'invalid code'];
            $this->insertRequest($data);
            return \response([
                'data' => [
                    'message' => $data['message']
                ],
                'status' => 'error'
            ], $data['status']);
        }
    }

    private function ValidCode($code): array
    {
        $salt = config('site.salt');
        try {
            $decrypt = explode('-', base64_decode($code));
            if (
                ( $decrypt[1] && is_numeric($decrypt[1]) && sizeof($decrypt) == 4 && $decrypt[0] == md5($salt . $decrypt[1] . $salt)) ||
                app()->environment('local')
            ) {
                if (Cart::query()->ready()->whereHas('category',fn($q) => $q->where('id',$decrypt[1]))->exists()) {
                    $this->category_id = $decrypt[1];
                    return ['status' => 200, 'message' => 'درخواست معتبر'];
                } else {
                    $data =  ['status' => 404, 'message' => 'product not found'];
                    $this->insertRequest($data);
                    return $data;
                }
            } else {
                $data = ['status' => 422, 'message' => 'product key required'];
                $this->insertRequest($data);
                return $data;
            }
        } catch (\Exception $e) {
            $data = ['status' => 500, 'message' => $e->getMessage()];
            $this->insertRequest($data);
            return $data;
        }
    }

    private function insertRequest($data)
    {
        \App\Models\Request::create([
            'code' => $this->sendCartRequest['code'],
            'phone' => $this->sendCartRequest['phone'],
            'order_id' => $this->sendCartRequest['base_id'],
            'ip' => $this->sendCartRequest->ip(),
            'status' => $data['status'] ?? '',
            'sms' => $data['message'] ?? '',
            'http_referer' => $this->sendCartRequest->headers->get('referer') ?? '1',
        ]);
    }

    private function rateLimiter($phone): bool
    {
        $rateKey = 'verify-attempt-cart:' . $phone;
        if (RateLimiter::tooManyAttempts($rateKey, 10)) {
            return true;
        }
        RateLimiter::hit($rateKey, 3 * 60 * 60);
        return false;
    }

    private function send($request)
    {
        try {
            DB::beginTransaction();
            $cart = Cart::query()->whereHas('category',function ($q){
                return $q->where('id',$this->category_id);
            })->ready()->take(1)->first();
            $cart->update([
                'status' => CartEnum::USED,
                'panel_id' => Panel::query()->where('phone',$request->get('phone'))->first()->id
            ]);
            DB::commit();
            return \response([
                'data' => [
                    'message' => 'ok'
                ], 'status' => 'success'
            ], 200);
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
            ], 500);
        }
    }
}

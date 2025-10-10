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

        $cart = Cart::query()->create([
            'panel_id' => $request->panel_id,
            'status' => CartEnum::USED,
        ]);
        return $this->send($cart);
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

    private function send($cart)
    {
        try {
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

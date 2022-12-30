<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\Panel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function __invoke(AuthRequest $request)
    {
        if (app()->environment('production')) {
            if ($this->rateLimiter(username: $request['username']))
                return response([
                    'data' => [
                        'message' => 'زیادی تلاش کردی لطفا پس از مدتی دوباره سعی کنید.'
                    ],
                    'status' => 'error'
                ], 429);
        }

        $panel = Panel::query()->where('username',$request->get('username'))->firstOrFail();

        if (Hash::check($request->get('password'),$panel->password)) {
            return response([
                'data' => [
                    'message' => 'ok'
                ]
            ],200);
        }

        return response([
            'data' => [
                'message' => 'error'
            ]
        ],401);
    }

    private function rateLimiter($username): bool
    {
        $rateKey = 'verify-attempt-auth:' . $username;
        if (RateLimiter::tooManyAttempts($rateKey, 10)) {
            return true;
        }
        RateLimiter::hit($rateKey, 3 * 60 * 60);
        return false;
    }
}

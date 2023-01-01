<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\PanelEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\v1\AuthResource;
use App\Models\Panel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function __invoke(AuthRequest $request)
    {
        $rateKey = false;
        if (app()->environment('production')) {
            if ($rateKey = $this->rateLimiter(username: $request['username']))
                return response([
                    'data' => [
                        'message' => 'زیادی تلاش کردی لطفا پس از مدتی دوباره سعی کنید.'
                    ],
                    'status' => 'error'
                ], 429);
        }

        $panel = Panel::query()->where('username',$request->get('username'))->firstOrFail();

        if (Hash::check($request->get('password'),$panel->password) && $panel->status == PanelEnum::ACTIVE) {
            $panel->token = Crypt::encrypt(config('site.salt').microtime().$panel->username.$panel->phone);
            $panel->save();
            RateLimiter::clear($rateKey);
            return response()->json(
                ['data' => AuthResource::make($panel)]
            );
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
        if (RateLimiter::tooManyAttempts($rateKey, 35)) {
            return $rateKey;
        }
        RateLimiter::hit($rateKey, 3 * 60 * 60);
        return false;
    }
}

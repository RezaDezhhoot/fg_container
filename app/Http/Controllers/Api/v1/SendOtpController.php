<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendOtpRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Sends\Facades\SendMessages;

class SendOtpController extends Controller
{
    function __invoke(SendOtpRequest $sendOtpRequest)
    {
        if (app()->environment('production'))
            if ($this->rateLimiter(phone: $sendOtpRequest['phone']))
                return response([
                    'data' => [
                        'message' => 'زیادی تلاش کردی لطفا پس از مدتی دوباره سعی کنید.'
                    ],
                    'status' => 'error'
                ], 429);
        try {
            $code = mt_rand(1234, 9999);
            $user = User::where('phone', $sendOtpRequest['phone'])->first();
            $user->otp = $code;
            $user->save();
            SendMessages::sendCode($code, $sendOtpRequest['phone']);
            return response([
                'data' => [
                    'message' => 'sms has been sent'
                ], 'status' => 'success'
            ],200);
        } catch (\Exception $th) {
            return response([
                'data' => [
                    'message' => 'error'
                ], 'status' => 'error'
            ],500);
        }
    }

    private function rateLimiter($phone): bool
    {
        $rateKey = 'verify-attempt-otp:' . $phone;
        if (RateLimiter::tooManyAttempts($rateKey, 30)) {
            return true;
        }
        RateLimiter::hit($rateKey, 3 * 60 * 60);
        return false;
    }
}

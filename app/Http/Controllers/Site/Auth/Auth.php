<?php

namespace App\Http\Controllers\Site\Auth;

use App\Http\Controllers\Sends\Facades\SendMessages;
use Livewire\Component;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Rules\ReCaptchaRule;


class Auth extends Component
{
    public $recaptcha;
    public $phone , $password , $name, $otp ;
    public $logo , $authImage , $sms = false , $data = [] ;
    public $passwordLabel = 'رمز عبور';
    public $email , $sent = false , $user_name;

    public function render()
    {
        return view('site.auth.auth')->extends('site.auth.app');
    }

    public function login()
    {

        $this->validate([
            'phone' => ['required','string','max:250'],
            'password' => ['required','string','max:250'],
            'recaptcha' => ['required', new ReCaptchaRule],
        ],[],[
            'phone' => 'شماره همراه یا نام کاربری',
            'password' => 'رمز عبور',
            'recaptcha' => 'فیلد امنیتی'
        ]);

        $user = User::where('phone', $this->phone)->orWhere('email',$this->phone)->first();
        if (!is_null($user)) {
            if (Hash::check($this->password, $user->password) || ( !is_null($user->otp) && Hash::check($this->password, $user->otp) && $this->sms === true)) {
                \Illuminate\Support\Facades\Auth::login($user,true);
                request()->session()->regenerate();
                $user->otp = null;
                $user->save();

                return redirect()->intended(route('dashboard'));
            } else
                return $this->addError('password','گذواژه یا شماره همراه اشتباه می باشد.');
        } else
            return $this->addError('password','گذواژه یا شماره همراه اشتباه می باشد.');
    }


    private function resetInputs()
    {
        $this->reset(['phone', 'password']);
    }

    public function sendSMS()
    {
        $rateKey = 'verify-attempt:' . $this->phone . '|' . request()->ip();
        if (RateLimiter::tooManyAttempts($rateKey, 10)) {
            $this->resetInputs();
            return $this->addError('phone', 'زیادی تلاش کردید. لطفا پس از مدتی دوباره تلاش کنید.');
        }
        RateLimiter::hit($rateKey, 3 * 60 * 60);
        $this->validate([
            'phone' => ['required','string'],
        ],[],[
            'phone' => 'شماره همراه یا نام کاربری',
        ]);
        $this->sms = true;
        $rand = rand(12345,999998);
        $user = User::where('phone', $this->phone)->first();
        if (!is_null($user)) {
            $user->otp = $rand;
            $this->passwordLabel = 'رمز ارسال شده را وارد نماید';
            $user->save();
            SendMessages::sendCode($rand,$user->phone);
            $this->sent = true;
        } else
            return $this->addError('phone','این شماره همراه یافت نشد.');
    }
}

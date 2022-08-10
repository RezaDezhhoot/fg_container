<form class="form" wire:submit.prevent="login" method="post" id="kt_login_signin_form">
    <div class="form-group mb-5">
        <input wire:model.defer="phone" style="text-align: right" class="form-control h-auto form-control-solid py-4 px-8" type="text" placeholder="موبایل" name="username" autocomplete="off" />
        @error('phone')
        <span class="text-danger">
                            {{ $message }}
                        </span>
        @enderror
    </div>
    <div class="form-group mb-5">
        <input wire:model.defer="password" style="text-align: right" class="form-control h-auto form-control-solid py-4 px-8" type="password" placeholder="پسورد" name="password" />
        @error('password')
        <span class="text-danger">
                            {{ $message }}
                        </span>
        @enderror
        <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
            @if(!$sent)
            <a style="cursor: pointer" wire:click="sendSMS()" id="kt_login_forgot" class="text-muted text-hover-primary">ارسال رمز یکبار مصرف</a>
            @else
                <a  class="text-muted text-success">ارسال شد</a>
            @endif
        </div>
    </div>
    <div class="form-group mb-5">
        <div class="g-recaptcha d-inline-block" data-sitekey="{{ config('services.recaptcha.site_key') }}"
             data-callback="reCaptchaCallback" wire:ignore></div>
        @error('recaptcha')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
    <button type="submit" id="kt_login_signin_submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4">ورود</button>
</form>
@push('scripts')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script>
        function reCaptchaCallback(response) {
            @this.set('recaptcha', response);
        }


        Livewire.on('resetReCaptcha', () => {
            grecaptcha.reset();
        });
    </script>
@endpush

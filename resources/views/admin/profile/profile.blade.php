<div>
    @section('title','پروفایل')
    <x-admin.form-control  title="{{ $header }}"/>
    <div class="card card-custom gutter-b example example-compact">
        <div class="card-header">
            <h3 class="card-title">{{ $header }}</h3>
        </div>
        <x-admin.forms.validation-errors/>
        <div class="card-body">
            <div class="row">
                <x-admin.forms.input with="6" type="text" id="name" label="نام*" wire:model.defer="name"/>
                <x-admin.forms.input with="6" type="text" id="phone" label="شماره همراه*" wire:model.defer="phone"/>
                <x-admin.forms.input with="6" type="text" id="email" label="ایمیل*" wire:model.defer="email"/>
                <x-admin.forms.input with="6" type="password" id="pass_word"  label="رمزعبور" wire:model.defer="password"/>
            </div>
            <x-admin.form-section label="نقش های من">
                <ul>
                    @foreach($user->roles as $item)
                        <il>
                            <h5>{{$item->name}}</h5>
                            <hr>
                        </il>
                    @endforeach
                </ul>
            </x-admin.form-section>
        </div>
    </div>
</div>

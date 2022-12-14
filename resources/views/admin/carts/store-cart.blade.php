<div>
    @section('title','کارت')
    <x-admin.form-control deleteAble="true" deleteContent="حذف کارت" mode="{{$mode}}" title="کارت "/>
    <div class="card card-custom gutter-b example example-compact">
        <div class="card-header">
            <h3 class="card-title">{{ $header }}</h3>
        </div>
        <x-admin.forms.validation-errors/>
        <div class="card-body">
            <x-admin.forms.input type="text" id="cart_number" label="شماره کارت*" wire:model.defer="cart_number"/>
            <x-admin.forms.input type="text" id="cart_cvv2" label="cvv2*" wire:model.defer="cart_cvv2"/>
            <x-admin.forms.lfm-standalone id="image" label="تصویر*" :file="$image" type="image" required="true" wire:model="image"/>
            <x-admin.forms.input type="text" id="expire" label="تاریخ انقضا*" wire:model.defer="expire"/>
            <x-admin.forms.dropdown :data="$data['category']" id="category" label="دسته بندی*" wire:model.defer="category"/>
            <x-admin.forms.dropdown :data="$data['type']" id="type" label="نوع *" wire:model.defer="type"/>
            <x-admin.forms.dropdown :data="$data['status']" id="status" label="وضعیت *" wire:model.defer="status"/>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        function deleteItem(id) {
            Swal.fire({
                title: 'حذف کارت !',
                text: 'آیا از حذف این کارت اطمینان دارید؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'خیر',
                confirmButtonText: 'بله'
            }).then((result) => {
                if (result.value) {
                @this.call('deleteItem', id)
                }
            })
        }
    </script>
@endpush

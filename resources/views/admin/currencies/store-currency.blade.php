<div>
    @section('title','واحد پول')
    <x-admin.form-control deleteAble="true" deleteContent="حذف واحد پول" mode="{{$mode}}" title="واحد پول"/>
    <div class="card card-custom gutter-b example example-compact">
        <div class="card-header">
            <h3 class="card-title">{{ $header }}</h3>
        </div>
        <x-admin.forms.validation-errors/>
        <div class="card-body">
            <x-admin.forms.input type="text" id="title" label="عنوان*" wire:model.defer="title"/>
            <x-admin.forms.input type="text" id="symbol" label="نماد*" wire:model.defer="symbol"/>
            <x-admin.forms.input type="text" id="amount" label="قیمت*" wire:model.defer="amount"/>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        function deleteItem(id) {
            Swal.fire({
                title: 'حذف واحد پول  !',
                text: 'آیا از حذف این واحد پول اطمینان دارید؟',
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

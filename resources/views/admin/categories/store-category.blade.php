<div>
    @section('title','دسته بندی')
    <x-admin.form-control deleteAble="true" deleteContent="حذف دسته بندی" mode="{{$mode}}" title="دسته بندی"/>
    <div class="card card-custom gutter-b example example-compact">
        <div class="card-header">
            <h3 class="card-title">{{ $header }}</h3>
        </div>
        <x-admin.forms.validation-errors/>
        <div class="card-body">
            <x-admin.forms.input type="text" id="title" label="عنوان*" wire:model.defer="title"/>
            <x-admin.forms.input type="text" id="price" label="قیمت*" wire:model.defer="price"/>
            <x-admin.forms.dropdown :data="$data['currency']" id="currency" label="واحد پول" wire:model.defer="currency"/>
            <x-admin.forms.checkbox label="دسته بندی پایه" id="{is_base" value="1" wire:model.defer="is_base"  />
            <x-admin.forms.lfm-standalone id="image" label="تصویر" :file="$image" type="image" required="true" wire:model="image"/>
            <x-admin.forms.input type="text" id="description" label="توضیحات کوتاه" wire:model.defer="description"/>

        </div>
    </div>
</div>
@push('scripts')
    <script>
        function deleteItem(id) {
            Swal.fire({
                title: 'حذف دسته بندی  !',
                text: 'آیا از حذف این دسته بندی اطمینان دارید؟',
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

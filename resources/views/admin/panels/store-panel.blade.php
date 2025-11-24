<div>
    @section('title','پنل')
    <x-admin.form-control deleteAble="true" deleteContent="حذف پنل" mode="{{$mode}}" title="پنل "/>
    <div class="card card-custom gutter-b example example-compact">
        <div class="card-header">
            <h3 class="card-title">{{ $header }}</h3>
        </div>
        <x-admin.forms.validation-errors/>
        <div class="card-body">
            <x-admin.forms.input type="text" id="username" label="نام کاربری*" wire:model.defer="username"/>
            <x-admin.forms.input type="text" id="phone" label="شماره همراه*" wire:model.defer="phone"/>
            <x-admin.forms.input type="text" id="name" label="نام*" wire:model.defer="name"/>
            <x-admin.forms.lfm-standalone id="image" label="تصویر*" :file="$image" type="image" required="true" wire:model="image"/>
            <x-admin.forms.dropdown :data="$data['status']" id="status" label="وضعیت *" wire:model.defer="status"/>
            <x-admin.form-section label="کارت ها">
                <div class="row">
                    <div class="px-2">
                        <x-admin.button class="btn btn-light-primary font-weight-bolder btn-sm"
                                        content=" افزودن کارت جدید" wire:click="addCart()" />
                    </div>
                    <div class="col-lg-12 table-responsive">
                        <table  class="table table-striped table-bordered" id="kt_datatable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>شماره کارت</th>
                                <th>وضیعت</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($carts as $key => $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item['cart_number'] ?? 'نعریف نشده' }}</td>
                                        <td>استفاده شده</td>
                                        <td>
                                            <x-admin.delete-btn onclick="removeCart({{$item['id']}},{{$key}})" />
                                        </td>
                                    </tr>
                                @empty
                                    <td class="text-center" colspan="11">
                                        دیتایی جهت نمایش وجود ندارد
                                    </td>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </x-admin.form-section>
            @if($panel)
                <x-admin.form-section label="شارژ ها">
                    <div class="row">
                        <div class="col-lg-12 table-responsive">
                            <table  class="table table-striped table-bordered" id="kt_datatable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>شارژ </th>
                                    <th>وضیعت</th>
                                    <th>تاریخ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($panel->charges as $key => $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item['amount']  }}</td>
                                        <td>{{ $item['confirm'] ? 'پرداخت شده' : 'پرداخت نشده'  }}</td>
                                        <td>{{ $item->created_at }}</td>
                                    </tr>
                                @empty
                                    <td class="text-center" colspan="11">
                                        دیتایی جهت نمایش وجود ندارد
                                    </td>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </x-admin.form-section>
            @endif
        </div>
    </div>
    <x-admin.modal-page id="carts" title="افزودن کارت چدید" wire:click="" :btn="false">
        <x-admin.forms.input type="text" id="search" label="جستوجو شماره کارت" wire:input="searchCart" wire:model.defer="search" />
        @foreach($searches as $item)
            <button class="btn btn-sm btn-primary" wire:click="saveCart('{{$item['masked_pan']}}')">
                {{$item['masked_pan']}}
            </button>
        @endforeach
    </x-admin.modal-page>
</div>
@push('scripts')
    <script>
        function deleteItem(id) {
            Swal.fire({
                title: 'حذف پنل !',
                text: 'آیا از حذف این پنل اطمینان دارید؟',
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
        function removeCart(id,key) {
            Swal.fire({
                title: 'حذف کارت !',
                text: 'آیا از حذف این پنل کارت دارید؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'خیر',
                confirmButtonText: 'بله'
            }).then((result) => {
                if (result.value) {
                @this.call('removeCart', id,key)
                }
            })
        }
    </script>
@endpush

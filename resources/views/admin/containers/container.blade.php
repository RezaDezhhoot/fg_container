<div>
    @section('title',' مخزن  ')
    <x-admin.form-control store="{{false}}" title="مخزن"/>
    <div class="card card-custom">
        <div class="card-body">
            <div class="row">
                <x-admin.forms.select2 id="products" :data="$data['product']" label="فیلتر بر حسب  محصول" wire:model.defer="product"/>
            </div>
            @include('admin.includes.advance-table')
            <div class="row pb-4">
                <div class="col-12">
                    <button class="btn btn-warning" wire:click="$set('tab','container')">مخزن</button>
                    <button class="btn btn-warning" wire:click="$set('tab','history')">ورود / خروج</button>
                </div>
            </div>
            @if($tab == 'container')
                <div class=" p-2">
                    <h3 class="text-info">لیست لایسنس ها</h3>
                </div>
                <div class="row">
                    <div class="col-12 pb-4">
                        @foreach ($counter as $key => $item)
                            <button wire:click="$set('table','{{$key}}')" class="btn btn-sm btn-primary">{{ $item['label'] }} ({{$item['count']}}) </button>
                        @endforeach
                    </div>
                    <div class="col-lg-12 table-responsive">
                        <table  class="table table-striped table-bordered" id="kt_datatable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>کد</th>
                                <th>محصول</th>
                                <th>وضعیت</th>
                                <th>شماره فرم ورود</th>
                                <th>شماره فرم خروج</th>
                                <th>تاریخ ثبت</th>
                                <th>تاریخ ویرایش</th>
                                <th>عملیات </th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($container as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->license  }}</td>
                                    <td>{{ $item->product_title }}</td>
                                    <td>{{ $item->status_label }}</td>
                                    <td>{{ $item->form_enter_id ?? '-' }}</td>
                                    <td>{{ $item->form_exit_id ?? '-' }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                    <td>
                                        @if ($table == 'deleted')
                                        <button onclick="restoreContainer({{$item->id}})" class="btn btn-outline-success btn-sm">بازیابی <i class="fa fa-trash"></i></button>
                                        @else
                                            <button wire:click="editContainer({{$item->id}})" class="btn btn-outline-success btn-sm">ویرایش <i class="fa fa-edit"></i></button>
                                            <button onclick="deleteFormContainer({{$item->id}})" class="btn btn-outline-danger btn-sm">حذف <i class="fa fa-trash"></i></button>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <td class="text-center" colspan="10">
                                    دیتایی جهت نمایش وجود ندارد
                                </td>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                {{$container->links('admin.includes.paginate')}}
            @elseif($tab == 'history')
                <div class=" p-2">
                    <h3 class="text-info"> تاریخچه ورود و خروج</h3>
                </div>
                <div class="row">
                    <div class="col-12 pb-4 d-flex align-items-center justify-content-between">
                        <div>
                            <button wire:click="$set('table','all')" class="btn btn-sm btn-success">همه</button>
                            <button wire:click="$set('table','enter')" class="btn btn-sm btn-success">لیست ورود</button>
                            <button wire:click="$set('table','exit')" class="btn btn-sm btn-success">لیست خروج</button>
                        </div>
                        <div>
                            <button wire:click="historyFormEnter(0)" class="btn btn-sm btn-primary">فرم ورود</button>
                            <button wire:click="historyFormExit(0)" class="btn btn-sm btn-primary">فرم خروج</button>
                        </div>
                    </div>
                    <div class="col-lg-12 table-responsive">
                        @if($table == 'enter')
                            <h5 class="text-primary">ورودی ها</h5>
                            <hr>
                            <table  class="table table-striped table-bordered" id="kt_datatable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>شماره فرم</th>
                                    <th>تعداد</th>
                                    <th>قیمت خرید</th>
                                    <th>توسط</th>
                                    <th>محصول</th>
                                    <th>تاریخ ثبت</th>
                                    <th>تاریخ ویرایش</th>
                                    <th>توضیحات </th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($history as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->id  }}</td>
                                        <td>{{ $item->count }}</td>
                                        <td>{{ number_format($item->enter_price). 'تومان' ?? '' }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->product_title }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->updated_at }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>
                                            <button wire:click="historyFormEnter({{$item->id}})" class="btn btn-outline-success btn-sm">ویرایش <i class="fa fa-edit"></i></button>
                                            <button onclick="deleteFormContainer({{$item->id}})" class="btn btn-outline-danger btn-sm">حذف <i class="fa fa-trash"></i></button>
                                        </td>

                                    </tr>
                                @empty
                                    <td class="text-center" colspan="13">
                                        دیتایی جهت نمایش وجود ندارد
                                    </td>
                                @endforelse
                                </tbody>
                            </table>
                        @elseif($table == 'exit')
                            <h5 class="text-primary">خروجی ها</h5>
                            <hr>
                            <table  class="table table-striped table-bordered" id="kt_datatable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>شماره فرم</th>
                                    <th>تعداد</th>
                                    <th>قیمت خرید</th>
                                    <th>قیمت فروش</th>
                                    <th>کد سفارش</th>
                                    <th>توسط</th>
                                    <th>محصول</th>
                                    <th>تاریخ ثبت</th>
                                    <th>تاریخ ویرایش</th>
                                    <th>توضیحات </th>
                                    <th>عملیات </th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($history as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->id  }}</td>
                                        <td>{{ $item->count }}</td>
                                        <td>{{ number_format($item->enter_price). 'تومان' ?? '' }}</td>
                                        <td>{{ number_format($item->exit_price). 'تومان' ?? '' }}</td>
                                        <td>{{ $item->order_id ?? '-' }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->product_title }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->updated_at }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>
                                            <button wire:click="historyFormExit({{$item->id}})" class="btn btn-outline-success btn-sm">ویرایش <i class="fa fa-edit"></i></button>
                                            <button onclick="deleteFormContainer({{$item->id}})" class="btn btn-outline-danger btn-sm">حذف <i class="fa fa-trash"></i></button>
                                        </td>

                                    </tr>
                                @empty
                                    <td class="text-center" colspan="13">
                                        دیتایی جهت نمایش وجود ندارد
                                    </td>
                                @endforelse
                                </tbody>
                            </table>
                        @else
                            <h5 class="text-primary">همه</h5>
                            <hr>
                            <table  class="table table-striped table-bordered" id="kt_datatable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>شماره فرم</th>
                                    <th>نوع</th>
                                    <th>تعداد</th>
                                    <th>قیمت خرید</th>
                                    <th>قیمت فروش</th>
                                    <th>کد سفارش</th>
                                    <th>توسط</th>
                                    <th>محصول</th>
                                    <th>تاریخ ثبت</th>
                                    <th>تاریخ ویرایش</th>
                                    <th>توضیحات </th>
                                    <th>عملیات </th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($history as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->id  }}</td>
                                            <td>{{ $item->action_label  }}</td>
                                            <td>{{ $item->count }}</td>
                                            <td>{{ number_format($item->enter_price). 'تومان' ?? '' }}</td>
                                            <td>{{ number_format($item->exit_price). 'تومان' ?? '' }}</td>
                                            <td>{{ $item->order_id ?? '-' }}</td>
                                            <td>{{ $item->user->name }}</td>
                                            <td>{{ $item->product_title }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>{{ $item->updated_at }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td>
                                                @if($item->action == \App\Enums\LicenseEnum::ENTER)
                                                    <button wire:click="historyFormEnter({{$item->id}})" class="btn btn-outline-success btn-sm">ویرایش <i class="fa fa-edit"></i></button>
                                                @else
                                                    <button wire:click="historyFormExit({{$item->id}})" class="btn btn-outline-success btn-sm">ویرایش <i class="fa fa-edit"></i></button>
                                                @endif

                                                <button onclick="deleteFormContainer({{$item->id}})" class="btn btn-outline-danger btn-sm">حذف <i class="fa fa-trash"></i></button>
                                            </td>

                                        </tr>
                                    @empty
                                        <td class="text-center" colspan="13">
                                            دیتایی جهت نمایش وجود ندارد
                                        </td>
                                    @endforelse
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
                {{$history->links('admin.includes.paginate')}}
            @endif
        </div>
    </div>
    <x-admin.modal-page id="edit_container" title="{{$formTitle}}" wire:click="storeContainer">
        <div class="table-responsive">
            <x-admin.forms.input type="text" id="container_code" label="کد*" wire:model.defer="container_code" />
            <x-admin.forms.dropdown id="container_status" :data="$data['status']" label="وضعیت *" wire:model="container_status" />
        </div>
    </x-admin.modal-page>
    <x-admin.modal-page id="form" title="{{$formTitle}}" wire:click="storeHistory">
        <x-admin.forms.validation-errors/>
        <div class="table-responsive">
                <div class="col-12">
                    @if($historyAction == 'edit')
                        <table class="table table-bordered">
                            <tr>
                                <td>محصول</td>
                                <td>{{ $history_row->product_title  }}</td>
                            </tr>
                            <tr>
                                <td>تعداد</td>
                                <td>{{ $history_row->count  }}</td>
                            </tr>
                        </table>
                    @endif
                </div>
                @if($historyAction == 'new')
                <x-admin.forms.input type="text" id="searchProduct" label="محصول*" wire:model="searchProduct" />
                <div class="col-12 pb-4">
                    @foreach($search_result as $key => $item)
                        <button wire:click="setProduct({{$key}})" class="btn p-4 m-2 btn-sm btn-link">
                            {{ $item }}
                        </button>
                    @endforeach
                </div>
                @endif
                <x-admin.forms.input type="number" id="enter_price" label="قیمت خرید*" wire:model.defer="enter_price" />
                @if($action == App\Enums\LicenseEnum::EXIT)
                    @if ($historyAction == 'new')
                        <x-admin.forms.input type="number"  id="count" label="تعداد*" wire:model.defer="count" />
                        <div class="col-12">
                            <p class="test-info"> تعداد موجودی : {{$maxCount}} </p>
                        </div>
                    @endif
                    <x-admin.forms.input type="number" id="exit_price" label="قیمت فروش*" wire:model.defer="exit_price" />
                    <x-admin.forms.input type="text" id="order_id" label="کد سفارش*" wire:model.defer="order_id" />
                @endif

                <x-admin.forms.text-area label="توضیحات *" id="description" wire:model.defer="description" />

                    @if($action == App\Enums\LicenseEnum::ENTER)
                        <button class="btn btn-sm btn-outline-success pb-2" wire:click="addCode">افزودن</button>
                        <div class="table-responsive mt-2">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>کد</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($codes as $key => $item)
                                    <tr>
                                        <td>
                                            <x-admin.forms.input type="text" id="codes.{{$key}}.license" label="کد*" wire:model.defer="codes.{{$key}}.license" />
                                        </td>
                                        <td>
                                            <button onclick="deleteCodeThroughHistory({{$item['id']}})" class="btn btn-outline-danger mt-5 btn-sm">حذف <i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @elseif($historyAction == 'edit')
                            <div class="table-responsive mt-2">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>کد های خروجی</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($codes as $item)
                                        <tr>
                                            <td>
                                                {{$item['license']}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                    @endif


        </div>
    </x-admin.modal-page>
</div>
@push('scripts')
    <script>
        function deleteFormContainer(id) {
            Swal.fire({
                title: 'حذف کد!',
                text: 'آیا از حذف کد اطمینان دارید؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'خیر',
                confirmButtonText: 'بله'
            }).then((result) => {
                if (result.value) {
                @this.call('deleteFormContainer', id)
                }
            })
        }
        function deleteCodeThroughHistory(id) {
            Swal.fire({
                title: 'حذف کد!',
                text: 'آیا از حذف کد اطمینان دارید؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'خیر',
                confirmButtonText: 'بله'
            }).then((result) => {
                if (result.value) {
                @this.call('deleteCodeThroughHistory', id)
                }
            })
        }
        function restoreContainer(id) {
            Swal.fire({
                title: 'بازیابی کد!',
                text: 'آیا از بازیابی کد اطمینان دارید؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'خیر',
                confirmButtonText: 'بله'
            }).then((result) => {
                if (result.value) {
                @this.call('restoreContainer', id)
                }
            })
        }


    </script>
@endpush

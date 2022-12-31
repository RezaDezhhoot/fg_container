<div>
    @section('title','مخزن کارت ها  ')
    <x-admin.form-control link="{{ route('cart',['create'] ) }}" title="مخزن کارت ها"/>
    <div class="card card-custom">
        <div class="card-body">
            @include('admin.includes.advance-table')
            <x-admin.forms.dropdown :data="$data['status']" id="currency" label="وضعیت" wire:model="status"/>
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table  class="table table-striped table-bordered" id="kt_datatable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>شماره کارت</th>
                            <th>cvv2</th>
                            <th>تاریخ انقضا</th>
                            <th>وضعیت</th>
                            <th>نوع</th>
                            <th>دسته بندی</th>
                            <th>واحد پول</th>
                            <th> قیمت  </th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->cart_number }}</td>
                                <td>{{ $item->cart_cvv2 }}</td>
                                <td>{{ $item->expire }}</td>
                                <td>{{ $item->status_label }}</td>
                                <td>{{ $item->type_label }}</td>
                                <td>{{ $item->category->title ?? '-' }}</td>
                                <td>{{ $item->category->currency->title ?? 'تومان' }}</td>
                                <td>{{ number_format($item->category->price) }}</td>
                                <td>
                                    <x-admin.edit-btn href="{{ route('cart',['edit', $item->id]) }}" />
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
            {{$items->links('admin.includes.paginate')}}
        </div>
    </div>
</div>

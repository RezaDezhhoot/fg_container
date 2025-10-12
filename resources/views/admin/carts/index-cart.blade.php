<div>
    @section('title','مخزن کارت ها  ')
    <x-admin.form-control link="{{ route('cart',['create'] ) }}" title="مخزن کارت ها"/>
    <div class="card card-custom">
        <div class="card-body">
            @include('admin.includes.advance-table')
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table  class="table table-striped table-bordered" id="kt_datatable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>شماره کارت</th>
                            <th>cvv2</th>
                            <th>استفاده شده</th>
                            <th>تاریخ انقضا</th>
                            <th>متصل به پنل</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->masked_pan }}</td>
                                <td>{{ $item->cart?->cart_cvv2 }}</td>
                                <td>{{ $item->used ? 'بله' : 'خیر' }}</td>
                                <td>{{ $item->cart?->expire }}</td>
                                <td>{{ $item->cart?->panel ? 'بله' : 'خیر' }}</td>
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

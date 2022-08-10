<div>
    @section('title','درخواست ها  ')
    <x-admin.form-control store="{{false}}" title="درخواست ها"/>
    <div class="card card-custom">
        <div class="card-body">
            @include('admin.includes.advance-table')
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table  class="table table-striped table-bordered" id="kt_datatable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>شماره همراه</th>
                            <th> کد درخواست</th>
                            <th>ای پی</th>
                            <th>وضعیت</th>
                            <th>ادرس مبدا</th>
                            <th>کد سفارش</th>
                            <th>متن sms</th>
                            <th>تاریخ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($requests as $item)
                            <tr>
                                <td>{{ $loop->phone }}</td>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->ip }}</td>
                                <td>{{ $item->status }}</td>
                                <td>{{ $item->http_refrer }}</td>
                                <td>{{ $item->order_id }}</td>
                                <td>{{ $item->sms }}</td>
                                <td>{{ $item->created_at }}</td>
                            </tr>
                        @empty
                            <td class="text-center" colspan="12">
                                دیتایی جهت نمایش وجود ندارد
                            </td>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{$requests->links('admin.includes.paginate')}}
        </div>
    </div>
</div>

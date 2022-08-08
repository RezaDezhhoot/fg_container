<div>
    @section('title','کاربران ')
    <x-admin.form-control link="{{ route('store.user',['create'] ) }}" title="کاربران"/>
    <div class="card card-custom">
        <div class="card-body">

            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                @include('admin.includes.advance-table')
                <div class="row">
                    <div class="col-sm-12 table-responsive">
                        <table class="table table-striped table-bordered" id="kt_datatable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th> نام</th>
                                <th>شماره همراه</th>
                                <th>ایمیل</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->phone }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>
                                        <x-admin.edit-btn href="{{ route('store.user',['edit', $item->id]) }}" />
                                    </td>
                                </tr>
                            @empty
                                <td class="text-center" colspan="9">
                                    دیتایی جهت نمایش وجود ندارد
                                </td>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{$users->links('admin.includes.paginate')}}
        </div>
    </div>
</div>

<div>
    @section('title','پنل ها  ')
    <x-admin.form-control link="{{ route('panel',['create'] ) }}" title="پنل ها"/>
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
                            <th>نام کاربر</th>
                            <th>شماره همراه</th>
                            <th>نام</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->username }}</td>
                                <td>{{ $item->phone }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->status_label }}</td>
                                <td>
                                    <x-admin.edit-btn href="{{ route('panel',['edit', $item->id]) }}" />
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

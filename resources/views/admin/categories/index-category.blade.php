<div>
    @section('title','دسته بندی ها  ')
    <x-admin.form-control link="{{ route('category',['create'] ) }}" title="دسته بندی ها"/>
    <div class="card card-custom">
        <div class="card-body">
            @include('admin.includes.advance-table')
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table  class="table table-striped table-bordered" id="kt_datatable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>عنوان</th>
                            <th> واحد پول</th>
                            <th> قیمت  </th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->currency->title ?? 'تومان' }}</td>
                                <td>{{ number_format($item->amount) }}</td>
                                <td>
                                    <x-admin.edit-btn href="{{ route('category',['edit', $item->id]) }}" />
                                </td>
                            </tr>
                        @empty
                            <td class="text-center" colspan="5">
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

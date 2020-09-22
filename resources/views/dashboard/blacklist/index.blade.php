@extends('dashboard.app')

@push('style')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/plugins/table/datatable/dt-global_style.css') }}">
@endpush

@section('content')
<div class="col-md-12">
    <div class="statbox widget box shadow-none rounded mb-3">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>Danh sách đen SĐT
                        @can('blacklist.phone.create')
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#create" class="btn btn-success rounded-circle"><i data-feather="plus"></i></a>
                        @endcan
                    </h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area shadow-none">

            <form id="search-form" action="" method="GET">
                <div class="row">
                    <div class="col-md-5 pl-md-0 order-first">
                        <div class="form-row">
                            <label for="phone" class="col-md-3 col-form-label text-md-right d-none d-md-block"><strong>Tìm kiếm: </strong></label>
            
                            <div class="col-md-9">
                                <div class="form-group input-group-sm">
                                <input id="phone" type="text" class="form-control" name="phone" value="{{ request('phone') }}" placeholder="tìm theo SĐT">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 pl-md-0 order-md-first order-last">
                        <button type="submit" class="btn btn-sm btn-primary">Tìm kiếm</button>
                        </a>
                    </div>
            
                </div>
            </form>

            <table id="example" class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Số điện thoại</th>
                        <th>Người thêm</th>
                        <th>Ngày thêm</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($blacklist as $blackphone)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="text-secondary font-weight-bold">{{ $blackphone->phone }}</span></td>
                        {{-- <td><input type="text" class="form-control note" value="{{ $blackphone->readNote() }}" placeholder="" data-id="{{ $blackphone->id }}" @cannot('blacklist.phone.modify') disabled @endcannot ></td> --}}
                        <td>{{ $blacklist->user->name ?? '' }}</td>
                        <td>{{ $blackphone->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>
                            @can('blacklist.phone.delete')
                            <button type="button" class="btn btn-danger delete" data-id="{{ $blackphone->id }}">Xóa</button>
                            @endcan
                        </td>
                    </tr>
                    @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Số điện thoại</th>
                        <th>Ngày thêm</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>


            {{ $blacklist->onEachSide(0)->withQueryString()->render() }}

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('manager.blacklist.phone.store') }}" method="post"> @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tạo mới</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="phone">Số điện thoại</label>
                      <input type="text"
                        class="form-control" name="phone" id="phone" placeholder="Số điện thoại">
                    </div>
                    {{-- <div class="form-group">
                      <label for="note">Ghi chú</label>
                      <textarea class="form-control" name="note" id="note" rows="3" placeholder="Ghi chú gì đó..."></textarea>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success">Thêm</button>
                </div>
            </div>
        </form>
    </div>
</div>

<form id="delete-form" action="" method="POST">@csrf</form>
@endsection

@push('script')
<script>

    $(document).ready(function() {
        $('.note').on('change', function () {
            let id = $(this).data('id');

            console.log(id);

            fetch(`/manager/blacklist/phone/${id}/update`, {
                headers: {
                    "Content-type": "application/x-www-form-urlencoded"
                },
                method: 'post',
                body: '_token=' + csrf() + '&note=' + $(this).val()
            })
        });

        $('.delete').on('click', function () {
            let id = $(this).data('id');

            let form = $('#delete-form');

            form.attr('action', `/manager/blacklist/phone/${id}/delete`);

            form.submit();
        });
    });

</script>
@endpush
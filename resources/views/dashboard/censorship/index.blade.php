@extends('dashboard.app')

@push('style')
<link rel="stylesheet" href="{{ asset('dashboard/assets/css/tables/table-basic.css') }}">
@endpush

@section('content')
<div id="tableLight" class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>
                        KIỂM DUYỆT TIN ONLINE
                    </h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">

            @include('dashboard.censorship.components.search')

            <div class="table-responsive">
                <form action="" method="post" id="form-table">
                @csrf
                <table class="table table-bordered table-hover table-striped table-checkable table-highlight-head">
                    <thead>
                        <tr>
                            <th class="checkbox-column">
                                <div class="custom-control custom-checkbox checkbox-primary">
                                  <input type="checkbox" class="custom-control-input" id="todoAll">
                                  <label class="custom-control-label" for="todoAll"></label>
                                </div>
                            </th>
                            <th>Tiêu đề</th>
                            <th>Số điện thoại</th>
                            <th>Trạng thái</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                        <tr>
                            <td class="checkbox-column">
                                <div class="custom-control custom-checkbox checkbox-primary">
                                <input type="checkbox" id="todo-{{ $post->id }}" class="custom-control-input todochkbox" name="ids[]" value="{{ $post->id }}">
                                <label class="custom-control-label" for="todo-{{ $post->id }}">{{ $loop->iteration }}</label>
                                </div>
                            </td>
                            <td class="open-post cursor-pointer" data-id="{{ $post->id }}">
                                <p class="mb-0"><i class="fa fa-file-text-o"></i> <strong>{{ Str::ucfirst(Str::of($post->title)->limit(73)) }}</strong> <br>
                                    <span class="mb-0" style="font-size: 12px;">
                                        <strong> </strong> <i class="text-info">{{ $post->categories[0]->name ?? '' }}</i> <span class="text-muted">|</span>
                                        <strong>Quận/huyện: </strong> <i class="text-info">{{ $post->district->name ?? 'N/a' }}</i> <span class="text-muted">|</span>
                                        <strong>Ngày đăng: </strong> <i class="text-info">{{ $post->publish_at ? $post->publish_at->format('d/m/Y H:i:s') : $post->updated_at->format('d/m/Y H:i:s')  }}</i>
                                        @if ($post->reverser) <span class="text-muted">|</span> <span class="text-danger">Đã đảo</span> @endif
                                    </span>
                                </p>
                            </td>
                            <td>
                                <div class="d-flex">
                                    {!! implode('<br>', explode(',', $post->phone ?? '')) ?? 'N/a' !!}
                                    <i class="lookup-phone t-icon t-hover-icon" data-feather="search" data-phone="{{ $post->phone ?? '' }}"></i>
                                </div>
                            </td>
                            <td> @include('dashboard.post.components.status', ['status' => $post->status]) </td>
                            <td>
                                <div class="{{ $post->phone ? 'add-blacklist' : '' }}" data-phone="{{ $post->phone ?? '' }}">
                                    <span class="badge badge-secondary cursor-pointer">Chặn SĐT</span>
                                </div>
                                <div class="{{ $post->phone ? 'add-whitelist' : '' }}" data-phone="{{ $post->phone ?? '' }}">
                                    <span class="badge outline-badge-info cursor-pointer mt-1">Chính chủ</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="btn-group mb-4 mr-2" role="group">
                    <button id="btndefault" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Hành động 
                        <i data-feather="chevron-down"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btndefault">
                        <a href="javascript:void(0);" id="delete-many" class="dropdown-item text-danger"><i class="flaticon-home-fill-1 mr-1"></i>Xóa</a>
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    {!! $posts->appends($_GET)->render() !!}
                </div>
                </form>
            </div>

        </div>
    </div>
</div>
<form id="form-add-blacklist" action="{{ route('manager.censorship.blacklist.add') }}" method="post">
    @csrf
    <input id="add-blacklist" type="hidden" name="phone" value="">
</form>
<form id="form-add-whitelist" action="{{ route('manager.censorship.whitelist.add') }}" method="post">
    @csrf
    <input id="add-whitelist" type="hidden" name="phone" value="">
</form>
@endsection

@push('script')
<script>
(function (window) {
    let form = $('#form-table');

    $('#todoAll').click(function () {
        let checked = $('#todoAll').prop('checked');
        $('.todochkbox').prop('checked', checked);
    });

    $(document).ready(function () {
        $('#delete-many').click(function () {
            form.attr('action', "{{ route('manager.post.online.delete.many') }}");
            form.submit();
        });

        $('.open-post').on('click', function () {
            let id = $(this).data('id');

            window.location.href = `/manager/post/online/${id}/view`;
        });

        $('.add-blacklist').on('click', function () {
            let phone = $(this).data('phone');

            let form = $('#form-add-blacklist');

            $('#add-blacklist').val(phone);

            if (confirm(`Bạn có muốn thêm số ${phone} vào danh sách đen`)) {
                form.submit();
            }
        });

        $('.add-whitelist').on('click', function () {
            let phone = $(this).data('phone');

            let form = $('#form-add-whitelist');

            $('#add-whitelist').val(phone);

            if (confirm(`Bạn có muốn thêm số ${phone} vào danh sách trắng`)) {
                form.submit();
            }
        });

        $('.lookup-phone').on('click', function () {
            let phone = $(this).data('phone');
            let uri   = '/manager/post/online?query=' + phone;

            window.open(uri, '_blank');
        });
    });
}(window))
</script>
@endpush
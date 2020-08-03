@extends('dashboard.app')

@section('content')
<div id="tableLight" class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>
                        Danh sách bài viết
                        <a href="{{ route('manager.post.create') }}" class="btn btn-success rounded-circle"><i data-feather="plus"></i></a>
                    </h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">

            @include('dashboard.post.components.search')

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
                            <th>Giá</th>
                            <th>Số điện thoại</th>
                            <th>Ngày đăng</th>
                            <th>Đăng bởi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                        @php
                            $meta = $post->loadMeta()->meta;
                        @endphp
                        <tr>
                            <td class="checkbox-column">
                                <div class="custom-control custom-checkbox checkbox-primary">
                                  <input type="checkbox" id="todo-{{ $post->id }}" class="custom-control-input todochkbox" name="ids[]" value="{{ $post->id }}">
                                  <label class="custom-control-label" for="todo-{{ $post->id }}"></label>
                                </div>
                            </td>
                            <td>
                                <p class="mb-0"><i class="fa fa-file-text-o"></i> <strong>{{ Str::ucfirst(Str::of($post->title)->limit(73)) }}</strong> <br>

                                    <span class="mb-0" style="font-size: 12px;">
                                        <strong> </strong> <i class="text-info">{{ $post->categories[0]->name ?? '' }}</i> <span class="text-muted">|</span>
                                        <strong>Quận/huyện: </strong> <i class="text-info">{{ $meta->district->district->name ?? 'N/a' }}</i>
                                        @if ($post->reverser) <span class="text-muted">|</span> <span class="text-danger">Đã đảo</span> @endif
                                    </span>
                                </p>
                            </td>
                            <td>{{ $meta->price ? format_web_price($meta->price->value) : 'N/a' }}</td>
                            <td>
                                <div class="d-flex">
                                    {!! implode('<br>', explode(',', $meta->phone->value)) ?? 'N/a' !!}
                                    <i class="lookup-phone t-icon t-hover-icon" data-feather="search" data-phone="{{ $meta->phone->value ?? '' }}"></i>
                                </div>
                            </td>
                            <td>{{ $post->publish_at ? $post->publish_at->format('d/m/Y H:i:s') : $post->updated_at->format('d/m/Y H:i:s')  }}</td>
                            <td>{{ $post->user ? $post->user->name . ' - ' . $post->user->phone : 'Hệ thống' }}</td>
                            <td>
                                <a href="{{ route('manager.post.view', ['id' => $post->id]) }}">
                                    <i class="role-edit t-icon t-hover-icon" data-feather="edit"></i>
                                </a>
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
            form.attr('action', "{{ route('manager.post.delete.many') }}");
            form.submit();
        });

        $('.lookup-phone').on('click', function () {
            let phone = $(this).data('phone');
            let uri   = window.location.pathname + '?query=' + phone;

            window.location.href = uri;
        });
    });
}(window))
</script>
@endpush
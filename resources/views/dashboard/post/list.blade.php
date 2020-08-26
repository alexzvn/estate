<?php use App\Enums\PostType; ?>

@extends('dashboard.app')
@push('style')
<link rel="stylesheet" href="{{ asset('dashboard/plugins/file-upload/file-upload-with-preview.min.css') }}">
@endpush

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
                            <th>Trạng thái</th>
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
                            <td class="cursor-pointer open-post" data-id="{{ $post->id }}">
                                <p class="mb-0">
                                    <strong>
                                        @if (isset($meta->phone->value) && $whitelist->whereIn('phone', $meta->phone->value)->isNotEmpty())
                                        [<span class="text-success font-weight-bolder">Chính chủ</span>]
                                        @endif
                                        {{ Str::ucfirst(Str::of($post->title)->limit(73)) }}
                                    </strong>
                                    <br>
                                    <span class="mb-0" style="font-size: 12px;">
                                        <strong> </strong> <i class="text-info">{{ $post->categories[0]->name ?? '' }}</i> <span class="text-muted">|</span>
                                        <strong>Quận/huyện: </strong> <i class="text-info">{{ $meta->district->district->name ?? 'N/a' }}</i>
                                        <strong>Ngày đăng: </strong> <i class="text-info">{{ $post->publish_at ? $post->publish_at->format('d/m/Y H:i:s') : $post->updated_at->format('d/m/Y H:i:s') }}</i>
                                        @if ($post->reverser) <span class="text-muted">|</span> <span class="text-danger">Đã đảo</span> @endif
                                    </span>
                                </p>
                            </td>
                            <td>{{ $meta->price ? format_web_price($meta->price->value ?? 0) : 'N/a' }}</td>
                            <td>
                                @isset($meta->phone->value)
                                <div class="d-flex">
                                    {!! implode('<br>', explode(',', $meta->phone->value ?? '')) ?? 'N/a' !!}
                                    <i class="lookup-phone t-icon t-hover-icon" data-feather="search" data-phone="{{ $meta->phone->value ?? '' }}"></i>
                                </div>
                                @endisset
                            </td>
                            <td> @include('dashboard.post.components.status', ['status' => $post->status]) </td>
                            <td>
                                @isset($meta->phone->value)
                                <div class="{{ $meta->phone->value ? 'add-blacklist' : '' }}" data-phone="{{ $meta->phone->value ?? '' }}">
                                    <span class="badge badge-secondary cursor-pointer">Chặn SĐT</span>
                                </div>
                                <div class="{{ $meta->phone->value ? 'add-whitelist' : '' }}" data-phone="{{ $meta->phone->value ?? '' }}">
                                    <span class="badge outline-badge-info cursor-pointer mt-1">Chính chủ</span>
                                </div>
                                @endisset

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
                        <a href="javascript:void(0);" id="reverse-many" class="dropdown-item text-info"><i class="flaticon-home-fill-1 mr-1"></i>Đảo tin</a>
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

<!-- post edit modal -->
<div class="modal fade" id="post-edit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa bài viết</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form id="post-form">
                    @csrf

                    <div class="form-group input-group-sm">
                        <label for="post-title">Tiêu đề</label>
                        <input type="text"
                        class="form-control" value="" name="title" id="post-title" aria-describedby="title" placeholder="Tiêu đề tin" required>
                    </div>

                    <div class="form-row">

                        <div class="col-md-4">
                            <div class="form-group input-group-sm">
                              <label for="post-price">Giá tiền</label>
                              <input type="text"
                                class="form-control" value="" name="price" id="post-price" placeholder="Giá tin" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group input-group-sm">
                              <label for="post-commission">Hoa Hồng</label>
                              <input type="text"
                                class="form-control" value="" name="commission" id="post-commission" placeholder="" step="1" value="" min="0" max="100">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group input-group-sm">
                                <label for="post-phone">Số điện thoại</label>
                                <input type="text"
                                  class="form-control" value="" name="phone" id="post-phone" placeholder="0355...." required>
                              </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="form-group input-group-sm">
                                <label for="post-category">Danh mục</label>
                                <select class="form-control" name="category" id="post-category">
                                <option value="">Chọn danh mục</option>
                                @php
                                    $catId = $category->id ?? null;
                                @endphp
                                  @foreach ($categories as $item)
                                    @if (!$item->children || count($item->children) < 1)
                                        <option value="{{ $item->id }}" {{ $item->id == $catId ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @else
                                        <option value="{{ $item->id }}" disabled style="font-weight: bold; color: #0e1726;"><strong> {{ $item->name }} </strong></option>
                                        @foreach ($item->children as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == $catId ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    @endUnless
                                  @endforeach
                                </select>
                            </div>
                        </div>
    
                        <div class="col-md-4">
                            <div class="form-group input-group-sm">
                                <label for="post-province">Tỉnh, thành phố</label>
                                <select class="form-control" name="province" id="post-province">
                                    <option value="" selected>Trống</option>
                                    @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}" {{ $meta->province && $meta->province->value == $province->id ? 'selected' :'' }}>{{ $province->name }}</option>
                                    @php
                                        if ($meta->province && $meta->province->value == $province->id) {
                                            $activeProvince = $province;
                                        }
                                    @endphp
                                    @endforeach
                                </select>
                            </div>
                        </div>
    
                        <div class="col-md-4">
                            <div class="form-group input-group-sm">
                                <label for="post-district">Quận/huyện</label>
                                <select class="form-control" name="district" id="post-district">
                                    <option value="" selected>Trống</option>
                                    @isset($activeProvince)
                                    @foreach ($activeProvince->districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endforeach
                                    @endisset
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="post-content">Nội dung</label>
                        <textarea class="form-control" name="post_content" id="post-content" rows="3"></textarea>
                    </div>
                    <div>
                        <p class="text-muted m-0">Ngày cập nhật cuối cùng là <span class="text-info"></span>, đăng bởi
                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info float-left">Duyệt lưu gốc</button>
                <button type="button" class="btn btn-primary">Lưu</button>
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
        $('.open-post').on('click', function () {
            let id = $(this).data('id');
            let modal = $('#post-edit');
            let form = $('#post-form');
            const ckeditor = () => {
                return ClassicEditor
                .create(document.querySelector('#post-content'))
                .catch( err => {
                    console.error( err.stack );
                });
            };


            $('#form-content').html('');
            $('.ck').remove();
            form.trigger('reset');
            modal.modal();

            fetch(`/manager/post/${id}/fetch`).then(res => {

                if (! res.ok) {
                    Snackbar.show({
                        text: 'Danger',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a',
                        text: 'có lỗi trong quá trình lấy tin',
                        pos: 'bottom-right',
                        duration: 5000,
                        showAction: false
                    });

                    modal.modal('toggle');
                }

                return res.json();
            }).then(post => {
                let map = {
                    phone: post.meta.phone,
                    commission: post.meta.commission,
                    price: post.meta.price,
                    title: {value: post.title},
                    content: {value: post.content}
                };

                for (const key in map) {
                    if (map.hasOwnProperty(key)) {
                        const meta = map[key];
                        $(`#post-${key}`).val(meta ? meta.value + '' : '');
                    }
                }

                ckeditor();

                let options = {
                    category: post.categories[0] ? post.categories[0]._id : null,
                    province: post.meta.province ? post.meta.province.value : null,
                    district: post.meta.district ? post.meta.district.value : null,
                };

                for (const key in options) {
                    if (options.hasOwnProperty(key) && options[key]) {
                        const option = options[key];
                        $(`#post-${key}`).val(option);
                    }
                }
            });
        });

        $('#delete-many').click(function () {
            if (! confirm('Xóa tất cả các mục đã chọn?')) {
                return;
            }

            form.attr('action', "{{ route('manager.post.delete.many') }}");
            form.submit();
        });

        $('#reverse-many').click(function () {
            form.attr('action', "{{ route('manager.post.reverse.many') }}");
            form.submit();
        });

        $('.lookup-phone').on('click', function () {
            let phone = $(this).data('phone');
            let uri   = window.location.pathname + '?query=' + phone;

            window.location.href = uri;
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
    });
}(window))
</script>
@endpush

@push('script')
<script src="https://cdn.ckeditor.com/ckeditor5/20.0.0/classic/ckeditor.js"></script>
<script src="{{ asset('dashboard/plugins/input-mask/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{ asset('dashboard/plugins/file-upload/file-upload-with-preview.min.js') }}"></script>
<script>
(function (window) {
    $(document).ready(function () {
        $('#post-price').inputmask({
            alias: 'currency',
            prefix: '',
            digits: 0,
            rightAlign: false
        });

        $('#post-phone').inputmask("9999.999.999");
    });
}(window))
</script>
@endpush
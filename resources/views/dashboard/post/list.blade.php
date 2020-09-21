<?php use App\Enums\PostType; ?>

@extends('dashboard.app')
@push('style')
<link rel="stylesheet" href="{{ asset('dashboard/plugins/file-upload/file-upload-with-preview.min.css') }}">
@endpush

@php
    $hasCommission = isset($posts[0]) && isset($posts[0]->commission);
@endphp

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
                            @if ($hasCommission)
                            <th>Hoa Hồng</th>
                            @endif
                            <th>Giá</th>
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
                                  <label class="custom-control-label" for="todo-{{ $post->id }}"></label>
                                </div>
                            </td>
                            <td class="cursor-pointer open-post" data-id="{{ $post->id }}">
                                <p class="mb-0">
                                    <strong>
                                        @if (isset($post->phone) && $whitelist->whereIn('phone', $post->phone)->isNotEmpty())
                                        [<span class="text-success font-weight-bolder">Chính chủ</span>]
                                        @endif
                                        {{ Str::ucfirst(Str::of($post->title)->limit(73)) }}
                                    </strong>
                                    <br>
                                    <span class="mb-0" style="font-size: 12px;">
                                        <strong> </strong> <i class="text-info">{{ $post->categories[0]->name ?? '' }}</i> <span class="text-muted">|</span>
                                        <strong>Quận/huyện: </strong> <i class="text-info">{{ $post->district->name ?? 'N/a' }}</i>
                                        <strong>Ngày đăng: </strong> <i class="text-info">{{ $post->publish_at ? $post->publish_at->format('d/m/Y H:i:s') : $post->updated_at->format('d/m/Y H:i:s') }}</i>
                                        @if ($post->reverser) <span class="text-muted">|</span> <span class="text-danger">Đã đảo</span> @endif
                                    </span>
                                </p>
                            </td>
                            @if ($hasCommission)
                            <td>{{ $post->commission ?? '' }}</td>
                            @endif
                            <td>{{ $post->price ? format_web_price($post->price ?? 0) : 'N/a' }}</td>
                            <td>
                                @isset($post->phone)
                                <div class="d-flex">
                                    {!! implode('<br>', explode(',', $post->phone ?? '')) ?? 'N/a' !!}
                                    <i class="lookup-phone t-icon t-hover-icon" data-feather="search" data-phone="{{ $post->phone ?? '' }}"></i>
                                </div>
                                @endisset
                            </td>
                            <td> @include('dashboard.post.components.status', ['status' => $post->status]) </td>
                            <td>
                                @isset($post->phone)
                                <div class="{{ $post->phone ? 'add-blacklist' : '' }}" data-phone="{{ $post->phone ?? '' }}">
                                    <span class="badge badge-secondary cursor-pointer">Chặn SĐT</span>
                                </div>
                                <div class="{{ $post->phone ? 'add-whitelist' : '' }}" data-phone="{{ $post->phone ?? '' }}">
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

@include('dashboard.post.components.popup')

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
<script src="{{ asset('dashboard/plugins/file-upload/file-upload-with-preview.min.js') }}"></script>
<script>

ClassicEditor
    .create(document.querySelector('#post-content'))
    .then(editor => {
        window.editor = editor;
    })
    .catch( err => {
        console.error( err.stack );
    });

let upload = new FileUploadWithPreview('mySecondImage', {
        text: {
            chooseFile: 'Chọn ảnh',
            browse: 'Tìm',
            selectedCount: 'ảnh đã chọn',
        },
    });

(function (window) {
    $(document).ready(function () {
        $('.open-post').on('click', function () {
            let id = $(this).data('id');
            let modal = $('#post-edit');
            let form = $('#post-form');

            resetForm();

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
                    phone: post.phone,
                    commission: post.commission,
                    price: post.price,
                    title: post.title,
                    id: post._id,
                };

                editor.setData(post.content);

                for (const key in map) {
                    if (map.hasOwnProperty(key)) {
                        const attr = map[key];
                        $(`#post-${key}`).val(attr ? attr + '' : '');
                    }
                }

                let options = {
                    category: post.category_ids[0],
                    province: post.province_id,
                    district: post.district_id,
                    type: post.type,
                    status: post.status,
                };

                console.log(options.category);

                address.setDistricts(options.province);

                for (const key in options) {
                    if (options.hasOwnProperty(key) && options[key]) {
                        const option = options[key];
                        $(`#post-${key}`).val(option);
                    }
                }

                let files = post.files.map(file => {
                    return `/storage/${file.path}?fid=${file._id}`;
                });

                upload.addImagesFromPath(files);
            });
        });
    });

    let resetForm = () => {
        let modal = $('#post-edit');
        let form = $('#post-form');
        upload.clearPreviewPanel();
        $('#sync-file-ids').html('');
        $('#form-modal').trigger('');
        $('#post-content').html('');
        form.trigger('reset');
        editor.setData('');
        modal.modal();
    }

    let syncInput = e => {
        let ids   = [];
        let files = e.detail.cachedFileArray;

        files.forEach(e => {
            let pos = e.name.search('fid=');

            if (pos !== -1) {
                ids.push(e.name.substr(pos+4, 24));
            }
        });

        let inputFid = $('#sync-file-ids');

        inputFid.html('');

        ids.forEach(id => {
            inputFid.append(`<input type="hidden" name="image_ids[]" value="${id}">`);
        });
    }

    $(window).on('fileUploadWithPreview:imagesAdded', syncInput);
    $(window).on('fileUploadWithPreview:imageDeleted', syncInput);
}(window))
</script>
@endpush

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
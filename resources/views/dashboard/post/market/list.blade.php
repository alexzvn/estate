<?php use App\Enums\PostType; ?>

@extends('dashboard.app')
@push('style')
<link rel="stylesheet" href="{{ asset('dashboard/plugins/file-upload/file-upload-with-preview.min.css') }}">
<link rel="stylesheet" href="{{ asset('dashboard/assets/css/forms/theme-checkbox-radio.css') }}">
<link rel="stylesheet" href="{{ asset('dashboard/plugins/lightbox/photoswipe.css') }}">
<link rel="stylesheet" href="{{ asset('dashboard/plugins/lightbox/default-skin/default-skin.css') }}">
<link rel="stylesheet" href="{{ asset('dashboard/plugins/lightbox/custom-photswipe.css') }}">

<style>
    .card-img-header {
        max-height: 13rem;
        border-top-left-radius: calc(.25rem - 1px);
        border-top-right-radius: calc(.25rem - 1px);
        object-fit: cover;
    }

    .t-icon {
        padding: 3px;
    }

    .card-text {
        line-height: 1.8;
    }
</style>
@endpush

@section('content')
<div class="col-12 mb-4">
    <h4>
        Danh sách bài viết
        <a href="javascript:void(0)" id="create-button" class="btn btn-success rounded-circle"><i data-feather="plus"></i></a>
    </h4>
</div>

@foreach ($posts as $post)
<div class="col-md-4 col-sm-6 mb-3">
    <div class="card component-card_2">
        <img src="{{ '/storage/' . $post->files[0]->path ?? '' }}" class="card-img-header cursor-pointer" data-images='@json($post->files)'>
        <div class="card-body">
            <h5 class="card-title">{{ $post->title }}</h5>
            <p class="card-text">
                <i class="t-icon" data-feather="phone"></i> {{ $post->phone ?? '' }} <br>
                <i class="t-icon" data-feather="bookmark"></i> {{ $post->categories[0]->name ?? '' }} <br>
                <i class="t-icon" data-feather="map"></i> {{ $post->province->name ?? '' }} <br>
                <i class="t-icon" data-feather="map-pin"></i> {{ $post->district->name ?? '' }}
            </p>
        </div>
        <div class="card-footer">
            <a href="javascript:void(0)" class="btn btn-primary editable" data-id="{{ $post->id }}">Chỉnh sửa</a>
        </div>
    </div>
</div>
@endforeach

<div class="col-12 justify-content-center">
    {{ $posts->withQueryString()->render() }}
</div>

@include('dashboard.layouts.photoswipe')


{{-- <!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#edit">
  Launch
</button> --}}

<!-- Modal -->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-modal" action="" method="POST" enctype="multipart/form-data"> @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tin thị trường</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">

                    <div class="statbox widget box box-shadow my-3">
                        <div class="widget-header">
                            <h4>Thông tin thêm</h4>
                        </div>
                        <div class="widget-content widget-content-area">
                            <div class="form-row">

                                <div class="col-md-8">
                                    <div class="form-group input-group-sm">
                                    <label for="title">Tiêu đề</label>
                                    <input type="text"
                                        class="form-control" value="" name="title" id="title" placeholder="Tiêu đề tin" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group input-group-sm">
                                        <label for="phone">Số điện thoại</label>
                                        <input type="text"
                                        class="form-control" value="" name="phone" id="phone" placeholder="0355...." required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="form-group input-group-sm">
                                        <label for="category">Danh mục</label>
                                        <select class="form-control" name="category_ids" id="category" required>
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
                                        <label for="province">Tỉnh, thành phố</label>
                                        <select class="form-control" name="province" id="province" required>
                                            <option value="" selected>Trống</option>
                                            @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}" {{ $post->province_id == $province->id ? 'selected' :'' }}>{{ $province->name }}</option>
                                            @php
                                                if ($post->province_id == $province->id) {
                                                    $activeProvince = $province;
                                                }
                                            @endphp
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group input-group-sm">
                                        <label for="district">Quận/huyện</label>
                                        <select class="form-control" name="district" id="district" required>
                                            <option value="" selected>Trống</option>
                                            @isset($activeProvince)
                                            @foreach ($activeProvince->districts as $district)
                                            <option value="{{ $district->id }}" {{ $post->district_id == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                            @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="statbox widget box box-shadow my-3">
                        <div class="widget-header">
                            <h4>Thêm ảnh cho tin</h4>
                        </div>

                        <div id="sync-file-ids">

                        </div>

                        <div class="widget-content widget-content-area">
                            <div class="custom-file-container" data-upload-id="mySecondImage">
                                <label>Chọn ảnh đại diện <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                                <label class="custom-file-container__custom-file" >
                                    <input type="file" name="images[]" accept="image/*" class="custom-file-container__custom-file__custom-file-input" multiple>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                                    <span class="custom-file-container__custom-file__custom-file-control"></span>
                                </label>
                                <div class="custom-file-container__image-preview"></div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.ckeditor.com/ckeditor5/20.0.0/classic/ckeditor.js"></script>
<script src="{{ asset('dashboard/plugins/file-upload/file-upload-with-preview.min.js') }}"></script>
<script>
(function (window) {

    const data =    JSON.parse('@json($provinces)');
    let upload = new FileUploadWithPreview('mySecondImage', {
            text: {
                chooseFile: 'Chọn ảnh',
                browse: 'Tìm',
                selectedCount: 'ảnh đã chọn',
            },
        });

    $(document).ready(() => {
        $('#create-button').click(() => {
            $('#form-modal').attr('action', "{{ route('manager.post.market.store') }}")
        });

        $('.editable').on('click', (e) => {
            const id = $(e.currentTarget).data('id');

            $('#form-modal').attr('action', `/manager/post/market/${id}/update`);

            fetch('/manager/post/' + id + '/view', {headers: {Accept: 'application/json'}})
                .then(res => res.json())
                .then(post => {
                    let map = {
                        phone: post.phone,
                        commission: post.commission,
                        price: post.price,
                        title: post.title,
                        id: post._id,
                    };

                    for (const key in map) {
                        if (map.hasOwnProperty(key)) {
                            const attr = map[key];
                            $(`#${key}`).val(attr ? attr + '' : '');
                        }
                    }

                    let options = {
                        category: post.categories[0] ? post.categories[0]._id : null,
                        province: post.province_id,
                        district: post.district_id,
                        type: post.type,
                        status: post.status,
                    };

                    address.setDistricts(options.province);

                    for (const key in options) {
                        if (options.hasOwnProperty(key) && options[key]) {
                            const option = options[key];
                            $(`#${key}`).val(option);
                        }
                    }

                    let files = post.files.map(file => {
                        return `/storage/${file.path}?fid=${file._id}`;
                    });

                    upload.addImagesFromPath(files);
                });

            resetForm();
            $('#edit').modal();
        });
    });

    let resetForm = () => {
        upload.clearPreviewPanel();
        $('#sync-file-ids').html('');
        $('#form-modal').trigger('');
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

    window.address = {
        setDistricts(provinceId) {
            let province = data.filter((e) => {return e._id === provinceId})[0];
            let district = $('#district');

            district.html('');
            district.append('<option value="" selected>Chọn</option');

            if (province === undefined) return;

            province.districts.map((e) => {
                district.append(`<option value="${e._id}">${e.name}</option`);
            });
        }
    };

    $(window).on('fileUploadWithPreview:imagesAdded', syncInput);
    $(window).on('fileUploadWithPreview:imageDeleted', syncInput);
}(window))
</script>
@endpush

@push('script')
<script src="{{ asset('dashboard/plugins/lightbox/photoswipe.min.js') }}"></script>
<script src="{{ asset('dashboard/plugins/lightbox/photoswipe-ui-default.min.js') }}"></script>

<script>
(function (window) {
$(document).ready(function () {
    $('.card-img-header').on('click', function () {
        const images = $(this).data('images').map(image => {
            return {src: `/storage/${image.path}`, w: 0, h: 0};
        });

        let pswpElement = document.querySelectorAll('.pswp')[0];

        let gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, images, {});

        gallery.listen('gettingData', function(index, item) {
            if (item.w < 1 || item.h < 1) { // unknown size
                let img = new Image(); 
                img.onload = function() { // will get size after load
                    item.w = this.width; // set image width
                    item.h = this.height; // set image height
                    gallery.invalidateCurrItems(); // reinit Items
                    gallery.updateSize(true); // reinit Items
                }
                img.src = item.src; // let's download image
            }
        });

        gallery.init();
    });
});
}(window))
</script>
@endpush
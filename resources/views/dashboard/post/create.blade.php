<?php use App\Enums\PostType; ?>

@extends('dashboard.app')
@push('style')
<link rel="stylesheet" href="{{ asset('dashboard/plugins/file-upload/file-upload-with-preview.min.css') }}">
@endpush

@section('content')
<div class="col-md-12">
    <form class="row" action="{{ route('manager.post.store') }}" method="post">
        <div class="col-md-9">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Tạo tin mới</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    @csrf
                    <div class="form-group input-group-sm">
                        <label for="title">Tiêu đề</label>
                        <input type="text"
                        class="form-control" name="title" id="title" aria-describedby="title" placeholder="Tiêu đề tin" required>
                    </div>
                    <div class="form-group">
                        <label for="post_content">Nội dung</label>
                        <textarea class="form-control" name="post_content" id="post_content" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <div class="statbox widget box box-shadow my-3">
                <div class="widget-header">
                    <div class="row">
                        <h4>Thông tin thêm</h4>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="form-row">

                        <div class="col-md-4">
                            <div class="form-group input-group-sm">
                              <label for="price">Giá tiền</label>
                              <input type="text"
                                class="form-control" name="price" id="price" placeholder="Giá tin" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group input-group-sm">
                              <label for="commission">Hoa Hồng</label>
                              <input type="text"
                                class="form-control" name="commission" id="commission" placeholder="" step="1" value="" min="0" max="100">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group input-group-sm">
                                <label for="phone">Số điện thoại</label>
                                <input type="text"
                                  class="form-control" name="phone" id="phone" placeholder="0355...." required>
                              </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="form-group input-group-sm">
                                <label for="category">Danh mục</label>
                                <select class="form-control" name="category" id="category">
                                  @foreach ($categories as $item)
                                    @if (!$item->children || count($item->children) < 1)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @continue
                                    @endif
                                    <option value="{{ $item->id }}" disabled style="font-weight: bold; color: #0e1726;"><strong> {{ $item->name }} </strong></option>
                                    @foreach ($item->children as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                  @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group input-group-sm">
                                <label for="province">Tỉnh, thành phố</label>
                                <select class="form-control" name="province" id="province">
                                    <option value="" selected>Trống</option>
                                    @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group input-group-sm">
                                <label for="district">Quận/huyện</label>
                                <select class="form-control" name="district" id="district">
                                    <option value="" selected>Trống</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="statbox widget box box-shadow my-3">
                <div class="widget-header">
                    <div class="row">
                        <h4>Thêm ảnh cho tin</h4>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="custom-file-container" data-upload-id="myFirstImage">
                        <label>Chọn ảnh đại diện <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                        <label class="custom-file-container__custom-file" >
                            <input type="file" name="avatar" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                            <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                            <span class="custom-file-container__custom-file__custom-file-control"></span>
                        </label>
                        <div class="custom-file-container__image-preview"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="statbox widget box box-shadow mb-3">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Actions</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="form-group input-group-sm">
                      <label for="type">Loại Tin</label>
                      <select class="form-control" name="type" id="type">
                        <option value="">Trống</option>
                        @foreach (PostType::getValues() as $name)
                        <option value="{{ $name }}">{{ $name }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="form-group input-group-sm">
                      <label for="status">Trạng thái</label>
                      <select class="form-control" name="status" id="status">
                        <option value="0">Bản nháp</option>
                        <option value="1">Chờ duyệt</option>
                        <option value="2">Xuất bản</option>
                      </select>
                    </div>

                    <button type="submit" class="btn btn-success"><i data-feather="plus"></i> Tạo mới</button>
                </div>
            </div>
        </div>
    </form>
</div>

<input class="d-none" type="hidden" id="data-province" value="">

@endsection

@push('script')
<script src="https://cdn.ckeditor.com/ckeditor5/20.0.0/classic/ckeditor.js"></script>
<script src="{{ asset('dashboard/plugins/input-mask/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{ asset('dashboard/plugins/file-upload/file-upload-with-preview.min.js') }}"></script>
<script>

    $('#price').inputmask({
        alias: 'currency',
        prefix: '',
        digits: 0,
        rightAlign: false
    });

    $('#phone').inputmask("9999.999.999")

    ClassicEditor
    .create(document.querySelector('#post_content'))
    .catch( err => {
        console.error( err.stack );
    });

    var firstUpload = new FileUploadWithPreview('myFirstImage')
</script>
@endpush

@push('script')
<script>
$(document).ready(() => {
    let data = JSON.parse('@json($provinces)');

    $('#province').on('change', () => {
        let id = $('#province').val();
        let district = $('#district');

        let province = data.filter((e) => {return e._id === id})[0];

        district.html('');
        district.append('<option value="" selected>Chọn</option');

        if (province === undefined) return;

        province.districts.map((e) => {
            district.append(`<option value="${e._id}">${e.name}</option`);
        });
    });
});
</script>
@endpush
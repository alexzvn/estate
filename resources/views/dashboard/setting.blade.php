@extends('dashboard.app')

@push('style')
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/elements/tooltip.css') }}">
@endpush

@section('content')
<div id="tableLight" class="col-lg-12 col-12 layout-spacing">
    <form id="update-form" class="row" action="{{ route('manager.setting.update') }}" method="POST">
        @csrf
        <div class="col-md-8 mb-4">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Cài đặt trang</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">

                    <div class="form-group input-group-sm">
                      <label for="title">Tiêu đề trang web</label>
                      <input type="text"
                        class="form-control" name="title" id="title" value="{{ $setting->title }}" placeholder="">
                    </div>

                    <div class="form-group input-group-sm">
                      <label for="notification">Thông báo trên trang</label>
                      <textarea class="form-control" name="notification" id="notification" rows="3">{{ $setting->notification }}</textarea>
                    </div>

                    <div class="form-group input-group-sm">
                        <label for="role">Vai trò mặc định cho tài khoản mới</label>
                        <select class="form-control" name="role" id="role">
                          @foreach ($roles as $item)
                              <option value="{{ $item->id }}" {{ $item->id === $setting->get('user.role.default') ? 'selected' : '' }}>{{ $item->name }}</option>
                          @endforeach
                        </select>
                      </div>

                    <div class="form-group">
                      <label for="provinces">Chọn khu vực hoạt động</label>
                      <select class="form-control tagging" name="provinces[]" id="provinces" multiple>
                        @foreach ($provinces as $item)
                            <option value="{{ $item->id }}" {{ $item->active ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="form-group input-group-sm">
                      <label for="google_analytics">Mã tracking google Analytics</label>
                      <input type="text" name="google_analytics" id="google_analytics" class="form-control" placeholder="UA-17429...." value="{{ $setting->get('google.analytics') }}">
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="reverse" id="reverse" value="true" @if($setting->get('post.reverse', false)) checked @endif>
                            Đảo tin
                          </label>
                        </div>
                    </div>

                </div>
            </div>

            <div class="statbox widget box box-shadow mt-4">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Thông báo & Popup</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">

                    <div class="form-group">
                      <label for="online">Thông báo tin Online</label>
                      <textarea class="form-control" name="online" id="online">{{ $setting->get('popup.online') }}</textarea>
                    </div>

                    <div class="form-group">
                      <label for="fee">Thông báo tin xin phí</label>
                      <textarea class="form-control" name="fee" id="fee">{{ $setting->get('popup.fee') }}</textarea>
                    </div>

                    <div class="form-group">
                      <label for="market">Thông báo tin thị trường</label>
                      <textarea class="form-control" name="market" id="market">{{ $setting->get('popup.market') }}</textarea>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Tác vụ</h4>

                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <button type="submit" class="btn btn-primary">Cập Nhật</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('script')
<script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
<script src="{{ asset('dashboard/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('dashboard/plugins/input-mask/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{ asset('dashboard/assets/js/elements/tooltip.js') }}"></script>
<script>
$(".tagging").select2({
    tags: true
});

const editor =  section => ClassicEditor.create(document.querySelector(section))

const onlineEditor = editor('#online'),
    marketEditor = editor('#market'),
    feeEditor = editor('#fee')

</script>
@endpush

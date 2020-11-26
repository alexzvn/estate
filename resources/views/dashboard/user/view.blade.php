@extends('dashboard.app')

@push('style')
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/elements/tooltip.css') }}">
@endpush
@push('meta')
    <meta name="user_id" content="{{ $user->id }}">
@endpush

@section('content')
<div id="tableLight" class="col-lg-12 col-12 layout-spacing">
    <form id="update-form" class="row" action="{{ route('manager.user.update', ['id' => $user->id]) }}" method="POST">
        @csrf
        <div class="col-md-8 mb-4">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Thông tin {{ $user->name }}</h4>

                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="form-group input-group-sm">
                      <label for="name">Họ Tên</label>
                      <input type="text" value="{{ $user->name }}" name="name" id="name" class="form-control" placeholder="Họ tên tài khoản">
                    </div>

                    <div class="form-group input-group-sm">
                      <label for="phone">
                          Số điện thoại
                            @if (! $user->hasVerifiedPhone())
                            <a href="{{ route('manager.customer.verify.phone', ['id' => $user->id]) }}" class="badge badge-warning bs-tooltip" title="Nhấn để xác thực SĐT">Chưa xác thực</a>
                            @else
                            <a href="{{ route('manager.customer.unverified.phone', ['id' => $user->id]) }}" class="badge badge-success bs-tooltip" title="Nhấn để bỏ xác thực SĐT"> Đã xác thực</a>
                            @endif
                      </label>
                      <input type="text" value="{{ $user->phone }}" name="phone" id="phone" class="form-control" placeholder="Số điện thoại">
                    </div>

                    <div class="form-group input-group-sm">
                      <label for="email">Email</label>
                      <input type="text" value="{{ $user->email }}" name="email" id="email" class="form-control" placeholder="Email">
                    </div>

                    <div class="form-group input-group-sm">
                      <label for="roles">Các vai trò <small>(có thể chọn nhiều)</small></label>
                      <select class="form-control tagging" name="roles[]" id="roles" multiple="multiple">
                        @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                      </select>
                    </div>

                    <hr>

                    <a href="javascript:void(0)" id="change-password" class="text-info">Đặt lại mật khẩu?</a>

                    <div class="mt-3" id="change-password-input" style="display: none;">
                        <div class="form-group input-group-sm">
                            <label for="password">Mật khẩu mới</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Mật khẩu mới cho tài khoản này">
                          </div>
                          <div class="form-group input-group-sm">
                            <label for="password_confirm">Nhập lại mật khẩu</label>
                            <input type="password" class="form-control" name="password_confirm" id="password_confirm" placeholder="Nhập lại mật khẩu mới">
                          </div>
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
                    @can('manager.user.modify')
                    <a id="submit" class="btn btn-primary">Cập Nhật</a>
                    @endcan
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('script')
<script src="{{ asset('dashboard/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('dashboard/plugins/input-mask/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{ asset('dashboard/assets/js/elements/tooltip.js') }}"></script>
<script>
$(".tagging").select2({
    tags: true
});

$('#submit').click(function () {
    if (confirm('Bạn có chắc muốn thực hiện các thay đổi này?')) {
        document.getElementById('update-form').submit();
    }
});

$('#change-password').click(function () {
    $('#change-password-input').fadeIn();
});

$('#email').inputmask({alias: "email"});
</script>
@endpush
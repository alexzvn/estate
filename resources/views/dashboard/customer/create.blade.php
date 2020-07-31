@extends('dashboard.app')

@section('content')
<div id="tableLight" class="col-lg-12 col-12 layout-spacing">
    <form id="update-form" class="row" action="{{ route('manager.customer.store') }}" method="POST">
        @csrf
        <div class="col-md-8 mb-4">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Tạo tài khoản mới</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="form-group input-group-sm">
                      <label for="name">Họ Tên</label>
                      <input type="text" name="name" id="name" class="form-control" placeholder="Họ tên tài khoản" required>
                    </div>

                    <div class="form-group input-group-sm">
                      <label for="phone">Số điện thoại</label>
                      <input type="text" name="phone" id="phone" class="form-control" placeholder="Số điện thoại" required>
                    </div>

                    <div class="form-group input-group-sm">
                      <label for="email">Email</label>
                      <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                    </div>

                    <div class="mt-3" id="change-password-input">
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
                    @can('manager.customer.create')
                    <button type="submit" class="btn btn-success">Tạo mới</button>
                    @endcan
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('script') 
<script src="{{ asset('dashboard/plugins/input-mask/jquery.inputmask.bundle.min.js') }}"></script>
<script>
$('#phone').inputmask("9999.999.999")
</script>
@endpush
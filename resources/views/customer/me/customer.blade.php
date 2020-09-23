@extends('customer.layouts.app')

@section('content')
<div class="container">
    <div class="row">

        @include('customer.me.components.sidebar')

        <div class="col-lg-9 col-md-8">
            <form action="{{ route('customer.self.account.update') }}" method="POST" class="frm-edit-user">
                @csrf

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Thông tin tài khoản</h4>
                                <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">

                                @if (session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        <span class="sr-only">đóng</span>
                                    </button>
                                    {{ session('success') }}
                                </div>
                                @endif

                                <div class="form-group">
                                    <label for="name">Họ và tên</label>
                                    <input id="name" name="name" value="{{ $customer->name }}"
                                    class="form-control" type="text" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 pd-r-5">
                                        <div class="form-group">
                                            <label for="phone">Số điện thoại</label>
                                            <input id="phone" name="phone" value="{{ $customer->phone }}"
                                            class="form-control @error('phone') is-invalid @enderror" type="text" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 pd-l-5">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input id="email" name="email" value="{{ $customer->email }}"
                                            class="form-control @error('email') is-invalid @enderror" type="text">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 pd-r-5">
                                        <div class="form-group"  >
                                            <label for="birthday">Ngày sinh</label>
                                            <input type="date" id="birthday" name="birthday" value="{{ $customer->birthday ? $customer->birthday->format('Y-m-d') : '' }}" placeholder="thay đổi ngày sinh"
                                            class="form-control @error('address') is-invalid @enderror">
                                            @error('birthday')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 pd-l-5">
                                        <div class="form-group">
                                            <label for="address" >Địa chỉ</label>
                                            <input id="address @error('address') is-invalid @enderror" name="address" value="{{ $customer->address }}" placeholder="Địa chỉ của bạn"
                                            class="form-control" type="text">
                                            @error('address')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <p class="text-primary lb-change-password">Đổi mật khẩu
                                        <i class="fa fa-cog" aria-hidden="true"></i>
                                    </p>

                                    <div class="group-change-pass" id="group-change-pass" @if($errors->has('password') || $errors->has('password_old') || $errors->has('password_confirm')) data-display="block" @endif>
                                        <div class="form-group">
                                            <input id="password_old" name="password_old" placeholder="Mật khẩu cũ"
                                            class="form-control @error('password_old') is-invalid @enderror" type="password">
                                            @error('password_old')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <input id="password" name="password" placeholder="Mật khẩu mới" minlength="8"
                                            class="form-control @error('password') is-invalid @enderror" type="password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-0">
                                            <input id="password_confirm" name="password_confirm" placeholder="Nhập lại mật khẩu mới"
                                            class="form-control @error('password_confirm') is-invalid @enderror" type="password">
                                            @error('password_confirm')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary btn-lg fa-pull-right">Cập nhật thông tin</button>
                </div>

            </form>
        </div>
    </div>
<div>

{{-- @include('customer.components.post-create') --}}

@endsection

@push('script')
<script>
(function(window){
    $(document).ready(function(){

        if ($('#group-change-pass').data('display') === 'block') {
            $('.group-change-pass').slideToggle();
        }

        $('body').on('click','.lb-change-password',function(){
            $('.group-change-pass').slideToggle();
        });
    });
}(window));
</script>
@endpush

@push('style')
<style>
.wrap-info {
    background: #fff;
    margin-bottom: 20px;
    padding: 20px;
}
.wrap-avatar {
    height: 130px;
    width: 130px;
    border-radius: 50%;
    margin: auto;
    overflow: hidden;
    position: relative;
}
.wrap-avatar img {
    object-fit: cover;
}

.user-status-noti {
    background: #eee;
    padding: 2px 4px 4px 4px;
    font-size: 13px;
    font-weight: 500;
}
.wrap-avatar-info a {
    font-size: 13px;
    color: #555;
}
.frm-edit-user .form-group label {
    font-weight: 500;
    font-size: 15px;
}
.frm-edit-user .form-group input,
.frm-edit-user .form-group select {
    font-size: 14px;
}
.overlay-change-avatar {
    width: 100%;
    height: 60px;
    position: absolute;
    border: 0px;
    bottom: 0px;
    cursor: pointer;
    transition: background ease-out 0.15s;
    text-align: center;
    opacity: 0;
}
.wrap-avatar:hover .overlay-change-avatar {
    background: #00000054;
    opacity: 1;
    transition: all ease-in 0.15s;
}
.overlay-change-avatar i {
    margin-top: 13px;
    font-size: 16px;
    color: #ffffff;
}
.overlay-change-avatar span {
    font-size: 13px;
    font-weight: 500;
    color: #fff;
}
.pd-l-5 {
    padding-left: 5px;
}
.pd-r-5 {
    padding-right: 5px;
}
@media screen and (max-width: 767px) {
    .pd-l-5 {
        padding-left: 15px;
    }
    .pd-r-5 {
        padding-right: 15px;
    }
}
.lb-change-password {
    cursor: pointer;
}
.group-change-pass {
    padding: 20px;
    border: 1px solid #eee;
    border-radius: 5px;
    display: none;
}
.group-change-pass input {
    margin-bottom: 20px;
}
.group-change-pass input:last-child {
    margin-bottom: 0px;
}
</style>
@endpush
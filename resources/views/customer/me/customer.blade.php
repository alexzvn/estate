@extends('customer.me.components.app')

@section('main-content')
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



@extends('layouts.app')

@section('content')
<div class="container bg-white shadow py-5">
    <div class="row">
        <div class="col-md-5">

            <form class="col-md-10 offset-md-2" method="POST" action="{{ route('register') }}">
                <h2 class="text-center">Đăng Ký Tài khoản</h2>
                @csrf

                <div class="form-group">
                    <label for="name">{{ __('Tên đăng ký') }}</label>

                    <input id="name" type="text" class="form-control rounded-0 @error('name') is-invalid @enderror" placeholder="Tên của bạn" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">{{ __('Số điện thoại') }}</label>

                    <input id="phone" type="text" class="form-control rounded-0 @error('phone') is-invalid @enderror" placeholder="VD: 0355121999" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus>

                    @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">{{ __('Password') }}</label>

                    <input id="password" type="password" class="form-control rounded-0 @error('password') is-invalid @enderror" placeholder="Mật khẩu" name="password" required autocomplete="new-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm">{{ __('Confirm Password') }}</label>
                    <input id="password-confirm" type="password" class="form-control rounded-0" name="password_confirmation" placeholder="Nhập lại mật khẩu" required autocomplete="new-password">
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-success rounded-0">
                        {{ __('Đăng ký ngay') }}
                    </button>

                    @if (false && Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </div>

                <hr class="w-50 text-center my-4">
                <p class="text-center">Đã có tài khoản rồi? <a href="{{ route('login') }}">Đăng nhập ở đây</a></p>
            </form>
        </div>
        <div class="col-md-7 text-center">
            <img class="mb-3" width="80%" src="https://cdn.vietnambiz.vn/2020/1/14/random-reinforcement-1578972191747814403297.png" alt="" srcset="">
            <h4>Chúc bạn 1 buổi tối tốt lành !</h4>
            <p>Hãy nghỉ ngơi và thư giãn cùng gia đình nhé.</p>
        </div>
    </div>
</div>
@endsection
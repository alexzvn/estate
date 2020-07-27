@extends('layouts.app')

@section('content')
<div class="container bg-white shadow py-5">
    <div class="row h-100">
        <div class="col-md-5 my-auto">

            <form class="col-md-10 offset-md-2" method="POST" action="{{ route('login') }}">
                <h2 class="text-center">Đăng nhập</h2>

                @if (session()->has('reject.title'))
                <div class="alert alert-danger" role="alert" style="border: dashed brown;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <strong>{{ session('reject.title') }}</strong>
                    @if (session()->has('reject.message'))
                    <p class="m-0" style="color: black;">{{ session('reject.message') }}</p>
                    @endif
                    
                </div>
                @endif

                

                @csrf

                <div class="form-group">
                    <label for="phone">{{ __('Số điện thoại') }}</label>

                    <input id="phone" type="text" class="form-control form-control-lg rounded-0 @error('phone') is-invalid @enderror" placeholder="Số điện thoại của bạn" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus>

                    @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">{{ __('Password') }}</label>

                    <input id="password" type="password" class="form-control form-control-lg rounded-0 @error('password') is-invalid @enderror" placeholder="Mật khẩu" name="password" required autocomplete="current-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary rounded-0">
                        {{ __('Đăng nhập bằng điện thoại') }}
                    </button>

                    @if (false && Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </div>

                <hr class="w-50 text-center my-4">
                <p class="text-center">Bạn chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a></p>
            </form>
        </div>
        <div class="col-md-7 text-center my-auto">
            <img class="mb-3" width="80%" src="https://cdn.vietnambiz.vn/2020/1/14/random-reinforcement-1578972191747814403297.png" alt="" srcset="">
            <h4>Chúc bạn 1 buổi tối tốt lành !</h4>
            <p>Hãy nghỉ ngơi và thư giãn cùng gia đình nhé.</p>
        </div>
    </div>
</div>
@endsection
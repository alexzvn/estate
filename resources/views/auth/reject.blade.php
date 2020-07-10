@extends('layouts.app')



@section('content')
<div class="container bg-white shadow py-5">
    <div class="row h-100">
        <div class="col-md-5 my-auto text-center">


            <h1 class="mb-5">Đăng nhập</h1>

            <strong style="color: red; font-size: 1.3em;">
                <p> Có vẻ như tài khoản này đã đăng nhập từ nơi khác. <br>
                    Xin hãy đăng xuất tài khoản trên thiết bị cũ hoặc đợi {{ \App\Models\User::SESSION_TIMEOUT }} phút để đăng nhập lại.
                </p>
            </strong>

            <p class="text-muted">Liên hệ hotline để được hỗ trợ thêm!</p>

            <a href="{{ route('login') }}" class="btn btn-outline-primary my-3"><i class="fas fa-arrow-left"></i> Quay lại trang đăng nhập</a>

        </div>
        <div class="col-md-7 text-center my-auto">
            <img class="mb-3" width="80%" src="https://cdn.vietnambiz.vn/2020/1/14/random-reinforcement-1578972191747814403297.png" alt="" srcset="">
            <h4>Chúc bạn 1 buổi tối tốt lành !</h4>
            <p>Hãy nghỉ ngơi và thư giãn cùng gia đình nhé.</p>
        </div>
    </div>
</div>
@endsection
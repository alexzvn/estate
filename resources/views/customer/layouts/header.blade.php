<nav class="navbar navbar-expand-md navbar-light shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            TRANG CHỦ
        </a>

        <div class="d-flex">
            {{-- @auth
            <a class="nav-link btn btn-warning d-block d-md-none mr-3" href="javascript:void(0)" data-toggle="modal"
                data-target="#create-post-modal">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-edit">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Đăng Bài
            </a>
            @endauth --}}
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>


        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            {{-- <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link"> Tôi bán</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"> Hỗ trợ</a>
                </li>
            </ul> --}}

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                {{-- <li class="nav-item d-none d-sm-block mr-md-3">
                    <a class="nav-link btn btn-warning" style="color: black;" data-toggle="modal"
                        data-target="#create-post-modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-edit">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Đăng Tin
                    </a>
                </li> --}}

                <li class="nav-item dropdown d-none">
                    <a id="notification" class="nav-link" href="#" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>

                        <span class="badge badge-danger rounded-circle notification-count">3</span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right notification shadow-sm" aria-labelledby="notification">
                        <div class="notification-head">
                            <a href="#">Thông Báo (3)</a>
                            <a href="#">Đánh dấu tất cả đã đọc</a>
                        </div>
                        <div class="notification-body">
                            <div class="d-block">
                                <span>Some thing happened at <strong>abc</strong></span> <br>
                                <small class="text-muted">10 sec ago</small>
                            </div>
                            <div class="d-block">
                                <span>Some thing happened at <strong>abc</strong></span> <br>
                                <small class="text-muted">10 sec ago</small>
                            </div>
                            <div class="d-block">
                                <span>Some thing happened at <strong>abc</strong></span> <br>
                                <small class="text-muted">10 sec ago</small>
                            </div>
                        </div>
                        <div class="notification-footer text-center">
                            Xem tất cả
                        </div>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        Xin chào, {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        @can('manager.dashboard.access')
                        <a class="dropdown-item" href="{{ route('manager') }}">Quản lý</a>
                        @endcan
                        <a class="dropdown-item" href="{{ route('customer.self.account') }}">Tài khoản</a>
                        <a class="dropdown-item" href="{{ route('customer.self.orders') }}">Đơn hàng</a>
                        <a class="dropdown-item" href="{{ route('customer.self.subscriptions') }}">Gói đăng ký</a>
                        <a class="dropdown-item" href="{{ route('customer.self.history') }}">Lịch sử</a>

                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

@push('script')
<script>
$('#notification').on('hide.bs.dropdown', function (e) {
    if (e.clickEvent) {
      e.preventDefault();
    }
});
</script>
@endpush
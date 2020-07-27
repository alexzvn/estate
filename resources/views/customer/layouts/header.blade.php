<nav class="navbar navbar-expand-md navbar-light shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>

        <div class="d-flex">
            @auth
            <a class="nav-link btn btn-warning d-block d-md-none mr-3" href="{{ route('register') }}"> <i class="far fa-edit"></i> Đăng Bài</a>
            @endauth
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
            @auth
            <li class="nav-item">
                <a href="#" class="nav-link"><i class="fas fa-landmark"></i> Tôi bán</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link"><i class="far fa-comments"></i> Hỗ trợ</a>
            </li>
            @endauth
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary" style="color: white" href="{{ route('register') }}">Đăng ký ngay</a>
                    </li>
                    @endif
                @else
                    <li class="nav-item d-none d-sm-block mr-md-3">
                        <a class="nav-link btn btn-warning" style="color: black;" href="{{ route('register') }}">
                            <i class="far fa-edit"></i> Đăng Tin
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Xin chào, {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
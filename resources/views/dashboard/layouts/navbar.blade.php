    <!--  BEGIN NAVBAR  -->
    <div class="header-container fixed-top">
        <header class="header navbar navbar-expand-sm">

            <ul class="navbar-item theme-brand flex-row  text-center">
                <li class="nav-item theme-logo">
                    <a href="{{ route('manager') }}">
                        <img src="{{ asset('assets/img/90x90.jpg') }}" class="navbar-logo" alt="logo">
                    </a>
                </li>
                <li class="nav-item theme-text">
                    <a href="{{ route('manager') }}" class="nav-link"> CORK </a>
                </li>
            </ul>

            <ul class="navbar-item flex-row ml-md-auto">

                <li class="nav-item dropdown notification-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg><span class="badge badge-success"></span>
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="notificationDropdown">
                        <div class="notification-scroll">

                            @foreach (user()->notifications->take(15) as $notify)
                            @php
                                $payload = (object) $notify->data;
                            @endphp

                            <div class="dropdown-item @isset($payload->link) cursor-pointer link @endisset" data-link="@isset($payload->link) {{ $payload->link }} @endisset">
                                <div class="media">
                                    <i data-feather="{{ $payload->feather_icon ?? '' }}"></i>
                                    <div class="media-body">
                                        <div class="notification-para">
                                            <p>{{ $payload->message ?? '' }}</p>
                                            <span class="text-muted">{{ $notify->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>
                    </div>
                </li>


                <li class="nav-item dropdown user-profile-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <img src="{{ asset('assets/img/90x90.jpg') }}" alt="avatar">
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                        <div class="">
                            <div class="dropdown-item">
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"><i data-feather="log-out"></i> Đăng xuất</a>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </header>
    </div>
    <!--  END NAVBAR  -->

    @push('script')
    <script>
    (function (window) {
        $(document).ready(function () {
            $('.link').on('click', function () {
                let link = $(this).data('link');
                if (link) window.location.href = link;
            });
        });
    }(window))
    </script>
    @endpush
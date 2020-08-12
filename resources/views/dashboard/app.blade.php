<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('meta')
    <title>{{ $setting->title}}</title>

    @if (! session('loader'))
    <link href="{{ asset('dashboard/assets/css/loader.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('dashboard/assets/js/loader.js') }}"></script>
        @php
        session(['loader' => true]);
        @endphp
    @endif

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('dashboard/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dashboard/assets/css/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dashboard/plugins/notification/snackbar/snackbar.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->

    <style>
        .layout-px-spacing {
            min-height: calc(100vh - 166px)!important;
        }

        .form-group label, label {
            font-size: 16px;
            color: #161a2d;
        }
    </style>

    @stack('style')

    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

</head>
<body>
    @if (! session('loader'))
    @include('dashboard.layouts.loader')
    @endif

    @include('dashboard.layouts.navbar')
    @include('dashboard.layouts.breadcrumbs')

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">


        <div class="overlay"></div>
        <div class="search-overlay"></div>


        @include('dashboard.layouts.sidebar')

        <!--  BEGIN CONTENT PART  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="row layout-top-spacing">

                    @yield('content')

                </div>

            </div>
            <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                    <p class="">Copyright © 2020 <a target="_blank" href="https://designreset.com">DesignReset</a>, All rights reserved.</p>
                </div>
                <div class="footer-section f-section-2">
                    <p class="">Coded with <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></p>
                </div>
            </div>
        </div>
        <!--  END CONTENT PART  -->

    </div>
    <!-- END MAIN CONTAINER -->

    <form class="d-none" id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form>

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('dashboard/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('dashboard/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('dashboard/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('dashboard/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('dashboard/plugins/notification/snackbar/snackbar.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/app.js') }}"></script>
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script src="/assets/js/custom.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="{{ asset('dashboard/plugins/font-icons/feather/feather.min.js') }}"></script>
    <script>feather.replace();</script>
    @include('dashboard.layouts.alert')
    @stack('script')
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
</body>
</html>
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $setting->title }}</title>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/assets/css/font-awesome.min.css') }}">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css"> --}}

    <!-- Styles -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/snackbar/snackbar.min.css') }}">
    @stack('style')
    

    <style>
        body {
            /* background: url("{{ asset('assets/img/background-1.jpg') }}") no-repeat center; */
        }
    </style>
</head>
<body>
    <div id="app">
        @include('customer.layouts.header')

        <main class="py-4">
            @yield('content')
        </main>

        @include('customer.layouts.footer')
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    @if ($tracking = $setting->get('google.analytics', false))
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $tracking }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{ $tracking }}');
    </script>
    @endif

    <script src="{{ asset('assets/snackbar/snackbar.min.js') }}"></script>
    @stack('script')
</body>
</html>

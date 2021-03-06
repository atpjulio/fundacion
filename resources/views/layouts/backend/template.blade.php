<!doctype html>
<html class="no-js" lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>
        @yield('title', config('constants.companyInfo.name'))
    </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Place favicon.ico in the root directory -->
    <link rel="icon" href="{{ asset('favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('modular-admin/css/vendor.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/regular.css') }}">
    <link rel="stylesheet" href="{{ asset('css/solid.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css').'?version='.config('constants.stylesVersion') }}">

    <!-- Theme initialization -->
    <script>
        var themeSettings =  (localStorage.getItem('themeSettings')) ? JSON.parse(localStorage.getItem('themeSettings')) : {};
        var themeName = themeSettings.themeName || '';

        if (themeName) {
            document.write('<link rel="stylesheet" id="theme-style" href="/modular-admin/css/app-' + themeName + '.css">');
        }
        else {
            document.write('<link rel="stylesheet" id="theme-style" href="/modular-admin/css/app-green.css">');
        }
    </script>
    @stack('styles')
</head>
<body>
    <div class="main-wrapper">
        <div class="app" id="app">
            @include('layouts.backend.header')
            @include('layouts.backend.sidebar')

            <div class="mobile-menu-handle"></div>
            <article class="content">
                @include('partials.messages')
                @yield('content')
            </article>

            @include('layouts.backend.footer')
            @include('layouts.backend.modals')
        </div>
    </div>
    <!-- Reference block for JS -->
    <div class="ref" id="ref">
        <div class="color-primary"></div>
        <div class="chart">
            <div class="color-primary"></div>
            <div class="color-secondary"></div>
        </div>
    </div>
    {{--#if GOOGLE_ANALYTICS_ID
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', '{{GOOGLE_ANALYTICS_ID}}', 'auto');
        ga('send', 'pageview');

    </script>
    --}}
    <script src="{{ asset('modular-admin/js/vendor.js') }}"></script>
    <script src="{{ asset('modular-admin/js/app.js') }}"></script>
    <script src="{{ asset('js/app.js').'?version='.config('constants.stylesVersion') }}"></script>
    <script src="{{ asset('js/global.js').'?version='.config('constants.stylesVersion') }}"></script>
    @stack('scripts')
    <input type="hidden" value="{{ csrf_token() }}" name="_tokenBase" id="_tokenBase" /> 
</body>
</html>

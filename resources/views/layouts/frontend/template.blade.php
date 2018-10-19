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

    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Place favicon.ico in the root directory -->

    <link rel="stylesheet" href="{{ asset('modular-admin/css/vendor.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/regular.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/solid.min.css') }}">
    <link rel="icon" href="{{ asset('favicon.png') }}">
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
<div class="auth" style="background-image: url('/img/fundacionBg1.jpg'); background-repeat: no-repeat; background-size: cover;">
    <div class="auth-container">
        <div class="card">
            <header class="auth-header">
                <h1 class="d-block d-sm-none">
                    <img src="{{ asset('img/logo.png') }}" height="110" >
                </h1>
                <h1 class="auth-title" style="color: #333 !important;">
                    <div class="logo d-none d-sm-block">
                        <img src="{{ asset('img/logo.png') }}" height="60">
                    </div> 
                    {!! config('constants.companyInfo.longName') !!} 
                </h1>
            </header>
            @yield('content')
        </div>
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
<script src="{{ asset('modular-admin/js/vendor.js') }}"></script>
<script src="{{ asset('modular-admin/js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
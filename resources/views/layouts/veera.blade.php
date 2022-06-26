<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description')">
    <meta name="facebook-domain-verification" content="maoq8bfxpspvvt8vmxcyt9dhojxtui" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!--=== Favicon ===-->
    <link rel="shortcut icon" href="/assets/img/favicon.ico" type="image/x-icon"/>

    <!--== Google Fonts ==-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,600,700,800" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">


    <!--=== Revolution Slider CSS ===-->
    <link href="/assets/css/revslider/settings.css" rel="stylesheet">

    <!--=== Bootstrap CSS ===-->
    <link href="/assets/css/vendor/bootstrap.min.css" rel="stylesheet">
    <!--=== Font-Awesome CSS ===-->
    <link href="/assets/css/vendor/font-awesome.css" rel="stylesheet">
    <!--=== Dl Icon CSS ===-->
    <link href="/assets/css/vendor/dl-icon.css" rel="stylesheet">
    <!--=== Plugins CSS ===-->
    <link href="/assets/css/plugins.css" rel="stylesheet">
    <!--=== Helper CSS ===-->
    <link href="/assets/css/helper.min.css" rel="stylesheet">
    <!--=== Main Style CSS ===-->
    <link href="/assets/css/style.min.css" rel="stylesheet">
    @yield('styles-after')
    <style>
    .img-lang{
        height:20px;
    }
    </style>
    <!-- Modernizer JS -->
    <script src="/assets/js/vendor/modernizr-2.8.3.min.js"></script>

    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body class="preloader-active">
    <!--== Start PreLoader Wrap ==-->
    <div class="preloader-area-wrap">
        <div class="spinner d-flex justify-content-center align-items-center h-100">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div>
    <!--== End PreLoader Wrap ==-->

    @include('partials.header')

    @yield('content')

    @include('partials.footer')
    @include('partials.additional')
    <!--=======================Javascript============================-->
    <!--=== Jquery Min Js ===-->
    <script src="/assets/js/vendor/jquery-3.3.1.min.js"></script>
    <!--=== Jquery Migrate Min Js ===-->
    <script src="/assets/js/vendor/jquery-migrate-1.4.1.min.js"></script>
    <!--=== Popper Min Js ===-->
    <script src="/assets/js/vendor/popper.min.js"></script>
    <!--=== Bootstrap Min Js ===-->
    <script src="/assets/js/vendor/bootstrap.min.js"></script>
    <!--=== Plugins Js ===-->
    <script src="/assets/js/plugins.js"></script>

    
    <script>var isMember = {{ (int) auth()->check() }};</script>
    <!--=== Active Js ===-->
    <script src="/assets/js/active.js"></script>

    @yield('scripts')
    <script>
    $( "#form-search" ).submit(function( event ) {
        event.preventDefault();
        var val = $('#form-search #search').val();
        var dt = $('#i-product').data('url');
        var url = window.location.origin;
        if(dt){
            url = dt;
            if(val){
                url = url+"&search="+val;
            }
        }else{
            url = url+'/shop?search='+val;
        }
        window.location.replace(url);
    });
    </script>
    
</body>
</html>

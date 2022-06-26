<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Administrator | @yield('title')</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
  @yield('styles-before')
  <link rel="stylesheet" href="/css/admin/plugins.bundle.css">
  <link rel="stylesheet" href="/css/admin/style.bundle.css">
  <style>
    .a-logo{color:#fff}
  </style>
  @yield('styles-after')
  <link rel="shortcut icon" href="/assets/admin/media/logos/favicon.ico" />
</head>
<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-aside--enabled kt-aside--fixed">
  @yield('content')
  <script>
    var KTAppOptions = {
      "colors": {
        "state": {
          "brand": "#5d78ff",
          "dark": "#282a3c",
          "light": "#ffffff",
          "primary": "#5867dd",
          "success": "#34bfa3",
          "info": "#36a3f7",
          "warning": "#ffb822",
          "danger": "#fd3995"
        },
        "base": {
          "label": [
            "#c5cbe3",
            "#a1a8c3",
            "#3d4465",
            "#3e4466"
          ],
          "shape": [
            "#f0f3ff",
            "#d9dffa",
            "#afb4d4",
            "#646c9a"
          ]
        }
      }
    };
  </script>
  <script src="/js/admin/plugins.bundle.js" type="text/javascript"></script>
  <script src="/js/admin/scripts.bundle.js" type="text/javascript"></script>
  @yield('scripts')

  <script>
    @if(session('status'))
    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": false,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    };

    toastr.success("{!! session('status') !!}", "SUKSES");
    @endif

    @if(session('error'))
    toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": false,
      "progressBar": false,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    };

    toastr.warning("{!! session('error') !!}", "{{ __('main.failed') }}");
    @endif

    @if (isset($errors) && $errors->any())
    toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": false,
      "progressBar": false,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    };

    toastr.warning("Silahkan periksa kembali form yang anda isi!", "{{ __('main.failed') }}");
    @endif



  </script>
</body>
</html>

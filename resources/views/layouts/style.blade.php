<!-- App favicon -->
<link rel="shortcut icon" href="{{asset('assets/logos/logo.png')}}">
<!-- jsvectormap css -->
<link href="{{asset('assets/libs/jsvectormap/css/jsvectormap.min.css')}}" rel="stylesheet" type="text/css" />

<!--Swiper slider css-->
<link href="{{asset('assets/libs/swiper/swiper-bundle.min.css')}}" rel="stylesheet" type="text/css" />

<!-- Layout config Js -->
<script src="{{asset('assets/js/layout.js')}}"></script>
<!-- Bootstrap Css -->
<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
<!-- custom Css-->
<link href="{{asset('assets/css/custom.min.css')}}" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="http://cdn.datatables.net/1.10.18/css/jquery.dataTables.min.css">

@yield('css')

<link rel="stylesheet" href="{{ asset('assets/backend/vendors/iconfonts/font-awesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/backend/vendors/css/vendor.bundle.base.css') }}">
<link rel="stylesheet" href="{{ asset('assets/backend/vendors/css/vendor.bundle.addons.css') }}">
<link rel="stylesheet" href="{{ asset('assets/backend/css/style.css') }}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css">

@stack('style')

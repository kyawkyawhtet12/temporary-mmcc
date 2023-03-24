<!doctype html>
<html lang="en" data-layout="vertical" data-sidebar="dark" data-sidebar-size="lg" data-layout-mode="light"
    data-layout-width="fluid" data-layout-position="fixed" data-layout-style="default" data-topbar="dark">

<head>

    <meta charset="utf-8" />
    <title> Dashboard </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('layouts.style')

</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        @include('layouts.header')
        <!-- ========== App Menu ========== -->

        @if (Auth::user()->is_admin)
            @include('layouts.sidebar.admin')
        @else
            @include('layouts.sidebar.staff')
        @endif
        <!-- Left Sidebar End -->

        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            @yield('content')
            <!-- End Page-content -->

            @include('layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    @include('layouts.script')

</body>

</html>

<!doctype html>
<html lang="en" data-layout="twocolumn" data-sidebar="light" data-sidebar-size="lg" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>

        <meta charset="utf-8" />
        <title> 2D/3D </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('assets/logos/logo.png')}}">

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

        <style>
            .card{
                box-shadow: 0 1px 2px rgb(56 65 74 / 45%) !important;
            }

            .btn-success{
                background-color: #212529 !important;
                border-color: #212529 !important;
            }
        </style>


    </head>

    <body>

        <div class="auth-page-wrapper pt-5">
            <!-- auth page bg -->
            {{-- <div class="auth-one-bg-position">
                <div class="bg-overlay"></div>
            </div> --}}

            <!-- auth page content -->
                @yield('content')
            <!-- end auth page content -->
            
            <!-- footer -->
            <footer class="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <p class="mb-0 text-muted">
                                    &copy; <?= now()->format('Y') ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->
        </div>
        <!-- end auth-page-wrapper -->

        <!-- JAVASCRIPT -->
        <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>
        <script src="{{asset('assets/libs/feather-icons/feather.min.js')}}"></script>
        <script src="{{asset('assets/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
        <script src="{{asset('assets/js/plugins.js')}}"></script>

        <!-- particles js -->
        <script src="{{asset('assets/libs/particles.js/particles.js')}}"></script>
        <!-- particles app js -->
        <script src="{{asset('assets/js/pages/particles.app.js')}}"></script>
        <!-- password-addon init -->
        <script src="{{asset('assets/js/pages/password-addon.init.js')}}"></script>
    </body>

</html>

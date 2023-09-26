<!doctype html>
<html lang="en" data-layout="vertical" data-sidebar="dark" data-sidebar-size="lg" data-layout-mode="light"
    data-layout-width="fluid" data-layout-position="fixed" data-layout-style="default" data-topbar="dark">

<head>

    <meta charset="utf-8" />
    <title> Dashboard </title>
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> --}}
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('assets/logos/logo.png') }}">

    {{-- <link href="{{ asset('assets/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" /> --}}
    <link href="{{ asset('assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />

    {{-- <script src="{{ asset('assets/js/layout.js') }}"></script> --}}
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="{{ asset('assets/backend/vendors/iconfonts/font-awesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/vendors/css/vendor.bundle.addons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/style.css') }}">

    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.18/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css">

    @yield('css')
    @stack('style')

</head>

<body>

    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex align-items-center">
                        <button type="button"
                            class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                            id="topnav-hamburger-icon">
                            <span class="hamburger-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>

                        <?php

                            $prev_route = url()->previous();

                            if( strpos(request()->url(), 'ballone-add-result/maung') ){
                                $prev_route = Session::get('prev_route') ?? '/admin/ballone/maung';
                            }

                            if( strpos(request()->url(), 'ballone-add-result/body') ){
                                $prev_route = Session::get('prev_route') ?? '/admin/ballone/body';
                            }

                            if( strpos(request()->url(), 'body-report') ){
                                $prev_route = Session::get('prev_route') ?? '/admin/ballone/body';
                            }

                        ?>

                        <a class="btn btn-sm btn-info ms-5 px-3" href="{{ $prev_route }}">
                            Back
                        </a>
                    </div>

                    <div class="d-flex align-items-center">

                        @if (is_admin())
                            <div class="me-5">
                                <input class="close-all-bets" type="checkbox" data-toggle="toggle"
                                    data-onstyle="success" data-offstyle="danger"
                                    data-on="<i class='fa fa-pause'></i> Close All Bets"
                                    data-off="<i class='fa fa-play'></i> Open All Bets"
                                    {{ $enabled->close_all_bets ? '' : 'checked' }}>
                            </div>
                        @endif

                        <div class="dropdown ms-sm-3 header-item topbar-user">
                            <button type="button" class="btn" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    <img class="rounded-circle header-profile-user"
                                        src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="Header Avatar">
                                    <span class="text-start ms-xl-2">
                                        <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">
                                            {{ Auth::user()->name }}</span>
                                        <span class="d-none d-xl-block ms-1 mt-2 fs-12 text-muted user-name-sub-text">
                                            {{ Auth::user()->is_admin ? 'Admin' : 'Staff' }}
                                        </span>
                                    </span>
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <h6 class="dropdown-header">Welcome {{ Auth::user()->name }}!</h6>
                                <a class="dropdown-item" href="{{ route('profile') }}"><i
                                        class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                                        class="align-middle">Profile</span></a>

                                <a class="dropdown-item" href="#"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
                                    <span class="align-middle" data-key="t-logout">Logout</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </header>

        @include('layouts.sidebar.admin')

        <div class="vertical-overlay"></div>

        <div class="main-content">

            @yield('content')

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="text-sm-end d-none d-sm-block">
                                @ {{ date('Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </footer>

        </div>
    </div>

    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.0/jquery.min.js"
        integrity="sha512-suUtSPkqYmFd5Ls30Nz6bjDX+TCcfEzhFfqjijfdggsaFZoylvTj+2odBzshs0TCwYrYZhQeCgHgJEkncb2YVQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>

    <script src="{{ asset('assets/backend/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/backend/vendors/js/vendor.bundle.addons.js') }}"></script>
    <script src="{{ asset('assets/backend/js/formpickers.js') }}"></script>
    <script src="{{ asset('assets/backend/js/form-addons.js') }}"></script>
    <script src="{{ asset('assets/backend/js/x-editable.js') }}"></script>
    <script src="{{ asset('assets/backend/js/dropify.js') }}"></script>
    <script src="{{ asset('assets/backend/js/jquery-file-upload.js') }}"></script>
    <script src="{{ asset('assets/backend/js/formpickers.js') }}"></script>
    <script src="{{ asset('assets/backend/js/form-repeater.js') }}"></script>
    <script src="{{ asset('assets/backend/js/date-range.js') }}"></script>
    <script src="{{ asset('assets/backend/js/jquery.tabledit.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/file-upload.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/dropzone.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "showDuration": "300",
                "closeDuration": "100",
                "timeOut" : "3000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut",
                "closeMethod": "fadeOut"
            }

            $('.close-all-bets').change(function() {
                $.ajax({
                    type: "GET",
                    url: '{{ route('close-all-bets') }}',
                    success: function(data) {
                        toastr.success('* Successfully Done');
                    }
                });
            });
        });
    </script>

    @yield('script')
    @stack('scripts')


</body>

</html>

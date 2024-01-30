@extends('layouts.master')

@section('css')
    <style>
        @media (min-width: 768px) {
            .col-md-1 {
                flex: 0 0 9% !important;
                max-width: 9% !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Admin Dashboard</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row grid-margin">
                <div class="col-12  grid-margin stretch-card">
                    <div class="card card-statistics">
                        <div class="card-body">
                            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                                <div class="statistics-item">
                                    <p>
                                        <i class="icon-sm fa fa-user mr-2"></i>
                                        All Agents
                                    </p>
                                    <h2>{{ $total_agent }}</h2>
                                </div>
                                <div class="statistics-item">
                                    <p>
                                        <i class="icon-sm fa fa-user mr-2"></i>
                                        All Users
                                    </p>
                                    <h2>{{ $total_user }}</h2>
                                </div>
                                <div class="statistics-item">
                                    <p>
                                        <i class="icon-sm fas fa-hourglass-half mr-2"></i>
                                        Total Amount
                                    </p>
                                    <h2>{{ $total_amount }}</h2>
                                </div>

                                <div class="statistics-item">
                                    <p>
                                        <i class="icon-sm fas fa-chart-line mr-2"></i>
                                        Agent Withdraw (Today)
                                    </p>
                                    <h2>{{ $agent_withdraw }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-12">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Lottery opening and closing</h4>
                                <p class="card-description">
                                    Switchery Button <code>to change</code>
                                </p>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="bg-primary text-white" role="row">
                                                <th>#</th>
                                                <th>Two Digit</th>
                                                <th>Three Digit</th>
                                                <th>Current Date</th>
                                                <th>Current Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>
                                                    <input class="toggle-thai" type="checkbox" data-toggle="toggle"
                                                        data-onstyle="success" data-offstyle="danger"
                                                        data-on="<i class='fa fa-pause'></i> Enabled"
                                                        data-off="<i class='fa fa-play'></i> Disabled"
                                                        {{ $enabled->two_thai_status === 0 ? 'checked' : '' }}>
                                                </td>
                                                <td>
                                                    <input class="toggle-three" type="checkbox" data-toggle="toggle"
                                                        data-onstyle="success" data-offstyle="danger"
                                                        data-on="<i class='fa fa-pause'></i> Enabled"
                                                        data-off="<i class='fa fa-play'></i> Disabled"
                                                        {{ $enabled->three_status === 0 ? 'checked' : '' }}>
                                                </td>
                                                <td>
                                                    <span style="font-size: 20px; font-weight: bold;" id='date-part'></span>
                                                </td>
                                                <td>
                                                    <span style="font-size: 20px; font-weight: bold;" id='time-part'></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(document).on("click", ".checkbox", function() {
                var idsArr = [];
                $(".checkbox:checked").each(function() {
                    idsArr.push($(this).attr('data-id'));
                });
                if (idsArr.length > 0) {
                    $('.orderActionContainer').show();
                } else {
                    $('.orderActionContainer').hide();
                }
                console.log(idsArr)
            });


            var interval = setInterval(function() {
                var momentNow = moment();
                $('#date-part').html(momentNow.format('dddd') + ', ' + momentNow.format('MMMM DD, YYYY'));
                $('#time-part').html(momentNow.format('hh:mm:ss A'));
            }, 100);

            $('.toggle-thai').change(function() {
                var status = $(this).prop('checked') == true ? 0 : 1;
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{ route('two.thai.changeStatus') }}',
                    data: {
                        'status': status
                    },
                    success: function(data) {
                        toastr.options.closeButton = true;
                        toastr.options.closeMethod = 'fadeOut';
                        toastr.options.closeDuration = 100;
                        toastr.success(data.message);
                    }
                });
            })

            $('.toggle-three').change(function() {
                var status = $(this).prop('checked') == true ? 0 : 1;
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{ route('three.changeStatus') }}',
                    data: {
                        'status': status
                    },
                    success: function(data) {
                        toastr.options.closeButton = true;
                        toastr.options.closeMethod = 'fadeOut';
                        toastr.options.closeDuration = 100;
                        toastr.success(data.message);
                    }
                });
            })

        });
    </script>
@endpush

@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0"> Payment Report </h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active"> Payment Report</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row input-daterange aign-items-center">

                                <div class="col-md-4">
                                    <h5 class="mb-0">{{ $agent->name }}</h5>
                                </div>

                                <div class="col">
                                    <div class="input-group">
                                        <input type="text" name="start_date" id="start_date" class="form-control"
                                            placeholder="From Date" readonly />
                                        <span class="input-group-addon input-group-append border-left">
                                            <span class="far fa-calendar input-group-text"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="input-group">
                                        <input type="text" name="end_date" id="end_date" class="form-control"
                                            placeholder="To Date" readonly />
                                        <span class="input-group-addon input-group-append border-left">
                                            <span class="far fa-calendar input-group-text"></span>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-3 row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary btn-block"
                                            id="filter">Filter</button>
                                    </div>

                                    <div class="col-6">
                                        <button type="button" class="btn btn-success btn-block"
                                            id="refresh">Refresh</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-body">

                            <div class="mb-4 d-flex justify-content-between">
                                <h6 class="mr-5"> Total Deposit :
                                    <span class="text-success ml-2 totalDeposit"> 0 </span>
                                </h6>
                                <h6 class="mr-5"> Total Withdraw :
                                    <span class="text-danger ml-2 totalWithdraw"> 0 </span>
                                </h6>

                                <h6> Total Net Amount :
                                    <span class="text-info ml-2 totalNetAmount"> 0 </span>
                                </h6>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap data-table text-right">
                                            <thead>
                                                <tr class="bg-primary text-white" role="row">
                                                    <th>No.</th>
                                                    <th>Date</th>
                                                    <th>Deposit</th>
                                                    <th>Withdrawal</th>
                                                    <th>Net Amount </th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
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
    <script>
        $('.input-daterange').datepicker({
            todayBtn: 'linked',
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        load_data();

        function load_data(start_date = '', end_date = '') {
            $('.data-table').DataTable({
                processing: true,
                "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>'
                },
                bFilter: false, // search input
                serverSide: true,
                ajax: {
                    url: "{{ route('payment.report', $agent->id) }}",
                    data: function(d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    },
                    dataFilter: function(res) {

                        let {
                            total
                        } = jQuery.parseJSON(res);

                        $(".totalDeposit").text(total.deposit);
                        $(".totalWithdraw").text(total.withdraw);
                        $(".totalNetAmount").text(total.net);

                        return res;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'deposit',
                        name: 'deposit'
                    },
                    {
                        data: 'withdraw',
                        name: 'withdraw'
                    },
                    {
                        data: 'net',
                        name: 'net'
                    },
                ],
            });
        }

        $('#filter').click(function() {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            if (start_date != '' && end_date != '') {
                $('.data-table').DataTable().destroy();
                load_data(start_date, end_date);
            } else {
                alert('Both Date is required');
            }
        });

        $('#refresh').click(function() {
            $('#start_date').val('');
            $('#end_date').val('');
            $('.data-table').DataTable().destroy();
            load_data();
        });

        $('.js-example-basic-single').change(function() {
            $('.data-table').DataTable().draw();
        });
    </script>
@endpush

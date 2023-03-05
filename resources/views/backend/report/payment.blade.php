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
                        <div class="card-body">
                            <h4 class="card-title"> Agent Payment Report </h4>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="payments" class="table table-bordered table-hover nowrap">
                                            <thead>
                                                <tr class="bg-primary text-white" role="row">
                                                    <th>No.</th>
                                                    <th>Date</th>
                                                    <th>Agent</th>
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
        var table = $('#payments').DataTable({
            processing: true,
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>'
            },
            serverSide: true,
            ajax: {
                url: "{{ route('payment.report', $id) }}",
                data: function(d) {
                    d.search = $('input[type="search"]').val()
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
                    data: 'agent',
                    name: 'agent'
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
    </script>
@endpush

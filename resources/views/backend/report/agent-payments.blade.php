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
                            <div class="row input-daterange">
                                <form action="{{ route('agent.payment-reports.search') }}" method="POST" class="col-md-10 row">
                                    @csrf
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <select name="agent" id="agentSelect" class="form-control">
                                                <option value="all">All</option>
                                                @foreach ($agents as $agent)
                                                <option value="{{ $agent->id }}" {{ ($select_agent == $agent->id ? 'selected' : '') }}> {{ $agent->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" class="form-control" placeholder="Start Date" name="start_date">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" class="form-control" placeholder="End Date" name="end_date">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" name="filter" id="filter"
                                            class="btn btn-primary">Filter</button>
                                        <button type="button" name="refresh" id="refresh"
                                            class="btn btn-success">Refresh</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 my-3 d-flex">
                                    <h6 class="mr-5"> Recharge : <span class="text-info">{{ $total_recharge }}</span></h6>
                                    <h6 class="ml-5"> Cash : <span class="text-info">{{ $total_cash }}</span></h6>
                                </div>
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap data-table">
                                            <thead>
                                                <tr class="bg-primary text-white" role="row">
                                                    <th>No.</th>
                                                    <th>Date</th>
                                                    <th>Recharge</th>
                                                    <th>Cash</th>
                                                    <th>Net Amount </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ( $data as $x => $dt )

                                                    <tr>
                                                        <td> {{ ++$x }}</td>
                                                        <td> {{ $dt->created_at->format('d-m-Y'); }}</td>
                                                        <td> {{ $dt->deposit }}</td>
                                                        <td> {{ $dt->withdraw }}</td>
                                                        <td> {{ $dt->deposit - $dt->withdraw }}</td>
                                                    </tr>

                                                @endforeach
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
    </div>
@endsection

@push('scripts')
    <script>
        // $('.input-daterange').datepicker({
        //     todayBtn: 'linked',
        //     format: 'yyyy-mm-dd',
        //     autoclose: true
        // });

        // load_data();

        function load_data() {
            $('.data-table').DataTable({
                processing: true,
                "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>'
                },
                serverSide: true,
                searching : false,
                ajax: {
                    url: "{{ route('agent.payment-reports') }}",
                    data: function(d) {
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                        d.agent = $('#agentSelect').val();
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

        // $('#filter').click(function() {
        //     $('.data-table').DataTable().destroy();
        //     load_data();
        // });

        $('#refresh').click(function() {
            // $('#from_date').val('');
            // $('#to_date').val('');
            // $('#agentSelect').val('all');
            // $("#from_date, #to_date").datepicker('setDate', null);
            // $('.data-table').DataTable().destroy();
            // load_data();

            window.location = "/admin/agent-payment-reports";
        });
    </script>
@endpush

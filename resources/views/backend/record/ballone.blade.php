@extends('layouts.master')

@section('css')
    <style>
        input {
            padding: 0.6rem !important;
        }

        .multiSelect button {
            padding: 0.6rem !important;
        }

        table .done {
            background-color: #dff8ff;
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
                        <h4 class="mb-sm-0">Betting {{ $type }} Record</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Game</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row mb-3 d-flex">

                <div class="col-md-3 multiSelect">
                    <x-agent-select />
                </div>

                <div class="col-md-3 multiSelect">
                    <x-football-round-select />
                </div>

                <div class="col">
                    <input type="date" class="form-control" placeholder="Start Date" name="start_date" id="start_date">
                </div>

                <div class="col">
                    <input type="date" class="form-control" placeholder="End Date" name="end_date" id="end_date">
                </div>

                <div class="col">
                    <div class="row">
                        <button type="button" class="btn btn-primary col mx-1 btn-sm" id="search">Filter</button>
                        <a href="#" class="btn btn-danger col mx-1 btn-sm" id="refresh">Refresh</a>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h6> Betting Amount : <span class="ml-2 text-info totalBettingAmount"> 0 </span></h6>
                            <h6> Win Amount : <span class="ml-2 text-info totalWinAmount"> 0 </span></h6>
                            <h6> Net Amount : <span class="ml-2 text-info totalNetAmount"> 0 </span></h6>
                            <h6> Status : <span class="ml-2 text-info totalStatus"> ... </span></h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="datatable" class="table table-bordered nowrap text-center">
                                            <thead>
                                                <tr class="bg-primary text-white" role="row">
                                                    <th>Round</th>
                                                    <th>Betting Amount</th>
                                                    <th>Win Amount </th>
                                                    <th>Net Amount </th>
                                                    <th>Status</th>
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
        $(function() {

            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: {
                    url: "{{ route('ballone.record', $type) }}",
                    data: function(d) {
                        d.search = $('input[type="search"]').val(),
                        d.agent_id = $('#agent_id').val(),
                        d.round = $('#round').val(),
                        d.start_date = $('#start_date').val(),
                        d.end_date = $('#end_date').val()
                    },
                    dataFilter: function(res) {

                        let {
                            total
                        } = jQuery.parseJSON(res);

                        $(".totalBettingAmount").text(total.betting);
                        $(".totalWinAmount").text(total.win);
                        $(".totalNetAmount").text(total.net);
                        $(".totalStatus").text(total.status);

                        return res;
                    }
                },
                columns: [{
                        data: 'round',
                        name: 'round',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'betting_amount',
                        name: 'betting_amount'
                    },
                    {
                        data: 'win_amount',
                        name: 'win_amount'
                    },
                    {
                        data: 'net_amount',
                        name: 'net_amount',
                    },
                    {
                        data: 'status',
                        name: 'status'
                    }
                ],
            });

            $("#search").on('click', function(e) {
                e.preventDefault();
                $("#datatable").DataTable().ajax.reload();
            });

            $("#refresh").on('click', function(e) {
                e.preventDefault();
                $('#agent_id').multiselect('reset');
                $('#round').multiselect('reset');
                $('#start_date').val('');
                $('#end_date').val('');
                $("#datatable").DataTable().ajax.reload();
            });

        });
    </script>
@endpush

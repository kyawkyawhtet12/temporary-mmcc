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
                        <h4 class="mb-sm-0">Betting Maung Record</h4>

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
                    <select name="agent_id[]" id="agent_id" multiple="multiple" class="agentSelect form-control">
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}">
                                {{ $agent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 multiSelect">
                    <select name="round[]" id="round" multiple="multiple" class="roundSelect form-control">
                        @for ($i = $current_round; $i >= 1; $i--)
                            <option value="{{ $i }}">
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col">
                    <input type="date" class="form-control" placeholder="Start Date" name="start_date" id="start_date">
                </div>

                <div class="col">
                    <input type="date" class="form-control" placeholder="End Date" name="end_date" id="end_date">
                </div>

                <div class="col">
                    <button type="button" class="btn btn-primary btn-block btn-sm" id="search">Search</button>
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

            setTimeout(() => {
                calculateTotal();
            }, 700);

            function calculateTotal() {

                let totalBetting = 0;
                let totalWin = 0;

                $(".bettingAmount").each(function(e) {
                    totalBetting += parseFloat($(this).data('amount'));
                });

                $(".winAmount").each(function(e) {
                    totalWin += parseFloat($(this).data('amount'));
                });

                let totalNet = totalBetting - totalWin;

                $(".totalBettingAmount").text(totalBetting.toLocaleString());

                $(".totalWinAmount").text(totalWin.toLocaleString());

                $(".totalNetAmount").text(totalNet.toLocaleString());

                $(".totalStatus").text(getStatus(totalNet));

            }

            function getStatus(amount) {

                if (amount > 0) return "Win";

                if (amount < 0) return "No Win";

                return "...";
            }

            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                ajax: {
                    url: "{{ route('maung.record') }}",
                    data: function(d) {
                        d.search = $('input[type="search"]').val(),
                            d.agent_id = $('#agent_id').val(),
                            d.round = $('#round').val(),
                            d.start_date = $('#start_date').val(),
                            d.end_date = $('#end_date').val()
                    }
                },
                columns: [
                    {
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

                setTimeout(() => {
                    calculateTotal();
                }, 700);

            });

            $('.agentSelect').multiselect({
                columns: 2,
                placeholder: 'Select Agent',
                search: true,
                searchOptions: {
                    'default': 'Search Agents'
                },
                selectAll: true
            });

            $('.roundSelect').multiselect({
                columns: 3,
                placeholder: 'Select Round',
                search: true,
                searchOptions: {
                    'default': 'Search Rounds'
                },
                selectAll: true
            });

        });

    </script>
@endpush

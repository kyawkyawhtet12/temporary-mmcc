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

        <div class="d-flex justify-content-center align-items-center w-100 vh-100" id="loader">
            <img src="{{ asset('assets/backend/images/loader.gif') }}" alt="" width="200px">
        </div>

        <div class="container-fluid d-none" id="mainpage">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Betting Record</h4>

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

                    <div class="col-md-2 multiSelect">
                        <select name="agent_id[]" id="agent_id" multiple="multiple" class="agentSelect form-control">
                            @foreach ($agents as $agent)
                                <option value="{{ $agent->id }}" >
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col">
                        <input type="text" class="form-control" placeholder="User ID" name="user_id" id="user_id"
                            value="{{ Session::get('search.user_id') }}">
                    </div>


                    <div class="col">
                        <select name="type" id="type" class="form-control">
                            <option value=""> All Type </option>
                            @foreach (get_all_types() as $type)
                                <option>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col">
                        <input type="number" class="form-control" placeholder="Min Amount" name="min" id="min_amount" >
                    </div>

                    <div class="col">
                        <input type="number" class="form-control" placeholder="Max Amount" name="max" id="max_amount" >
                    </div>

                    <div class="col">
                        <input type="date" class="form-control" placeholder="Start Date" name="start_date" id="start_date" >
                    </div>

                    <div class="col">
                        <input type="date" class="form-control" placeholder="End Date" name="end_date" id="end_date" >
                    </div>

                    <div class="col">
                        <button type="button" class="btn btn-primary btn-block btn-sm" id="search">Search</button>
                    </div>

            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="datatable" class="table table-bordered nowrap">
                                            <thead>
                                                <tr class="bg-primary text-white" role="row">
                                                    <th>No.</th>
                                                    <th>User ID</th>
                                                    <th>Type</th>
                                                    <th>Count</th>
                                                    <th>Amount</th>
                                                    <th>Time</th>
                                                    <th>Betting Results </th>
                                                    <th>Betting Wins </th>
                                                    <th></th>
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

            {{-- Betting Detail --}}
            <div class="row grid-margin">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-header">
                            <h5> Betting Detail </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive" id="betting-detail">
                                <table id="body" class="table table-bordered nowrap text-center">
                                    <thead id="betting-heading">
                                    </thead>

                                    <tbody id="betting-data">

                                    </tbody>

                                    <tfoot id="betting-total">

                                    </tfoot>
                                </table>
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
                $("#loader").removeClass('d-flex').addClass('d-none');
                $("#mainpage").removeClass('d-none');
            }, 700);

            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength : 15,
                ajax: {
                    url: "{{ route('betting.record') }}",
                    data: function(d) {
                        d.search = $('input[type="search"]').val(),
                        d.agent_id = $('#agent_id').val(),
                        d.user_id = $('#user_id').val(),
                        d.type = $('#type').val(),
                        d.min_amount = $('#min_amount').val(),
                        d.max_amount = $('#max_amount').val(),
                        d.start_date = $('#start_date').val(),
                        d.end_date = $('#end_date').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user_id',
                        name: 'user_id',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'type',
                        name: 'type',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'count',
                        name: 'count',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'amount',
                        name: 'amount',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'time',
                        name: 'time',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'results',
                        name: 'results',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'wins',
                        name: 'wins',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
            });

            $("#search").on('click',function(e){
                e.preventDefault();
                $("#datatable").DataTable().ajax.reload();
            });

            $('.agentSelect').multiselect({
                columns: 1,
                placeholder: 'Select Agent',
                search: true,
                searchOptions: {
                    'default': 'Search Agents'
                },
                selectAll: true
            });

            const url_prefix = "/admin/betting-record/detail/";

            let total_columns;

            let num = 1;

            let no_match_lists = ["2D", "3D"];

            let no_match_status = false;

            const columns = ['No', 'Match', 'Betting', 'Odds', 'Betting Type', 'Results', 'Betting Amount',
                'Betting Wins'
            ];

            add_table_heading();

            add_table_body();

            add_table_footer();

            function add_table_heading() {
                let th = '';

                columns.forEach((dt, index) => {
                    th += `<th class="${dt.toLowerCase()}-column"> ${dt} </th>`;
                });

                $("#betting-heading").html(` <tr> ${th} </tr>`);

                total_columns = columns.length;
            }

            function add_table_body() {
                $('table tr').removeClass('text-danger');
                $("#betting-data").html(`<tr> <td colspan="${total_columns}"> No Data Available. </td> </tr>`);
            }

            function add_table_footer() {
                let colspan = (no_match_status) ? total_columns - 3 : total_columns - 2;

                $("#betting-total").html(`
                                        <tr>
                                            <td colspan="${colspan}">Total Amount</td>
                                            <td id='betting-amount'></td>
                                            <td id='win-amount'> </td>
                                        </tr>`);
            }

            $('body').on('click', '.viewDetail', function() {

                add_table_heading();

                add_table_body();

                fetchData(url_prefix + $(this).data('id'));

                // $(this).addClass('text-danger');

            });

            function fetchData(url) {
                fetch(`${url}`)
                    .then((response) => response.json())
                    .then((data) => {

                        let tr = '';

                        data.betting.forEach((dt, index) => {

                            tr += `
                            <tr>
                                <td> ${index + 1} </td>
                                <td class="match-column"> ${dt.match} </td>
                                <td> ${dt.betting} </td>
                                <td> ${dt.odds} </td>
                                <td> ${data.type} </td>
                                <td> ${dt.result} </td>
                                <td> ${dt.amount} </td>
                                <td> ${dt.win}</td>
                            </tr>`;
                        });

                        $("#betting-data").html(tr);

                        no_match_status = no_match_lists.includes(data.type);

                        if (no_match_status) {
                            $(".match-column").addClass('d-none');
                        }

                        add_table_footer();

                        $("tfoot #betting-amount").html(data.amount);
                        $("tfoot #win-amount").html(data.win_amount);
                    });
            }
        });
    </script>
@endpush

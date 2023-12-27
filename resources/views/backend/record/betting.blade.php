@extends('layouts.master')

@section('css')
    <style>
        input {
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
                <div class="col-md-2">
                    <select name="agent" id="agentSelect" class="form-control">
                        <option value="all">All</option>
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}" {{ $select_agent == $agent->id ? 'selected' : '' }}>
                                {{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <form action="{{ route('betting.record.search') }}" method="POST" class="col-md-10 row pr-0">
                    @csrf

                    <div class="col-md-11 row">
                        <div class="col-md-2">
                            <input type="text" class="form-control" placeholder="User ID" name="user_id">
                        </div>

                        <div class="col-md-2">
                            <select name="type" id="type" class="form-control">
                                <option value="all"> All Type </option>
                                @foreach (get_all_types() as $type)
                                    <option {{ $select_type == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <input type="number" class="form-control" placeholder="Min Amount" name="min">
                        </div>

                        <div class="col-md-2">
                            <input type="number" class="form-control" placeholder="Max Amount" name="max">
                        </div>

                        <div class="col-md-2">
                            <input type="date" class="form-control" placeholder="Start Date" name="start_date">
                        </div>

                        <div class="col-md-2">
                            <input type="date" class="form-control" placeholder="End Date" name="end_date">
                        </div>
                    </div>

                    <div class="col-md-1">
                        <input type="submit" class="form-control btn btn-primary btn-sm" name="search" value="Search">
                    </div>
                </form>

            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="payments" class="table table-bordered nowrap">
                                            <thead>
                                                <tr class="bg-primary text-white" role="row">
                                                    <th>User ID</th>
                                                    <th>Type</th>
                                                    <th>Count</th>
                                                    <th>Amount</th>
                                                    <th>Time</th>
                                                    <th>Betting Results </th>
                                                    <th>Betting Wins </th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @forelse($data as $dt)
                                                    <tr class="viewDetail" data-id="{{ $dt->id }}">
                                                        <td>{{ $dt->user->user_id }}</td>
                                                        <td>{{ $dt->type }}</td>
                                                        <td>{{ $dt->count }}</td>
                                                        <td>{{ number_format($dt->amount) }}</td>
                                                        <td>{{ $dt->created_at }}</td>
                                                        <td>
                                                            {{ $dt->result ?? "No Prize" }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($dt->win_amount ?? 0) }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center"> No Data Available. </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    {{ $data->links() }}
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

            const url_prefix = "/admin/betting-record/detail/";

            let total_columns ;

            let num = 1;

            let no_match_lists = [ "2D", "3D" ];

            let no_match_status = false;

            const columns = [ 'No', 'Match', 'Betting', 'Odds', 'Betting Type', 'Results', 'Betting Amount' , 'Betting Wins' ];

            add_table_heading();

            add_table_body();

            add_table_footer();

            $("#agentSelect").on('change', function() {
                window.location.href = `?agent=${$(this).val()}`;
            });

            function add_table_heading()
            {
                let th = '';

                columns.forEach((dt,index) => {
                    th += `<th class="${dt.toLowerCase()}-column"> ${dt} </th>`;
                });

                $("#betting-heading").html(` <tr> ${th} </tr>`);

                total_columns = columns.length;
            }

            function add_table_body()
            {
                $('table tr').removeClass('text-danger');
                $("#betting-data").html(`<tr> <td colspan="${total_columns}"> No Data Available. </td> </tr>`);
            }

            function add_table_footer()
            {
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

                $(this).addClass('text-danger');

            });

            function fetchData(url)
            {
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

                    if(no_match_status){
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

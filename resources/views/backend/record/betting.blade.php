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
                                                    <tr class="viewDetail" data-id="{{ $dt->id }}" data-type="{{ $dt->type }}">
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
                            <div class="table-responsive">
                                <table id="body" class="table table-bordered nowrap text-center">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Betting </th>
                                            <th>Odds</th>
                                            <th>Betting Type</th>
                                            <th>Betting Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="betting-data">
                                        <tr>
                                            <td colspan="5">No Data Available.</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4">Total Amount</td>
                                            <td id='total-betting-amount'></td>
                                        </tr>
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

            $("#agentSelect").on('change', function() {
                let agent_id = $(this).val();
                window.location.href = `?agent=${agent_id}`;
            });

            function getUpTeam(data) {
                return (data.upteam == 1) ? getHomeTeam(data) : getAwayTeam(data);
            }

            function getMatch(data) {
                return `${getHomeTeam(data)} Vs ${getAwayTeam(data)}`;
            }

            function getHomeTeam(data) {
                return `(${data.match.home_no}) ${data.match.home.name}`;
            }

            function getAwayTeam(data) {
                return `(${data.match.away_no}) ${data.match.away.name}`;
            }

            function getFees(data) {
                return (data.type == 'home' || data.type == 'away') ?
                    `${getUpTeam(data)} ${data.fees.body}` :
                    `${data.fees.goals}`;
            }

            function getType(data) {
                switch (data.type) {
                    case 'home':
                        return getHomeTeam(data);
                        break;
                    case 'away':
                        return getAwayTeam(data);
                        break;
                    case 'over':
                        return `${getHomeTeam(data)} ( Goal Over )`;
                        break;
                    case 'under':
                        return `${getHomeTeam(data)} ( Goal Under )`;
                        break;
                }
            }

            $('body').on('click', '.viewDetail', function() {
                let id = $(this).data('id');
                let type = $(this).data('type');

                $('table tr').removeClass('text-danger');

                $("#betting-data").html(`<tr>
                                            <td colspan="5">No Data Available.</td>
                                        </tr>`);

                fetch(`/admin/betting-record/${type}/detail/${id}`, {
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: "same-origin"
                })
                .then((response) => response.json())
                .then((data) => {

                    let tr = '';

                    if (data.type == '2D') {
                        data.two_digit.forEach((dt, index) => {
                            tr += `<tr>
                                <td> ${index + 1} </td>
                                <td> ${dt.twodigit.number} </td>
                                <td> ${dt.za} </td>
                                <td> 2D </td>
                                <td> ${dt.amount.toLocaleString('en-US')} </td>
                            </tr>`;
                        });
                    }

                    if (data.type == '3D') {
                        data.three_digit.forEach((dt, index) => {
                            tr += `<tr>
                                <td> ${index + 1} </td>
                                <td> ${dt.threedigit.number} </td>
                                <td> ${dt.za} </td>
                                <td> 3D </td>
                                <td> ${dt.amount.toLocaleString('en-US')} </td>
                            </tr>`;
                        });
                    }

                    if (data.type == 'Body') {
                        data.ballone.forEach((dt, index) => {
                            tr += `<tr>
                                <td> ${index + 1} </td>
                                <td> ${getType(dt.body)} </td>
                                <td> ${getFees(dt.body)} </td>
                                <td> Body </td>
                                <td> ${dt.amount.toLocaleString('en-US')} </td>
                            </tr>`;
                        });
                    }

                    if (data.type == 'Maung') {
                        data.ballone[0].maung.teams.forEach((dt, index) => {
                            tr += `<tr>
                                <td> ${index + 1} </td>
                                <td> ${getType(dt)} </td>
                                <td> ${getFees(dt)} </td>
                                <td> Maung </td>
                                <td> - </td>
                            </tr>`;
                        });
                    }

                    $("#betting-data").html(tr);
                    $("#total-betting-amount").html(data.amount.toLocaleString('en-US'));
                });

                $(this).addClass('text-danger');
            });
        });
    </script>
@endpush

@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0"> Match Report </h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Ballone</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            {{-- Body Report List --}}
            <div class="row grid-margin">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-header">
                            <h5> Body Report </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="body" class="table table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>Agent</th>
                                            <th>Betting Time</th>
                                            <th>Total Betting Amount</th>
                                            <th>Betting Result</th>
                                            <th>Betting Wins</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($body as $k => $dt)
                                            <tr>
                                                <td>{{ $dt->user?->user_id }}</td>
                                                <td>{{ $dt->agent?->name }}</td>
                                                <td>{{ $dt->betting_time }}</td>
                                                <td>{{ $dt->bet?->amount }}</td>
                                                <td>{{ $dt->bet?->status_format }}</td>
                                                <td>{{ $dt->bet?->net_amount }}</td>
                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-success btn-sm viewBody"
                                                        data-id="{{ $dt->id }}">
                                                        view
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Body Detail --}}
            <div class="row grid-margin">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-header">
                            <h5> Betting Detail </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="maung" class="table table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Betting Team</th>
                                            <th>Betting Type</th>
                                            <th>Odds</th>
                                            <th>Betting Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="betting-body-data">
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Maung Report List --}}
            <div class="row grid-margin">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-header">
                            <h5> Maung Report </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="maung" class="table table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>Agent</th>
                                            <th>Betting Time</th>
                                            <th>Total Betting Amount</th>
                                            <th>Betting Count</th>
                                            <th>Betting Result</th>
                                            <th>Betting Wins</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($maung as $k => $dt)
                                            <tr>
                                                <td>{{ $dt->user?->user_id }}</td>
                                                <td>{{ $dt->agent?->name }}</td>
                                                <td>{{ $dt->betting_time }}</td>
                                                <td>{{ $dt->bet?->bet?->amount }}</td>
                                                <td>{{ $dt->count() }}</td>
                                                <td>{{ $dt->bet?->bet?->status_format }}</td>
                                                @if ($dt->bet?->bet?->status == 0)
                                                    <td> 0 </td>
                                                @else
                                                    <td>{{ $dt->bet?->bet?->net_amount }}</td>
                                                @endif
                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-success btn-sm viewMaung"
                                                        data-id="{{ $dt->maung_group_id }}">
                                                        view
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Maung Detail --}}
            <div class="row grid-margin">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-header">
                            <h5> Betting Detail </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="maung" class="table table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Betting Team</th>
                                            <th>Betting Type</th>
                                            <th>Odds</th>
                                            <th>Betting Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="betting-maung-data">
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- container-fluid -->

    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var body = $('#body').DataTable();
            var maung = $('#maung').DataTable();

            function getFees(data) {
                // console.log(data);
                let fees = (data.type == 'home' || data.type == 'away') ? data.fees.body : data
                    .fees.goals;

                return fees;
            }

            function getType(data) {
                switch (data.type) {
                    case 'home':
                        return data.match.home.name;
                        break;
                    case 'away':
                        return data.match.away.name;
                        break;
                    case 'over':
                        return 'Goal Over';
                        break;
                    case 'under':
                        return 'Goal Under';
                        break;
                }
            }

            $('body').on('click', '.viewBody', function() {
                let id = $(this).data('id');

                fetch(`/admin/football/body-detail/${id}`, {
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        credentials: "same-origin"
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        let tr = '';

                        var fees = getFees(data);
                        var type = getType(data);

                        tr += `<tr>
                                    <td> 1 </td>
                                    <td> ${type} </td>
                                    <td> Body </td>
                                    <td> ${fees} </td>
                                    <td> ${data.bet.amount}</td>
                                </tr>`;

                        $("#betting-body-data").html(tr);

                    });
            });

            $('body').on('click', '.viewMaung', function() {
                let id = $(this).data('id');

                fetch(`/admin/football/maung-detail/${id}`, {
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        credentials: "same-origin"
                    })
                    .then((response) => response.json())
                    .then((res) => {
                        let tr = '';

                        res.forEach((data, index) => {

                            var fees = getFees(data);
                            var type = getType(data);

                            tr += `<tr>
                                    <td> ${index + 1} </td>
                                    <td> ${type} </td>
                                    <td> Maung </td>
                                    <td> ${fees} </td>
                                    <td> ${data.bet.bet.amount}</td>
                                </tr>`;
                        });

                        $("#betting-maung-data").html(tr);

                    });
            });
        });
    </script>
@endsection

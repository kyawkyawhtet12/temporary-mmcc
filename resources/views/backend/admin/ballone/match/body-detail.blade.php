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
                                <table id="body" class="table table-bordered nowrap text-center">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>Agent</th>
                                            <th>Betting Time</th>
                                            <th>Total Betting Amount</th>
                                            <th>Betting Result</th>
                                            <th>Betting Wins</th>
                                            <th>Betting Cancel</th>
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
                                                    @if( $dt->refund ==0 )
                                                        <a href="{{ route('ballone.body.refund', $dt->id) }}"
                                                            class="btn btn-danger btn-sm"
                                                            data-id="{{ $dt->id }}">
                                                            Cancel
                                                        </a>
                                                    @endif
                                                </td>
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
                                <table id="body" class="table table-bordered nowrap text-center">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Match</th>
                                            <th>Betting Team</th>
                                            <th>Betting Type</th>
                                            <th>Odds</th>
                                            <th>Result</th>
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

            function getUpTeam(data) {
                return (data.upteam == 1) ? getHomeTeam(data) : getAwayTeam(data);
            }

            function getMatch(data) {
                return `${getHomeTeam(data)} Vs ${getAwayTeam(data)}`;
            }

            function getHomeTeam(data){
                return `(${data.match.home_no}) ${data.match.home.name}`;
            }

            function getAwayTeam(data){
                return `(${data.match.away_no}) ${data.match.away.name}`;
            }

            function getFees(data) {
                return (data.type == 'home' || data.type == 'away')
                        ? `${getUpTeam(data)} ${data.fees.body}`
                        : data.fees.goals;
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
                        return `Goal Over`;
                        break;
                    case 'under':
                        return `Goal Under`;
                        break;
                }
            }

            function getResult(data) {
                return (data.match.type == 0) ? "P-P" :
                    (data.match.calculate_body) ? (data.match.body_temp_score) : '-';
            }

            // For Body Detail View
            $('body').on('click', '.viewBody', function() {
                let id = $(this).data('id');

                $('table tr').removeClass('text-danger');

                fetch(`/admin/football/body-detail/${id}`, {
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        credentials: "same-origin"
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        let tr = '';
                        tr += `<tr>
                                    <td> 1 </td>
                                    <td> ${getMatch(data)} </td>
                                    <td> ${getType(data)} </td>
                                    <td> Body </td>
                                    <td> ${getFees(data)} </td>
                                    <td> ${getResult(data)} </td>
                                    <td> ${data.bet.amount}</td>
                                </tr>`;

                        $("#betting-body-data").html(tr);
                    });

                $(this).parent().parent().addClass('text-danger');
            });

        });
    </script>
@endsection

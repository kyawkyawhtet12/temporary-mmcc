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

            <div class="row">
                <div class="col-12 stretch-card">
                    <div class="card bg-light">
                        <div class="card-body px-5">
                            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between text-center">

                                <div>
                                    <h5 class="mb-3"> Home </h5>
                                    <h4>{{ $data['report']['home'] ?? 0 }}</h4>
                                </div>

                                <div>
                                    <h5 class="mb-3"> Away </h5>
                                    <h4>{{ $data['report']['away'] ?? 0 }}</h4>
                                </div>

                                <div>
                                    <h5 class="mb-3"> Goal Over </h5>
                                    <h4>{{ $data['report']['over'] ?? 0 }}</h4>
                                </div>

                                <div>
                                    <h5 class="mb-3"> Goal Under </h5>
                                    <h4>{{ $data['report']['under'] ?? 0 }}</h4>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Body Report List --}}
            <div class="row grid-margin">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-header">
                            <h5> Body Report </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table" class="table table-bordered nowrap text-center">
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
                                        @foreach ($data['bettings'] as $x => $bet)
                                            <tr>
                                                <td>{{ $bet->user->user_id }}</td>

                                                <td>{{ $bet->agent->name }}</td>

                                                <td>{{ $bet->betting_time }}</td>

                                                <td>{{ $bet->amount_format }}</td>

                                                <td class="status-{{ $bet->id }}">
                                                    {{ $bet->status_format }}
                                                </td>

                                                <td>{{ $bet->net_amount }}</td>

                                                <td>
                                                    @if(is_admin())
                                                    @if ($bet->status == 0)
                                                        <a href="javascript:void(0)" class="btn btn-danger btn-sm cancelBet"
                                                            data-id="{{ $bet->body_id }}"
                                                            data-url="{{ route('ballone.body.refund') }}"
                                                        >
                                                            Cancel
                                                        </a>
                                                    @endif
                                                    @endif
                                                </td>

                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-success btn-sm viewDetail"
                                                        data-id="{{ $bet->body_id }}"
                                                        data-type="body-detail"
                                                        data-amount="{{ $bet->amount_format }}">
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
            @include("backend.admin.ballone.match.partials.report_detail")

            @include("backend.admin.ballone.match.partials._betting_cancel")
\
        </div>
        <!-- container-fluid -->
    </div>
@endsection



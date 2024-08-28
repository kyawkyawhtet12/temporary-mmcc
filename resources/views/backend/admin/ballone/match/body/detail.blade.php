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
                                        @foreach ($body as $k => $dt)
                                            <tr>
                                                <td>{{ $dt->user?->user_id }}</td>
                                                <td>{{ $dt->agent?->name }}</td>
                                                <td>{{ $dt->betting_time }}</td>
                                                <td>{{ $dt->bet?->amount_format }}</td>
                                                <td class="status-{{ $dt->id }}">{{ $dt->bet?->status_format }}</td>
                                                <td>{{ $dt->bet?->net_amount }}</td>
                                                <td>
                                                    @if(is_admin())
                                                    @if ($dt->refund == 0 && $dt->bet?->status == 0)
                                                        <a href="javascript:void(0)" class="btn btn-danger btn-sm cancelBet"
                                                            data-id="{{ $dt->id }}"
                                                            data-url="{{ route('ballone.body.refund') }}"
                                                        >
                                                            Cancel
                                                        </a>
                                                    @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-success btn-sm viewDetail"
                                                        data-id="{{ $dt->id }}"
                                                        data-type="body-detail"
                                                        data-amount="{{ $dt->bet?->amount_format }}">
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
            <x-ballone.view-detail></x-ballone.view-detail>

            <x-ballone.betting-cancel></x-ballone.betting-cancel>
        </div>
        <!-- container-fluid -->
    </div>
@endsection



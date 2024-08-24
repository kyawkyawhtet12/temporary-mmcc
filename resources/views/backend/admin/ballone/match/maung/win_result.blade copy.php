@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0"> Maung Win Result </h4>

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

            {{-- Maung Report List --}}
            <div class="row grid-margin">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                Round {{ $data['round'] }} , &nbsp; Maung Win Result
                            </h6>

                            <h6 class="mb-0">
                                Total Win Amount :
                                <span class="text-info">
                                    {{ number_format($data['total_win_amount']) }}
                                    ( {{ $data['total_count'] }} )
                                </span>
                            </h6>

                        </div>

                        <div class="card-body">
                            <div class="table-responsive">

                                <table id="table" class="table table-striped table-bordered nowrap text-center">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Agent</th>
                                            <th>User ID</th>
                                            <th>Betting Time</th>
                                            <th>Betting Count</th>
                                            <th>Betting Amount</th>
                                            <th>Win Amount</th>
                                            <th>Result</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($wins as $x => $win)
                                            <tr>
                                                <td>{{ ++$x }}</td>
                                                <td>{{ $win->agent?->name }}</td>
                                                <td>{{ $win->user?->user_id }}</td>
                                                <td>{{ $win->created_at->format('d-m-Y h:i A') }}</td>
                                                <td>{{ $win->total_count }}</td>
                                                <td>{{ number_format($win->amount) }}</td>
                                                <td>{{ number_format($win->net_amount) }}</td>
                                                <td class="{{ $win->result_color }}">
                                                    {{ $win->result }}
                                                </td>
                                                <td>{{ $win->updated_at->format('d-m-Y h:i A') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                {{ $wins->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

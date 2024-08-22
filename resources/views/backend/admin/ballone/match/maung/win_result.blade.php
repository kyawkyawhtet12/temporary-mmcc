@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0"> Match Win Result </h4>

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
                        <div class="card-header">
                            <h5> Maung Win Result </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table" class="table table-bordered nowrap text-center">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>Agent</th>
                                            <th>Betting Time</th>
                                            <th>Betting Amount</th>
                                            <th>Betting Count</th>
                                            <th>Betting Wins</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($wins as $x => $win)
                                            <tr>
                                                <td>{{ $win->user?->user_id }}</td>
                                                <td>{{ $win->agent?->name }}</td>
                                                <td>{{ $win->created_at }}</td>
                                                <td>{{ $win->amount }}</td>
                                                <td>{{ $win->total_count }}</td>
                                                <td>{{ $win->net_amount }}</td>
                                                <td>{{ $win->updated_at }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

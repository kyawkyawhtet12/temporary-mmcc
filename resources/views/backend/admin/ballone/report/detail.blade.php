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

            <div class="row grid-margin">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-header">
                            <h5> Body Report </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="leagues" class="table table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th>Round</th>
                                            <th>League</th>
                                            <th>Date Time</th>
                                            <th>Match</th>
                                            <th colspan="2">Body</th>
                                            <th>Goals</th>
                                            <th>Result</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($body as $k => $dt)
                                            <tr>
                                                <td>{{ $dt->match->round }}</td>
                                                <td>{{ $dt->match->league->name }}</td>
                                                <td>{{ get_date_time_format($dt) }}</td>
                                                <td>
                                                    <a href="{{ route('ballone.body.report', $dt->match->id) }}">
                                                        {{ $dt->match->home->name }} vs {{ $dt->match->away->name }}
                                                    </a>
                                                </td>
                                                <td>{{ $dt->match->score }}</td>
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
        <!-- container-fluid -->
    </div>
@endsection

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
                                <table id="body" class="table table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>Agent</th>
                                            <th>Betting Time</th>
                                            <th>Total Betting Amount</th>
                                            <th>Betting Result</th>
                                            <th>Betting Wins</th>
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
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


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

@section('script')
    <script>
        $(document).ready(function() {
            var body = $('#body').DataTable();
            var maung = $('#maung').DataTable();
        });
    </script>
@endsection

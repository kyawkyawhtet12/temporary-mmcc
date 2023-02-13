@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0"> Today Report</h4>

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
                                @foreach ($data as $k => $dt)
                                    <tr>
                                        <td>{{ $dt->round }}</td>
                                        <td>{{ $dt->league->name }}</td>
                                        <td>{{ $dt->date_time }}</td>
                                        <td>
                                            <a href="{{ route('ballone.maung.report', $dt->id) }}">
                                                {{ $dt->home->name }} vs {{ $dt->away->name }}
                                            </a>
                                        </td>

                                        <td>

                                            <div>
                                                @if ($dt->maungFess?->up_team == 1)
                                                    <div class="mt-3"> {{ $dt->maungFess?->body }}
                                                    </div>
                                                @else
                                                    <div class="mt-3">-</div>
                                                @endif

                                                @foreach ($dt->oldMaungfees as $old)
                                                    @if ($old->up_team == 1)
                                                        <div class="mt-3"> {{ $old->body }} </div>
                                                    @else
                                                        <div class="mt-3">-</div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                @if ($dt->maungFess?->up_team == 2)
                                                    <div class="mt-3"> {{ $dt->maungFess?->body }}
                                                    </div>
                                                @else
                                                    <div class="mt-3">-</div>
                                                @endif

                                                @foreach ($dt->oldMaungfees as $old)
                                                    @if ($old->up_team == 2)
                                                        <div class="mt-3"> {{ $old->body }} </div>
                                                    @else
                                                        <div class="mt-3">-</div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>

                                        <td>
                                            <div>
                                                <div class="mt-3"> {{ $dt->maungFess?->goals }} </div>
                                                @foreach ($dt->oldMaungfees as $old)
                                                    <div class="mt-3">
                                                        {{ $old->goals }} </div>
                                                @endforeach
                                            </div>
                                        </td>

                                        <td>{{ $dt->score }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-md-3">
                            {{ $data->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- container-fluid -->
    </div>
@endsection

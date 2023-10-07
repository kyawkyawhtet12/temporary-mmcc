@extends('layouts.master')

@section('css')
    <style>
        #results input {
            width: 70px !important;
            padding: 7px 10px !important;
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
                        <h4 class="mb-sm-0">Match Maung Result</h4>

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
                <div class="col-lg-8 offset-lg-2">
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong> Success</strong> {{ Session::get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-body">

                            <table class="table">
                                <tr>
                                    <td> League </td>
                                    <td> : </td>
                                    <td> {{ $match->league->name }} </td>
                                </tr>
                                <tr>
                                    <td> Date Time </td>
                                    <td> : </td>
                                    <td> {{ get_date_time_format($match) }} </td>
                                </tr>
                            </table>

                            <form action="{{ route('calculate.maung.result', $match->id) }}" class="my-3" method="POST">
                                @csrf
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="up-team">
                                            {{ $match->home_team }}
                                        </span>
                                    </div>

                                    <input type="number" name="home" class="form-control" min="0"
                                        value="{{ Session::get('refresh') ? $match->maung_score(0) : '' }}">
                                    <input type="number" name="away" class="form-control" min="0"
                                        value="{{ Session::get('refresh') ? $match->maung_score(1) : '' }}">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="down-team">
                                            {{ $match->away_team }}
                                        </span>
                                    </div>
                                </div>

                                <div class="d-flex">
                                    <button class="btn btn-sm btn-info mt-3 mr-2"> Refresh </button>

                                    @if (!$match->calculate_maung && $match->maung_temp_score && Session::get('refresh'))
                                        <a href="javascript:void(0)" class="btn btn-sm btn-success mt-3 result-done"
                                            data-url="{{ route('ballone.calculate.maung.result', $match->id) }}"
                                        >
                                            Done
                                        </a>
                                    @endif

                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5> Maung Fees </h5>
                                <h5 class="text-danger" id="error-message"> </h5>
                            </div>
                            <table class="table" id="results">

                                <thead>
                                    <tr>
                                        <th> </th>
                                        <th> Fees / Goals </th>
                                        <th> : </th>
                                        <th> Home </th>
                                        <th> Away </th>
                                        <th> Over </th>
                                        <th> Under </th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($match->allMaungFees as $key => $fee)
                                        @if ($fee)
                                            <tr>
                                                <td>
                                                    {{ $match->upteam_name($fee->up_team) }}
                                                </td>
                                                <td>
                                                    {{ $fee?->body }} / {{ $fee?->goals }}
                                                </td>
                                                <td> : </td>

                                                @if (Session::get('refresh'))
                                                    <form action="{{ route('manual.maung.result', $fee->result->id) }}"
                                                        class="my-3" method="POST">
                                                        @csrf

                                                        <td> {!! $fee?->result?->check_result('home') !!} </td>
                                                        <td> {!! $fee?->result?->check_result('away') !!} </td>

                                                        <td> {!! $fee?->result?->check_result('over') !!} </td>
                                                        <td> {!! $fee?->result?->check_result('under') !!} </td>

                                                        <td> {!! $fee?->result?->check_button() !!}</td>

                                                    </form>
                                                @else
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td></td>
                                                @endif
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include("backend.admin.ballone.match.components.result-manual-script")

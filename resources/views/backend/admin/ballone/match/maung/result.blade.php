@extends('layouts.master')

@section('content')
    <div class="page-content">

        <div class="d-flex justify-content-center align-items-center w-100 vh-100" id="loader">
            <img src="{{ asset('assets/backend/images/loader.gif') }}" alt="" width="200px">
        </div>

        <div class="container-fluid d-none" id="mainpage">

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

            <div class="row justify-content-center">
                <div class="col-lg-11">
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

                                    <div class="input-group-prepend col-4 p-0">
                                        <span class="input-group-text w-100 justify-content-center" id="up-team">
                                            {{ $match->home_team }}
                                        </span>
                                    </div>

                                    @if (!$match->calculate_maung)
                                        <input type="number" name="home" class="form-control text-center" min="0"
                                            value="{{ Session::get('refresh') ? $match->maung_score(0) : '' }}">

                                        <input type="number" name="away" class="form-control text-center" min="0"
                                            value="{{ Session::get('refresh') ? $match->maung_score(1) : '' }}">
                                    @else
                                        <div class="input-group-prepend col-4 p-0">
                                            <span class="input-group-text w-100 justify-content-center">
                                                {{ $match->maung_temp_score }}
                                            </span>
                                        </div>
                                    @endif

                                    <div class="input-group-prepend col-4 p-0">
                                        <span class="input-group-text w-100 justify-content-center" id="down-team">
                                            {{ $match->away_team }}
                                        </span>
                                    </div>
                                </div>

                                @if (!$match->calculate_maung)
                                    <div class="d-flex">
                                        <button class="btn btn-sm btn-info mt-3 mr-2"> Refresh </button>

                                        @if ($match->maung_temp_score && Session::get('refresh'))
                                            <a href="javascript:void(0)" class="btn btn-sm btn-success mt-3 result-done"
                                                data-url="{{ route('ballone.calculate.maung.result', $match->id) }}">
                                                Done
                                            </a>
                                        @endif
                                    </div>
                                @endif

                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <x-ballone.result-manual type="maung" :match="$match" :fees="$match->allMaungFees" :isCalculationDone="$match->calculate_maung" />

        </div>
    </div>


@endsection

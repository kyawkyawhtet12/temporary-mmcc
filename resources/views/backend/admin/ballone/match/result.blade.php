@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Match Result</h4>

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
                <div class="col-lg-6 offset-lg-3">
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
                                    <td> Round </td>
                                    <td> : </td>
                                    <td> {{ $match->round }} </td>
                                </tr>
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

                            <form action="{{ route('calculate.result', $match->id) }}" class="my-3" method="POST">
                                @csrf
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="up-team">
                                            {{ $match->home->name }}
                                        </span>
                                    </div>

                                    <input type="number" name="home" class="form-control" min="0"
                                        value="{{ getHomeScore($match->temp_score) }}">
                                    <input type="number" name="away" class="form-control" min="0"
                                        value="{{ getAwayScore($match->temp_score) }}">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="down-team">
                                            {{ $match->away->name }}
                                        </span>
                                    </div>
                                </div>

                                <div class="d-flex">
                                    <button class="btn btn-sm btn-info mt-3 mr-2"> Refresh </button>

                                    @if (!$match->calculate && $match->temp_score)
                                        <a href="{{ route('ballone.calculate.result', $match->id) }}"
                                            class="btn btn-sm btn-success mt-3"> Done </a>
                                    @endif

                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5> Body Fees </h5>
                            <table class="table">

                                <thead>
                                    <tr>
                                        <th> Fees / Goals </th>
                                        <th> : </th>
                                        <th> Home </th>
                                        <th> Away </th>
                                        <th> Over </th>
                                        <th> Under </th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($match->allBodyFees as $key => $fee)
                                        <tr>
                                            <td> {{ $fee->body }} / {{ $fee->goals }}</td>
                                            <td> : </td>
                                            <td> {{ $fee->result->home }} </td>
                                            <td> {{ $fee->result->away }} </td>
                                            <td> {{ $fee->result->over }} </td>
                                            <td> {{ $fee->result->under }} </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5> Maung Fees </h5>
                            <table class="table">

                                <thead>
                                    <tr>
                                        <th> Fees / Goals </th>
                                        <th> : </th>
                                        <th> Home </th>
                                        <th> Away </th>
                                        <th> Over </th>
                                        <th> Under </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($match->allMaungfees as $key => $maungfee)
                                        <tr>
                                            <td> {{ $maungfee->body }} / {{ $maungfee->goals }}</td>
                                            <td> : </td>
                                            <td> {{ $maungfee->result->home }} </td>
                                            <td> {{ $maungfee->result->away }} </td>
                                            <td> {{ $maungfee->result->over }} </td>
                                            <td> {{ $maungfee->result->under }} </td>
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
@endsection

@extends('layouts.master')

@section('css')
    <style>
        @media (min-width: 768px) {
            .col-md-1 {
                flex: 0 0 9% !important;
                max-width: 9% !important;
            }
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
                        <h4 class="mb-sm-0">2D Open/Close</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">2D Open/Close</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>

            @include('backend.admin.2d-close.partials._form')

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">

                            <div class="col-md-3">
                                <input type="date" class="form-control p-2" placeholder="Date" name="date"
                                    value="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-md-3">
                                <select name="time_id" id="time_id" class="form-control">
                                    <option value="">--Select Time --</option>
                                    @foreach ($times as $time)
                                        <option value="{{ $time->id }}">
                                            {{ $time->time }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">

                                <select name="agent_id[]" id="agent_id" multiple="multiple"
                                    class="agentSelect form-control">
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}"> {{ $agent->name }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <a href="" class="btn btn-danger"> All Clear </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table text-center" id="datatable_id">
                                            <thead>
                                                <tr>
                                                    <th> No. </th>
                                                    <th> Time </th>
                                                    <th> Agent </th>
                                                    <th> User </th>
                                                    <th> Limit Numbers </th>
                                                    <th> Actions </th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @forelse ($data as $x => $dt)
                                                    <tr>
                                                        <td> {{ ++$x }}</td>
                                                        <td> {{ $dt->time->time }}</td>
                                                        <td> {{ $dt->agent->name }} </td>
                                                        <td> {{ $dt->user->name }} </td>
                                                        <td>
                                                            @foreach ($dt->limit_number_group as $amount => $numbers)
                                                                <div class="d-flex justify-content-between my-3 px-5">
                                                                    <span>{{ $numbers->pluck('number')->implode(' , ') }}</span>

                                                                    <p> {{ $amount }} </p>
                                                                </div>
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            <a href="#" class="btn btn-danger btn-sm">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center"> No Data Available.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

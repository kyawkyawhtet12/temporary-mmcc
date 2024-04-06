@extends('layouts.master')


@section('css')
    <style>
        input {
            padding: 0.6rem !important;
        }

        .multiSelect button{
            padding: 0.6rem !important;
        }
    </style>
@endsection

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
                        <h4 class="mb-sm-0"> Payment Report </h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active"> Payment Report</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">

                            <div class="row input-daterange">

                                <form action="{{ route('agent.payment-reports.search') }}" method="POST" class="col-md-12 row">
                                    @csrf

                                    <div class="col-md-4 multiSelect">
                                        <select name="agent_id[]" id="agent_id" multiple="multiple" class="agentSelect form-control">
                                            @foreach ($agents as $agent)
                                                <option value="{{ $agent->id }}"
                                                    {{ in_array($agent->id, $search['agent_id'] ?? [] ) ? 'selected' : '' }}>
                                                    {{ $agent->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col">
                                        <input type="date" class="form-control" placeholder="Start Date" name="start_date" value="{{ $search['start_date'] ?? '' }}">
                                    </div>

                                    <div class="col">
                                        <input type="date" class="form-control" placeholder="End Date" name="end_date">
                                    </div>

                                    <div class="col-1">

                                        <button type="submit" name="filter" id="filter"
                                            class="btn btn-primary d-block">Filter</button>
                                    </div>

                                    <div class="col-1">

                                        <a href="" class="btn btn-success">Refresh</a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 my-3 d-flex">
                                    <h6 class="mr-5"> Recharge : <span class="text-info">{{ number_format($query->sum('deposit')) }}</span></h6>
                                    <h6 class="ml-5"> Cash : <span class="text-info">{{ number_format($query->sum('withdraw')) }}</span></h6>
                                </div>
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap data-table">
                                            <thead>
                                                <tr class="bg-primary text-white" role="row">
                                                    <th>Date</th>
                                                    <th>Recharge</th>
                                                    <th>Cash</th>
                                                    <th>Net Amount </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ( $data as $date => $dt )

                                                    <tr>
                                                        <td> {{ $date }}</td>
                                                        <td> {{ number_format($dt->sum('deposit')) }}</td>
                                                        <td> {{ number_format($dt->sum('withdraw')) }}</td>
                                                        <td> {{ number_format($dt->sum('net_amount')) }}</td>
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
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $(function() {

            setTimeout(() => {
                $("#loader").removeClass('d-flex').addClass('d-none');
                $("#mainpage").removeClass('d-none');
            }, 700);

            $('.agentSelect').multiselect({
                columns: 2,
                placeholder: 'Select Agent',
                search: true,
                searchOptions: {
                    'default': 'Search Agents'
                },
                selectAll: true
            });

        });
    </script>
@endpush

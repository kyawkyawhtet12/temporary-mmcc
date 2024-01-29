@extends('layouts.master')

@section('css')
    <style>
        input {
            padding: 0.6rem !important;
        }

        .multiSelect button{
            padding: 0.6rem !important;
        }

        table .done {
            background-color: #dff8ff;
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
                        <h4 class="mb-sm-0">Cash Record</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Game</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row mb-3 d-flex">

                <form action="{{ route('cash.record.search') }}" method="POST" class="row">

                    @csrf

                    <div class="col-md-3 multiSelect">
                        <select name="agent_id[]" id="agent_id" multiple="multiple" class="agentSelect form-control">
                            @foreach ($agents as $agent)
                                <option value="{{ $agent->id }}"
                                    {{ in_array($agent->id, Session::get('search.agent_id')) ? 'selected' : '' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <input type="text" class="form-control" placeholder="User ID" name="user_id"
                            value="{{ Session::get('search.user_id') }}">
                    </div>

                    <div class="col-md-2">
                        <input type="text" class="form-control" placeholder="User Phone" name="phone"
                            value="{{ Session::get('search.phone') }}">
                    </div>

                    <div class="col-md-2">
                        <input type="date" class="form-control" placeholder="Start Date" name="start_date"
                            value="{{ Session::get('search.start_date') }}">
                    </div>

                    <div class="col-md-2">
                        <input type="date" class="form-control" placeholder="End Date" name="end_date"
                            value="{{ Session::get('search.end_date') }}">
                    </div>

                    <div class="col-md-1">
                        <input type="submit" class="form-control btn btn-primary btn-sm" name="search" value="Search">
                    </div>
                </form>

            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="payments" class="table table-bordered nowrap">
                                            <thead>
                                                <tr class="bg-primary text-white" role="row">
                                                    <th>User ID</th>
                                                    <th>Phone</th>
                                                    <th>Amount</th>
                                                    <th>Payment</th>
                                                    <th>Status</th>
                                                    <th>Request Time</th>
                                                    <th>Action Time</th>
                                                    <th>Process Time</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @forelse($data as $dt)
                                                    <tr class="{{ $dt->status == 'Approved' ? 'done' : '' }}">
                                                        <td>{{ $dt->user->user_id }}</td>
                                                        <td>{{ $dt->phone }}</td>
                                                        <td>{{ number_format($dt->amount) }}</td>
                                                        <td>{{ $dt->provider_name }}</td>
                                                        <td>{{ $dt->status }}</td>
                                                        <td>{{ $dt->created_at }}</td>
                                                        <td>{{ $dt->action_time }}</td>
                                                        <td>{{ $dt->process_time }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center"> No Data Available. </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    {{ $data->links() }}
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

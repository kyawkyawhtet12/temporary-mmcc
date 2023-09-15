@extends('layouts.master')

@section('css')
    <style>
        input{
            padding: 0.6rem !important;
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
                        <h4 class="mb-sm-0">Recharge Record</h4>

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
                <div class="col-md-2">
                    <select name="agent" id="agentSelect" class="form-control">
                        <option value="all">All</option>
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}" {{ ($select_agent == $agent->id ? 'selected' : '') }}> {{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <form action="{{ route('recharge.record.search') }}" method="POST" class="col-md-10 row">
                    @csrf
                    <div class="col-md-2">
                        <input type="text" class="form-control" placeholder="User ID" name="user_id">
                    </div>

                    <div class="col-md-2">
                        <input type="text" class="form-control" placeholder="User Name" name="name">
                    </div>

                    <div class="col-md-2">
                        <input type="text" class="form-control" placeholder="User Phone" name="phone">
                    </div>

                    <div class="col-md-2">
                        <input type="date" class="form-control" placeholder="Start Date" name="start_date">
                    </div>

                    <div class="col-md-2">
                        <input type="date" class="form-control" placeholder="End Date" name="end_date">
                    </div>

                    <div class="col-md-2">
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
                                        <table id="payments" class="table table-bordered table-hover nowrap">
                                            <thead>
                                                <tr class="bg-primary text-white" role="row">
                                                    <th>User ID</th>
                                                    <th>Amount</th>
                                                    <th>Remark</th>
                                                    <th>Time</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @forelse( $data as $dt )
                                                    <tr>
                                                        <td>{{ $dt->user->user_id }}</td>
                                                        <td>{{ number_format($dt->amount) }}</td>
                                                        <td>{{ $dt->provider_name }}</td>
                                                        <td>{{ $dt->created_at }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center"> No Data Available. </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    {{  $data->links() }}
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

        $("#agentSelect").on('change', function(){
            let agent_id = $(this).val();
            window.location.href = `?agent=${agent_id}`;
        })

    });
</script>

@endpush

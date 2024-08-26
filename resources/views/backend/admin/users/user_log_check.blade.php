@extends('layouts.master')

@section('css')
    <style>
        input{
            padding: 0.6rem !important;
        }

        label{
            font-size: 0.8rem;
            margin-right:10px;
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
                        <h4 class="mb-sm-0"> User Log Check </h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Users</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->



            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="payments" class="table table-bordered nowrap text-center">
                                    <thead>
                                        <tr class="bg-primary text-white" role="row">
                                            <th>No.</th>
                                            <th>User ID</th>
                                            <th>Current Amount</th>
                                            <th>End Balance From Log</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse( $users as $x => $dt )
                                            @if($dt->amount != $dt->last_log?->end_balance)
                                            <tr>
                                                <td>{{ ++$x }}</td>
                                                <td>{{ $dt->user_id }}</td>
                                                <td>{{ $dt->amount }}</td>
                                                <td>{{ $dt->last_log?->end_balance }}</td>
                                            </tr>
                                            @endif
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center"> No Data Available </td>
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

@endsection

@push('scripts')

@endpush

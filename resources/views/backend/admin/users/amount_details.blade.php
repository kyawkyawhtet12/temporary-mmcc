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
                        <h4 class="mb-sm-0">Amount Details</h4>

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

            <div class="row mb-3 d-flex">

                <form action="{{ route('amount_details.filter', $user_id) }}" method="POST" class="col-md-12 row">
                    @csrf
                    <div class="col-md-3 d-flex align-items-center">
                        <label for="type" class="form-label">Type : </label>
                        @php $all_types = get_all_types() @endphp
                        <select name="type" id="type" class="form-control col-md-10">
                            <option value="all"> All </option>
                            @foreach ($all_types as $type )
                                <option {{ $select_type == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-center">
                        <label for="type" class="form-label">Date : </label>
                        <input type="date" class="form-control col-md-10" placeholder="Start Date" name="start_date">
                    </div>

                    <div class="col-md-3 d-flex align-items-center">
                        <label for="type" class="form-label">To : </label>
                        <input type="date" class="form-control col-md-10" placeholder="End Date" name="end_date">
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
                            <div class="table-responsive">
                                <table id="payments" class="table table-bordered nowrap text-center">
                                    <thead>
                                        <tr class="bg-primary text-white" role="row">
                                            <th>User ID</th>
                                            <th>Operation</th>
                                            <th>Amount</th>
                                            <th>Start Balance</th>
                                            <th>End Balance</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse( $data as $dt )
                                            <tr>
                                                <td>{{ $dt->user->user_id }}</td>
                                                <td>{{ $dt->operation }}</td>
                                                <td>
                                                    {{ $dt->start_balance > $dt->end_balance ? '-' : '' }} {{ number_format($dt->amount) }}
                                                </td>
                                                <td>{{ number_format($dt->start_balance) }}</td>
                                                <td>{{ number_format($dt->end_balance) }}</td>
                                                <td>{{ $dt->created_at }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center"> No Data Available </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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

@endsection

@push('scripts')

@endpush

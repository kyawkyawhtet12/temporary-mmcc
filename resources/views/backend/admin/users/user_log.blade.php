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
                        <h4 class="mb-sm-0"> User Log </h4>

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

            <div class="row mb-3 d-flex justify-content-center">

                <div class="col-md-10 mt-4">
                    <div class="card">
                        <div class="card-body py-5">
                            <form action="{{ route('amount_details.add', $user->id) }}" method="POST" >
                                @csrf

                                <div class="form-group row justify-content-center align-items-center">
                                    <label for="name" class="col-md-2 control-label"> User ID </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="username" name="name"
                                            placeholder="Enter Name" value="{{ $user->user_id }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group row justify-content-center align-items-center">
                                    <label for="name" class="col-md-2 control-label"> Amount </label>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" id="amount" name="amount"
                                            placeholder="Enter Amount" required>
                                    </div>
                                </div>

                                <div class="form-group row justify-content-center align-items-center">
                                    <label for="name" class="col-md-2 control-label"> Remark (betting id) </label>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" id="remark" name="remark"
                                            placeholder="Enter remark" required>
                                    </div>
                                </div>

                                <div class="form-group row justify-content-center align-items-center">
                                    <label for="name" class="col-md-2 control-label"> Operation </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="operation" name="operation"
                                            placeholder="Enter operation" required>
                                    </div>
                                </div>

                                <div class="form-group row justify-content-center align-items-center">
                                    <label for="type" class="col-md-2 control-label"> Type : </label>
                                    <div class="col-md-6">
                                        <select name="type" id="type" class="form-control" required>
                                            <option value="increment"> Increment </option>
                                            <option value="decrement"> Decrement </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row justify-content-end mt-3">
                                    <div class="col-md-2">
                                        <input type="submit" class="form-control btn btn-success btn-sm" name="submit" value="Submit">
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>



            </div>

        </div>
    </div>

@endsection

@push('scripts')

@endpush

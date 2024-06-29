@extends('layouts.master')

@section('content')
    <div class="page-content">

        <div class="container-fluid" id="mainpage">

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
                    <div class="card-body">
                        @include('backend.report._filter', ['agents' => $agents])
                    </div>
                   </div>
                </div>

                <div class="col-12">

                    <div class="card">

                        <div class="card-header">
                            <div class="d-flex justify-content-between">

                                <div class="d-flex align-items-center">
                                    <small> Total Deposit : </small>
                                    <h6 class="text-success totalDeposit mb-0 ml-2"></h6>
                                </div>

                                <div class="d-flex align-items-center">
                                    <small> Total Withdrawal : </small>
                                    <h6 class="text-danger totalWithdraw mb-0 ml-2"></h6>
                                </div>

                                <div class="d-flex align-items-center">
                                    <small class=""> Total Net : </small>
                                    <h6 class="text-info totalNetAmount mb-0 ml-2"></h6>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">

                            @include('backend.report._data')

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0"> Lottery </h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active"> Today Report </li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12 grid-margin stretch-card d-md-flex">
                    <div class="card">
                        <div class="row">
                            <div class="col-12 grid-margin stretch-card d-md-flex">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        3D (This Month)
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 mx-auto">
                                                <ul class="nav nav-pills nav-pills-custom" id="pills-tab-custom" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="three-digit-tabs" data-toggle="pill"
                                                            href="#three-digit-first" role="tab" aria-controls="pills-contact"
                                                            aria-selected="false">
                                                            3D (This Month > First)
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="pills-info-tab-custom" data-toggle="pill"
                                                            href="#three-digit-last" role="tab" aria-controls="three-digit-last"
                                                            aria-selected="false">
                                                            3D (This Month > Last)
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content tab-content-custom-pill" id="pills-tabContent-custom">
                                                    <div class="tab-pane fade show active" id="three-digit-first" role="tabpanel"
                                                        aria-labelledby="three-digit-tabs">
                                                        <div class="icons-list row">
                                                            @foreach ($three_lucky_draws as $draw)
                                                                <div class="col-sm-6 col-md-4 col-lg-2 align-middle">
                                                                    <button type="button" class="btn btn-info btn-rounded btn-icon">
                                                                        {{ $draw->threedigit->number }}
                                                                    </button> &nbsp;
                                                                    <span class="badge badge-success badge-pill">Ks {{ $draw->amount }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="icons-list row">
                                                            <div class="col-12 align-middle">
                                                                <h4 class="text-right">Total : {{ $three_total }} MMK</h4>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="tab-pane fade" id="three-digit-last" role="tabpanel"
                                                        aria-labelledby="three-digit-tabs">
                                                        <div class="icons-list row">
                                                            @foreach ($three_lucky_draws_last as $draw)
                                                                <div class="col-sm-6 col-md-4 col-lg-2 align-middle">
                                                                    <button type="button" class="btn btn-info btn-rounded btn-icon">
                                                                        {{ $draw->threedigit->number }}
                                                                    </button> &nbsp;
                                                                    <span class="badge badge-success badge-pill">Ks {{ $draw->amount }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="icons-list row">
                                                            <div class="col-12 align-middle">
                                                                <h4 class="text-right">Total : {{ $three_total_last }} MMK</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- container-fluid -->
    </div>
@endsection

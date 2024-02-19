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
                                    <div class="card-header ">
                                        2D Today Report
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 mx-auto">
                                                <ul class="nav nav-pills nav-pills-custom" id="pills-tab-custom" role="tablist">

                                                    @foreach ($thai_times as $key => $thai)
                                                        <li class="nav-item">
                                                            <a class="nav-link {{ $key == 0 ? 'active' : '' }}" id="pills-profile-tab-custom"
                                                                data-toggle="pill" href="#thai-{{ $thai->id }}" role="tab"
                                                                aria-controls="pills-profile" aria-selected="false" style="padding:0.5rem 1rem">
                                                                {{ $thai->time }}
                                                            </a>
                                                        </li>
                                                    @endforeach

                                                </ul>
                                                <div class="tab-content tab-content-custom-pill" id="pills-tabContent-custom">

                                                    <div class="tab-pane fade show active" id="thai-1" role="tabpanel"
                                                        aria-labelledby="pills-home-tab-custom">
                                                        <div class="icons-list row">
                                                            @foreach ($two_digits as $digit)
                                                                <div class="col-sm-6 col-md-4 col-lg-2 d-flex justify-content-between align-items-center py-3 px-4">
                                                                    <h5>
                                                                        {{ $digit->number }}
                                                                    </h5>

                                                                    @foreach ($thai_one as $d)
                                                                        @if ($digit->id == $d->two_digit_id)
                                                                            <span class="badge badge-pill" style="{{ getBadgeColor($badgeColors, $d->amount) }}">
                                                                                 {{ number_format($d->amount) }} MMK
                                                                            </span>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="icons-list row">
                                                            <div class="col-12 align-middle">
                                                                <h4 class="text-right">Total : {{ $thai_one_total }} MMK</h4>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="tab-pane fade" id="thai-2" role="tabpanel"
                                                        aria-labelledby="pills-profile-tab-custom">
                                                        <div class="icons-list row">
                                                            @foreach ($two_digits as $digit)
                                                            <div class="col-sm-6 col-md-4 col-lg-2 d-flex justify-content-between align-items-center py-3 px-4">
                                                                <h5>
                                                                    {{ $digit->number }}
                                                                </h5>

                                                                @foreach ($thai_two as $d)
                                                                    @if ($digit->id == $d->two_digit_id)
                                                                        <span class="badge badge-pill" style="{{ getBadgeColor($badgeColors, $d->amount) }}">
                                                                             {{ number_format($d->amount) }} MMK
                                                                        </span>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="icons-list row">
                                                            <div class="col-12 align-middle">
                                                                <h4 class="text-right">Total : {{ $thai_two_total }} MMK</h4>
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

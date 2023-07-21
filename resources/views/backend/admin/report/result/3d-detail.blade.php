@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0"> 3D Results </h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active"> Results </li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row mb-5">
                <div class="col-md-12 mx-auto">

                    <div class="d-flex justify-content-between mb-3">
                        <h4 class="mb-3">
                            {{ date('F j, Y', strtotime($data->created_at)) }}
                        </h4>

                        <select name="agent" id="agent" class="form-control col-md-3">
                                {{-- <option value="all">-- Select All --</option> --}}
                            @foreach($agents as $agent)
                                <option value="{{  $agent->id }}" {{ request()->agent == $agent->id ? 'selected' : '' }}>
                                    {{  $agent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="tab-content tab-content-custom-pill" id="pills-tabContent-custom">

                        <div class="tab-pane fade show active" id="thai-1" role="tabpanel"
                            aria-labelledby="pills-home-tab-custom">
                            <div class="icons-list row">
                                @foreach ($three_digits as $digit)
                                    <div class="col-sm-6 col-md-4 col-lg-2 align-middle">
                                        <button type="button" class="btn btn-info btn-rounded btn-icon">
                                            {{ $digit->number }}
                                        </button> &nbsp;
                                        @foreach ($draw as $d)
                                            @if ($digit->id == $d->three_digit_id)
                                                <span class="badge badge-success badge-pill">
                                                    Ks {{ $d->amount }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-3">
                                @php
                                    $win = $win_betting * $odds;
                                    $betting = $draw->sum('amount');
                                @endphp
                                <h5>Winning number : {{ $data->three_digit?->number }}</h5>
                                <h5>Number betting : {{ $win_betting }}</h5>
                                <h5>Odds : {{ $odds }}</h5>
                                <h5>betting : {{ $betting }} </h5>
                                <h5>Win : {{ $win }}</h5>
                                <h5>Profit : {{ $betting - $win }}</h5>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- container-fluid -->
    </div>
@endsection

@push('scripts')

<script>

    $("#agent").on('change', function(){
        // let agent_id = $(this).val();
        window.location.href = `?agent=${$(this).val()}`;
    })
    </script>

@endpush

@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0"> 2D Results </h4>

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
                            {{ date('F j, Y', strtotime($lucky_number->date)) }} {{ $lucky_number->lottery_time->time }}
                        </h4>

                        <select name="agent" id="agent" class="form-control col-md-3">
                                <option value="all">-- Select All --</option>
                            @foreach($agents as $id => $name)
                                <option value="{{  $id }}" {{ request()->agent == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="tab-content tab-content-custom-pill" id="pills-tabContent-custom">

                        <div class="tab-pane fade show active" id="thai-1" role="tabpanel"
                            aria-labelledby="pills-home-tab-custom">
                            <div class="icons-list row">

                                @foreach ($two_digits as $id => $number)
                                    <div class="col-sm-6 col-md-4 col-lg-2 d-flex justify-content-between align-items-center py-3 px-4">

                                        <h5>
                                            {{ $number }}
                                        </h5>

                                        <span class="badge badge-pill" style="{{ getBadgeColor($badgeColors, $draw[$id] ?? 0) }}">
                                            {{ number_format($draw[$id] ?? 0) }} MMK
                                        </span>

                                    </div>
                                @endforeach

                            </div>

                            <div class="mt-3">
                                @php
                                    $win = $win_betting * $odds;
                                    $betting = array_sum($draw);
                                @endphp
                                <h5>Winning number : {{ $lucky_number->two_digit?->number }}</h5>
                                <h5>Number betting : {{ number_format($win_betting) }}</h5>
                                <h5>Odds : {{ $odds }}</h5>
                                <h5>betting : {{ number_format($betting )}} </h5>
                                <h5>Win : {{ number_format($win) }}</h5>
                                <h5>Profit : {{ number_format($betting - $win) }}</h5>
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
        let agent_id = $(this).val();
        window.location.href = `?agent=${agent_id}`;
    })
</script>

@endpush

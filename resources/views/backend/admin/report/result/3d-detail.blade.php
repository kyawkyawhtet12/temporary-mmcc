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
                            {{ date('F j, Y', strtotime($data->date)) }}
                        </h4>

                        <select name="agent" id="agent" class="form-control col-md-3">
                            <option value="all">-- Select All --</option>
                            @foreach ($agents as $agent)
                                <option value="{{ $agent->id }}" {{ request()->agent == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row my-4">

                        @forelse ($transactions->keys() as $num)
                            <div class="col-sm-4 col-lg-2 border d-flex justify-content-between align-items-center px-4 py-3">

                                <h5> {{ sprintf('%03d', $num - 1) }} </h5>

                                <span class="btn btn-success btn-sm">
                                    {{ $transactions[$num] }}
                                </span>
                            </div>

                        @empty
                        <div class="col-12 text-center">
                            <h5> No Numbers Found. </h5>
                        </div>
                        @endforelse

                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="my-4 d-flex align-items-center">
                                <h5 class="col-2"> Winning number </h5>
                                <h5 class="col-1 text-center"> {{ $results['lucky_number'] }} </h5>
                            </div>

                            <div class="my-4 d-flex align-items-center">
                                <h5 class="col-2"> Number betting </h5>
                                <h5 class="col-1 text-center"> {{ $results['number_betting'] }} </h5>
                            </div>

                            <div class="my-4 d-flex align-items-center">
                                <h5 class="col-2"> Odds </h5>
                                <h5 class="col-1 text-center"> {{ $results['odds'] }} </h5>
                            </div>

                            <div class="my-4 d-flex align-items-center">
                                <h5 class="col-2"> Betting </h5>
                                <h5 class="col-1 text-center"> {{ $results['betting'] }} </h5>
                            </div>

                            <div class="my-4 d-flex align-items-center">
                                <h5 class="col-2"> Win </h5>
                                <h5 class="col-1 text-center"> {{ $win = $results['number_betting'] * $results['odds'] }} </h5>
                            </div>

                            <div class="my-4 d-flex align-items-center">
                                <h5 class="col-2"> Profit </h5>
                                <h5 class="col-1 text-center"> {{ $results['betting'] - $win }} </h5>
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
        $("#agent").on('change', function() {
            // let agent_id = $(this).val();
            window.location.href = `?agent=${$(this).val()}`;
        })
    </script>
@endpush

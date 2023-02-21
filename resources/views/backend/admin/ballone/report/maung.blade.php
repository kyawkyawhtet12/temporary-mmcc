@extends('layouts.master')

@section('content')

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0"> Today Report</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Ballone</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            @forelse($data as $x => $dt)
                <div class="row">
                    <div class="col-lg-8 offset-lg-2 bg-light p-5 mb-3">
                        <h4> {{ $dt->user?->name }}</h4>
                        @if ($dt->maung)
                            <div class="">
                                @foreach ($dt->maung->teams as $team)
                                    {{-- <h4> {{ $team->match->league->name }}</h4> --}}

                                    <!-- item 1  -->
                                    <div class="p-2 mt-4 bg-slate-200 rounded-lg shadow-sm mb-3">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" style="width:20px">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span> {{ get_date_time_format($team->match) }} </span>
                                        </div>
                                        <div class="mt-2">
                                            <!-- 1 -->
                                            <div class="w-full d-flex">
                                                <!-- t1  -->
                                                <div class="w-50">

                                                    <label for="f1-{{ $x }}"
                                                        class="bg-white d-block text-center px-1 py-3"
                                                        id="{{ $team->type == 'home' ? 'active' : '' }}">
                                                        <span class="text-lg font-thin">
                                                            {{ $team->match->home->name }}
                                                        </span>

                                                        @if ($team->type == 'home' || $team->type == 'away')
                                                            @if ($team->fees->up_team == 1)
                                                                <span class="ml-3">
                                                                    {{ $team->fees->body }}
                                                                </span>
                                                            @endif
                                                        @endif

                                                    </label>
                                                </div>
                                                <!-- t2  -->
                                                <div class="w-50">

                                                    <label for="f2-{{ $x }}"
                                                        class="bg-white d-block text-center px-1 py-3"
                                                        id="{{ $team->type == 'away' ? 'active' : '' }}">
                                                        <span class="text-lg font-thin">
                                                            {{ $team->match->away->name }}
                                                        </span>

                                                        @if ($team->type == 'home' || $team->type == 'away')
                                                            @if ($team->fees->up_team == 2)
                                                                <span class="ml-3">
                                                                    {{ $team->fees->body }}
                                                                </span>
                                                            @endif
                                                        @endif

                                                    </label>
                                                </div>
                                            </div>
                                            <!-- 2 -->
                                            <div class="w-full d-flex">
                                                <div class="w-40">

                                                    <label for="f3-{{ $x }}"
                                                        class="bg-white d-block text-center px-1 py-3"
                                                        id="{{ $team->type == 'over' ? 'active' : '' }}">
                                                        <span class="text-lg font-semibold">Over</span>
                                                    </label>
                                                </div>
                                                <div class="w-20">
                                                    @if ($team->type == 'over' || $team->type == 'under')
                                                        <div class="bg-white d-block text-center px-1 py-3">
                                                            {{ $team->fees->goals }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="w-40">
                                                    <label for="f4-{{ $x }}"
                                                        class="bg-white d-block text-center px-1 py-3"
                                                        id="{{ $team->type == 'under' ? 'active' : '' }}"><span
                                                            class="text-lg font-semibold">Under</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="my-3 d-flex justify-content-between">
                                    <p class="w-40"> လောင်းငွေ - {{ $dt->amount }} </p>
                                    @if ($dt->maung->refund == 1)
                                        <p class="w-20 text-center rounded-full bg-danger px-2 py-1 text-white"> Cancel
                                            Match </p>
                                        <p class="w-40 text-right"> ပြန်ရငွေ - {{ $dt->amount }} </p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <h3 class="mt-5 text-center"> ပွဲစဉ်များ မရှိသေးပါ။ </h3>
            @endforelse

            <div class="d-flex justify-content-center">
                {{ $data->links() }}
            </div>
        </div>
        <!-- container-fluid -->
    </div>
@endsection

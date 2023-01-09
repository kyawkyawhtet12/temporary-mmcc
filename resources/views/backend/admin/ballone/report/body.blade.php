@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0"> Today Body Report</h4>

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
            @forelse( $data as $x => $dt )
            <div class="row">
                <div class="col-lg-8 offset-lg-2 bg-light p-5 mb-3">
                    @if( $dt->body )
                    <h4> {{ $dt->user?->name }}</h4>
                    {{-- <h5 class="text-center my-3"> ဘော်ဒီ / ဂိုးပေါင်း </h5> --}}
        
                    {{-- <h3 class="font-bold text-lg mt-4"> {{ $dt->body->match->league->name }}</h3> --}}
                    <!-- item 1  -->
                    <div class="p-3 mt-4 bg-slate-200 rounded-lg shadow-sm">
                      <div
                        class="d-flex align-items-center justify-content-center gap-2"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                          style="width:20px"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"
                          />
                        </svg>
                        <span> {{ get_date_time_format($dt->body->match->date_time) }} </span>
                      </div>
                      <div class="mt-2">
                        <!-- 1 -->
                        <div class="w-full d-flex">
                          <!-- t1  -->
                          <div class="w-50">
                            
                            <label
                              for="f1-{{ $x }}"
                              class="bg-white d-block text-center px-1 py-3"
                              id="{{ $dt->body->type == 'home' ? 'active' : '' }}"
                            >
                              <span class="text-lg font-thin">
                                {{ $dt->body->match->home->name }}
                              </span>
        
                              @if( $dt->body->type == 'home' || $dt->body->type == 'away')
                                @if( $dt->body->fees->up_team == 1 )
                                <span class="ml-3">
                                  {{ $dt->body->fees->body }}                  
                                </span>
                                @endif
                              @endif
        
                            </label>
                          </div>
        
                          {{-- <div class="w-20">
                            <div
                              class="h-full d-flex font-bold align-items-center justify-content-center"
                            >
                              {{ $dt->body->match->score }}
                            </div>
                          </div> --}}
        
                          <!-- t2  -->
                          <div class="w-50">
                            
                            <label
                              for="f2-{{ $x }}"
                              class="bg-white d-block text-center px-1 py-3"
                              id="{{ $dt->body->type == 'away' ? 'active' : '' }}"
                            >
                              <span class="text-lg font-thin">
                                {{ $dt->body->match->away->name }}
                              </span>
        
                              @if( $dt->body->type == 'home' || $dt->body->type == 'away')
                                @if( $dt->body->fees->up_team == 2 )
                                <span class="ml-3">
                                    {{ $dt->body->fees->body }}
                                  </span>
                                @endif
                              @endif
        
                            </label>
                          </div>
                        </div>
                        <!-- 2 -->
                        <div class="w-full d-flex">
                          <div class="w-40">
                            
                            <label
                              for="f3-{{ $x }}"
                              class="bg-white d-block text-center px-1 py-3"
                              id="{{ $dt->body->type == 'over' ? 'active' : '' }}"
                            >
                              <span class="text-lg font-semibold">Over</span>
                            </label>
                          </div>
                          <div class="w-20">
                            @if( $dt->body->type == 'over' || $dt->body->type == 'under')
                              <div
                                class="h-full gradient text-white d-flex font-bold align-items-center justify-content-center"
                              >
                                  {{ $dt->body->fees->goals }}
                              </div>
                            @endif
                          </div>
                          <div class="w-40">
                            <label
                              for="f4-{{ $x }}"
                              class="bg-white d-block text-center px-1 py-3"
                              id="{{ $dt->body->type == 'under' ? 'active' : '' }}"
                              ><span class="text-lg font-semibold">Under</span>
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
        
                    <div class="my-3 d-flex justify-content-between">
                        <p class="w-40"> လောင်းငွေ  - {{ $dt->amount }} </p>
        
                        @if( $dt->status == 4 )
                        <p class="w-20 text-center rounded-full bg-danger px-2 py-1 text-white"> Cancel Match </p>
                        <p class="w-40 text-right"> ပြန်ရငွေ  - {{ $dt->amount }} </p>
                        @endif
        
                        @if( $dt->status == 1)
                        <p class="w-20 text-center rounded-full bg-green-500 px-3 py-1 text-white"> Win </p>
                        <p class="w-40 text-right"> ပြန်ရငွေ - {{ $dt->net_amount }} </p>
                        @endif
        
                        @if( $dt->status == 2)
                        <p class="w-20 text-center rounded-full bg-red-500 px-3 py-1 text-white"> Lose </p>
                        <p class="w-40 text-right"> ပြန်ရငွေ - {{ $dt->net_amount }} </p>
                        @endif
        
                        @if( $dt->status == 3)
                        <p class="w-20 text-center rounded-full bg-blue-500 px-3 py-1 text-white"> Draw </p>
                        <p class="w-40 text-right"> ပြန်ရငွေ - {{ $dt->net_amount }} </p>
                        @endif
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
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
            
            <div class="row grid-margin">
              <div class="col-12  grid-margin stretch-card">
                <div class="card card-statistics">
                  <div class="card-body">
                    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                      <div class="statistics-item">
                        <p>
                          <i class="icon-sm fa fa-user mr-2"></i>
                          Total Bet Number
                        </p>
                        <h2>{{ $bet_count }}</h2>
                      </div>
                      <div class="statistics-item">
                        <p>
                          <i class="icon-sm fas fa-hourglass-half mr-2"></i>
                          Total Body Number
                        </p>
                        <h2>{{ $body_count }}</h2>
                      </div>
                      <div class="statistics-item">
                        <p>
                          <i class="icon-sm fas fa-check-circle mr-2"></i>
                          Total Maung Number
                        </p>
                        <h2>{{ $maung_count }}</h2>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        
             
        
            </div>

            <div class="row grid-margin">
                <div class="col-12  grid-margin stretch-card">
                  <div class="card card-statistics">
                    <div class="card-body">
                      <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                        
                        <div class="statistics-item">
                          <p>
                            <i class="icon-sm fa fa-user mr-2"></i>
                            Total Bet Amount
                          </p>
                          <h2>{{ $bet_amount }}</h2>
                        </div>

                        <div class="statistics-item">
                          <p>
                            <i class="icon-sm fas fa-hourglass-half mr-2"></i>
                            Bet Pending Amount
                          </p>
                          <h2>{{ $pending }}</h2>
                        </div>

                        <div class="statistics-item">
                            <p>
                              <i class="icon-sm fas fa-hourglass-half mr-2"></i>
                              Bet Refund Amount
                            </p>
                            <h2>{{ $refund }}</h2>
                          </div>

                        <div class="statistics-item">
                            <p>
                              <i class="icon-sm fas fa-hourglass-half mr-2"></i>
                              Bet Win Amount
                            </p>
                            <h2>{{ $win }}</h2>
                        </div>

                        <div class="statistics-item">
                            <p>
                              <i class="icon-sm fas fa-hourglass-half mr-2"></i>
                              Bet Lose Amount
                            </p>
                            <h2>{{ $lose }}</h2>
                        </div>

                        <div class="statistics-item">
                          <p>
                            <i class="icon-sm fas fa-check-circle mr-2"></i>
                            Total Net Amount
                          </p>
                          <h2>{{ $net }}</h2>
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
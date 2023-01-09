@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Agent Dashboard</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="">
                <div class="content-wrapper">
                  <div class="page-header">
                    <h3 class="page-title">
                      Dashboard
                    </h3>
                  </div>
                  <div class="row grid-margin">
                    <div class="col-12  grid-margin stretch-card">
                      <div class="card card-statistics">
                        <div class="card-body">
                          <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                            <div class="statistics-item">
                              <p>
                                <i class="icon-sm fa fa-users mr-2"></i>
                                All users
                              </p>
                              <h2>{{ $total_user }}</h2>
                            </div>

                            @foreach( $thai_times as $x => $t )
                            <div class="statistics-item">
                              <p>
                                <i class="icon-sm fas fa-hourglass-half mr-2"></i>
                                2D Thai ( {{ $t->time }})
                              </p>

                              @if( $x == 0 )
                                <h2>{{ $thai_one }}</h2>
                              @else
                                <h2>{{ $thai_two }}</h2>
                              @endif
                            </div>
                            @endforeach

                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-12  grid-margin stretch-card">
                        <div class="card card-statistics">
                            <div class="card-body">
                                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                                    
                                    @foreach( $dubai_times as $x => $db )
                                    <div class="">
                                        <p>
                                            <i class="icon-sm fas fa-hourglass-half mr-2"></i>
                                            2D Dubai ({{ $db->time }})
                                        </p>

                                        @if( $x == 0 )
                                            <h2>{{ $dubai_one }}</h2>
                                        @elseif( $x == 1)
                                            <h2>{{ $dubai_two }}</h2>
                                        @elseif( $x == 2)
                                            <h2>{{ $dubai_three }}</h2>
                                        @elseif( $x == 3)
                                            <h2>{{ $dubai_four }}</h2>
                                        @elseif( $x == 4)
                                            <h2>{{ $dubai_five }}</h2>
                                        @elseif( $x == 5)
                                            <h2>{{ $dubai_six }}</h2>
                                        @endif
                                    </div>

                                    @endforeach
    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12  grid-margin stretch-card">
                      <div class="card card-statistics">
                          <div class="card-body">
                              <div class="row align-items-center justify-content-between">
                                  
                                  <div class="col-md-6">
                                      <p>
                                          <i class="icon-sm fas fa-hourglass-half mr-2"></i>
                                          3D First
                                      </p>                                      
                                      <h2>{{ $three_total_first }}</h2>
                                  </div>

                                  <div class="col-md-6">
                                      <p>
                                          <i class="icon-sm fas fa-hourglass-half mr-2"></i>
                                          3D Last
                                      </p>                                      
                                      <h2>{{ $three_total_last }}</h2>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>

                    <div class="col-lg-12 grid-margin stretch-card">
                      <div class="card">
                        <div class="card-body">
                          <div class="d-flex justify-content-between mb-3">
                            <h4 class="card-title">Two Lucky Draw Lists By Day</h4>
                          </div>
                          <div class="row">
                            <div class="col-12">
                              <div class="table-responsive">
                                <table id="agent-two" class="table table-bordered table-hover">
                                  <thead>
                                    <tr class="bg-primary text-white" role="row">
                                      <th>No.</th>
                                      <th>Date</th>
                                      <th>Total Amount</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    

                    <div class="col-lg-12 grid-margin stretch-card">
                      <div class="card">
                        <div class="card-body">
                          <div class="d-flex justify-content-between mb-3">
                            <h4 class="card-title">Three Lucky Draw Lists By Day</h4>
                          </div>
                          <div class="row">
                            <div class="col-12">
                              <div class="table-responsive">
                                <table id="agent-three" class="table table-bordered table-hover">
                                  <thead>
                                    <tr class="bg-primary text-white" role="row">
                                      <th>No.</th>
                                      <th>Date</th>
                                      <th>Total Amount</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach($three_lucky_draws as $key => $draw)
                                    <tr>
                                      <td>{{++ $key}}</td>
                                      <td>{{ $draw->day }}</td>
                                      <td><label class="badge badge-success badge-pill">{{ $draw->amount }} MMK</label> </td>
                                    </tr>
                                    @endforeach
                                  </tbody>
                                </table>
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

@push('scripts')
  <script>
    $('#agent-three').DataTable();
  </script>
  <script>
    $(function () {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      var table = $('#agent-two').DataTable({
        processing: true,
        "language": { processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>' },
        serverSide: true,
        ajax: {
          url: "{{ route('agent.dashboard') }}",
        },
        columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'day', name: 'day'},
        {data: 'amount', name: 'amount'},
        ],
      });

    });
  </script>
  @endpush
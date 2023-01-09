@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Two Lucky Draw Lists</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Two Lucky Draw Lists</li>
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
                          <div class="d-flex justify-content-between mb-3">
                            <h4 class="card-title">Two Lucky Draw Lists</h4>
                          </div>
                          <div class="row">
                            <div class="col-12">
                              <div class="table-responsive">
                                <table id="agent-table" class="table table-bordered table-hover nowrap">
                                  <thead>
                                    <tr class="bg-primary text-white" role="row">
                                      <th>No.</th>
                                      <th>Agent</th>
                                      <th>Total Amount</th>
                                      <th>Date</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach($two_lucky_draws as $key => $draw)
                                    <tr>
                                      <td>{{ ++$key }}</td>
                                      <td>{{ $draw->name }}</td>
                                      <td>{{ $draw->amount }}</td>
                                      <td>{{ $draw->day }}</td>
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
@endsection

@push('scripts')
<script type="text/javascript">
  $('#agent-table').DataTable();
</script>  
@endpush
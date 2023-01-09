@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Agent Percentage</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Agent Percentage</li>
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
                            <h4 class="card-title">Agent Percentage Lists</h4>
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
                                      <th>Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach($percentages as $key=>$percentage)
                                    <tr>
                                      <td>{{ ++$key }}</td>
                                      <td>{{ $percentage['name'] }}</td>
                                      <td>{{ $percentage['amount'] }}</td>
                                      <td>
                                        <form id="agent-form" action="{{ url('admin/percentage') }}" method="POST">
                                          @csrf
                                          <input type="hidden" name="agent_id" value="{{ $percentage['id'] }}">
                                          <input type="hidden" name="amount" value="{{ $percentage['amount'] }}">
                                          <button class="btn btn-primary" type="submit">Add Percentage</button>
                                        </form>
                                      </td>
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
  <script>
    $('#agent-table').DataTable()
  </script>  
@endpush
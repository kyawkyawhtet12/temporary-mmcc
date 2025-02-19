@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Lucky Draws</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">2D Lucky Draw</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                          <div class="row input-daterange">
                            <div class="col-md-4">
                              <div class="input-group">
                                <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly />
                                <span class="input-group-addon input-group-append border-left">
                                  <span class="far fa-calendar input-group-text"></span>
                                </span>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="input-group">
                                <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
                                <span class="input-group-addon input-group-append border-left">
                                  <span class="far fa-calendar input-group-text"></span>
                                </span>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                              <button type="button" name="refresh" id="refresh" class="btn btn-success">Refresh</button>
                            </div>
                          </div>
                        </div>
                        <div class="card-body">
                          <div class="row">
                            <div class="col-12">
                              <div class="table-responsive">
                                <table class="table table-bordered table-hover nowrap data-table">
                                  <thead>
                                    <tr class="bg-primary text-white" role="row">
                                      <th>No.</th>
                                      <th>User</th>
                                      <th>Agent</th>
                                      <th>3 Digits Number</th>
                                      <th>Amount </th>
                                      <th>Date</th>
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
            </div>
        </div>
    </div>
@endsection

@push('scripts')
  
  <script>

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $('.input-daterange').datepicker({
      todayBtn:'linked',
      format:'yyyy-mm-dd',
      autoclose:true
    });

    load_data();

    function load_data(from_date = '', to_date = '')
    {
      $('.data-table').DataTable({
        processing: true,
        "language": { processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>' },
        serverSide: true,
        ajax: {
          url:'{{ route("threedigits.index") }}',
          data: function (d) {              
            d.from_date = $('#from_date').val();
            d.to_date = $('#to_date').val();
            d.search = $('input[type="search"]').val()
          }
       },
       columns: [
          {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
          {data: 'user', name: 'user'},
          {data: 'agent', name: 'agent'},
          {data: 'number', name: 'number'},
          {data: 'amount', name: 'amount'},
          {data: 'created_at', name: 'created_at'},
        ],
      });
    }

    $('#filter').click(function(){
      var from_date = $('#from_date').val();
      var to_date = $('#to_date').val();
      if(from_date != '' &&  to_date != '')
      {
        $('.data-table').DataTable().destroy();
        load_data(from_date, to_date);
      }
      else
      {
       alert('Both Date is required');
      }
    });

    $('#refresh').click(function(){
      $('#from_date').val('');
      $('#to_date').val('');
      $('.data-table').DataTable().destroy();
        load_data();
    });
      
  </script>
@endpush
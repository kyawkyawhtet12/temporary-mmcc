@extends('layouts.master')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Body Setting</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Body Setting</li>
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
                            <h4 class="card-title"> Body Setting </h4>
                          </div>
                          <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="leagues" class="table table-bordered nowrap">
                                        <thead>
                                            <tr>
                                              <th> No. </th>
                                              <th> Min Amount </th>
                                              <th> Max Amount </th>
                                              <th> Percentage </th>
                                              <th> Action </th>
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

    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="form" name="form" class="form-horizontal">
                
                    <div class="form-group">
                        <label for="min_amount" class="col-sm-12 control-label"> Min Amount </label>
                        <div class="col-sm-12">
                            <input type="number" class="form-control" id="min_amount" name="min_amount" placeholder="Enter Minimum Amount" value="" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="max_amount" class="col-sm-12 control-label"> Max Amount </label>
                        <div class="col-sm-12">
                            <input type="number" class="form-control" id="max_amount" name="max_amount" placeholder="Enter Maximum Amount" value="" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="percentage" class="col-sm-12 control-label"> Percentage </label>
                        <div class="col-sm-12">
                            <input type="number" class="form-control" id="percentage" name="percentage" placeholder="Enter Percentage" value="" required="">
                        </div>
                    </div>
      
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-primary" id="saveBtn" value="create"> Save changes
                      </button>
                    </div>
              </form>
            </div>
          </div>
        </div>
    </div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var table = $('#leagues').DataTable({
      processing: true,
      "language": { processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>' },
      serverSide: true,
      ajax: {
          url: "{{ route('ballone.body-setting.index') }}",
          data: function (d) {                
            d.search = $('input[type="search"]').val()
          }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'min_amount', name: 'min_amount'},
            {data: 'max_amount', name: 'max_amount'},
            {data: 'percentage', name: 'percentage'},
            {data: 'action', name: 'action'},
        ],
      });     

      $('body').on('click', '.editBtn', function () {
        
        $.get("{{ route('ballone.body-setting.get') }}", function (data) {            
            console.log(data);
            $('#modelHeading').html("Update Setting");
            $('#saveBtn').val("edit-league");
            $('#ajaxModel').modal('show');
            $('#min_amount').val(data.min_amount);
            $('#max_amount').val(data.max_amount);
            $('#percentage').val(data.percentage);
        })
      });

      $('#saveBtn').click(function (e) {
        e.preventDefault();
          $(this).html('Sending..');
          $.ajax({
            data: $('#form').serialize(),
            url: "{{ route('ballone.body-setting.store') }}",
            type: "POST",
            dataType: 'json',
            success: function (data) {
              $('#form').trigger("reset");
              $('#ajaxModel').modal('hide');
              table.draw();
            },
            error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
            }
        });
      });
      
    });
  </script>  
@endsection
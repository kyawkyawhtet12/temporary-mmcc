@extends('layouts.master')

@push('style')
{{-- <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" /> --}}
<link rel="stylesheet" href="{{ asset('assets/backend/DateTimePicker.css') }}">

@endpush

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">3D Setting</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">3D Setting</li>
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
                          <h4 class="card-title">3D Setting</h4>
                          <p class="card-description">
                            Click to change <code>3D Date/Time</code>
                          </p>
                          <div class="row">
                            <div class="col-12">
                              <div class="table-responsive">
                                <table id="three-lottery-close" class="table table-bordered table-hover nowrap">
                                  <thead>
                                    <tr class="bg-primary text-white" role="row">
                                      <th>No.</th>
                                      <th>Disable Date/Time</th>
                                      <th>Updated Date</th>
                                      <th>Action</th>
                                    </tr>
                                  </thead>
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
                </div>
                <form id="dateTimeForm" name="dateTimeForm">
                  <div class="modal-body">
                    <div class="col-12">
                      <div class="form-group">
                        <input type="text" class="form-control" id="datetime" name="datetime" data-field="datetime" value="" required />
                        <div id="dtBox"></div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="saveBtn" value="create">Save changes</button>
                  </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
{{-- <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script> --}}

<script src="{{ asset('assets/backend/DateTimePicker.js') }}"></script>

<script type="text/javascript">
  // $('#datetime').datetimepicker({
  //   // datepicker: { showOtherMonths: true },
  //   format: 'yyyy-mm-dd HH:MM',
  //   modal: true,
  //   footer: true
  // });
  $("#dtBox").DateTimePicker({
    defaultDate : new Date(),
    dateTimeFormat : "yyyy-mm-dd hh:mm"
  });

  $(function () {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var table = $('#three-lottery-close').DataTable({
        processing: true,
        "language": { processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>' },
        serverSide: true,
        ajax: {
            url: "{{ route('three-lottery-close.index') }}",
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'datetime', name: 'datetime'},
            {data: 'updated_at', name: 'updated_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
    });

    $('body').on('click', '.editDateTime', function () {
      $.get("{{ route('three-lottery-close.index') }}" +'/' + '1' +'/edit', function (data) {
          $('#modelHeading').html("Update Date/Time");
          $('#saveBtn').val("edit-user");
          $('#ajaxModel').modal('show');
          $('#datetime').val(data.datetime);
      })
   });
    
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Sending..');
        $.ajax({
          data: $('#dateTimeForm').serialize(),
          url: "{{ route('three-lottery-close.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
            console.log(data);
            $('#dateTimeForm').trigger("reset");
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
@endpush
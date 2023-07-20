@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">3D Lucky Number</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">3D Lucky Number</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <h4 class="card-title">3D Lucky Numbers</h4>
                                <div>
                                <a class="btn btn-success" href="javascript:void(0)" id="createThreeNumber"> Create Lucky Number</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                <div class="table-responsive">
                                    <table id="three_lucky_numbers" class="table table-bordered table-hover nowrap">
                                    <thead>
                                        <tr class="bg-primary text-white" role="row">
                                        <th>No.</th>
                                        {{-- <th>Round</th> --}}
                                        <th>3 Digit Number</th>
                                        {{-- <th>Votes</th> --}}
                                        <th>Lottery Date</th>
                                        <th>Status</th>
                                        {{-- <th>Created Date</th> --}}
                                        <th>Action</th>
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

                </div> <!-- end col -->

            </div>

        </div>
        <!-- container-fluid -->
    </div>

    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="numberForm" name="numberForm" class="form-horizontal">
                       <input type="hidden" name="threedigit_id" id="threedigit_id">

                        <div class="form-group">
                          <label for="name" class="col-sm-12 control-label">Three Digit Number</label>
                          <div class="col-sm-12">
                            <select class="w-100" name="threedigit_number" style="width: 100%" id="threedigit_number">
                              <option value="">--- Select Lucky Number ---</option>
                              @foreach($threedigit_numbers as $number)
                              <option value="{{ $number->id }}">{{ $number->number }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>

                        {{-- <div class="form-group">
                          <label for="name" class="col-sm-12 control-label">Three Digit Number (Votes)</label>
                          <div class="col-sm-12">
                            <select class="js-example-basic-multiple w-100" name="votes[]" id="votes" style="width: 100%" multiple="multiple">
                              @foreach($threedigit_numbers as $number)
                                <option value="{{ $number->number }}">{{ $number->number }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div> --}}

                        <div class="form-group">
                            <label for="date" class="col-sm-12 control-label">Date</label>
                            <div class="col-sm-12">
                                <input type="date" class="form-control" id="date" name="date"
                                    placeholder="Two Digit Number" value="" required="">
                            </div>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10 mt-3">
                         <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                         </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

  <script type="text/javascript">
      $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#threedigit_number').select2({
          dropdownParent: $('#ajaxModel')
        });

        var table = $('#three_lucky_numbers').DataTable({
            processing: true,
            "language": { processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>' },
            serverSide: true,
            ajax: {
                url: "{{ route('three_lucky_numbers.index') }}",
                data: function (d) {
                  d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                // {data: 'round', name: 'round'},
                {data: 'number', name: 'number'},
                // {data: 'votes', name: 'votes'},
                {data: 'date', name: 'date'},
                {data: 'status', name: 'status'},
                // {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action'},
            ],
        });

        $('#three_lucky_numbers').on('draw.dt', function(){
          if ($('#editable-form').length) {
            $.fn.editable.defaults.mode = 'inline';
            $.fn.editableform.buttons =
              '<button type="submit" class="btn btn-primary btn-sm editable-submit">' +
              '<i class="fa fa-fw fa-check"></i>' +
              '</button>' +
              '<button type="button" class="btn btn-default btn-sm editable-cancel">' +
              '<i class="fa fa-fw fa-times"></i>' +
              '</button>';

            $.ajaxSetup({
              headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
              }
            });

            $('.three_lucky_no_status').editable({
              url: '/admin/three_lucky_no_status',
              type: 'select',
              pk: 1,
              name: 'status',
              source: [
                { value: 'Pending', text: 'Pending' },
                { value: 'Approved', text: 'Approved' },
                { value: 'Rejected', text: 'Rejected' }
              ],
              success: function(data) {
                toastr.options.closeButton = true;
                toastr.options.closeMethod = 'fadeOut';
                toastr.options.closeDuration = 100;
                toastr.success(data.message);
                $('#three_lucky_numbers').DataTable().ajax.reload();
              }
            });
          }
        });

        $('#createThreeNumber').click(function () {
            $('#saveBtn').val("create-number");
            $('#threedigit_id').val('');
            $('#numberForm').trigger("reset");
            $('.js-example-basic-multiple').select2().trigger("change");
            $('#modelHeading').html("Create Three Digit Lucky Number");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editNumber', function () {
          var threedigit_id = $(this).data('id');
          $.ajax({
            url: "{{ route('three_lucky_numbers.index') }}" +'/' + threedigit_id +'/edit',
            dataType:"JSON",
            success:function(data)
            {
              $('#threedigit_id').val(data.id);
              $('#threedigit_number').val(data.three_digit_id).trigger('change');
              // $("#votes").val(data.votes).trigger("change");
              $('#modelHeading').html("Edit Two Digit Lucky Number");
              $('#saveBtn').val("edit-number");
              $('#ajaxModel').modal('show');
            },
            error: function (data) {
              console.log('Error:', data);
            }
          })
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Sending..');

            $.ajax({
              data: $('#numberForm').serialize(),
              url: "{{ route('three_lucky_numbers.store') }}",
              type: "POST",
              dataType: 'json',
              success: function (data) {
                $('#numberForm').trigger("reset");
                $('#ajaxModel').modal('hide');
                if (data.error) alert(data.error);
                table.draw();
              },
              error: function (data) {
                console.log('Error:', data);
                $('#saveBtn').html('Save Changes');
              }
          });
        });

        $('body').on('click', '.deleteNumber', function () {
          if(!confirm('Are You sure want to delete !')) return;
          var threedigit_id = $(this).data("id");

          $.ajax({
              type: "DELETE",
              url: "{{ route('three_lucky_numbers.store') }}"+'/'+threedigit_id,
              success: function (data) {
                  table.draw();
              },
              error: function (data) {
                  console.log('Error:', data);
              }
          });
        });
      });
    </script>
@endpush

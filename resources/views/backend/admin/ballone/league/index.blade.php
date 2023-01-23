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
                        <h4 class="mb-sm-0">League</h4>

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

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                          <div class="d-flex justify-content-between mb-3">
                            <h4 class="card-title"> League List </h4>
                            <div>
                                <a class="btn btn-primary" href="javascript:void(0)" id="createLeague"> Add League</a>
                              </div>
                          </div>
                          <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="leagues" class="table table-bordered nowrap">
                                        <thead>
                                            <tr>
                                              <th>No.</th>
                                              <th>Name</th>
                                              {{-- <th>Level</th> --}}
                                              {{-- <th>Country</th> --}}
                                              <th>Created Date</th>
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
                <form id="leagueForm" name="leagueForm" class="form-horizontal">
                    <input type="hidden" name="league_id" id="league_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-12 control-label">Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" required="">
                        </div>
                    </div>
      
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
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
    $('.mySelect2').select2({
      dropdownParent: $('#ajaxModel')
    });
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
          url: "{{ route('ballone.league.index') }}",
          data: function (d) {                
            d.search = $('input[type="search"]').val()
          }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            // {data: 'level', name: 'level'},
            // {data: 'country', name: 'country'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action'},
        ],
      });

      $('#createLeague').click(function () {
        $('#saveBtn').val("create-league");
        $('#league_id').val('');
        $('#leagueForm').trigger("reset");
        $('.mySelect2').trigger("change");
        $('#modelHeading').html("Create New League");
        $('#ajaxModel').modal('show');
      });

      $('body').on('click', '.editLeague', function () {
        var league_id = $(this).data('id');
        $.get("{{ route('ballone.league.index') }}" +'/' + league_id +'/edit', function (data) {            
            $('#modelHeading').html("Update League");
            $('#saveBtn').val("edit-league");
            $('#ajaxModel').modal('show');
            $('#league_id').val(data.id);
            $('#name').val(data.name);
            // $('#level').val(data.level).trigger('change');
            // $('#country').val(data.country).trigger('change');
        })
      });

      $('#saveBtn').click(function (e) {
        e.preventDefault();
          $(this).html('Sending..');
          $.ajax({
            data: $('#leagueForm').serialize(),
            url: "{{ route('ballone.league.store') }}",
            type: "POST",
            dataType: 'json',
            success: function (data) {
              $('#leagueForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              table.draw();
            },
            error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
            }
        });
      });

      $('body').on('click', '.deleteLeague', function () {
        swal({
            text: "Are you sure to delete this ?",
            icon: "info",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                var league_id =  $(this).data("id");
                $.ajax({
                    url: "{{ route('ballone.league.store') }}"+'/'+league_id,
                    method : 'DELETE',
                }).done(function(res) {                        
                    swal({
                        text: "အောင်မြင်ပါသည်",
                        icon: "success",                            
                    }).then((e) => {
                      table.draw();
                    })
                })
                
            }
        });
      });
    });
  </script>  
@endsection
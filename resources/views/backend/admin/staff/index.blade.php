@extends('layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
@endsection

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Staff Lists</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Staff Lists</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex justify-content-end">
                                <a href='javascript:void(0)' class='btn btn-success btn-sm ml-3 my-2' id="createStaff"> Create Staff </a>
                            </div>

                            <table id="table_id" class="display">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Registered Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach( $data as $key => $dt )
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $dt->name }}</td>
                                        <td>{{ $dt->email }}</td>
                                        <td>{{ $dt->created_at }}</td>
                                        <td>
                                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="{{ $dt->id }}" data-original-title="Edit" class="edit btn btn-primary editStaff">Edit</a>

                                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="{{ $dt->id }}" data-original-title="Delete" class="btn btn-danger delete">Delete</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div><!-- end card -->
                    </div>
                    <!-- end col -->
                </div>

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
                  <form id="staffForm" name="staffForm" class="form-horizontal">
                    <input type="hidden" name="staff_id" id="staff_id">

                      <div class="form-group">
                          <label for="name" class="col-sm-12 control-label">Name</label>
                          <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" required="">
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="name" class="col-sm-12 control-label">Email</label>
                          <div class="col-sm-12">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="" required="">
                          </div>
                      </div>

                      <div class="form-group" id="pass">
                          <label for="password" class="col-sm-12 control-label">Password</label>
                          <div class="col-sm-12">
                            <input id="password" type="password" class="form-control" name="password">
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

@section('script')

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>

<script>
    $(document).ready( function () {
        var table = $('#table_id').DataTable();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#createStaff').click(function () {
            $('#amou').hide();
            $('#saveBtn').val("create-user");
            $('#staff_id').val('');
            $('#staffForm').trigger("reset");
            $('#modelHeading').html("Create New Staff");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editStaff', function () {
            $('#amou').show();
            var staff_id = $(this).data('id');
            $.get("{{ route('staff.index') }}" +'/' + staff_id +'/edit', function (data) {
                $('#modelHeading').html("Update Staff");
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#staff_id').val(data.id);

                $('#name').val(data.name);
                $('#email').val(data.email);
            })
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Sending..');
            $.ajax({
                data: $('#staffForm').serialize(),
                url: "{{ route('staff.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    $('#staffForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    // table.draw();
                    location.reload();
                },
                error: function (data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Save Changes');
                }
            });
        });

        $('body').on('click', '.delete', function () {
            if(!confirm('Are You sure want to delete !')) return;
            var staff_id = $(this).data("id");
            $.ajax({
                type: "DELETE",
                url: "{{ route('staff.store') }}"+'/'+staff_id,
                success: function (data) {
                    // table.draw();
                    location.reload();
                },
                error: function (data) {
                console.log('Error:', data);
                }
            });
        });

    } );

</script>
@endsection

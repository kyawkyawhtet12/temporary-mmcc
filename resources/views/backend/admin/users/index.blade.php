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
                        <h4 class="mb-sm-0">User Lists</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">User Lists</li>
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
                            <div class="d-flex justify-content-between mb-3">
                                <h4 class="card-title">User Lists</h4>
                                <div>
                                    <a class="btn btn-success" href="javascript:void(0)" id="createUser"> Create User</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="users" class="table table-bordered table-hover nowrap">
                                            <thead>
                                                <tr class="bg-primary text-white" role="row">
                                                    <th>No.</th>
                                                    <th>Name</th>
                                                    <th>User ID</th>
                                                    <th>Balance</th>
                                                    <th>Status</th>
                                                    <th>Registered Date</th>
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
        <!-- container-fluid -->
    </div>


    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="userForm" name="userForm" class="form-horizontal">
                        <input type="hidden" name="old_id" id="old_id">

                        <div class="form-group">
                            <label for="name" class="col-sm-12 control-label">Name</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter Name" value="" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-12 control-label">User ID</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="user_id" name="user_id"
                                    placeholder="Enter User ID" value="" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-12 control-label">Amount</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="amount" name="amount" value=""
                                    required="">
                            </div>
                        </div>

                        <div class="form-group" id="pass">
                            <label for="password" class="col-sm-12 control-label">Password</label>
                            <div class="col-sm-12">
                                <input id="password" type="password" class="form-control" name="password">
                            </div>
                        </div>

                        <div class="form-group" id="conpass">
                            <label for="password" class="col-sm-12 control-label">Confirm Password</label>
                            <div class="col-sm-12">
                                <input class="form-control" name="confirm-password" type="password">
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

@push('scripts')
    <script type="text/javascript">
        $(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#users').DataTable({
                processing: true,
                "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>'
                },
                serverSide: true,
                ajax: {
                    url: "{{ route('users.index') }}",
                    data: function(d) {
                        d.search = $('input[type="search"]').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'user_id',
                        name: 'user_id'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
            });

            $('#createUser').click(function() {
                $('#saveBtn').val("create-user");
                $('#old_id').val('');
                $('#user_id').val('');
                $('#userForm').trigger("reset");
                $('#modelHeading').html("Create New User");
                $('#ajaxModel').modal('show');
            });

            $('body').on('click', '.editUser', function() {
                var user_id = $(this).data('id');
                $.get("{{ route('users.index') }}" + '/' + user_id + '/edit', function(data) {
                    $('#modelHeading').html("Update User");
                    $('#saveBtn').val("edit-user");
                    $('#ajaxModel').modal('show');
                    $('#old_id').val(data.id);
                    $('#name').val(data.name);
                    $('#user_id').val(data.user_id);
                    $('#amount').val(data.amount);
                })
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Sending..');
                $.ajax({
                    data: $('#userForm').serialize(),
                    url: "{{ route('users.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('#userForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        alert("* Something is wrong.");
                        $('#saveBtn').html('Save Changes');
                    }
                });
            });

            $('body').on('click', '.deleteUser', function() {
                if (!confirm('Are You sure want to delete !')) return;
                var user_id = $(this).data("id");
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('users.store') }}" + '/' + user_id,
                    success: function(data) {
                        table.draw();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            });
        });
    </script>
@endpush

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
                                {{-- <div>
                                    <a class="btn btn-success" href="javascript:void(0)" id="createUser"> Create User</a>
                                </div> --}}
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="users" class="table table-bordered table-hover nowrap">
                                            <thead>
                                                <tr class="bg-primary text-white" role="row">
                                                    <th>No.</th>
                                                    <th>User ID</th>
                                                    <th>Initial Password</th>
                                                    <th>Phone</th>
                                                    <th>Balance</th>
                                                    <th>Status</th>
                                                    <th>Days not logged in</th>
                                                    <th>Payment</th>
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
                    <h4 class="modal-title text-capitalize py-1" id="modelHeading">
                        Update User
                    </h4>
                </div>
                <div class="modal-body">
                    <form id="userForm" name="userForm" class="form-horizontal">

                        <input type="hidden" name="old_id" id="old_id">

                        <div class="form-group">
                            <label for="name" class="col-sm-12 control-label">Name</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter Name" value="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-12 control-label">User ID</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="user_id" name="user_id"
                                    placeholder="Enter User ID" value="" required="">
                            </div>
                        </div>

                         {{-- <div class="form-group">
                            <label for="phone" class="col-sm-12 control-label">Phone</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="phone" name="phone" value=""
                                    required="" readonly>
                            </div>
                        </div> --}}

                        {{-- <div class="form-group">
                            <label for="name" class="col-sm-12 control-label">Amount</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="amount" name="amount" value=""
                                    required="" readonly>
                            </div>
                        </div> --}}

                        <div class="form-group" id="pass">
                            <label for="password" class="col-sm-12 control-label">Password</label>
                            <div class="col-sm-12">
                                <input id="password" type="password" class="form-control" name="password">
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

    <div class="modal fade" id="paymentModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-capitalize py-1" id="modelHeading">
                    </h4>
                </div>
                <div class="modal-body">

                    <div id="error" class="alert alert-warning alert-dismissible fade show d-none" role="alert">
                        <strong> Error !</strong> <span id="text"></span>
                    </div>

                    <form id="paymentForm" name="paymentForm" class="form-horizontal">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="type" id="type">

                        <div class="form-group">
                            <label for="name" class="col-sm-12 control-label">Name</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="username" name="name"
                                    placeholder="Enter Name" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="current" class="col-sm-12 control-label">Current Amount</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="current" name="current" value=""
                                    readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="amount" class="col-sm-12 control-label"> Amount </label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="amount" name="amount"
                                    placeholder="Enter Amount" value="">
                            </div>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="paymentSave">
                                 Submit
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
                        data: 'user_id',
                        name: 'user_id'
                    },
                    {
                        data: 'initial_password',
                        name: 'initial_password'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
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
                        data: 'days_not_logged_in',
                        name: 'days_not_logged_in'
                    },
                    {
                        data: 'payment',
                        name: 'payment'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
            });

            // $('#createUser').click(function() {
            //     $('#saveBtn').val("create-user");
            //     $('#old_id').val('');
            //     $('#user_id').val('');
            //     $('#userForm').trigger("reset");
            //     $('#modelHeading').html("Create New User");
            //     $('#ajaxModel').modal('show');
            // });

            // Edit User

            $('body').on('click', '.editUser', function() {

                // let user_id = $(this).data('id');
                let {id,name,user_id} = $(this).data();

                $('#old_id').val(id);
                $('#name').val(name);
                $('#user_id').val(user_id);

                $('#ajaxModel').modal('show');

            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $.ajax({
                    data: $('#userForm').serialize(),
                    url: "{{ route('users.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        toastr.success(data.success);
                        $('#ajaxModel').modal('hide');
                        $('#userForm').trigger("reset");
                        table.draw();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        swal.fire("Alert!", "* Something is wrong.Please try again later.", "error");
                    }
                });
            });

            // Payment Action

            $('body').on('click', '.paymentBtn', function() {

                let { id,type,title } = $(this).data();

                $('#paymentModel #paymentForm').trigger("reset");
                $('#paymentModel #modelHeading').html(title);

                $.get("{{ route('users.index') }}" + '/' + id + '/edit', function(data) {
                    $('#paymentModel #id').val(data.id);
                    $('#paymentModel #type').val(type);
                    $('#paymentModel #username').val(data.name);
                    $('#paymentModel #current').val(data.amount);
                    $('#paymentModel').modal('show');
                })
            });

            $('body').on('click', '#paymentSave', function(e) {
                e.preventDefault();
                $.ajax({
                    data: $('#paymentForm').serialize(),
                    url: "{{ route('payment.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {

                        if (data.error) {
                            toastr.error(data.error);
                        }

                        if(data.success){
                            toastr.success(data.success);
                            $('#paymentModel').modal('hide');
                            $('#paymentForm').trigger("reset");
                            table.draw();
                        }
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        swal.fire("Alert!", "* Something is wrong.Please try again later.", "error");
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

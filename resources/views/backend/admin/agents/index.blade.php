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
                        <h4 class="mb-sm-0">Agent Lists</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Agent Lists</li>
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
                                <a href='javascript:void(0)' class='btn btn-success btn-sm ml-3 my-2' id="createAgent"> Create Agent </a>
                            </div>

                            <table id="table_id" class="display">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Referral Code</th>
                                        <th>Balance</th>
                                        <th>Percentage</th>
                                        <th>Registered Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
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
                  <form id="agentForm" name="agentForm" class="form-horizontal">
                    <input type="hidden" name="agent_id" id="agent_id">
      
                      <div class="form-group">
                          <label for="name" class="col-sm-12 control-label">Name</label>
                          <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" required="">
                          </div>
                      </div>
      
                      <div class="form-group">
                          <label for="name" class="col-sm-12 control-label">Phone</label>
                          <div class="col-sm-12">
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone" value="" required="">
                          </div>
                      </div>
      
                      <div class="form-group">
                          <label for="name" class="col-sm-12 control-label">Percentage</label>
                          <div class="col-sm-12">
                            <input type="number" class="form-control" id="percentage" name="percentage" placeholder="Enter Percentage" required="">
                          </div>
                      </div>
      
                      <div class="form-group" id="amou">
                          <label for="name" class="col-sm-12 control-label">Amount</label>
                          <div class="col-sm-12">
                            <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter Amount" value="" required="">
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
        // var table = $('#table_id').DataTable();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });    

        var table = $('#table_id').DataTable({
            processing: true,
            "language": { processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>' },
            serverSide: true,
            ajax: {
                url: "{{ route('agents.index') }}",
                data: function (d) {                
                d.search = $('input[type="search"]').val()
                }
            },
            columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'phone', name: 'phone'},
            {data: 'referral_code', name: 'referral_code'},
            {data: 'amount', name: 'amount'},
            {data: 'percentage', name: 'percentage'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action'},
            ],
        });

        $('#createAgent').click(function () {
            $('#amou').hide();
            $('#saveBtn').val("create-user");
            $('#agent_id').val('');
            $('#agentForm').trigger("reset");
            $('#modelHeading').html("Create New Agent");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editAgent', function () {
            $('#amou').show();
            var agent_id = $(this).data('id');
            $.get("{{ route('agents.index') }}" +'/' + agent_id +'/edit', function (data) {
                $('#modelHeading').html("Update Agent");
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#agent_id').val(data.id);
                $('#amount').val(data.amount);
                $('#name').val(data.name);
                $('#percentage').val(data.percentage);
                $('#phone').val(data.phone);
            })
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Sending..');
            $.ajax({
                data: $('#agentForm').serialize(),
                url: "{{ route('agents.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    $('#agentForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();
                    // location.reload();
                },
                error: function (data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Save Changes');
                }
            });
        });

        $('body').on('click', '.deleteAgent', function () {
            if(!confirm('Are You sure want to delete !')) return;
            var agent_id = $(this).data("id");
            $.ajax({
                type: "DELETE",
                url: "{{ route('agents.store') }}"+'/'+agent_id,
                success: function (data) {
                    table.draw();
                    // location.reload();
                },
                error: function (data) {
                console.log('Error:', data);
                }
            });
        });

    } );

</script>
@endsection
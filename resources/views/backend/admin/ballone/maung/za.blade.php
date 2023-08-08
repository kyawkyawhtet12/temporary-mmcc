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
                        <h4 class="mb-sm-0">Maung Percentage</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Maung Setting</li>
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
                                <h4 class="card-title"> Maung Percentage </h4>
                                <div>
                                    <a class="btn btn-primary" href="javascript:void(0)" id="createLeague"> Add Percentage
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="leagues" class="table table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th> No. </th>
                                                    <th> Teams </th>
                                                    {{-- <th> Za </th> --}}
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
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="form" name="form" class="form-horizontal">

                        <input type="hidden" name="za_id" id="za_id">

                        <div class="form-group">
                            <label for="teams" class="col-sm-12 control-label"> Teams </label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="teams" name="teams"
                                    placeholder="Enter Teams Number" value="" required="">
                            </div>
                        </div>

                        {{-- <div class="form-group">
                        <label for="za" class="col-sm-12 control-label"> Za </label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="za" name="za" placeholder="Enter Za Amount" value="" required="">
                        </div>
                    </div> --}}

                        <div class="form-group">
                            <label for="percent" class="col-sm-12 control-label"> Percentage </label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="percent" name="percent"
                                    placeholder="Enter Percentage Amount" value="" required="">
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
                "pageLength": 25,
                "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>'
                },
                serverSide: true,
                ajax: {
                    url: "{{ route('ballone.maung-za.index') }}",
                    data: function(d) {
                        d.search = $('input[type="search"]').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'teams',
                        name: 'teams'
                    },
                    // {data: 'za', name: 'za'},
                    {
                        data: 'percent',
                        name: 'percent'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],

            });

            $('#createLeague').click(function() {
                $('#saveBtn').val("create-league");
                $('#za_id').val('');
                $('#form').trigger("reset");
                $('#modelHeading').html("Add Data");
                $('#ajaxModel').modal('show');
            });

            $('body').on('click', '.editBtn', function() {
                var za_id = $(this).data('id');
                var route = `/admin/ballone/maung-za/show/${za_id}`;
                $.get(route, function(data) {
                    console.log(data);
                    $('#modelHeading').html("Update Data");
                    $('#saveBtn').val("edit-league");
                    $('#ajaxModel').modal('show');

                    $('#teams').val(data.teams);
                    // $('#za').val(data.za);
                    $('#percent').val(data.percent);
                    $('#za_id').val(data.id);
                })
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Sending..');
                $.ajax({
                    data: $('#form').serialize(),
                    url: "{{ route('ballone.maung-za.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('#form').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Save Changes');
                    }
                });
            });

            $('body').on('click', '.deleteBtn', function() {

                if (!confirm('Are You sure want to delete !')) return;

                var za_id = $(this).data("id");

                $.ajax({
                    url: `/admin/ballone/maung-za/${za_id}`,
                    method: 'DELETE',
                }).done(function(res) {
                    table.draw();
                })
            });

        });
    </script>
@endsection

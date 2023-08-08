@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Club</h4>

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
                                <h4 class="card-title"> Club List </h4>
                                <div>
                                    <a class="btn btn-primary" href="javascript:void(0)" id="createClub"> Add Club</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="clubs" class="table table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Name</th>
                                                    {{-- <th>Code</th> --}}
                                                    <th>League</th>
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
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="clubForm" name="clubForm" class="form-horizontal">
                        <input type="hidden" name="club_id" id="club_id">
                        <div class="form-group">
                            <label for="name" class="col-sm-12 control-label">Club Name</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter Name" value="" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-12 control-label">League</label>
                            <div class="col-sm-12">
                                <select class="form-control selectLeague" name="league_id[]" id="league_id"
                                    multiple="multiple" style="width: 100%;">
                                    @foreach ($leagues as $league)
                                        <option value="{{ $league->id }}">{{ $league->name }}</option>
                                    @endforeach
                                </select>
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
    <script>
        $(document).ready(function() {
            $('.mySelect2').select2({
                dropdownParent: $('#ajaxModel')
            });
            $('.selectLeague').select2({
                dropdownParent: $('#ajaxModel')
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $('#clubs').DataTable({
                processing: true,
                "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>'
                },
                serverSide: true,
                ajax: {
                    url: "{{ route('ballone.club.index') }}",
                    data: function(d) {
                        d.search = $('input[type="search"]').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    // {data: 'code', name: 'code'},
                    {
                        data: 'league',
                        name: 'league'
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

            $('#createClub').click(function() {
                $('#clubForm').trigger("reset");
                $('#league_id').trigger('change');
                $('#saveBtn').val("create-club");
                $('#club_id').val('');
                $('#clubForm').trigger("reset");
                $('.mySelect2').trigger("change");
                $('#modelHeading').html("Create New Club");
                $('#ajaxModel').modal('show');

            });

            $('body').on('click', '.editClub', function() {
                var club_id = $(this).data('id');
                $.get("{{ route('ballone.club.index') }}" + '/' + club_id + '/edit', function(data) {
                    $('#modelHeading').html("Update Club");
                    $('#saveBtn').val("edit-club");
                    $('#ajaxModel').modal('show');
                    $('#club_id').val(data.club.id);
                    $('#name').val(data.club.name);
                    // $('#code').val(data.code);
                    $('#league_id').val(data.league).trigger('change');
                })
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Sending..');
                $.ajax({
                    data: $('#clubForm').serialize(),
                    url: "{{ route('ballone.club.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('#clubForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Save Changes');
                    }
                });
            });

            $('body').on('click', '.deleteClub', function() {

                if (!confirm('Are You sure want to delete !')) return;

                var club_id = $(this).data("id");

                $.ajax({
                    url: "{{ route('ballone.club.store') }}" + '/' + club_id,
                    method: 'DELETE',
                }).done(function(res) {
                    table.draw();
                })
            });
        });
    </script>
@endsection

@extends('layouts.master')

@section('css')
    <link
        href="{{ asset('assets/backend/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}"
        rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        #resultForm input.text {
            height: 30px;
        }
    </style>
@endsection

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Match Body Fees</h4>

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
                                <h4 class="card-title"> Match List </h4>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="matches" class="table table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Match</th>
                                                    <th>Date Time</th>
                                                    <th>Body (Home)</th>
                                                    <th>Body (Away)</th>
                                                    <th>Goals</th>
                                                    <th>By</th>
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
                    <h5 class="modal-title" id="modelHeading"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="matchForm" name="matchForm" class="form-horizontal">
                        <input type="hidden" name="match_id" id="match_id">

                        <div class="d-flex justify-content-between" style="width:70%;margin:0 auto">
                            <h5 id="home"> Arsenal </h5>
                            <h5 id="score"> Vs </h5>
                            <h5 id="away"> Liverpool </h5>
                        </div>

                        <input type="hidden" name="type" id="type" value="0">

                        <br>

                        <div class="col-12 editBet">
                            <div class="input-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="">Body</span>
                                    </div>
                                    <input name="home_body" id="home_body" type="text" class="form-control">

                                    <input name="away_body" id="away_body" type="text" class="form-control">
                                </div>
                            </div>
                        </div>

                        <br>

                        <div class="col-12 editBet">
                            <div class="input-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Goals</span>
                                    </div>
                                    <input name="goals" id="goals" type="text" class="form-control">
                                </div>
                            </div>
                        </div>

                        <br>

                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">
                                Save changes
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
    <script src="{{ asset('assets/backend/plugins/moment/moment.js') }}"></script>
    <script
        src="{{ asset('assets/backend/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}">
    </script>

    <script>
        $(document).ready(function() {
            $('#date-format').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD HH:mm:ss'
            });

            $('.selectLeague').select2({
                dropdownParent: $('#ajaxModel')
            });

            $('.selectHomeTeam').select2({
                dropdownParent: $('#ajaxModel')
            });

            $('.selectAwayTeam').select2({
                dropdownParent: $('#ajaxModel')
            });

            $("input:checkbox").on('click', function() {
                var $box = $(this);
                if ($box.is(":checked")) {
                    var group = "input:checkbox[name='" + $box.attr("name") + "']";
                    $(group).prop("checked", false);
                    $box.prop("checked", true);
                } else {
                    $box.prop("checked", false);
                }
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#matches').DataTable({
                processing: true,
                "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>'
                },
                serverSide: true,
                ajax: {
                    url: "{{ route('ballone.body.index') }}",
                    data: function(d) {
                        d.search = $('input[type="search"]').val()
                    }
                },
                columns: [{
                        data: 'match',
                        name: 'match'
                    },
                    {
                        data: 'date_time',
                        name: 'date_time'
                    },
                    {
                        data: 'body_home',
                        name: 'body_home'
                    },
                    {
                        data: 'body_away',
                        name: 'body_away'
                    },
                    {
                        data: 'goals',
                        name: 'goals'
                    },
                    {
                        data: 'by',
                        name: 'by'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
            });

            $('body').on('click', '.editMatch', function() {
                var match_id = $(this).data('id');
                $.get("{{ route('ballone.body.index') }}" + '/' + match_id + '/edit', function(data) {
                    console.log(data);
                    $('#modelHeading').html("Add Body Fees");
                    $('#ajaxModel').modal('show');
                    $('#match_id').val(data.id);
                    $("form #home").text(data.home.name);
                    $("form #away").text(data.away.name);
                })
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Sending..');
                $.ajax({
                    data: $('#matchForm').serialize(),
                    url: "{{ route('ballone.body.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        console.log(data)
                        $('#matchForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Save Changes');
                    }
                });
            });

        });
    </script>
@endsection

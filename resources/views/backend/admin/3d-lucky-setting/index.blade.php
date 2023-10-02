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
                                                    <th>Date</th>
                                                    <th>Start Time</th>
                                                    <th>End Time</th>
                                                    <th>Number</th>
                                                    <th>Action</th>
                                                    <th>Status</th>
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

                        <div class="form-group">
                            <div class="col-12">
                                <label for="threedigit_number"> Lucky Number </label>
                                <select class="w-100" name="threedigit_number" style="width: 100%" id="threedigit_number">
                                    <option value="">--- Select Lucky Number ---</option>
                                    @foreach ($threedigit_numbers as $number)
                                        <option value="{{ $number->id }}">{{ $number->number }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="start_time"> Start Time </label>
                                <input type="text" class="form-control" id="start_time" name="start_time"
                                    data-field="datetime" value="" required />
                                <div id="dtBox-start"></div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="end_time"> End Time </label>
                                <input type="text" class="form-control" id="end_time" name="end_time"
                                    data-field="datetime" value="" required />
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

    <div class="modal fade" id="confirmModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5> Confirm </h5>
                </div>
                <form action="{{ route('3d.lucky-number.approve') }}" method="POST">

                    @csrf

                    <div class="modal-body">

                        <div class="row">
                            <div class="col-12 py-4 text-center">
                                <h5 class="text"> Are you sure ? </h5>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="old_id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> No </button>
                        <button type="submit" class="btn btn-success" id="saveBtn" value="create"> Yes </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <script src="{{ asset('assets/backend/DateTimePicker.js') }}"></script>

    <script type="text/javascript">

        $("#dtBox-start").DateTimePicker({
            defaultDate: new Date(),
            dateTimeFormat: "yyyy-mm-dd hh:mm"
        });

        $("#dtBox").DateTimePicker({
            defaultDate: new Date(),
            dateTimeFormat: "yyyy-mm-dd hh:mm"
        });

        $('#threedigit_number').select2({
            dropdownParent: $('#ajaxModel')
        });

        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#three-lottery-close').DataTable({
                processing: true,
                "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>'
                },
                serverSide: true,
                ajax: {
                    url: "{{ route('3d.lucky-number.index') }}",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'start_time',
                        name: 'start_time'
                    },
                    {
                        data: 'end_time',
                        name: 'end_time'
                    },
                    {
                        data: 'number',
                        name: 'number'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                ],
            });

            $('body').on('click', '.editDateTime', function() {

                $('#dateTimeForm').trigger("reset");

                $('#start_time').val($(this).data('start'));
                $('#end_time').val($(this).data('end'));

                $('.js-example-basic-multiple').select2().trigger("change");

                $('#ajaxModel').modal('show');
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Sending..');
                $.ajax({
                    data: $('#dateTimeForm').serialize(),
                    url: "{{ route('3d.lucky-number.update') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        $('#dateTimeForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Save Changes');
                    }
                });
            });


            $('body').on('click', '.finish', function() {
                let id = $(this).data('id');
                let number = $(this).data('number');

                $('#confirmModel #old_id').val(id);
                $("#confirmModel .text").text(`Are you sure ${number} is lucky number ?`);
                $('#confirmModel').modal('show');
            });


        });
    </script>
@endpush

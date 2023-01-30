@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">2D Lucky Number</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">2D Lucky Number</li>
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
                                <h4 class="card-title">2D Lucky Numbers</h4>
                                <div>
                                    <a class="btn btn-success" href="javascript:void(0)" id="createNewNumber"> Create Lucky
                                        Number</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="two_lucky_numbers" class="table table-bordered table-hover nowrap">
                                            <thead>
                                                <tr class="bg-primary text-white" role="row">
                                                    <th>No.</th>
                                                    <th>2 Digit Number</th>
                                                    <th>Lottery Date</th>
                                                    <th>Lottery Time</th>
                                                    <th>Status</th>
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
                    <form id="productForm" name="productForm" class="form-horizontal">
                        <input type="hidden" name="twodigit_id" id="twodigit_id">
                        <div class="form-group">
                            <label for="name" class="col-sm-12 control-label">Two Digit Number</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="twodigit_number" name="twodigit_number"
                                    placeholder="Two Digit Number" value="" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="date" class="col-sm-12 control-label">Date</label>
                            <div class="col-sm-12">
                                <input type="date" class="form-control" id="date" name="date"
                                    placeholder="Two Digit Number" value="" required="">
                            </div>
                        </div>

                        <div class="form-group" id="thai">
                            <label class="col-sm-12 control-label">Lottery Time</label>
                            <div class="col-sm-12">
                                <select class="form-control" id="lottery_time_1" name="lottery_time_1">
                                    @foreach ($times_one as $time)
                                        <option value="{{ $time->id }}"> {{ $time->time }}</option>
                                    @endforeach
                                </select>
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
        $(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#two_lucky_numbers').DataTable({
                processing: true,
                "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>'
                },
                serverSide: true,
                ajax: {
                    url: "{{ route('two_lucky_numbers.index') }}",
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
                        data: 'number',
                        name: 'number'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'lottery_time',
                        name: 'lottery_time'
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

            $('#two_lucky_numbers').on('draw.dt', function() {
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

                    $('.two_lucky_no_status').editable({
                        url: '/admin/two_lucky_no_status',
                        type: 'select',
                        pk: 1,
                        name: 'status',
                        source: [{
                                value: 'Pending',
                                text: 'Pending'
                            },
                            {
                                value: 'Approved',
                                text: 'Approved'
                            },
                            {
                                value: 'Rejected',
                                text: 'Rejected'
                            }
                        ],
                        success: function(data) {
                            console.log(data);
                            toastr.options.closeButton = true;
                            toastr.options.closeMethod = 'fadeOut';
                            toastr.options.closeDuration = 100;
                            toastr.success(data.message);
                            $('#two_lucky_numbers').DataTable().ajax.reload();
                        }
                    });
                }
            });

            $('#createNewNumber').click(function() {
                $('#saveBtn').val("create-number");
                $('#twodigit_id').val('');
                $('#productForm').trigger("reset");
                $('#modelHeading').html("Create Two Digit Lucky Number");
                $('#ajaxModel').modal('show');
            });

            $('body').on('click', '.editNumber', function() {
                var twodigit_id = $(this).data('id');
                $.get("{{ route('two_lucky_numbers.index') }}" + '/' + twodigit_id + '/edit', function(
                    data) {

                    $('#modelHeading').html("Edit Two Digit Lucky Number");
                    $('#saveBtn').val("edit-number");
                    $('#ajaxModel').modal('show');
                    $('#twodigit_id').val(data.id);
                    $('#twodigit_number').val(data.two_digit.number);
                    $("#type").val(data.type);

                    if (data.type == 1) {
                        $('#dubai').removeClass('d-none');
                        $('#thai').addClass('d-none');
                        $("#lottery_time_2").val(data.lottery_time_id);
                    } else {
                        $('#dubai').addClass('d-none');
                        $('#thai').removeClass('d-none');
                        $("#lottery_time_1").val(data.lottery_time_id);
                    }

                    // $('#lottery_time').val(data.lottery_time);
                })
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Sending..');

                $.ajax({
                    data: $('#productForm').serialize(),
                    url: "{{ route('two_lucky_numbers.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('#productForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        console.log(data)
                        if (data.error) alert(data.error);
                        table.draw();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Save Changes');
                    }
                });
            });

            $('body').on('click', '.deleteNumber', function() {

                if (!confirm('Are You sure want to delete !')) return;
                var twodigit_id = $(this).data("id");

                $.ajax({
                    type: "DELETE",
                    url: "{{ route('two_lucky_numbers.store') }}" + '/' + twodigit_id,
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

    <script>
        $("#type").on('change', function() {

            if ($(this).val() == 1) {
                $('#dubai').removeClass('d-none');
                $('#thai').addClass('d-none');
            } else {
                $('#dubai').addClass('d-none');
                $('#thai').removeClass('d-none');
            }

        })
    </script>
@endpush

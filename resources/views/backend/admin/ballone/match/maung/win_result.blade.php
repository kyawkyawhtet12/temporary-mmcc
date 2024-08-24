@extends('layouts.master')


@section('css')
    <style>
        input {
            padding: 0.6rem !important;
        }

        .multiSelect button {
            padding: 0.6rem !important;
        }

        table .done {
            background-color: #dff8ff;
        }

        table .bg-error{
            background-color : rgb(248, 186, 186, .7) !important;
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
                        <h4 class="mb-sm-0"> Maung Win Result </h4>

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

            <div class="card">
                <div class="card-body">
                    <div class="row d-flex">

                        <div class="col">
                            <input type="text" class="form-control" value="Round : 100" readonly>
                        </div>

                        <div class="col-md-3 multiSelect">
                            <x-agent-select />
                        </div>

                        <div class="col">
                            <input type="text" class="form-control" placeholder="User ID" name="user_id" id="user_id">
                        </div>

                        <div class="col">
                            <select name="type" id="type" class="form-control">
                                <option value="">-- Choose Result --</option>
                                <option value="win"> Win </option>
                                <option value="no_win"> No Win </option>
                            </select>
                        </div>

                        <div class="col">
                            <select name="done" id="done" class="form-control">
                                <option value="">-- Payment Status --</option>
                                <option value="success"> Success </option>
                                <option value="pending"> Pending </option>
                            </select>
                        </div>

                        <div class="col">
                            <div class="row">
                                <button type="button" class="btn btn-success col mx-1 btn-sm" id="search">Filter</button>
                                <a href="#" class="btn btn-outline-danger col mx-1 btn-sm" id="reset">Reset</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row grid-margin">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">

                            <h6 class="mb-0">
                                Total Win Amount :
                                <span class="text-info" id="total_report"></span>
                            </h6>

                            <div>
                                <a href="{{ route('ballone.maung.win.result') }}" class="btn btn-danger btn-sm btn-block d-none" id="refreshBtn">
                                    <i class="fas fa-redo mr-1" style="font-size: 0.75rem"></i>
                                    Refresh
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-bordered nowrap text-center">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Agent</th>
                                            <th>User ID</th>
                                            <th>Betting Time</th>
                                            <th> Count</th>
                                            <th>Betting Amount</th>
                                            <th>Win Amount</th>
                                            <th>Result</th>
                                            <th>Payment</th>
                                            <th>Result Time</th>
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
@endsection


@push('scripts')
    <script>
        $(function() {

            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ordering: false,
                pageLength: 25,
                ajax: {
                    url: "{{ route('ballone.maung.win.result.lists') }}",
                    data: function(d) {
                        d.agent_id = $('#agent_id').val(),
                        d.user_id = $('#user_id').val(),
                        d.type = $('#type').val(),
                        d.done = $("#done").val()
                    },
                    dataFilter: function(res) {

                        let {
                            report
                        } = jQuery.parseJSON(res);

                        $("#total_report").text(`
                            ${parseFloat(report.win).toLocaleString()} ( ${report.count} )
                        `);

                        $("#round_no").text(report.round);

                        if(report.no_done > 0){
                            $("#refreshBtn").removeClass('d-none');
                        }else{
                            $("#refreshBtn").addClass('d-none');
                        }

                        return res;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'agent_name',
                        name: 'agent_name'
                    },
                    {
                        data: 'user_id',
                        name: 'user_id'
                    },
                    {
                        data: 'betting_time',
                        name: 'betting_time'
                    },
                    {
                        data: 'total_count',
                        name: 'betting_count'
                    },
                    {
                        data: 'betting_amount',
                        name: 'betting_amount'
                    },
                    {
                        data: 'win_amount',
                        name: 'win_amount'
                    },
                    {
                        data: 'result',
                        name: 'result'
                    },
                    {
                        data: 'done',
                        name: 'done'
                    },
                    {
                        data: 'result_time',
                        name: 'result_time'
                    }

                ],
            });

            $("#search").on('click', function(e) {
                e.preventDefault();
                $("#datatable").DataTable().ajax.reload();
            });

            $("#reset").on('click', function(e) {
                e.preventDefault();
                $('#agent_id').multiselect('reset');
                $('#user_id').val('');
                $('#type').val('');
                $('#done').val('');
                $("#datatable").DataTable().ajax.reload();
            });

        });
    </script>
@endpush

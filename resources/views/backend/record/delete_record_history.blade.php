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
    </style>
@endsection

@section('content')
    <div class="page-content">

        <div class="d-flex justify-content-center align-items-center w-100 vh-100" id="loader">
            <img src="{{ asset('assets/backend/images/loader.gif') }}" alt="" width="200px">
        </div>

        <div class="container-fluid d-none" id="mainpage">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Betting Record Delete History </h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Delete History</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card  bg-light">
                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-3 multiSelect">
                                    <x-agent-select />
                                </div>

                                <div class="col-md-1">
                                    <select name="type" id="type" class="form-control">
                                        <option value=""> All Type </option>
                                        <option value="2D"> 2D </option>
                                        <option value="3D"> 3D </option>
                                    </select>
                                </div>

                                <div class="col">
                                    <input type="text" class="form-control" placeholder="User ID" name="user_id" id="user_id">
                                </div>

                                <div class="col">
                                    <input type="date" class="form-control" placeholder="Start Date" name="start_date"
                                        id="start_date">
                                </div>

                                <div class="col">
                                    <input type="date" class="form-control" placeholder="End Date" name="end_date"
                                        id="end_date">
                                </div>

                                <div class="col">
                                    <div class="row">
                                        <button type="button" class="btn btn-primary col mx-1 btn-sm" id="search">Filter</button>
                                        <a href="#" class="btn btn-danger col mx-1 btn-sm" id="refresh">Refresh</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">

                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive text-center">
                                        <table id="datatable" class="table table-bordered nowrap">
                                            <thead>
                                                <tr class="bg-primary text-white" role="row">
                                                    <th>No.</th>
                                                    <th>User ID</th>
                                                    <th>Type</th>
                                                    <th>Count</th>
                                                    <th>Amount</th>
                                                    <th>Betting Time</th>
                                                    <th>Deleted Time </th>
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
@endsection

@push('scripts')
    <script>
        $(function() {

            setTimeout(() => {
                $("#loader").removeClass('d-flex').addClass('d-none');
                $("#mainpage").removeClass('d-none');
            }, 700);

            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                searching : false,
                pageLength: 25,
                ajax: {
                    url: "{{ route('betting.record.delete.history') }}",
                    data: function(d) {
                        d.search = $('input[type="search"]').val(),
                            d.agent_id = $('#agent_id').val(),
                            d.user_id = $('#user_id').val(),
                            d.type = $('#type').val(),
                            d.min_amount = $('#min_amount').val(),
                            d.max_amount = $('#max_amount').val(),
                            d.start_date = $('#start_date').val(),
                            d.end_date = $('#end_date').val(),
                            d.round = $('#round').val()
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
                        name: 'user_id',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'type',
                        name: 'type',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'count',
                        name: 'count',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'amount',
                        name: 'amount',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'time',
                        name: 'time',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'deleted_at',
                        name: 'deleted_at',
                        orderable: false,
                        searchable: false
                    },
                ],
            });


            $("#search").on('click', function(e) {
                e.preventDefault();

                $("#datatable").DataTable().ajax.reload();
            });

            $("#refresh").on('click', function(e) {
                e.preventDefault();

                $('#agent_id').multiselect('reset');

                $('#type').val('');
                $('#user_id').val('');
                $('#start_date').val('');
                $('#end_date').val('');

                $("#datatable").DataTable().ajax.reload();
            });

        });
    </script>
@endpush

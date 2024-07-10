@extends('layouts.master')


@section('css')
    <style>
        input {
            padding: 0.6rem !important;
        }

        .multiSelect button {
            padding: 0.6rem !important;
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
                        <h4 class="mb-sm-0">Agent {{ $type }} Betting Lists</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Agents</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row mb-3 d-flex">

                <div class="col-md-4 multiSelect">
                    <x-agent-select />
                </div>

                <div class="col-3">
                    <input type="date" class="form-control" placeholder="Start Date" name="start_date" id="start_date" value="{{ $filter['start_date'] }}">
                </div>

                <div class="col-3">
                    <input type="date" class="form-control" placeholder="End Date" name="end_date" id="end_date" value="{{ $filter['end_date'] }}">
                </div>

                <div class="col">
                    <div class="row">
                        <button type="button" class="btn btn-primary col mx-1 btn-sm" id="search">Filter</button>
                        <a href="#" class="btn btn-danger col mx-1 btn-sm" id="refresh">Refresh</a>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <table id="datatable" class="table table-bordered nowrap text-center">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Agent</th>
                                        <th>Total Amount</th>
                                        <th>Date</th>
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
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            $('#datatable').DataTable({
                pageLength : 25,
                processing: true,
                serverSide: true,
                searching : false,
                ordering : false,
                ajax: {
                    url: "{{ route('agents.records', $type) }}",
                    data: function(d) {
                        d.agent_id = $("#agent_id").val(),
                        d.start_date = $('#start_date').val(),
                        d.end_date = $('#end_date').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'agent',
                        name: 'agent'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    }
                ],
            });

            $("#search").on('click', function(e) {
                e.preventDefault();
                $("#datatable").DataTable().ajax.reload();
            });

            $("#refresh").on('click', function(e) {
                e.preventDefault();
                location.reload();
            });

        });
    </script>
@endsection

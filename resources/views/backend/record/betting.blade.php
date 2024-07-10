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
                        <h4 class="mb-sm-0">Betting Record</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Game</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            @include("backend.record.partials._betting_filter")

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="datatable" class="table table-bordered nowrap">
                                            <thead>
                                                <tr class="bg-primary text-white" role="row">
                                                    <th>No.</th>
                                                    <th>User ID</th>
                                                    <th>Type</th>
                                                    <th>Count</th>
                                                    <th>Amount</th>
                                                    <th>Time</th>
                                                    <th>Betting Results </th>
                                                    <th>Betting Wins </th>
                                                    <th></th>
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

            {{-- Betting Detail --}}
            @include("backend.record.partials._betting_detail")

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
                pageLength: 15,
                ajax: {
                    url: "{{ route('betting.record') }}",
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
                        data: 'results',
                        name: 'results',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'wins',
                        name: 'wins',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
            });


        });
    </script>
@endpush

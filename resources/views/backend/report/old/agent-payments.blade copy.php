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

        <div class="container-fluid" id="mainpage">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0"> Payment Report </h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active"> Payment Report</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">

                            <div class="row input-daterange">

                                <div class="col-md-4 multiSelect">
                                    <select name="agent_id[]" id="agent_id" multiple="multiple"
                                        class="agentSelect form-control">
                                        @foreach ($agents as $agent)
                                            <option value="{{ $agent->id }}"
                                                {{ in_array($agent->id, $search['agent_id'] ?? []) ? 'selected' : '' }}>
                                                {{ $agent->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col">
                                    <div class="input-group">
                                        <input type="text" name="from_date" id="from_date" class="form-control"
                                            placeholder="From Date" readonly />
                                        <span class="input-group-addon input-group-append border-left">
                                            <span class="far fa-calendar input-group-text"></span>
                                        </span>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="input-group">
                                        <input type="text" name="to_date" id="to_date" class="form-control"
                                            placeholder="To Date" readonly />
                                        <span class="input-group-addon input-group-append border-left">
                                            <span class="far fa-calendar input-group-text"></span>
                                        </span>
                                    </div>
                                </div>


                                <div class="col-1">

                                    <button type="submit" name="filter" id="filter"
                                        class="btn btn-primary d-block">Filter</button>
                                </div>

                                <div class="col-1">
                                    <a href="" class="btn btn-success">Refresh</a>
                                </div>

                            </div>
                        </div>

                        <div class="card-body">
                            <div class="mb-4 d-flex">
                                <h6 class="mr-5"> Recharge :
                                    <span class="text-info ml-2 total_deposit"> 0 </span>
                                </h6>
                                <h6 class="mr-5"> Cash :
                                    <span class="text-info ml-2 total_withdraw"> 0 </span>
                                </h6>

                                <h6> Net Amount :
                                    <span class="text-info ml-2 total_net_amount"> 0 </span>
                                </h6>
                            </div>

                            <div class="table-responsive px-1">
                                <table class="table table-bordered table-hover data-table">
                                    <thead>
                                        <tr>
                                            <th width="50px">No.</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-right">Recharge</th>
                                            <th class="text-right">Cash</th>
                                            <th class="text-right">Net Amount </th>
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

            $('.input-daterange').datepicker({
                todayBtn: 'linked',
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            $('.agentSelect').multiselect({
                columns: 2,
                placeholder: 'Select Agent',
                search: true,
                searchOptions: {
                    'default': 'Search Agents'
                },
                selectAll: true
            });

            $('.data-table').DataTable({
                processing: true,
                pageLength : 25,
                "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>'
                },
                bFilter: false, // search input
                serverSide: true,
                ajax: {
                    url: "{{ route('agent.payment-reports') }}",
                    data: function(d) {
                        d.agent_id = $('#agent_id').val();
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'deposit',
                        name: 'deposit',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'withdraw',
                        name: 'withdraw',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'net',
                        name: 'net',
                        orderable: false,
                        searchable: false
                    },
                ],
            });

            setTimeout(() => {
                calculateTotal();
            }, 500);

            $('#filter').click(function(e) {
                e.preventDefault();

                $(".data-table").DataTable().ajax.reload();

                setTimeout(() => {
                    calculateTotal();
                },500);
            });

            $('#refresh').click(function() {
                $('#agent_id').val('');
                $('#from_date').val('');
                $('#to_date').val('');

                $(".data-table").DataTable().ajax.reload();

                setTimeout(() => {
                    calculateTotal();
                }, 500);
            });

            function calculateTotal() {

                $.post("{{ route('agent.payment-reports.total') }}", {
                    agent_id: $('#agent_id').val(),
                    from_date: $('#from_date').val(),
                    to_date: $('#to_date').val()

                }).done(function(res) {

                    $(".total_deposit").text(amount_format(parseFloat(res.deposit)));

                    $(".total_withdraw").text(amount_format(parseFloat(res.withdraw)));

                    $(".total_net_amount").text(amount_format(parseFloat(res.net_amount)));
                });

            }

            function amount_format($value) {
                return $value ? $value.toLocaleString() : 0;
            }

        });
    </script>
@endpush

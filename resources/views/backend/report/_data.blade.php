

<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover nowrap data-table text-right">
                <thead>
                    <tr class="bg-primary text-white" role="row">
                        <th>No.</th>
                        <th class="text-center">Date</th>
                        <th>Deposit</th>
                        <th>Withdrawal</th>
                        <th>Net Amount </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        $(function() {

            $('.input-daterange').datepicker({
                todayBtn: 'linked',
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            $('#allReport .agentSelect').multiselect({
                columns: 2,
                placeholder: 'Select Agent',
                search: true,
                selectAll: true
            });

            $('#perReport .agentSelect').multiselect({
                columns: 1
            });

            $('.data-table').DataTable({
                processing: true,
                pageLength: 25,
                "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>'
                },
                bFilter: false, // search input
                searching : false,
                serverSide: true,
                ajax: {
                    url: "{{ route('agents.payment-reports', request()->id) }}",
                    data: function(d) {
                        d.agent_id = $('#agent_id').val();
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    },
                    dataFilter: function(res) {
                        let {
                            total
                        } = jQuery.parseJSON(res);

                        $(".totalDeposit").text(total.deposit);
                        $(".totalWithdraw").text(total.withdraw);
                        $(".totalNetAmount").text(total.net);

                        return res;
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

            $('#filter').click(function(e) {
                e.preventDefault();

                $(".data-table").DataTable().ajax.reload();
            });

            $('#refresh').click(function() {
                $('#agent_id').multiselect('reset');
                $('#start_date').val('');
                $('#end_date').val('');

                $(".data-table").DataTable().ajax.reload();
            });

        });
    </script>
@endpush


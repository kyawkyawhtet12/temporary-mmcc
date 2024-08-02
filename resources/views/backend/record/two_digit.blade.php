<table id="datatable" class="table table-striped table-bordered nowrap text-center">
    <thead>
        <tr class="bg-primary text-white" role="row">
            <th>Date</th>
            <th>Time</th>
            <th>Result</th>
            <th>Betting Amount</th>
            <th>Win Amount </th>
            <th>Net Amount </th>
            <th>Status</th>
        </tr>
    </thead>
</table>

@push('scripts')
    <script>
        $(function() {

            function amountformat(value)
            {
                return parseFloat(value).toLocaleString();
            }

            function getStatus(value)
            {
                if( value > 0 ){
                   return 'Win';
                }

                if( value < 0 ){
                   return 'No Win';
                }

                if( value == 0 ){
                   return '.....';
                }
            }

            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: {
                    url: "{{ route('2d.record') }}",
                    data: function(d) {
                        d.search = $('input[type="search"]').val(),
                            d.agent_id = $('#agent_id').val(),
                            d.start_date = $('#start_date').val(),
                            d.end_date = $('#end_date').val()
                    },
                    dataFilter: function(res) {

                        let {
                            total
                        } = jQuery.parseJSON(res);

                        let netAmount = total.betting - total.win;

                        $(".totalBettingAmount").text(amountformat(total.betting));

                        $(".totalWinAmount").text(amountformat(total.win));

                        $(".totalNetAmount").text(amountformat(netAmount));

                        $(".totalStatus").text(getStatus(netAmount));

                        return res;
                    }
                },
                columns: [{
                        data: 'date',
                        name: 'date',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'time',
                        name: 'time'
                    },
                    {
                        data: 'result_number',
                        name: 'result_number'
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
                        data: 'net_amount',
                        name: 'net_amount',
                    },
                    {
                        data: 'status',
                        name: 'status'
                    }
                ],
            });
        });
    </script>
@endpush

<div class="row grid-margin" id="record-details">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h5> Betting Detail </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive" id="betting-detail">
                    <table id="body" class="table table-bordered nowrap text-center">
                        <thead id="betting-heading">
                        </thead>

                        <tbody id="betting-data">

                        </tbody>

                        <tfoot id="betting-total">

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



@push('scripts')
    <script>
        $(function() {

            const url_prefix = "/admin/betting-record/detail/";

            let total_columns;

            let num = 1;

            let no_match_lists = ["2D", "3D"];

            let match_lists = ["Body", "Maung"];

            let no_match_status = false;

            const columns = ['No', 'Match', 'Betting', 'Odds', 'Betting Type', 'Results', 'Betting Amount',
                'Betting Wins'
            ];


            function tableReset() {
                add_table_heading();

                add_table_body();

                add_table_footer();
            }

            function roundDisable()
            {

                $('#round').multiselect('reset');

                $("#round").multiselect(
                    "disable",
                    !match_lists.includes($('#type').val())
                );
            }

            roundDisable();

            $("#type").on('change', function(e) {
                roundDisable();
            });

            $("#refresh").on('click', function(e) {
                e.preventDefault();

                $('#agent_id').multiselect('reset');
                $('#round').multiselect('reset');

                $('#type').val('');
                $('#user_id').val('');
                $('#min_amount').val('');
                $('#max_amount').val('');
                $('#start_date').val('');
                $('#end_date').val('');

                $("#datatable").DataTable().ajax.reload();
            });


            $("#search").on('click', function(e) {
                e.preventDefault();

                tableReset();

                $("#datatable").DataTable().ajax.reload();
            });


            tableReset();

            function add_table_heading() {
                let th = '';

                columns.forEach((dt, index) => {
                    th += `<th class="${dt.toLowerCase()}-column"> ${dt} </th>`;
                });

                $("#betting-heading").html(` <tr> ${th} </tr>`);

                total_columns = columns.length;
            }

            function add_table_body() {
                $('table tr').removeClass('text-danger');
                $("#betting-data").html(`<tr> <td colspan="${total_columns}"> No Data Available. </td> </tr>`);
            }

            function add_table_footer() {
                let colspan = (no_match_status) ? total_columns - 3 : total_columns - 2;

                $("#betting-total").html(`
                                        <tr>
                                            <td colspan="${colspan}">Total Amount</td>
                                            <td id='betting-amount'></td>
                                            <td id='win-amount'> </td>
                                        </tr>`);
            }

            $('body').on('click', '.viewDetail', function() {

                $(".viewDetail").removeClass('btn-danger');

                tableReset();

                fetchData(url_prefix + $(this).data('id'));

                $(this).addClass('btn-danger');

            });

            function fetchData(url) {
                fetch(`${url}`)
                    .then((response) => response.json())
                    .then((data) => {

                        let tr = '';

                        data.betting.forEach((dt, index) => {

                            tr += `
                            <tr>
                                <td> ${index + 1} </td>
                                <td class="match-column"> ${dt.match} </td>
                                <td> ${dt.betting} </td>
                                <td> ${dt.odds} </td>
                                <td> ${data.type} </td>
                                <td> ${dt.result} </td>
                                <td> ${dt.amount} </td>
                                <td> ${dt.win}</td>
                            </tr>`;
                        });

                        $("#betting-data").html(tr);

                        no_match_status = no_match_lists.includes(data.type);

                        if (no_match_status) {
                            $(".match-column").remove();
                        }

                        add_table_footer();

                        $("tfoot #betting-amount").html(data.amount);
                        $("tfoot #win-amount").html(data.win_amount);
                    });
            }

        });
    </script>
@endpush

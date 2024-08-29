<div class="row grid-margin">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h5> Betting Detail </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="detail-table" class="table table-bordered nowrap text-center">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Match</th>
                                <th>Betting Team</th>
                                <th>Betting Type</th>
                                <th>Odds</th>
                                <th>Result</th>
                                <th>Betting Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center" colspan="7">
                                    No Data Available.
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="d-none">
                            <tr>
                                <td class="" colspan="6"></td>
                                <td id="amount"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@section('script')
    <script>
        $(document).ready(function() {

            let table = $('#table').DataTable();

            $('body').on('click', '.viewDetail', function() {

                let id = $(this).data('id');
                let type = $(this).data('type');
                let amount = $(this).data('amount');

                $('table tr').removeClass('text-danger');

                fetch(`/admin/football/${type}/${id}`, {
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        credentials: "same-origin"
                    })
                    .then((response) => response.json())
                    .then((data) => {

                        let tr = '';

                        if(data.length){
                            data.forEach((dt, index) => {
                                tr += `<tr>
                                        <td> ${index + 1} </td>
                                        <td> ${dt.match} </td>
                                        <td> ${dt.betting} </td>
                                        <td> Maung </td>
                                        <td> ${dt.odds} </td>
                                        <td> ${dt.result} </td>
                                        <td> </td>
                                    </tr>`;

                            });


                            $("tfoot #amount").text(amount);
                            $("tfoot").removeClass('d-none');

                        }else{
                            tr += `<tr>
                                    <td> 1 </td>
                                    <td> ${data.match} </td>
                                    <td> ${data.betting} </td>
                                    <td> Body </td>
                                    <td> ${data.odds} </td>
                                    <td> ${data.result} </td>
                                    <td> ${amount}</td>
                                </tr>`;
                        }

                        $("#detail-table tbody").html(tr);

                    });

                $(this).parent().parent().addClass('text-danger');

            });

        });
    </script>
@endsection

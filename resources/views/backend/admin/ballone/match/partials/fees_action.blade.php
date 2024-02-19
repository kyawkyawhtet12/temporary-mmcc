@section('css')
    <style>
        #resultForm input.text {
            height: 30px;
        }

        .done {
            background-color: #0ed318 !important;
        }

        .old {
            background: rgb(238 236 236) !important
        }

        .done-old {
            background-color: #84e388 !important;
        }

        .time-old {
            background-color: #fbe376 !important;
        }

        .refund {
            background-color: #ffb59c !important;
        }

        table a.match-detail {
            color: black !important;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #e0e0ef !important;
        }
    </style>
@endsection

<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modelHeading"></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="feesForm" name="feesForm" class="form-horizontal" action="">

                    <input type="hidden" name="match_id" id="match_id">

                    <div class="d-flex justify-content-between" style="width:70%;margin:0 auto">
                        <h5 id="home"> Arsenal </h5>
                        <h5 id="score"> Vs </h5>
                        <h5 id="away"> Liverpool </h5>
                    </div>

                    <input type="hidden" name="type" id="type" value="0">

                    <br>

                    <div class="col-12 editBet">
                        <div class="input-group mb-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="">Body</span>
                                </div>
                                <input name="home_body" id="home_body" type="text" class="form-control fees">

                                <input name="away_body" id="away_body" type="text" class="form-control fees">
                            </div>
                        </div>
                    </div>

                    <br>

                    <div class="col-12 editBet">
                        <div class="input-group mb-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Goals</span>
                                </div>
                                <input name="goals" id="goals" type="text" class="form-control fees">
                            </div>
                        </div>
                    </div>

                    <br>

                    <div id="error-box" class="text-center">
                        <h5 class="text-danger" id="error-message"> </h5>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">
                            Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




@push('scripts')
    <script src="{{ asset('assets/js/ballone.js') }}"></script>

    <script>
        $(document).ready(function() {

            $('body').on('click', '.editFees', function() {

                let type = $(this).data("type");

                let title = (type == 'body') ? "Edit Body Fees" : "Edit Maung Fees";
                let route = (type == 'body') ? "{{ route('ballone.body.store') }}" :
                    "{{ route('ballone.maung.store') }}";

                $('#modelHeading').html(title);
                $('#feesForm').attr("action", route);

                feesModal($(this).data());
            });

            function feesModal(data) {

                $('#feesForm').trigger("reset");
                $("#error-message").text('');

                let {
                    id,
                    home,
                    away,
                    up_team,
                    bodyFees,
                    goalFees
                } = data;

                $('form #match_id').val(id);
                $("form #home").text(home);
                $("form #away").text(away);

                let upteamID = (up_team == 1) ? "form #home_body" : "form #away_body";

                $(upteamID).val(bodyFees);

                $("form #goals").val(goalFees);

                $('#ajaxModel').modal('show');
            };

            $('#saveBtn').click(function(e) {

                e.preventDefault();

                $("#error-message").text('');

                let url = $("#feesForm").attr("action");

                $(this).prop("disabled", true);

                $.ajax({
                    data: $('#feesForm').serialize(),
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {

                        if (data.error) {
                            $("#error-message").text(data.error);
                        }

                        if (data.success) {
                            $('#feesForm').trigger("reset");
                            $('#ajaxModel').modal('hide');
                            location.reload();
                        }
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Save Changes');
                    }
                });
            });

        });

        // match action

        $('body').on('click', '.deleteMatch', function() {

            let id = $(this).attr('data-id');

            Swal.fire({
                    text: "Are you sure to delete match ?",
                    icon: "info",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                })
                .then(function(e) {
                    if (e.isConfirmed) {
                        $.ajax({
                            url: "{{ route('ballone.match.store') }}" + '/' + id,
                            method: 'DELETE',
                        }).done(function(res) {
                            Swal.fire({
                                text: "အောင်မြင်ပါသည်",
                                icon: "success",
                            }).then((e) => {
                                // table.draw();
                                location.reload();
                            })
                        })
                    }
                });
        });

        $('body').on('click', '.cancelMatch', function() {

            let id = $(this).attr('data-id');
            let type = $(this).data('type');

            Swal.fire({
                    text: "Are you sure to cancel match and make refund ?",
                    icon: "info",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                })
                .then(function(e) {
                    if (e.isConfirmed) {
                        $.ajax({
                            url: `/admin/ballone/match-refund/${type}/${id}`,
                            method: 'POST',
                        }).done(function(res) {
                            if (res == 'error') {
                                Swal.fire({
                                    text: "something is wrong.",
                                    icon: "error",
                                })
                            } else {
                                Swal.fire({
                                    text: "အောင်မြင်ပါသည်",
                                    icon: "success",
                                }).then((e) => {
                                    // table.draw();
                                    location.reload();
                                })
                            }
                        })
                    }
                });
        });

        $('body').on('click', '.closeMatch', function() {

            let {
                id,
                type,
                status
            } = $(this).data();

            Swal.fire({
                    text: `Are you sure to ${status} this match ?`,
                    icon: "info",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                })
                .then(function(e) {
                    if (e.isConfirmed) {
                        $.ajax({
                            url: `/admin/ballone/match/${type}/${id}/${status}`,
                            method: 'POST',
                        }).done(function(res) {
                            if (res == 'error') {
                                Swal.fire({
                                    text: "something is wrong.",
                                    icon: "error",
                                })
                            } else {
                                Swal.fire({
                                    text: "အောင်မြင်ပါသည်",
                                    icon: "success",
                                }).then((e) => {
                                    // table.draw();
                                    location.reload();
                                })
                            }
                        })
                    }
                });
        });
    </script>
@endpush

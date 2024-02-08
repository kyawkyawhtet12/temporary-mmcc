@section('css')
    <style>
        #results input {
            /* width: 70px !important; */
            padding: 7px 10px !important;
        }

        .resultGroup > * {
            width: 20% !important;
        }
    </style>
@endsection

<div class="row justify-content-center">
    <div class="col-lg-11">
        <div class="card">
            <div class="card-body  table-responsive">
                <div class="d-flex justify-content-between">
                    <h5 class="text-capitalize"> {{ $type }} Fees </h5>
                    <h5 class="text-danger" id="error-message"> </h5>
                </div>
                <table class="table" id="results">

                    <thead>
                        <tr>
                            <th width="150px"> </th>
                            <th width="150px"> Fees / Goals </th>
                            <th> : </th>

                            <th>
                                <div class="d-flex gap-3 resultGroup">
                                    <h5> Home </h5>
                                    <h5> Away </h5>
                                    <h5> Over </h5>
                                    <h5> Under </h5>
                                    <h5> </h5>
                                </div>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($fees as $key => $fee)
                            @if ($fee)
                                <tr>
                                    <td>
                                        {{ $fee->up_team_name }}
                                    </td>

                                    <td>
                                        {{ $fee?->body }} / {{ $fee?->goals }}
                                    </td>

                                    <td> : </td>

                                    <td>

                                        <x-ballone.result-manual-form :result="$fee->result" isCalculationDone="{{ $isCalculationDone }}">
                                            <input type="hidden" name="type" value="{{ $type }}">
                                        </x-ballone.result-manual-form>

                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@section('script')
    <script>
        $(document).ready(function() {

            $('body').on('click', '.result-done', function(e) {
                e.preventDefault();

                let url = $(this).data("url");

                Swal.fire({
                        text: "Are you sure ?",
                        icon: "info",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                    })
                    .then(function(e) {
                        if (e.isConfirmed) {
                            $.ajax({
                                url: url,
                                type: "POST",
                            }).done(function(res) {

                                if (res.error) {
                                    Swal.fire({
                                        text: res.error,
                                        icon: "error",
                                    }).then((e) => {
                                        location.reload();
                                    });
                                } else {
                                    location.replace(res.url);
                                }
                            })
                        }
                    });
            });


            $("#results form input").on("input", function(e) {
                let $form = $(this).closest("form");
                validationForm($form);
            });

            $('body').on('submit', '#results form', function(e) {
                e.preventDefault();

                if (!validationForm($(this))) {

                    $("#results form .submit").prop('disabled', true);

                    $.ajax({
                        url: $(this).attr('action'),
                        type: "POST",
                        data: $(this).serialize()
                    }).done(function(res) {

                        if (res.success) {
                            toastr.success(res.success);
                        } else {
                            toastr.error("Error !");
                        }

                    })
                }

            });


            function validationForm(form) {
                $("#error-message").text('');

                let formError = false;

                // let regexFormat = /^[-+]?[0-9]+$/;

                let regexFormat = /^0$|^[+-](?!0\d)\d+$/;

                let $submitButton = form.find('.submit');

                let input_array = ['home', 'away', 'over', 'under'];

                let message =
                    `* please fill data correctly. ( Example Format => +50 (or) -50 )`;

                $.each(input_array, function(index, value) {

                    let inputForm = form.find(`input[name='${value}']`);

                    let inputValue = inputForm.val().trim();

                    let number = parseInt(inputValue, 10);

                    let isValid = regexFormat.test(inputValue);

                    if (isValid && (number >= -100 && number <= 100)) {

                        inputForm.removeClass('is-invalid');

                    } else {
                        inputForm.addClass('is-invalid');
                        formError = true;
                    }

                });

                $submitButton.prop('disabled', formError);

                return formError;
            }

        });
    </script>
@endsection

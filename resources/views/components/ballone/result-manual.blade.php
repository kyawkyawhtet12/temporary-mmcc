@section('script')
    <script>
        $(document).ready(function() {

            $('body').on('click', '.result-done', function() {

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
                                url: $(this).data('url'),
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
                                    Swal.fire({
                                        text: "အောင်မြင်ပါသည်",
                                        icon: "success",
                                    }).then((e) => {
                                        location.replace(res.url);
                                    });
                                }
                            })
                        }
                    });
            });


            $(".result-input").on("input", function(e) {
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

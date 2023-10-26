@section('script')
    <script>
        $(document).ready(function() {

            $('body').on('click', '.result-done', function() {

                let url = $(this).data('url');

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

                                if( res.error ){
                                    Swal.fire({
                                        text: res.error,
                                        icon: "error",
                                    }).then((e) => {
                                        location.reload();
                                    });
                                }else{
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


            $("#results input").on("keypress", function(e) {

                $("#error-message").text('');

                let num = $(this).val();

                let length = num.length;

                let code = String.fromCharCode(e.keyCode);

                let message = `* please add first (+) or (-) to calculate win or lose.`;

                if (length == 0 && code.match(/^[^0 ^+ ^-]/g)) {
                    $("#error-message").text(message);
                    return false;
                }

                if (length > 0 && !(/[+-]/).test(num)) {
                    $("#error-message").text(message);
                    return false;
                }

                if (length > 0 && !(/[0-9]/).test(num) && code == '0') return false;

                if (length > 0 && code.match(/^[^0-9]/g)) return false;

                if (length >= 3 && code != '0') return false;

                if (length >= 4) return false;

            });

        });
    </script>

@endsection

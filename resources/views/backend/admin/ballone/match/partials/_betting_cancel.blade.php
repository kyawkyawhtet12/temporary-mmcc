@push('scripts')
    <script>
        $(document).ready(function() {

            $('body').on('click', '.cancelBet', function() {

                let id = $(this).data('id');
                let url = $(this).data('url');
                let button = $(this).closest("a");

                Swal.fire({
                    text: "Are you sure to cancel this bet ?",
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
                            dataType: 'json',
                            data: {
                                'id': id
                            }
                        }).done(function(res) {
                            Swal.fire({
                                text: "အောင်မြင်ပါသည်",
                                icon: "success",
                            }).then((e) => {
                                location.reload();
                            })
                        })
                    }
                });
            });
        });
    </script>
@endpush

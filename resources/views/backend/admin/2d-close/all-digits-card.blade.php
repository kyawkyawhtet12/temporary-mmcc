<div class="row">
    <div class="col-12 grid-margin stretch-card d-none d-md-flex">
        <div class="card">

            <div class="card-body">

                <div class="row icons-list">
                    @foreach ($two_digits as $digit)
                        <div class="d-flex col-md-1 justify-content-between align-items-center">
                            <div class="form-check">
                                <label class="form-check-label text-success" style="font-weight: bold;">

                                    {{ $digit->number }}
                                    <i class="input-helper"></i></label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.selectAgent').select2({
                placeholder: {
                    id: 'all',
                    text: 'All Agents'
                }
            });

            $('.selectUser').select2({
                placeholder: {
                    id: 'all',
                    text: 'All Users'
                }
            });

            $('.selectTime').select2({
                placeholder: "Select Time"
            });

            $('.selectNumbers').select2({
                placeholder: "Select Numbers"
            });

            $(document).on("click", ".checkbox", function() {
                var idsArr = [];
                $(".checkbox:checked").each(function() {
                    idsArr.push($(this).attr('data-id'));
                });
                if (idsArr.length > 0) {
                    $('.orderActionContainer').show();
                } else {
                    $('.orderActionContainer').hide();
                }
                console.log(idsArr)
            });

            $('.enabled-all').on('click', function(e) {
                var idsArr = [];
                $(".checkbox:checked").each(function() {
                    idsArr.push($(this).attr('data-id'));
                });

                swal.fire({
                    title: "Enable this checked 2 digit numbers",
                    icon: 'warning',
                    text: "Are you sure, you want to proceed?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, proceed it!",
                    cancelButtonText: "No, cancel!",
                    confirmButtonColor: '#eb3422',
                    cancelButtonColor: "#fe9365",
                    reverseButtons: true
                }).then(function(e) {
                    if (e.value === true) {
                        var strIds = idsArr.join(",");
                        $.ajax({
                            url: "{{ route('twodigits.enabled-all') }}",
                            type: "POST",
                            dataType: "text",
                            data: {
                                'ids': strIds,
                                'status': 0,
                            },
                            success: function(data) {
                                swal.fire("Done!",
                                        "Two digit status changed successfully!",
                                        "success")
                                    .then(function() {
                                        window.location.reload(true);
                                    });
                            }
                        });
                    } else {
                        e.dismiss;
                    }
                }, function(dismiss) {
                    return false
                })
            });

            $('.disabled-all').on('click', function(e) {
                var idsArr = [];
                $(".checkbox:checked").each(function() {
                    idsArr.push($(this).attr('data-id'));
                });
                var date = $("#date").val();

                swal.fire({
                    title: "Disable this checked 2 digit numbers",
                    icon: 'warning',
                    text: "Are you sure, you want to proceed?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, proceed it!",
                    cancelButtonText: "No, cancel!",
                    confirmButtonColor: '#eb3422',
                    cancelButtonColor: "#fe9365",
                    reverseButtons: true
                }).then(function(e) {
                    if (e.value === true) {
                        var strIds = idsArr.join(",");
                        $.ajax({
                            url: "{{ route('twodigits.disabled-all') }}",
                            type: "POST",
                            dataType: "text",
                            data: {
                                'ids': strIds,
                                'status': 1,
                                'date': date,
                            },
                            success: function(data) {
                                swal.fire("Done!",
                                        "Two digit status changed successfully!",
                                        "success")
                                    .then(function() {
                                        window.location.reload(true);
                                    });
                            }
                        });
                    } else {
                        e.dismiss;
                    }
                }, function(dismiss) {
                    return false
                })
            });

            $('.submit-all').on('click', function(e) {
                var idsArr = [];
                $(".checkbox:checked").each(function() {
                    idsArr.push($(this).attr('data-id'));
                });
                var date = $("#date").val();

                swal.fire({
                    title: "Limit amount this checked 2 digit numbers",
                    icon: 'question',
                    text: "Are you sure, you want to proceed?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, proceed it!",
                    cancelButtonText: "No, cancel!",
                    confirmButtonColor: '#eb3422',
                    cancelButtonColor: "#fe9365",
                    reverseButtons: true
                }).then(function(e) {
                    if (e.value === true) {
                        var strIds = idsArr.join(",");
                        $.ajax({
                            url: "{{ route('twodigits.submit-all') }}",
                            type: "POST",
                            dataType: "text",
                            data: {
                                'ids': strIds,
                                'amount': $('#amount').val(),
                                'date': date,
                            },
                            success: function(data) {
                                swal.fire("Done!",
                                        "Two digit status changed successfully!",
                                        "success")
                                    .then(function() {
                                        window.location.reload(true);
                                    });
                            }
                        });
                    } else {
                        e.dismiss;
                    }
                }, function(dismiss) {
                    return false
                })
            });

        });
    </script>
@endpush

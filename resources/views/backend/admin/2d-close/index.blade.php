@extends('layouts.master')

@section('css')
    <style>
        @media (min-width: 768px) {
            .col-md-1 {
                flex: 0 0 9% !important;
                max-width: 9% !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">2D Open/Close</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">2D Open/Close</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            {{-- <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <select name="agent" id="agentSelect" class="form-control">
                                            <option value="all">All</option>
                                            @foreach ($agents as $agent)
                                                <option value="{{ $agent->id }}"> {{ $agent->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="row">
                <div class="col-12 grid-margin stretch-card d-none d-md-flex">
                    <div class="card">
                        <div class="card-header badge-dark text-white">
                            2 Digit (Disable & Enable Selected Numbers)
                        </div>
                        <div class="card-body">
                            <div class="mb-4 row align-items-end orderActionContainer" style="display: none;">
                                <div class="form-group mb-0 col-sm-4">
                                    <button class="btn btn-outline-success enabled-all">Enable</button>
                                    <button class="btn btn-outline-danger disabled-all">Disable</button>
                                </div>
                                <div class="form-row col-sm-4">
                                    <div class="col">
                                        <input type="date" name="date" id="date" value="<?= date('Y-m-d') ?>"
                                            class="form-control" placeholder="Choose Date">
                                    </div>
                                </div>
                                <div class="form-row col-sm-4">
                                    <div class="col">
                                        <input type="number" name="amount" id="amount" autocomplete="off"
                                            class="form-control" placeholder="Enter Amount">
                                    </div>
                                    <div class="col">
                                        <button class="btn btn-outline-success submit-all">Submit</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row icons-list">
                                @foreach ($two_digits as $digit)
                                    @if ($digit->status === 1)
                                        <div class="d-flex col-md-1 justify-content-between">
                                            <div class="form-check">
                                                <label class="form-check-label text-danger mb-1" style="font-weight: bold;">
                                                    <input class="form-check-input checkbox" type="checkbox"
                                                        data-id="{{ $digit->id }}">
                                                    {{ $digit->number }}
                                                    <i class="input-helper"></i></label>
                                                <span>{{ $digit->date }}</span>
                                            </div>
                                        </div>
                                    @elseif($digit->amount > 0)
                                        <div class="d-flex col-md-1 justify-content-between align-items-center">
                                            <div class="form-check">
                                                <label class="form-check-label text-warning" style="font-weight: bold;">
                                                    <input class="form-check-input checkbox" type="checkbox"
                                                        data-id="{{ $digit->id }}">
                                                    {{ $digit->number }}
                                                    <i class="input-helper"></i></label>
                                                <span class="btn btn-sm btn-info mb-2">{{ $digit->amount }}</span>
                                                <span>{{ $digit->date }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-flex col-md-1 justify-content-between align-items-center">
                                            <div class="form-check">
                                                <label class="form-check-label text-success" style="font-weight: bold;">
                                                    <input class="form-check-input checkbox" type="checkbox"
                                                        data-id="{{ $digit->id }}">
                                                    {{ $digit->number }}
                                                    <i class="input-helper"></i></label>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- container-fluid -->
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
<script>
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
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
                            'status': 0
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
                            'date': date
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
                            'date': date
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

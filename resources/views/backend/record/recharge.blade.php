@extends('layouts.master')

@section('css')
    <style>
        input {
            padding: 0.6rem !important;
        }

        .multiSelect button {
            padding: 0.6rem !important;
        }
    </style>
@endsection

@section('content')
    <div class="page-content">

        <div class="d-flex justify-content-center align-items-center w-100 vh-100" id="loader">
            <img src="{{ asset('assets/backend/images/loader.gif') }}" alt="" width="200px">
        </div>

        <div class="container-fluid d-none" id="mainpage">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Recharge Record</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Game</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row mb-3 d-flex">

                <div class="col-md-3 multiSelect">
                   <x-agent-select />
                </div>

                <div class="col">
                    <input type="text" class="form-control" placeholder="User ID" name="user_id" id="user_id">
                </div>

                <div class="col">
                    <input type="text" class="form-control" placeholder="Phone" name="phone" id="phone">
                </div>

                <div class="col">
                    <input type="date" class="form-control" placeholder="Start Date" name="start_date" id="start_date">
                </div>

                <div class="col">
                    <input type="date" class="form-control" placeholder="End Date" name="end_date" id="end_date">
                </div>

                <div class="col">
                    <button type="button" class="btn btn-block btn-primary btn-sm" id="search">Search</button>
                </div>

            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"> Total Amount : <span class="totalAmount"></span> </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="datatable" class="table table-bordered table-hover nowrap">
                                            <thead>
                                                <tr class="bg-primary text-white" role="row">
                                                    <th>No.</th>
                                                    <th>User ID</th>
                                                    <th>Phone</th>
                                                    <th>Amount</th>
                                                    <th>Payment</th>
                                                    <th>Status</th>
                                                    <th>Request Time</th>
                                                    <th>Action Time</th>
                                                    <th>Process Time</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="confirmModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-capitalize py-1" id="modelHeading">
                        Confirm Delete ?
                    </h6>
                </div>
                <div class="modal-body">

                    <div id="error" class="alert alert-warning alert-dismissible fade show d-none" role="alert">
                        <strong> Error !</strong> <span id="text"></span>
                    </div>

                    <div class="p-3 text-center">
                        <h6> Are you Sure to delete this payment ? </h6>
                    </div>

                </div>
                <div class="modal-footer">
                    <form action="{{ route('payment.delete') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" id="id">
                        <div class="">
                            <a href="#" class="btn btn-outline-danger"> No </a>
                            <button type="submit" class="btn btn-success">
                                Yes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {

            setTimeout(() => {
                $("#loader").removeClass('d-flex').addClass('d-none');
                $("#mainpage").removeClass('d-none');
            }, 700);

            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 15,
                searching: false,
                ajax: {
                    url: "{{ route('recharge.record') }}",
                    data: function(d) {
                        d.search = $('input[type="search"]').val(),
                            d.agent_id = $('#agent_id').val(),
                            d.user_id = $('#user_id').val(),
                            d.phone = $('#phone').val(),
                            d.start_date = $('#start_date').val(),
                            d.end_date = $('#end_date').val()
                    },
                    dataFilter: function(data) {
                        var json = jQuery.parseJSON(data);
                        $(".totalAmount").text(parseFloat(json.total).toLocaleString());
                        return JSON.stringify(json);
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user_id',
                        name: 'user_id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'phone',
                        name: 'phone',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'provider_name',
                        name: 'provider_name',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action_time',
                        name: 'action_time',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'process_time',
                        name: 'process_time',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $("#search").on('click', function(e) {
                e.preventDefault();
                $("#datatable").DataTable().ajax.reload();
            });

            $('body').on('click', '.deleteBtn', function() {
                $("#confirmModal form #id").val($(this).data('id'));
                $('#confirmModal').modal('show');
            });
        });
    </script>
@endpush

@extends('layouts.master')

@section('css')
    <style>
        input {
            padding: 0.6rem !important;
        }

        .multiSelect button{
            padding: 0.6rem !important;
        }

        table .done {
            background-color: #dff8ff;
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
                        <h4 class="mb-sm-0">Win Record</h4>

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
                    <input type="text" class="form-control" placeholder="User ID" name="user_id" id="user_id" >
                </div>

                <div class="col">
                    <input type="date" class="form-control" placeholder="Start Date" name="start_date" id="start_date" >
                </div>

                <div class="col">
                    <input type="date" class="form-control" placeholder="End Date" name="end_date" id="end_date" >
                </div>

                <div class="col">
                  <select name="type" id="type" class="form-control">
                    <option value="">--All--</option>
                    <option value="2d"> 2D </option>
                    <option value="3d"> 3D </option>
                    <option value="body"> Body </option>
                    <option value="maung"> Maung </option>
                  </select>
                </div>

                <div class="col">
                    <div class="row">
                        <button type="button" class="btn btn-primary col mx-1 btn-sm" id="search">Filter</button>
                        <a href="#" class="btn btn-danger col mx-1 btn-sm" id="refresh">Refresh</a>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="datatable" class="table table-bordered nowrap">
                                            <thead>
                                                <tr class="bg-primary text-white" role="row">
                                                    <th>No.</th>
                                                    <th>User ID</th>
                                                    <th>Amount</th>
                                                    <th>Type</th>
                                                    <th>Time</th>
                                                    <th>Action</th>
                                                    <th>Remark</th>
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
                pageLength: 25,
                searching : false,
                ajax: {
                    url: "{{ route('win.record.check') }}",
                    data: function(d) {
                        d.search = $('input[type="search"]').val(),
                        d.agent_id = $('#agent_id').val(),
                        d.user_id = $('#user_id').val(),
                        d.phone = $('#phone').val(),
                        d.start_date = $('#start_date').val(),
                        d.end_date = $('#end_date').val(),
                        d.type = $('#type').val()
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
                        data: 'amount',
                        name: 'amount',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'type',
                        name: 'type',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'time',
                        name: 'time',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data : 'action' , name : 'action'
                    },
                    {
                        data : 'remark' , name : 'remark'
                    }
                ],
            });

            $("#search").on('click',function(e){
                e.preventDefault();
                $("#datatable").DataTable().ajax.reload();
            });

            $("#refresh").on('click', function(e) {
                e.preventDefault();
                $('#agent_id').multiselect('reset');
                $('#user_id').val('');
                $('#start_date').val('');
                $('#end_date').val('');
                $("#datatable").DataTable().ajax.reload();
            });

            $('body').on('click', '.delete_btn', function(e) {
                e.preventDefault();
                let url = $(this).data('route');
                console.log(url);

                Swal.fire({
                    text: "Are you sure to delete this win record ?",
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
                            type: "POST"
                        }).done(function(res) {

                            console.log(res);

                            if(res.error){
                                toastr.error('error');
                                return;
                            }

                            Swal.fire({
                                text: "အောင်မြင်ပါသည်",
                                icon: "success",
                            }).then((e) => {
                                location.reload();
                            });
                        })
                    }
                });
            })

        });
    </script>
@endpush

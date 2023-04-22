@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Agent Deposits History</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Agent Deposits</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">

                <div class="col-12 mb-3">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('agent-deposit.index') }}" class="btn btn-success"> Deposit Pending </a>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Agent Deposit History Lists</h4>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="deposits" class="table table-bordered table-hover nowrap">
                                            <thead>
                                                <tr class="bg-primary text-white" role="row">
                                                    <th>No.</th>
                                                    <th>Agent</th>
                                                    <th>Provider</th>
                                                    {{-- <th>Account</th> --}}
                                                    <th>Amount </th>
                                                    <th>Transaction</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
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

    <div class="modal fade" id="imgModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="" width="100%">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var table = $('#deposits').DataTable({
            processing: true,
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>'
            },
            serverSide: true,
            ajax: {
                url: "{{ route('agent-deposit.history') }}",
                data: function(d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'agent',
                    name: 'agent',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'provider',
                    name: 'provider',
                    orderable: false,
                    searchable: false
                },
                // {
                //     data: 'account',
                //     name: 'account'
                // },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'transaction',
                    name: 'transaction'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
            ],
        });

        $('body').on('click', '#imgClick', function() {
            var img = $(this).data("img");
            $("#imgModal img").attr('src', img)
            $("#imgModal").modal('show')
        });
    </script>
@endpush

@extends('layouts.master')

@section('content')

<div class="page-content">
    <div class="container-fluid">
        <div class="page-header">
        <h3 class="page-title">
            Withdraw Lists
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Withdraw</a></li>
            <li class="breadcrumb-item active" aria-current="page">Withdraw Lists</li>
            </ol>
        </nav>
        </div>
        <div class="card">
        <div class="card-body">
            <h4 class="card-title">Withdraw Lists</h4>
            <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                <table id="cashouts" class="table table-bordered table-hover nowrap">
                    <thead>
                    <tr class="bg-primary text-white" role="row">
                        <th>No.</th>
                        <th>Payment Provider</th>
                        <th>Agent</th>
                        <th>Amount </th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Remark</th>
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
<!-- content-wrapper ends -->

@endsection

  @push('scripts')
  <script>
    var table = $('#cashouts').DataTable({
      processing: true,
      "language": { processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>' },
      serverSide: true,
      ajax: {
        url: "{{ route('agent.withdrawal-history') }}",
        data: function (d) {                
          d.search = $('input[type="search"]').val()
        }
      },
      columns: [
      {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
      {data: 'provider', name: 'provider', orderable: false, searchable: false},
      {data: 'agent', name: 'agent', orderable: false, searchable: false},
      {data: 'amount', name: 'amount'},
      {data: 'phone', name: 'phone'},
      {data: 'status', name: 'status'},
      {data: 'remark', name: 'remark'},
      {data: 'created_at', name: 'created_at'},
      ],
    });
  </script>
  @endpush
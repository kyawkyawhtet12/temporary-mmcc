@extends('layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">3D Winner Lists</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                            <li class="breadcrumb-item active">3D Winner Lists</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">3D Winner Lists</h4>
                      <div class="row">
                        <div class="col-12">
                          <div class="table-responsive">
                            <table id="three_winners" class="table table-bordered table-hover nowrap">
                              <thead>
                                <tr class="bg-primary text-white" role="row">
                                  <th>No.</th>
                                  <th>User</th>
                                  <th>Agent</th>
                                  <th>Number</th>
                                  <th>Amount</th>
                                  <th>Za</th>
                                  <th>Total</th>
                                  <th>Date</th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                          </div>
                        </div>
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
<script>
    var table = $('#three_winners').DataTable({
      processing: true,
      "language": { processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>' },
      serverSide: true,
      ajax: {
          url: "{{ route('three_winners.index') }}",
          data: function (d) {
            d.search = $('input[type="search"]').val()
          }
      },
      columns: [
          {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
          {data: 'user', name: 'user', orderable: false, searchable: false},
          {data: 'agent', name: 'agent', orderable: false, searchable: false},
          {data: 'number', name: 'number', orderable: false, searchable: false},
          {data: 'amount', name: 'amount', orderable: false, searchable: false},
          {data: 'za', name: 'za', orderable: false, searchable: false},
          {data: 'total', name: 'total', orderable: false, searchable: false},
          {data: 'created_at', name: 'created_at', orderable: false, searchable: false},
      ],
    });
</script>
@endpush

@extends('layouts.master')

@section('css')

<style>
  table img{
    border-radius: 0 !important;
    width: 100% !important;
    height: auto !important;
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
                        <h4 class="mb-sm-0">Deposits</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Deposits</li>
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
                          <h4 class="card-title"> Deposit Lists</h4>
                          <div class="row">
                            <div class="col-12">
                              <div class="table-responsive">
                                <table id="payments" class="table table-bordered table-hover nowrap">
                                  <thead>
                                    <tr class="bg-primary text-white" role="row">
                                      <th>No.</th>
                                      <th>Payment Provider</th>
                                      <th>User</th>
                                      <th>Amount</th>
                                      <th>Phone</th>
                                      <th>Transacation</th>
                                      <th>Screenshot</th>
                                      <th>Status</th>
                                      <th>Payment Date</th>
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
    var table = $('#payments').DataTable({
      processing: true,
      "language": { processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>' },
      serverSide: true,
      ajax: {
          url: "{{ route('user-payments.index') }}",
          data: function (d) {                
            d.search = $('input[type="search"]').val(),
            d.payment_status = $('input[type="search"]').val()
          }
      },
      columns: [
          {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
          {data: 'provider', name: 'provider', orderable: false, searchable: false},
          {data: 'user', name: 'user', orderable: false, searchable: false},
          {data: 'amount', name: 'amount'},
          {data: 'phone', name: 'phone'},
          {data: 'transation_no', name: 'transation_no'},
          {data: 'transation_ss', name: 'transation_ss'},
          {data: 'status', name: 'status'},
          {data: 'created_at', name: 'created_at'},
      ],
    });
    
    $('#payments').on('draw.dt', function(){
      if ($('#editable-form').length) {
        $.fn.editable.defaults.mode = 'inline';    
        $.fn.editable.defaults.send = "always";  
        $.fn.editableform.buttons =
          '<button type="submit" class="btn btn-primary btn-sm editable-submit">' +
          '<i class="fa fa-fw fa-check"></i>' +
          '</button>' +
          '<button type="button" class="btn btn-default btn-sm editable-cancel">' +
          '<i class="fa fa-fw fa-times"></i>' +
          '</button>';

        $.ajaxSetup({
          headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $('.payment_status').editable({
          url: '/admin/paymentstatus',
          type: 'select',
          name: 'status',
          source: [
            { value: 'Pending', text: 'Pending' },
            { value: 'Approved', text: 'Approved' },
            { value: 'Rejected', text: 'Rejected' }
          ],
          success: function(data) {
            toastr.options.closeButton = true;
            toastr.options.closeMethod = 'fadeOut';
            toastr.options.closeDuration = 100;
            toastr.success(data.message);
            $('#payments').DataTable().ajax.reload();
          }
        });
      }
    });
  </script>
@endpush
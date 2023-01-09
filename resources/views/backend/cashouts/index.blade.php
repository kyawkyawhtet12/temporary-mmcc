@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Withdrawal Lists</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Withdrawal Lists</li>
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
                          <h4 class="card-title"> Withdrawal Lists</h4>
                          <div class="row">
                            <div class="col-12">
                              <div class="table-responsive">
                                <table id="cashouts" class="table table-bordered table-hover nowrap">
                                  <thead>
                                    <tr class="bg-primary text-white" role="row">
                                      <th>No.</th>
                                      <th>Payment Provider</th>
                                      <th>User</th>
                                      {{-- <th>Balance</th> --}}
                                      <th>Amount </th>
                                      <th>Phone</th>
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
@endsection

@push('scripts')
<script>
    var table = $('#cashouts').DataTable({
      processing: true,
      "language": { processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>' },
      serverSide: true,
      ajax: {
          url: "{{ route('cashouts.index') }}",
          data: function (d) {                
            d.search = $('input[type="search"]').val()
          }
      },
      columns: [
          {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
          {data: 'provider', name: 'provider', orderable: false, searchable: false},
          {data: 'user', name: 'user', orderable: false, searchable: false},
          // {data: 'balance', name: 'balance'},
          {data: 'amount', name: 'amount'},
          {data: 'phone', name: 'phone'},
          {data: 'status', name: 'status'},
          {data: 'created_at', name: 'created_at'},
      ],
    });
    // $('#cashouts').on('draw.dt', function(){
    //   let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    //   elems.forEach(function(html) {
    //     let switchery = new Switchery(html,  { size: 'large', color: '#218838', secondaryColor: '#C82333' });
    //   });
    //   $('.js-switch').change(function () {
    //     let status = $(this).prop('checked') === true ? "Completed" : "Uncomplete";
    //     let cashoutId = $(this).data('id');
    //     $.ajax({
    //       type: "GET",
    //       dataType: "json",
    //       url: '{{ route('cash.changeStatus') }}',
    //       data: {'status': status, 'cashout_id': cashoutId},
    //       success: function (data) {
    //         toastr.options.closeButton = true;
    //         toastr.options.closeMethod = 'fadeOut';
    //         toastr.options.closeDuration = 100;
    //         toastr.success(data.message);
    //         $('#cashouts').DataTable().ajax.reload();
    //       }
    //     });
    //   });
    // });

    $('#cashouts').on('draw.dt', function(){
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
          url: '{{ route('cash.changeStatus') }}',
          type: 'select',
          name: 'status',
          source: [
            { value: 'Pending', text: 'Pending', type: 'select' },
            { value: 'Approved', text: 'Approved' },
            { value: 'Rejected', text: 'Rejected' }
          ],
          success: function(data) {
            toastr.options.closeButton = true;
            toastr.options.closeMethod = 'fadeOut';
            toastr.options.closeDuration = 100;
            toastr.success(data.message);
            $('#cashouts').DataTable().ajax.reload();
          }
        });
      }
    });
  </script>
@endpush
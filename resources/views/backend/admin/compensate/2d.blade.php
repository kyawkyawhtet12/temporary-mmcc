@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">2D Limit Compensate</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Limit Compensate</li>
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
                            <h4 class="card-title">2D Limit Compensate </h4>
                            <p class="card-description">
                            Click to change<code>Compensate amount</code>
                            </p>
                            <div class="table-responsive">
                            <table class="table table-bordered nowrap">
                                <thead>
                                <tr class="bg-primary text-white" role="row">
                                    <th>Compensate Amount</th>
                                    <th>Updated Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <form id="editable-form" class="editable-form">
                                                <div class="form-group row">
                                                <div class="col-6 col-lg-8 d-flex align-items-center">
                                                    <a href="#" id="two_compensate" data-type="number" data-pk="{{ $data->id }}">{{ $data->compensate }}</a>
                                                </div>
                                                </div>
                                            </form>
                                        </td>
                                        <td>
                                            <label class="badge badge-info badge-pill">{{ date("F j, Y, g:i A", strtotime($data->updated_at)) }}</label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
  }
</script>

@endpush

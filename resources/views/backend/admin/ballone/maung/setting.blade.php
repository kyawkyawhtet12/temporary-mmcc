@extends('layouts.master')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Maung Teams Setting</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Maung Limit</li>
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
                          <div class="d-flex justify-content-between mb-3">
                            <h4 class="card-title"> Maung minimum and maximum setting </h4>
                          </div>
                          <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="leagues" class="table table-bordered nowrap">
                                        <thead>
                                            <tr>
                                              <th> Min Teams </th>
                                              <th> Max Teams </th>
                                              <th> Action </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    {{ $data->min_teams }}
                                                </td>
                                                <td>
                                                    {{ $data->max_teams }}
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-primary edit"
                                                        data-min={{ $data->min_teams}}
                                                        data-max={{ $data->max_teams}}
                                                    > Edit </a>
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
        </div>
    </div>

    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('ballone.maung-teams-setting.store') }}" method="POST" id="form" name="form" class="form-horizontal">
                    @csrf
                    <div class="form-group">
                        <label for="min_teams" class="col-sm-12 control-label"> Min Teams </label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="min_teams" name="min_teams" placeholder="Enter Minimum Teams" value="" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="max_teams" class="col-sm-12 control-label"> Max Teams </label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="max_teams" name="max_teams" placeholder="Enter Maximum Teams" value="" required="">
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-primary" id="saveBtn" value="create"> Save changes
                      </button>
                    </div>
              </form>
            </div>
          </div>
        </div>
    </div>

@endsection

@section('script')

<script>

    $(function() {
        $('body').on('click', '.edit', function() {

            let min = $(this).data('min');
            let max = $(this).data('max');

            $('#modelHeading').html("Edit Maung Teams Number");
            $('#saveBtn').val("edit-number");
            $('#ajaxModel').modal('show');

            $("#min_teams").val(min);
            $("#max_teams").val(max);

        });

    });
    </script>

@endsection

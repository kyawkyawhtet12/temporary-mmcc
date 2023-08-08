@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Maung Limit Amount</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Ballone</li>
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
                            <h4 class="card-title">Maung Limit Amount </h4>
                            <p class="card-description">
                                Click to change<code>percentage , minimum and maximum amount</code>
                            </p>
                            <div class="row">
                                <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered nowrap">
                                    <thead>
                                        <tr class="bg-primary text-white" role="row">
                                            <th>Agent</th>
                                            <th>Minimum Amount</th>
                                            <th>Maximum Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($agents as $key => $agent)
                                        <tr>
                                            <td>{{  $agent->name }}</td>
                                            <td>
                                                {{ $agent->maung_limit ? $agent->maung_limit->min_amount : 500 }}
                                            </td>
                                            <td>
                                                {{ $agent->maung_limit ? $agent->maung_limit->max_amount : 1000000 }}
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" class="btn btn-primary edit"
                                                    data-agent={{ $agent->id }}
                                                    data-min={{ $agent->maung_limit ? $agent->maung_limit->min_amount : 500 }}
                                                    data-max={{ $agent->maung_limit ? $agent->maung_limit->max_amount : 1000000 }}
                                                > Edit </a>
                                            </td>
                                        </tr>
                                        @endforeach
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
                </div>
                <div class="modal-body">
                    <form action="{{ route('ballone.maung-limit.store') }}" method="POST" id="form" name="form" class="form-horizontal">
                        @csrf
                        <input type="hidden" name="agent_id" id="agent_id">

                        <div class="form-group">
                            <label for="min" class="col-sm-12 control-label">Min Amount</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="min" name="min"
                                    placeholder="Min Amount" value="" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="max" class="col-sm-12 control-label">Max Amount</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="max" name="max"
                                    placeholder="Min Amount" value="" required="">
                            </div>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10 mt-3">
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
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
    $('body').on('click', '.edit', function() {

        let agent = $(this).data('agent');
        let min = $(this).data('min');
        let max = $(this).data('max');

        $('#modelHeading').html("Edit Limit Amount");
        $('#saveBtn').val("edit-number");
        $('#ajaxModel').modal('show');

        $('#agent_id').val(agent);
        $("#min").val(min);
        $("#max").val(max);

    });

});
</script>

@endpush

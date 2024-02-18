@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0"> Report Amount Color </h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active"> Setting </li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>  {{ $type }} Report Amount Color </h5>

                            @if( is_admin())
                            <a href="javascript:void(0)" class="btn btn-success btn-sm addBtn">
                                <i class="fa fa-plus"></i>
                                Add Color
                            </a>
                            @endif
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered nowrap">
                                            <thead>
                                                <tr class="bg-primary text-white" role="row">
                                                    <th>No.</th>
                                                    <th>Max Amount</th>
                                                    <th>Color</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($groups as $key => $group)
                                                    <tr>

                                                        <td>
                                                            {{ ++$key }}
                                                        </td>

                                                        <td>
                                                            {{ number_format($group->max_amount) }}
                                                        </td>

                                                        <td>
                                                            <span class="badge" style="background-color: {{ $group->color }}">

                                                            </span>
                                                        </td>

                                                        <td>
                                                            @if( is_admin())
                                                            <a href="javascript:void(0)" class="text-success editBtn mx-1"
                                                                data-group="{{ $group }}">
                                                                <i class="fa fa-edit"></i>
                                                            </a>

                                                            <a href="javascript:void(0)" class="text-danger deleteBtn mx-1"
                                                                data-id="{{ $group->id }}">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">
                                                            No Data Available.
                                                        </td>
                                                    </tr>
                                                @endforelse
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

                <div class="modal-body">
                    <form action="{{ route('report-color.setting.store', $type) }}" method="POST" id="form"
                        name="form" class="form-horizontal">
                        @csrf
                        <input type="hidden" name="id" id="id">

                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="color" class="" id="color" name="color"
                                    placeholder="Color" value="#04b76b" required="" style="width: 100%">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="max_amount" name="max_amount"
                                    placeholder="Max Amount" value="" required="">
                            </div>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10 mt-3">
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">
                                Submit
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

            $('body').on('click', '.addBtn', function() {

                $("form #id").val('');
                $("form #color").val('#04b76b');
                $("#form #max_amount").val('');

                $('#ajaxModel').modal('show');

            });

            $('body').on('click', '.editBtn', function() {

                let {
                    id,
                    color,
                    max_amount
                } = $(this).data("group");

                $("form #id").val(id);
                $("form #color").val(color);
                $("#form #max_amount").val(max_amount);

                $('#ajaxModel').modal('show');

            });

            $('body').on('click', '.deleteBtn', function() {

                let id = $(this).data("id");

                Swal.fire({
                        text: "Are you sure to delete ?",
                        icon: "info",
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                    })
                    .then(function(e) {
                        if (e.isConfirmed) {
                            $.ajax({
                                url: `/admin/report-amount-setting/${id}`,
                                method: 'DELETE',
                            }).done(function(res) {
                                location.reload();
                            })
                        }
                    });
            });

        });
    </script>
@endpush

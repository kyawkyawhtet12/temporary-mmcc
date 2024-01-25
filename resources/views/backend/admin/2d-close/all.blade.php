@extends('layouts.master')

@section('content')
    <div class="page-content">

        <div class="d-flex justify-content-center align-items-center w-100 vh-100" id="loader">
            <img src="{{ asset('assets/backend/images/loader.gif') }}" alt="" width="200px">
        </div>

        <div class="container-fluid d-none" id="mainpage">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">2D Open/Close For All</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">2D Open/Close</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            @includeWhen(is_admin(), 'backend.admin.2d-close.partials._form_all')

            <div class="row">
                <div class="col-12">

                    <div class="card">

                        <div class="card-body">
                            <div class="row">
                                    @if( $data )
                                    <div class="col-md-12">
                                        <div class="card">
                                            @if( is_admin())
                                            <div class="card-header d-flex justify-content-between">
                                                <div>
                                                    <a href="#" class="btn btn-danger btn-sm deleteLimit"
                                                        data-id="{{ $data->id }}">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="card-body">
                                                <div class="row  justify-content-center gap-3">
                                                    @foreach ($data->limit_number_group as $number => $amount)
                                                        <div class="col-2 text-center"
                                                            style="border: 1px solid lightgray; padding: 5px;">
                                                            <p style="margin-bottom: 5px">
                                                                {{ sprintf('%02d', $number) }}
                                                            </p>
                                                            <span class="btn btn-danger btn-sm"
                                                                style="padding:6px;font-size: 11px">
                                                                {{ $amount }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    @else
                                    <div>
                                        <p class="text-center"> No Data Available.</p>
                                    </div>
                                    @endif
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

            $('body').on('click', '.deleteLimit', function() {

                let id = $(this).data('id');

                Swal.fire({
                        text: "Are you sure to delete ?",
                        icon: "info",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                    })
                    .then(function(e) {
                        if (e.isConfirmed) {
                            $.ajax({
                                url: "{{ route('2d.disable.all') }}" + '/' + id,
                                method: 'DELETE',
                            }).done(function(res) {
                                Swal.fire({
                                    text: "အောင်မြင်ပါသည်",
                                    icon: "success",
                                }).then((e) => {
                                    // table.draw();
                                    location.reload();
                                })
                            })
                        }
                    });
            });
        })
    </script>
@endpush

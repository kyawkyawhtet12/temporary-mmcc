@extends('layouts.master')

@section('css')
    <style>
    </style>
@endsection

@section('content')
    <div class="page-content">

        <div class="d-flex justify-content-center align-items-center w-100 vh-100" id="loader">
            <img src="{{ asset('assets/backend/images/loader.gif') }}" alt="" width="200px">
        </div>


        <div class="container-fluid d-none" id="mainpage">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">2D Open/Close</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">2D Open/Close</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>

            @include('backend.admin.2d-close.partials._form')

            <div class="row">
                <div class="col-12">

                    @if ($data->count())
                        <div class="mb-3">
                            <a href="#" class="btn btn-danger deleteLimit" data-id="all">
                                Delete All
                            </a>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <form action="{{ route('2d.disable') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="date" class="form-control p-2" placeholder="Date" name="date"
                                            value="{{ $filtered['date'] }}">
                                    </div>

                                    <div class="col-md-3">
                                        <select name="time_id" id="time_id" class="form-control">
                                            @foreach ($times as $time)
                                                <option value="{{ $time->id }}"
                                                    {{ $time->id == $filtered['time_id'] ? 'selected' : '' }}>
                                                    {{ $time->time }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <select name="agent_id[]" id="agent_id" multiple="multiple"
                                            class="agentSelect form-control">
                                            @foreach ($agents as $agent)
                                                <option value="{{ $agent->id }}"
                                                    {{ in_array($agent->id, $filtered['agents']) ? 'selected' : '' }}>
                                                    {{ $agent->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary"> Filter </button>

                                        <a href="{{ route('2d.disable') }}" class="btn btn-warning"> Reset </a>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body">
                            <div class="row">

                                @forelse ($data as $x => $dt)
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between">
                                                <p> {{ $dt->agent->name }} </p>

                                                <div>
                                                    <a href="#" class="btn btn-danger btn-sm deleteLimit"
                                                        data-id="{{ $dt->id }}">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row  justify-content-center gap-3">
                                                    @foreach ($dt->limit_number_group as $number => $amount)
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

                                @empty
                                    <div>
                                        <p class="text-center"> No Data Available.</p>
                                    </div>
                                @endforelse

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
                                url: "{{ route('2d.disable') }}" + '/' + id,
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

@extends('layouts.master')


@section('css')
    <link
        href="{{ asset('assets/backend/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}"
        rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        #resultForm input.text {
            height: 30px;
        }

        .done {
            background-color: #0ed318 !important;
        }

        .old {
            background: rgb(238 236 236) !important
        }

        .done-old{
            background-color: #84e388 !important;
        }

        .time-old{
            background-color: #fbe376 !important;
        }

        .refund{
            background-color: #ffb59c !important;
        }

        table a.match-detail {
            color: black !important;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #e0e0ef !important;
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
                        <h4 class="mb-sm-0">Match</h4>

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
                            <div class="d-flex justify-content-between mb-3">
                                <h4 class="card-title"> Match List </h4>
                                <div>
                                    <a class="btn btn-primary" href="{{ route('ballone.match.create') }}"> Add Match</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">

                                    @if (Session::has('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <strong> Success</strong> {{ Session::get('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif

                                    @if (Session::has('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong> Error</strong> {{ Session::get('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif

                                    <div class="table-responive">
                                        <table id="table_id" class="table table-bordered no-warp text-center">
                                            <thead>
                                                <tr>
                                                    <th>Round</th>
                                                    <th>Match</th>
                                                    <th class="sorting_disabled">Date Time</th>

                                                    <th class="sorting_disabled">Edit</th>
                                                    <th class="sorting_disabled">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($data as $x => $dt)
                                                    <tr class="{{ $dt->match_status }}" >
                                                        <td> {{  $dt->match->round }} </td>
                                                        <td>
                                                            {{  $dt->match->match_format }}
                                                        </td>
                                                        <td>{{ get_date_time_format($dt->match) }}</td>
                                                        <td>
                                                            <a href="{{ route('ballone.match.edit', $dt->match->id) }}"
                                                                class="text-success">
                                                                <i class="fa fa-edit text-success m-r-10"></i></a>
                                                        </td>
                                                        <td>
                                                            @if (count($dt->match->bodies) == 0 && count($dt->match->maungs) == 0 && $dt->match->type == 1)
                                                                <a href="javascript:void(0)" data-toggle="tooltip"
                                                                    data-id="{{ $dt->match->id }}"
                                                                    data-original-title="Delete" class="deleteMatch mr-2">
                                                                    <i class="fa fa-trash text-danger m-r-10"></i></a>
                                                            @endif

                                                            @if (!$dt->match->score && $dt->match->type == 1)
                                                                <a href="javascript:void(0)" data-toggle="tooltip"
                                                                    data-id="{{ $dt->match->id }}"
                                                                    data-original-title="Refund" class="cancelMatch">
                                                                    <i class="far fa-times-circle text-danger m-r-10"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="13" class="text-center"> No Data Available. </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-3">
                                        {{  $data->links() }}
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

@section('script')
    <script>

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        $('body').on('click', '.deleteMatch', function() {
            swal({
                    text: "Are you sure to delete match ?",
                    icon: "info",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{ route('ballone.match.store') }}" + '/' + $(this).data(
                                "id"),
                            method: 'DELETE',
                        }).done(function(res) {
                            swal({
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

        $('body').on('click', '.cancelMatch', function() {
            swal({
                    text: "Are you sure to cancel match and make refund ?",
                    icon: "info",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: `/admin/ballone/match/refund/${$(this).data("id")}`,
                            method: 'POST',
                        }).done(function(res) {
                            if (res == 'error') {
                                swal({
                                    text: "something is wrong.",
                                    icon: "error",
                                })
                            } else {
                                swal({
                                    text: "အောင်မြင်ပါသည်",
                                    icon: "success",
                                }).then((e) => {
                                    // table.draw();
                                    location.reload();
                                })
                            }
                        })
                    }
                });
        });
    </script>
@endsection

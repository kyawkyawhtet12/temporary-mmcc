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

        #done {
            background-color: #0ed318 !important;
        }

        table a.match-detail {
            color: black !important;
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

                                    <div class="table-responsive">
                                        <table id="matches" class="table table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>League</th>
                                                    <th>Date Time</th>
                                                    <th>Match</th>
                                                    <th>Result</th>
                                                    <th>Add Result</th>
                                                    <th>Edit</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $x => $dt)
                                                    <tr id="{{ $dt->calculate ? 'done' : '' }}">
                                                        <td>{{ ++$x }}</td>
                                                        <td>{{ $dt->league->name }}</td>
                                                        <td>{{ get_date_time_format($dt) }}</td>
                                                        <td>
                                                            <a href="{{ route('match.report', $dt->id) }}"
                                                                class="match-detail">
                                                                ({{ $dt->round }})
                                                                {{ $dt->home->name }} Vs {{ $dt->away->name }}
                                                            </a>
                                                        </td>
                                                        <td>{{ $dt->score }}</td>
                                                        <td>
                                                            @if (!$dt->calculate)
                                                                <a href="/admin/ballone-add-result/{{ $dt->id }}">
                                                                    <i class="fa fa-plus-square text-inverse m-r-10"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (count($dt->bodies) == 0 && count($dt->maungs) == 0 && $dt->score == null)
                                                                <a href="{{ route('ballone.match.edit', $dt->id) }}"
                                                                    class="text-success">
                                                                    <i class="fa fa-edit text-success m-r-10"></i></a>
                                                            @endif
                                                        </td>
                                                        <td>

                                                            @if (count($dt->bodies) == 0 && count($dt->maungs) == 0)
                                                                <a href="javascript:void(0)" data-toggle="tooltip"
                                                                    data-id="{{ $dt->id }}"
                                                                    data-original-title="Delete" class="deleteMatch mr-2">
                                                                    <i class="fa fa-trash text-danger m-r-10"></i></a>
                                                            @endif

                                                            @if ($dt->score === null)
                                                                <a href="javascript:void(0)" data-toggle="tooltip"
                                                                    data-id="{{ $dt->id }}"
                                                                    data-original-title="Refund" class="cancelMatch">
                                                                    <i
                                                                        class="far fa-times-circle text-danger m-r-10"></i></a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-3">
                                        {{ $data->links() }}
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/backend/plugins/moment/moment.js') }}"></script>
    <script
        src="{{ asset('assets/backend/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}">
    </script>

    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // var table = $('#matches').DataTable({
            //     "pageLength": 30,
            //     'columnDefs': [{
            //         'targets': [1, 2, 3, 4, 5, 6, 7, 8],
            //         'orderable': false,
            //     }]
            // });

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


        });
    </script>
@endsection

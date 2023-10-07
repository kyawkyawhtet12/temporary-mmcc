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

        .done-old {
            background-color: #84e388 !important;
        }

        .time-old {
            background-color: #fbe376 !important;
        }

        .refund {
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
                        <h4 class="mb-sm-0">Match Body Fees</h4>

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
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="matches" class="table table-bordered nowrap text-center">
                                            <thead>
                                                <tr>
                                                    <th>Round</th>
                                                    <th>Match</th>
                                                    <th>Date Time</th>
                                                    <th>Result</th>
                                                    <th colspan="2">Body</th>
                                                    <th>Goals</th>
                                                    <th>Home</th>
                                                    <th>Away</th>
                                                    <th>Over</th>
                                                    <th>Under</th>
                                                    <th>Add Result</th>
                                                    <th>Edit Fees</th>
                                                    <th>By</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($data as $dt)
                                                    <tr class="{{ $dt->match_status }}">
                                                        <td>{{ $dt->match->round }}</td>
                                                        <td>
                                                            <a href="{{ route('match.body-report', $dt->match->id) }}"
                                                                class="match-detail">

                                                                {{ $dt->match->match_format }}
                                                            </a>
                                                        </td>

                                                        <td>
                                                            {{ get_date_time_format($dt->match) }}
                                                        </td>

                                                        <td>{{ $dt->match->calculate_body ? $dt->match->body_temp_score : '' }}
                                                        </td>

                                                        <td>
                                                            @if ($dt->up_team == 1)
                                                                {{ $dt->body }}
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if ($dt->up_team == 2)
                                                                {{ $dt->body }}
                                                            @endif
                                                        </td>

                                                        <td>
                                                            {{ $dt->goals }}
                                                        </td>

                                                        <td>
                                                            {{ $dt->get_result($dt->home) }}
                                                        </td>

                                                        <td>
                                                            {{ $dt->get_result($dt->away) }}
                                                        </td>

                                                        <td>
                                                            {{ $dt->get_result($dt->over) }}
                                                        </td>

                                                        <td>
                                                            {{ $dt->get_result($dt->under) }}
                                                        </td>

                                                        <td>
                                                            @if (!$dt->match->calculate_body && $dt->match->type == 1)
                                                                <a
                                                                    href="/admin/ballone-add-result/body/{{ $dt->match->id }}">
                                                                    <i class="fa fa-plus-square text-inverse m-r-10"></i>
                                                                </a>
                                                            @endif

                                                            @if ($dt->match->type == 0)
                                                                Refund Match
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if (!$dt->match->calculate_body && $dt->match->type == 1)
                                                                <a href="javascript:void(0)" class="editMatch mr-2"
                                                                data-id=" {{ $dt->match->id }}"
                                                                data-home="{{ $dt->match->home_team }}"
                                                                data-away="{{ $dt->match->away_team }}"
                                                                >
                                                                <i class="fa fa-edit text-inverse m-r-10"></i>
                                                            </a>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            {{ $dt->by_user }}
                                                        </td>

                                                        <td>
                                                            <a href="{{ route('ballone.match.edit', $dt->match->id) }}"
                                                                class="text-success">
                                                                <i class="fa fa-edit text-success m-1"></i>
                                                            </a>

                                                            @if ($dt->match->bodies_count == 0 && $dt->match->maungs_count == 0 && $dt->match->type == 1)
                                                                <a href="javascript:void(0)" data-toggle="tooltip"
                                                                    data-id="{{ $dt->match->id }}"
                                                                    data-original-title="Delete" class="deleteMatch mr-2">
                                                                    <i class="fa fa-trash text-danger m-1"></i></a>
                                                            @endif

                                                            @if (!$dt->match->score && $dt->match->type == 1)
                                                                <a href="javascript:void(0)" data-toggle="tooltip"
                                                                    data-id="{{ $dt->match->id }}"
                                                                    data-original-title="Refund" class="cancelMatch">
                                                                    <i class="far fa-times-circle text-danger m-1"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="14" class="text-center"> No Data Available. </td>
                                                    </tr>
                                                @endforelse
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

    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="matchForm" name="matchForm" class="form-horizontal">
                        <input type="hidden" name="match_id" id="match_id">

                        <div class="d-flex justify-content-between" style="width:70%;margin:0 auto">
                            <h5 id="home"> Arsenal </h5>
                            <h5 id="score"> Vs </h5>
                            <h5 id="away"> Liverpool </h5>
                        </div>

                        <input type="hidden" name="type" id="type" value="0">

                        <br>

                        <div class="col-12 editBet">
                            <div class="input-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="">Body</span>
                                    </div>
                                    <input name="home_body" id="home_body" type="text" class="form-control fees">

                                    <input name="away_body" id="away_body" type="text" class="form-control fees">
                                </div>
                            </div>
                        </div>

                        <br>

                        <div class="col-12 editBet">
                            <div class="input-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Goals</span>
                                    </div>
                                    <input name="goals" id="goals" type="text" class="form-control fees">
                                </div>
                            </div>
                        </div>

                        <br>

                        <div id="error-box" class="text-center">
                            <h5 class="text-danger" id="error-message"> </h5>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">
                                Save changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/backend/plugins/moment/moment.js') }}"></script>
    <script src="{{ asset("assets/js/ballone.js") }}"></script>
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

            // var table = $('#matches').DataTable();

            $('body').on('click', '.editMatch', function() {
                $('#modelHeading').html("Add Body Fees");
                $('#matchForm').trigger("reset");
                $("#error-message").text('');
                $('#ajaxModel').modal('show');
                $('#match_id').val($(this).data('id'));
                $("form #home").text($(this).data('home'));
                $("form #away").text($(this).data('away'));
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Sending..');
                $("#error-message").text('');
                $.ajax({
                    data: $('#matchForm').serialize(),
                    url: "{{ route('ballone.body.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {

                        if(data.error){
                            $("#error-message").text(data.error);
                        }

                       if(data.success){
                            $('#matchForm').trigger("reset");
                            $('#ajaxModel').modal('hide');
                            location.reload();
                       }
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Save Changes');
                    }
                });
            });

        });

        $('body').on('click', '.deleteMatch', function() {

            let id = $(this).attr('data-id');

            Swal.fire({
                    text: "Are you sure to delete match ?",
                    icon: "info",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                })
                .then(function(e) {
                    if (e.isConfirmed) {
                        $.ajax({
                            url: "{{ route('ballone.match.store') }}" + '/' + id,
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

        $('body').on('click', '.cancelMatch', function() {

            let id = $(this).attr('data-id');

            Swal.fire({
                    text: "Are you sure to cancel match and make refund ?",
                    icon: "info",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                })
                .then(function(e) {
                    if (e.isConfirmed) {
                        $.ajax({
                            url: `/admin/ballone/match/refund/${id}`,
                            method: 'POST',
                        }).done(function(res) {
                            if (res == 'error') {
                                Swal.fire({
                                    text: "something is wrong.",
                                    icon: "error",
                                })
                            } else {
                                Swal.fire({
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

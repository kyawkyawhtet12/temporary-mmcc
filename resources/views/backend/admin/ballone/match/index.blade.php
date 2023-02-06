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
                                    <a class="btn btn-primary" href="javascript:void(0)" id="createLeague"> Add Match</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="matches" class="table table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Round</th>
                                                    <th>League</th>
                                                    <th>Date Time</th>
                                                    <th>Home Team</th>
                                                    <th>Away Team</th>
                                                    <th>Result</th>
                                                    <th>Calculate</th>
                                                    {{-- <th>Body</th>
                                          <th>Goals</th> --}}
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
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
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="matchForm" name="matchForm" class="form-horizontal">
                        <input type="hidden" name="match_id" id="match_id">

                        <div class="form-group editTeams">
                            <label for="round" class="col-sm-6 control-label"> Round </label>
                            <div class="col-sm-12">
                                <input type="text" id="round" class="form-control" name="round">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="league" class="col-sm-12 control-label"> League </label>
                            <div class="col-sm-12">
                                <select class="form-control selectLeague" name="league_id" id="league"
                                    style="width: 100%;">
                                    <option value="">--Select League Name--</option>
                                    @foreach ($leagues as $league)
                                        <option value="{{ $league->id }}">{{ $league->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-12 control-label">Date Time</label>
                            <div class="col-sm-12">
                                <input type="text" id="date-format" class="form-control" name="date_time">
                            </div>
                        </div>

                        <div class="form-group editTeams">
                            <label for="home_id" class="col-sm-6 control-label"> Home Team </label>
                            <div class="col-sm-12">
                                <select class="form-control selectHomeTeam" name="home_id" id="home_id"
                                    style="width: 100%;">

                                </select>
                            </div>
                        </div>

                        <div class="form-group editTeams">
                            <label for="away_id" class="col-sm-12 control-label"> Away Team </label>
                            <div class="col-sm-12">
                                <select class="form-control selectAwayTeam" name="away_id" id="away_id"
                                    style="width: 100%;">

                                </select>
                            </div>
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

    <div class="modal fade" id="resultModal" aria-hidden="true">
        <div class="modal-dialog" role="document" style="min-width: 600px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="resultModalLabel1">Add Result</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <form id="resultForm" name="resultForm" class="form-horizontal">
                    <input type="hidden" name="match_id" id="result_id">
                    <div class="modal-body">

                        <h4 class="card-title">ပွဲပြီးရလဒ်အား ရိုက်ထည့်ပါ။</h4>
                        <div class="input-group mb-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="up-team"></span>
                                </div>
                                <input type="number" name="up_team" class="form-control" min="0">
                                <input type="number" name="down_team" class="form-control" min="0">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="down-team"></span>
                                </div>
                            </div>
                        </div>

                        <div id="bet-groups">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="updateResult"> Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="calculateModal" aria-hidden="true">
        <div class="modal-dialog" role="document" style="min-width: 600px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="calculateModalLabel1"> Calculate For Body </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <form id="calculateForm" name="calculateForm" class="form-horizontal">
                    <input type="hidden" name="match_id" id="result_id">
                    <input type="hidden" name="type" id="result_type">
                    <div class="modal-body">

                        <div class="d-flex justify-content-between" style="width:70%;margin:0 auto">
                            <h5 id="home-team"> Arsenal </h5>
                            <h5 id="score"> 1 -1 </h5>
                            <h5 id="away-team"> Liverpool </h5>
                        </div>

                        <div id="bet-groups">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="calculateResult"> Save changes</button>
                    </div>
                </form>
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
            $('#date-format').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD HH:mm:ss'
            });

            $('.selectLeague').select2({
                dropdownParent: $('#ajaxModel')
            });

            $('.selectHomeTeam').select2({
                dropdownParent: $('#ajaxModel')
            });

            $('.selectAwayTeam').select2({
                dropdownParent: $('#ajaxModel')
            });

            $("input:checkbox").on('click', function() {
                var $box = $(this);
                if ($box.is(":checked")) {
                    var group = "input:checkbox[name='" + $box.attr("name") + "']";
                    $(group).prop("checked", false);
                    $box.prop("checked", true);
                } else {
                    $box.prop("checked", false);
                }
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#matches').DataTable({
                processing: true,
                "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>'
                },
                serverSide: true,
                ajax: {
                    url: "{{ route('ballone.match.index') }}",
                    data: function(d) {
                        d.search = $('input[type="search"]').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'round',
                        name: 'round'
                    },
                    {
                        data: 'league',
                        name: 'league'
                    },
                    {
                        data: 'date_time',
                        name: 'date_time'
                    },
                    {
                        data: 'home',
                        name: 'home'
                    },
                    {
                        data: 'away',
                        name: 'away'
                    },
                    {
                        data: 'score',
                        name: 'score'
                    },
                    {
                        data: 'calculate',
                        name: 'calculate'
                    },
                    // {data: 'body', name: 'body'},
                    // {data: 'goals', name: 'goals'},
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
            });

            $('#createLeague').click(function() {
                $('#saveBtn').val("create-league");
                $('#match_id').val('');
                $('#matchForm').trigger("reset");
                $('.selectLeague').trigger("change");
                $('.selectHomeTeam').trigger("change");
                $('.selectAwayTeam').trigger("change");
                $('#modelHeading').html("Create New Match");
                $("#ajaxModel #type").val(0); // Add
                $("#editBetFees").addClass('d-none');
                $(".editBet").removeClass('d-none');
                $(".editTeams").removeClass('d-none');
                $('#ajaxModel').modal('show');
            });

            // $('body').on('click', '.editMatch', function () {
            //   var match_id = $(this).data('id');
            //   $.get("{{ route('ballone.match.index') }}" +'/' + match_id +'/edit', function (data) {            
            //       console.log(data);
            //       $('#modelHeading').html("Update Match");
            //       $('#saveBtn').val("edit-league");
            //       $('#ajaxModel').modal('show');
            //       $("#ajaxModel #type").val(1); // Edit
            //       $('#match_id').val(data.id);
            //       $('#round').val(data.round);
            //       $('#date-format').val(data.date_time);

            //       // $("#home_id").html('').trigger('change');
            //       // $("#away_id").html('').trigger('change');

            //       $('#league').val(data.league_id).trigger('change');
            //       // $('#home_id').val(data.home_id).trigger('change');
            //       // $('#away_id').val(data.away_id).trigger('change');


            //       $("#editBetFees").removeClass('d-none');            
            //       $(".editBet").addClass('d-none');

            //       $(".editTeams").addClass('d-none');

            //       if( data.fees.up_team == 1 ){
            //         $("#home_up").prop('checked', true);
            //       }else{
            //         $("#away_up").prop('checked', true);
            //       }
            //   })
            // });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Sending..');
                $.ajax({
                    data: $('#matchForm').serialize(),
                    url: "{{ route('ballone.match.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('#matchForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Save Changes');
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
                                    table.draw();
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
                                        table.draw();
                                    })
                                }
                            })
                        }
                    });
            });

            // Add Result
            $('body').on('click', '.addResult', function() {
                var match_id = $(this).data('id');
                $.get("{{ route('ballone.match.index') }}" + '/' + match_id, function(data) {

                    $('#resultModal').modal('show');
                    $('#result_id').val(data.id);
                    $('#up-team').html(data.home.name);
                    $('#down-team').html(data.away.name);
                    $('#teamone').val(data.home_id);
                    $('#teamtwo').val(data.away_id);
                    $("label[for = teamone]").text(data.home.name);
                    $("label[for = teamtwo]").text(data.away.name);
                    $("#bet-groups").html('');

                })
            });

            $('#updateResult').click(function(e) {
                e.preventDefault();
                $(this).html('Sending..');
                $.ajax({
                    data: $('#resultForm').serialize(),
                    url: "{{ route('ballone.match.result') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        $('#resultForm').trigger("reset");
                        $('#resultModal').modal('hide');
                        table.draw();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Save Changes');
                    }
                });
            });

            // Calculate Body
            $('body').on('click', '.calculateBtn', function() {
                var match_id = $(this).data('id');
                var type = $(this).data('type');
                $.get("{{ route('ballone.match.index') }}" + '/' + match_id, function(data) {

                    console.log(data);
                    $('#calculateModal').modal('show');
                    $('#calculateModal #result_id').val(data.id);
                    $('#calculateModal #result_type').val(type);

                    $('#calculateModal #up-team').html(data.home.name);
                    $('#calculateModal #down-team').html(data.away.name);

                    $('#calculateModal #home-team').html(data.home.name);
                    $('#calculateModal #score').html(data.score);
                    $('#calculateModal #away-team').html(data.away.name);

                    $('#calculateModal #teamone').val(data.home_id);
                    $('#calculateModal #teamtwo').val(data.away_id);
                    $("#calculateModal label[for = teamone]").text(data.home.name);
                    $("#calculateModal label[for = teamtwo]").text(data.away.name);
                    $("#calculateModal #bet-groups").html('');

                    var num = 1;

                    var allFeesData = '';

                    let d = (type == 'body') ? data.all_body_fees : data.all_maung_fees;

                    d.map(function(f) {
                        // console.log(f);
                        var upTeam = (f.up_team == 1) ? data.home.name : data.away.name;
                        allFeesData += `
                  <div id="bet-1">

                    <h5 class="my-3 text-center"> Bet Fees ${num} </h5>

                    <h5 class="mb-3"> 
                      ဘော်ဒီကြေး = ${f.body} ( ${upTeam} )
                    </h5>

                    <p>လောင်းကြေးနိုင်သော အသင်းအား ရွေးပေးပါ။ </p>

                    <div class="demo-checkbox mb-2 d-flex">
                      <div class="mr-3">
                        <input type="radio" id="teamone" name="club_id[${f.id}]" value="${data.home.id}" class="filled-in">
                        <label for="teamone">${data.home.name}</label>
                      </div>
                      <div class="mr-3">
                        <input type="radio" id="teamtwo" name="club_id[${f.id}]" value="${data.away.id}" class="filled-in">
                        <label for="teamtwo">${data.away.name}</label>
                      </div>
                      <div>
                        <input type="radio" id="teamdraw" name="club_id[${f.id}]" value="draw" class="filled-in">
                        <label for="teamtwo"> လောင်းကြေး အနိင်/အရှုံး မရှိပါ </label>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="deposit" class="control-label">
                        အပြည့်မနိုင်လျှင် နိုင်သည့် ရာခိုင်နှုန်း (နံပါတ်) အားရိုက်ထည့်ပါ :</label>
                      <input type="number" class="form-control text" id="body_percentage" name="body_percentage[${f.id}]" min="1">
                    </div>

                    <h5 class="mb-3"> ဂိုးပေါင်းကြေး = ${f.goals} </h5>

                    <p>ဂိုးပေါင်း ရွေးချယ်ပေးပါ။ </p>

                    <div class="demo-checkbox d-flex">
                      <div class="mr-3">
                        <input type="radio" id="goal_plus" value="over" name="goals[${f.id}]" class="filled-in">
                        <label for="goal_plus"> ဂိုးပေါ် </label>
                      </div>
                      <div class="mr-3">
                        <input type="radio" id="goal_minus" value="under" name="goals[${f.id}]" class="filled-in">
                        <label for="goal_minus"> ဂိုးအောက် </label>
                      </div>
                      <div>
                        <input type="radio" id="goal_draw" name="goals[${f.id}]" value="draw" class="filled-in">
                        <label for="teamtwo"> လောင်းကြေး အနိင်/အရှုံး မရှိပါ </label>
                      </div>
                    </div>

                    <div class="form-group mt-2">
                      <label for="deposit" class="control-label">
                        အပြည့်မနိုင်လျှင် နိုင်သည့် ရာခိုင်နှုန်း (နံပါတ်) အားရိုက်ထည့်ပါ :
                      </label>
                      <input type="number" class="form-control text" 
                        id="goals_percentage" name="goals_percentage[${f.id}]" min="1">
                    </div>
                  </div>
                  <hr>
                `;

                        num++;
                    });

                    $("#calculateModal #bet-groups").append(allFeesData);
                })
            });

            $('#calculateResult').click(function(e) {
                e.preventDefault();
                $(this).html('Sending..');
                let type = $("#result_type").val();

                let url = (type == 'body') ? "{{ route('ballone.calculate.body.result') }}" :
                    "{{ route('ballone.calculate.maung.result') }}";
                // console.log(url)

                $.ajax({
                    data: $('#calculateForm').serialize(),
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        $('#calculateForm').trigger("reset");
                        $('#calculateModal').modal('hide');
                        table.draw();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Save Changes');
                    }
                });
            });

            // $('body').on('change', '#edit_fees', function (e) {

            //   console.log(this.value);
            //   if( this.value == 1){
            //     $(".editBet").removeClass('d-none');
            //   }else{
            //     $(".editBet").addClass('d-none');
            //   }
            // });

            $("body").on('change', '#league', function(e) {
                // console.log(this.value);

                $("#home_id").html('').trigger('change');
                $("#away_id").html('').trigger('change');

                if (this.value) {
                    $.get(`/admin/get-clubs/${this.value}`, function(data) {

                        if (data) {
                            data.map(function(d) {
                                // console.log(d)
                                var newOption = new Option(d.name, d.id, true, true);
                                $("#home_id").append(newOption).trigger('change');
                            });

                            data.map(function(d) {
                                // console.log(d)
                                var newOption = new Option(d.name, d.id, true, true);
                                $("#away_id").append(newOption).trigger('change');
                            });
                        }
                    })
                }

            });

        });
    </script>
@endsection

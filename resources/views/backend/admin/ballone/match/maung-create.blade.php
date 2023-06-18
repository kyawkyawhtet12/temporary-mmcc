@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0"> Match Add </h4>

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

            <div class="row my-3">
                <div class="col-lg-6 offset-lg-3">

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong> Error ! </strong> Please fill all input.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('ballone.maung.fees.store') }}" method="POST" class="form-horizontal" novalidate>
                        @csrf

                        <div class="card">
                            <div class="card-body">
                                <label for="round" class="col-sm-6 control-label"> Round
                                </label>
                                <div class="col-sm-12">
                                    <input type="number" id="round" class="form-control" name="round" value="{{  $round }}">
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <label for="league" class="col-sm-12 control-label">
                                    League
                                </label>
                                <div class="col-sm-12">
                                    <select class="form-control selectLeague" name="league_id" id="league"
                                        style="width: 100%;" required>
                                        <option value="">--Select League Name--</option>
                                        @foreach ($leagues as $league)
                                            <option value="{{ $league->id }}">
                                                {{ $league->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="first">
                            <div class="card">
                                <div class="card-body">

                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <label for="name" class="control-label">Date</label>
                                            <div>
                                                <input type="date" id="date" class="form-control" name="date[]"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <label for="name" class="control-label">Time</label>
                                            <div>
                                                <input type="time" id="time" class="form-control" name="time[]"
                                                    required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-12 d-flex">
                                            <label for="other" class="control-label">
                                                ကြားကွင်း
                                            </label>

                                            <div>
                                                <input type="radio" id="home_other" name="other[0]" value="1"
                                                class="ml-2">
                                                <label class="ml-2 align-middle" for="home_other"> Home </label>

                                                <input type="radio" id="away_other" name="other[0]" value="2"
                                                class="ml-2">
                                                <label class="ml-2 align-middle" for="away_other"> Away </label>

                                                <input type="radio" id="no_other" name="other[0]" value="0"
                                                class="ml-2" checked>
                                                <label class="ml-2 align-middle" for="no_other"> None </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <label for="home_no" class="control-label"> Home No
                                            </label>
                                            <div>
                                                <input type="number" id="home_no" class="form-control" name="home_no[]">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <label for="away_no" class="control-label"> Away No
                                            </label>
                                            <div>
                                                <input type="number" id="away_no" class="form-control" name="away_no[]">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <label for="home_id" class="control-label"> Home Team
                                            </label>
                                            <div>
                                                <select class="form-control selectHomeTeam" name="home_id[]" id="home_id"
                                                    required style="width: 100%;">
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <label for="away_id" class="control-label"> Away Team
                                            </label>
                                            <div>
                                                <select class="form-control selectAwayTeam" name="away_id[]" id="away_id"
                                                    required style="width: 100%;">
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12 editBet">
                                            <div class="input-group mb-3">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="">Body</span>
                                                    </div>
                                                    <input name="home_body[]" id="home_body" type="text" class="form-control">

                                                    <input name="away_body[]" id="away_body" type="text" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12 editBet">
                                            <div class="input-group mb-3">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Goals</span>
                                                    </div>
                                                    <input name="goals[]" id="goals" type="text" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div id="more">
                            @for ($i = 1; $i < 20; $i++)
                                <div class="card d-none" id="group_{{ $i }}">
                                    <div class="card-body">

                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="name" class="control-label">Date</label>
                                                <div>
                                                    <input type="date" id="date" class="form-control"
                                                        name="date[]" required>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <label for="name" class="control-label">Time</label>
                                                <div>
                                                    <input type="time" id="time" class="form-control"
                                                        name="time[]" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-12 d-flex">
                                                <label for="other" class="control-label">
                                                    ကြားကွင်း
                                                </label>

                                                <div>
                                                    <input type="radio" id="home_other" name="other[{{ $i }}]" value="1"
                                                    class="ml-2">
                                                    <label class="ml-2" for="home_other"> Home </label>

                                                    <input type="radio" id="away_other" name="other[{{ $i }}]" value="2"
                                                    class="ml-2">
                                                    <label class="ml-2" for="away_other"> Away </label>

                                                    <input type="radio" id="no_other" name="other[{{ $i }}]" value="0"
                                                    class="ml-2" checked>
                                                    <label class="ml-2 align-middle" for="no_other"> None </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="home_no" class="control-label"> Home No
                                                </label>
                                                <div>
                                                    <input type="number" id="home_no" class="form-control"
                                                        name="home_no[]">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <label for="away_no" class="control-label"> Away No
                                                </label>
                                                <div>
                                                    <input type="number" id="away_no" class="form-control"
                                                        name="away_no[]">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="home_id" class="control-label"> Home Team
                                                </label>
                                                <div>
                                                    <select class="form-control selectHomeTeam" name="home_id[]"
                                                        id="home_id_{{ $i }}" required style="width: 100%;">
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <label for="away_id" class="control-label"> Away Team
                                                </label>
                                                <div>
                                                    <select class="form-control selectAwayTeam" name="away_id[]"
                                                        id="away_id_{{ $i }}" required style="width: 100%;">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-12 editBet">
                                                <div class="input-group mb-3">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="">Body</span>
                                                        </div>
                                                        <input name="home_body[]" id="home_body" type="text" class="form-control">

                                                        <input name="away_body[]" id="away_body" type="text" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-12 editBet">
                                                <div class="input-group mb-3">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Goals</span>
                                                        </div>
                                                        <input name="goals[]" id="goals" type="text" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <a href="javascript:void(0)" data-group="group_{{ $i }}"
                                                class="deleteMatch"> Delete </a>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <div class="form-group my-3">
                            <a href="javascript:void(0)" class="col-sm-12" id="add">
                                Add More Match
                            </a>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-success" value="create">
                                Add
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
    <script>
        $(document).ready(function() {

            let count = 1;

            $('#home_id').select2();
            $('#away_id').select2();

            $("body").on('change', '.selectLeague', function(e) {

                $(".selectHomeTeam").html('').trigger('change');
                $('.selectAwayTeam').html('').trigger('change');

                if (this.value) {
                    $.get(`/admin/get-clubs/${this.value}`, function(data) {

                        if (data) {
                            data.map(function(d) {
                                var newOption = new Option(d.name, d.id, true, true);
                                $(".selectHomeTeam").append(newOption).trigger('change');
                            });

                            data.map(function(d) {
                                var newOption = new Option(d.name, d.id, true, true);
                                $('.selectAwayTeam').append(newOption)
                                    .trigger('change');
                            });
                        }
                    })
                }
            });

            $("body").on('click', "#add", function(e) {

                $(`#group_${count}`).removeClass('d-none');

                $(`#home_id_${count}`).select2();
                $(`#away_id_${count}`).select2();

                count++;

                if (count == 20) {
                    $("#add").addClass('d-none');
                }
            });

            $("body").on('click', ".deleteMatch", function(e) {
                let group = $(this).data('group');
                $(`#${group}`).remove();
            });

        });
    </script>
@endsection

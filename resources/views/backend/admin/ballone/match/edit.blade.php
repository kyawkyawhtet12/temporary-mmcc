@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0"> Match Edit </h4>

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
                <div class="col">
                    <a class="btn btn-success" href="{{ $status ? '/admin/ballone/maung' : '/admin/ballone/body' }}">
                        Back
                    </a>
                </div>
            </div>

            <div class="row my-3">
                <div class="col-lg-6 offset-lg-3">

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong> Error ! </strong> Please fill all input.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('ballone.match.update', $match->id) }}" method="POST" class="form-horizontal">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="status" value="{{  $status }}">

                        <div class="card">
                            <div class="card-body">
                                <label for="round" class="col-sm-6 control-label"> Round
                                </label>
                                <div class="col-sm-12">
                                    <input type="number" id="round" class="form-control" name="round" value="{{  $match->round }}">
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
                                            <?php $select = $league->id == $match->league_id ? 'selected' : ''; ?>
                                            <option value="{{ $league->id }}" {{ $select }}>
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
                                            <label for="name" class="col-sm-12 control-label">Date</label>
                                            <div class="col-sm-12">
                                                <input type="date" id="date" class="form-control" name="date"
                                                    value="{{ get_date_format($match) }}" required>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <label for="name" class="col-sm-12 control-label">Time</label>
                                            <div class="col-sm-12">
                                                <input type="time" id="time" class="form-control" name="time"
                                                    value={{ get_time_format($match) }} required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-12 d-flex">
                                            <label for="other" class="control-label">
                                                ကြားကွင်း
                                            </label>

                                            <div>
                                                <input type="radio" id="home_other" name="other" value="1"
                                                class="ml-2" {{ $match->other == 1 ? 'checked' : '' }}>
                                                <label class="ml-2 align-middle" for="home_other"> Home </label>

                                                <input type="radio" id="away_other" name="other" value="2"
                                                class="ml-2" {{ $match->other == 2 ? 'checked' : '' }}>
                                                <label class="ml-2 align-middle" for="away_other"> Away </label>

                                                <input type="radio" id="no_other" name="other" value="0"
                                                class="ml-2" {{ $match->other == 0 ? 'checked' : '' }}>
                                                <label class="ml-2 align-middle" for="no_other"> None </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <label for="home_no" class="col-sm-6 control-label"> Home No
                                            </label>
                                            <div class="col-sm-12">
                                                <input type="number" id="home_no" class="form-control" name="home_no"
                                                    value="{{ $match->home_no }}">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <label for="away_no" class="col-sm-6 control-label"> Away No
                                            </label>
                                            <div class="col-sm-12">
                                                <input type="number" id="away_no" class="form-control" name="away_no"
                                                    value="{{ $match->away_no }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <label for="home_id" class="col-sm-6 control-label">
                                                Home Team
                                            </label>
                                            <div class="col-sm-12">
                                                <select class="form-control selectHomeTeam" name="home_id" id="home_id"
                                                    required style="width: 100%;">
                                                    @foreach ($clubs as $club)
                                                        <?php $select = $club->id == $match->home_id ? 'selected' : ''; ?>
                                                        <option value="{{ $club->id }}" {{ $select }}>
                                                            {{ $club->name }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <label for="away_id" class="col-sm-12 control-label">
                                                Away Team
                                            </label>
                                            <div class="col-sm-12">
                                                <select class="form-control selectAwayTeam" name="away_id" id="away_id"
                                                    required style="width: 100%;">
                                                    @foreach ($clubs as $club)
                                                        <?php $select = $club->id == $match->away_id ? 'selected' : ''; ?>
                                                        <option value="{{ $club->id }}" {{ $select }}>
                                                            {{ $club->name }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-success" value="create">
                                Update
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
        $(document).ready(function() {
            $("body").on('change', '.selectLeague', function(e) {

                $(".selectHomeTeam").html('').trigger('change');
                $('.selectAwayTeam').html('').trigger('change');

                if (this.value) {
                    $.get(`/admin/get-clubs/${this.value}`, function(data) {

                        if (data) {
                            data.map(function(d) {
                                // console.log(d)
                                var newOption = new Option(d.name, d.id, true, true);
                                $(".selectHomeTeam").append(newOption).trigger('change');
                            });

                            data.map(function(d) {
                                // console.log(d)
                                var newOption = new Option(d.name, d.id, true, true);
                                $('.selectAwayTeam').append(newOption)
                                    .trigger('change');
                            });
                        }
                    })
                }

            });

        })
    </script>
@endsection

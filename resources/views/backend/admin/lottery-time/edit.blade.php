@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Lottery Time Edit</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Lottery Time Edit</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="card">
                        <div class="card-body">

                          <h4 class="card-title">Lottery Time Edit</h4>
                          
                          <form class="form-sample" method="POST" action="{{ route('lottery-time.update', $time->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                              <label for="time">Time</label>
                              <input class="@error('time') is-invalid @enderror form-control" name="time" type="time" value="{{ $time->time_number }}" autocomplete="time" autofocus required />
                              @error('time')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>

                            <div class="form-group">
                                <label for="type">Type</label>
                                
                                <select name="type" id="type" class="form-control">
                                    <option value="">--Select--</option>
                                    <option value="0" {{ ($time->type) ? '' : 'selected' }}> Thai </option>
                                    <option value="1" {{ ($time->type) ? 'selected' : '' }}> Dubai </option>
                                </select>

                                @error('type')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                @enderror
                              </div>
                            
                            <button type="submit" class="btn btn-primary mr-2">Submit</button>
                            <a href="{{ route('lottery-time.index') }}" class="btn btn-light">Cancel</a>
                          </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

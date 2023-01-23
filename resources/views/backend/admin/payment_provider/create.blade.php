@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Create Payment Provider</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Create Payment Provider</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">

                            <form class="form-sample" method="POST" action="{{ route('providers.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <p class="card-description mt-3">
                                    Payment Provider info
                                </p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Provider Name</label>
                                            <div class="col-sm-9">
                                                <input class="form-control" name="name"type="text"
                                                    value="{{ old('name') }}" required />
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Owner (Name)</label>
                                            <div class="col-sm-9">
                                                <input class="@error('owner') is-invalid @enderror form-control"
                                                    name="owner" minlength="2" type="text" value="{{ old('owner') }}"
                                                    autocomplete="owner" required />
                                                @error('owner')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                                {{-- <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Account Number</label>
                                            <div class="col-sm-9">
                                                <input class="@error('phone_number') is-invalid @enderror form-control"
                                                    name="phone_number" minlength="2" type="text"
                                                    value="{{ old('phone_number') }}" autocomplete="phone_number"
                                                    required />
                                                @error('phone_number')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Image</label>
                                            <div class="col-sm-9">
                                                <input class="@error('image') is-invalid @enderror form-control"
                                                    name="image" minlength="2" type="file" required
                                                    accept="image/*" />
                                                @error('image')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div> --}}
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                <a href="{{ url()->previous() }}" class="btn btn-dark">Back</a>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- container-fluid -->
    </div>
@endsection

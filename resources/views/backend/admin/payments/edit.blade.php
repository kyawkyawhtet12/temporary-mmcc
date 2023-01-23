@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Edit Payment Provider</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Edit Payment Provider</li>
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

                            <form class="form-sample" method="POST" action="{{ route('providers.update', $provider->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <p class="card-description mt-3">
                                    Payment Provider info
                                </p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">
                                                Provider Name
                                            </label>
                                            <div class="col-sm-9">
                                                <input class="form-control" name="name"type="text"
                                                    value="{{ old('name', $provider->name) }}" required
                                                    style="padding:10px" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
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

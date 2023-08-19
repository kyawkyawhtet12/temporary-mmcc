@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0"> Banner Images </h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active"> Banner Images </li>
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

                            <form class="form-sample" method="POST" action="{{ route('banner.update', $agent->id) }}"
                                enctype="multipart/form-data">

                                @csrf
                                @method("PUT")

                                <h5 class="my-4" style="font-size: 20px"> Banner Images For {{ $agent->name }} </h5>

                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <input class="@error('images') is-invalid @enderror form-control"
                                            name="images[]" minlength="2" type="file" required multiple
                                            accept="image/*" />
                                        @error('images')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary mr-2">Add</button>
                                        <a href="{{ route('banner.index') }}" class="btn btn-dark">Back</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            @if( count($agent->banners) )
            <div class="row">
                @foreach( $agent->banners as $banner )
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-end">

                            <form action="{{ route('banner.destroy', $banner->id) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button
                                    onclick="return confirm('Are you sure to delete this?')"
                                    type="submit" class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>

                        </div>

                        <div class="card-body">
                            <img src="{{ $banner->image }}" alt="" srcset="" class="img-fluid">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
        <!-- container-fluid -->
    </div>
@endsection

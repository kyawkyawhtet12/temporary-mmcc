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
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"> Agent Lists </h4>
                            <div class="row">
                                <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered nowrap">
                                    <thead>
                                        <tr class="bg-primary text-white" role="row">
                                            <th>Agent</th>
                                            <th>No of Image</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($agents as $key => $agent)
                                        <tr>
                                            <td>{{  $agent->name }}</td>
                                            <td>{{  $agent->banners_count }}</td>
                                            <td>
                                                <a href="{{ route('banner.edit', $agent->id) }}" class="btn btn-primary btn-sm edit"> Edit </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    </table>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    {{ $agents->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

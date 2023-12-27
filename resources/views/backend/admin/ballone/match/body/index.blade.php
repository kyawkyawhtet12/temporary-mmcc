@extends('layouts.master')

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
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
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

                                @if( $enabled->body_status )
                                <a href="{{ route('ballone.body-fees.enabled') }}" class="btn btn-success">
                                    Close Body Fees
                                </a>
                                @else
                                <a href="{{ route('ballone.body-fees.enabled') }}" class="btn btn-danger">
                                    Open Body Fees
                                 </a>
                                @endif

                            </div>

                            @include('backend.admin.ballone.match.partials.fees_table', [
                                'feesType' => 'body',
                            ])

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

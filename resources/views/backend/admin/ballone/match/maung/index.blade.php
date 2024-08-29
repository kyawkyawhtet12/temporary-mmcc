@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Match Maung Fees</h4>

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

                                <div>
                                    <a href="{{ route('ballone.maung.win.result') }}" class="btn btn-info mr-3">
                                        <i class="fas fa-trophy mr-1" style="font-size:0.8rem"></i>
                                        Win Result
                                    </a>

                                    @if( $enabled->maung_status )
                                    <a href="{{ route('ballone.maung-fees.enabled') }}" class="btn btn-success">
                                        Close Maung Fees
                                    </a>
                                    @else
                                    <a href="{{ route('ballone.maung-fees.enabled') }}" class="btn btn-danger">
                                        Open Maung Fees
                                     </a>
                                    @endif

                                    <a href="{{ route('ballone.maung.fees.add') }}" class="btn btn-primary ml-3">
                                        Add Maung Fees
                                    </a>
                                </div>
                            </div>

                            @include('backend.admin.ballone.match.partials.fees_table', [
                                'feesType' => 'maung',
                            ])

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

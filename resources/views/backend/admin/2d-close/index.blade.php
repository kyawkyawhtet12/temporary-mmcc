@extends('layouts.master')

@section('css')
    <style>
        @media (min-width: 768px) {
            .col-md-1 {
                flex: 0 0 9% !important;
                max-width: 9% !important;
            }
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
                        <h4 class="mb-sm-0">2D Open/Close</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">2D Open/Close</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>

            @include('backend.admin.2d-close.partials._form')

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5> Limit Numbers </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table" id="datatable_id">
                                            <thead>
                                                <tr>
                                                    <th> No. </th>
                                                    <th> Date </th>
                                                    <th> Time </th>
                                                    <th> Number </th>
                                                    <th> Amount </th>
                                                    <th> Actions </th>
                                                </tr>
                                            </thead>
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
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.selectAgent').select2({
                placeholder: {
                    id: 'all',
                    text: '-- All Agents --'
                }
            });

            $('.selectAgent').select2({
                placeholder: {
                    id: 'all',
                    text: '-- All Agents --'
                }
            });

            $('.selectUser').select2({
                placeholder: {
                    id: 'all',
                    text: '-- All Users --'
                }
            });

            $('.selectTime').select2({
                placeholder: {
                    id: 'all',
                    text: '-- All Time --'
                }
            });

            $('.selectNumbers').select2();

        });
    </script>
@endpush

@extends('layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
@endsection

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Payment Provider Lists</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Payment Provider Lists</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('providers.create') }}" class='btn btn-success btn-sm ml-3 my-2'> Create
                                    Payment Account</a>
                            </div>

                            <table id="table_id" class="display">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Provider Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($providers as $key => $payment)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $payment->name }}</td>
                                            <td>
                                                @if ($payment->status)
                                                    <span class="badge badge-success badge-sm text-white"> Active </span>
                                                @else
                                                    <span class="badge badge-warning badge-sm text-white"> Unactive </span>
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-info btn-sm mb-1"
                                                    href="{{ route('providers.edit', $payment->id) }}">Edit</a>

                                                @if ($payment->status)
                                                    <form action="{{ route('providers.destroy', $payment->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="status" value="0">
                                                        <button
                                                            onclick="return confirm('Are you sure want to make unactive this?')"
                                                            type="submit" class="btn btn-danger btn-sm">
                                                            Close
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('providers.destroy', $payment->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="status" value="1">
                                                        <button
                                                            onclick="return confirm('Are you sure want to make active this?')"
                                                            type="submit" class="btn btn-success btn-sm">
                                                            Open
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div><!-- end card -->
                    </div>
                    <!-- end col -->
                </div>

            </div>

        </div>
        <!-- container-fluid -->
    </div>
@endsection

@section('script')
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(function() {
            $('#table_id').DataTable();
        });
    </script>
@endsection

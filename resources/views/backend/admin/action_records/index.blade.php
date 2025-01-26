@extends('layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
@endsection

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Action Records</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Action Records</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <form method="GET" action="{{ route('action-records.index') }}" class="mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" name="user_id" value="{{ request('user_id') }}" class="form-control" placeholder="User ID">
                                    </div>
                                    <div class="col-md-3">
                                        <select name="action" class="form-control">
                                            <option value="">All Actions</option>
                                            <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Create</option>
                                            <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Update</option>
                                            <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Delete</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="table_name" value="{{ request('table_name') }}" class="form-control" placeholder="Table Name">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                        <a href="{{ route('action-records.index') }}" class="btn btn-secondary">Reset</a>
                                    </div>
                                </div>
                            </form>

                            <table id="table_id" class="display">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User ID</th>
                                        <th>Action</th>
                                        <th>Table Name</th>
                                        <th>Record ID</th>
                                        <th>IP Address</th>
                                        <th>User Agent</th>
                                        <th>Data</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($actionRecords as $record)
                                        <tr>
                                            <td>{{ $record->id }}</td>
                                            <td>{{ $record->user_id }}</td>
                                            <td>{{ $record->action }}</td>
                                            <td>{{ $record->table_name }}</td>
                                            <td>{{ $record->record_id }}</td>
                                            <td>{{ $record->ip_address }}</td>
                                            <td>{{ Str::limit($record->user_agent, 30) }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#dataModal{{ $record->id }}">View</button>
                                            </td>
                                            <td>{{ $record->created_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>

                                        <div class="modal fade" id="dataModal{{ $record->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Action Data</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <pre>{{ json_encode(json_decode($record->data), JSON_PRETTY_PRINT) }}</pre>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

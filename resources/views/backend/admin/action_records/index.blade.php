@extends('layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.28.0/themes/prism.min.css">
    <style>
        .modal-body pre {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            font-family: "Courier New", Courier, monospace;
            font-size: 14px;
        }

        .table th,
        .table td {
            vertical-align: middle !important;
            font-size: 14px;
        }

        .table thead {
            background: #212529;
            color: white;
        }

        .badge-update {
            background-color: #ffc107 !important;
        }

        .badge-create {
            background-color: #198754 !important;
        }
    </style>
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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Action Records</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Records Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <table id="action_records_table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Admin</th>
                                        <th>Action</th>
                                        <th>Match</th>
                                        <th>League</th>
                                        <th>IP Address</th>
                                        <th>Changes</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($actionRecords as $record)
                                        <tr>
                                            <td>{{ $record->id }}</td>
                                            <td>{{ $record->admin->name }}</td>
                                            <td>
                                                <span class="badge badge-{{ $record->action }}">
                                                    {{ ucfirst($record->action) }}
                                                </span>
                                            </td>
                                            <td>
                                                ({{ $record->footballMatch->home_no ?? 'N/A' }})
                                                {{ $record->footballMatch->home->name ?? 'N/A' }}
                                                - ({{ $record->footballMatch->away_no ?? 'N/A' }})
                                                {{ $record->footballMatch->away->name ?? 'N/A' }}
                                            </td>
                                            <td>{{ $record->footballMatch->league->name ?? 'N/A' }}</td>
                                            <td>{{ $record->ip_address }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#changesModal{{ $record->id }}">View Changes</button>
                                            </td>
                                            <td>{{ $record->created_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>

                                        <!-- MODAL FOR CHANGES (MOVED INSIDE THE FOREACH LOOP) -->
                                        <div class="modal fade" id="changesModal{{ $record->id }}" tabindex="-1" aria-labelledby="changesModalLabel{{ $record->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title" id="changesModalLabel{{ $record->id }}">Changes for Action #{{ $record->id }}</h5>
                                                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="card border-warning">
                                                                    <div class="card-header bg-warning text-white">
                                                                        <strong>Before</strong>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        @php
                                                                            $changes = json_decode($record->data, true);
                                                                            $beforeData = $changes['before'] ?? [];
                                                                            $afterData = $changes['after'] ?? [];
                                                                            $allKeys = array_unique(array_merge(array_keys($beforeData), array_keys($afterData)));
                                                                        @endphp
                                                                        <ul class="list-group">
                                                                            @foreach ($allKeys as $key)
                                                                                <li class="list-group-item d-flex justify-content-between">
                                                                                    <strong class="text-primary">
                                                                                        {{ ucfirst(str_replace('_', ' ', $key)) }}:
                                                                                    </strong>
                                                                                    <span class="text-muted">
                                                                                        @if (in_array($key, ['date_time', 'updated_at']))
                                                                                            {{ \Carbon\Carbon::parse($beforeData[$key] ?? null)?->format('Y-m-d H:i:s') ?? 'N/A' }}
                                                                                        @elseif ($key === 'home_id')
                                                                                            ({{ $beforeData['home_no'] ?? 'N/A' }}) {{ $record->footballMatch->home->name ?? 'N/A' }}
                                                                                        @elseif ($key === 'away_id')
                                                                                            ({{ $beforeData['away_no'] ?? 'N/A' }}) {{ $record->footballMatch->away->name ?? 'N/A' }}
                                                                                        @elseif ($key === 'league_id')
                                                                                            {{ $record->footballMatch->league->name ?? 'N/A' }}
                                                                                        @else
                                                                                            {{ $beforeData[$key] ?? 'N/A' }}
                                                                                        @endif
                                                                                    </span>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="card border-success">
                                                                    <div class="card-header bg-success text-white">
                                                                        <strong>After</strong>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <ul class="list-group">
                                                                            @foreach ($allKeys as $key)
                                                                                <li class="list-group-item d-flex justify-content-between">
                                                                                    <strong class="text-success">
                                                                                        {{ ucfirst(str_replace('_', ' ', $key)) }}:
                                                                                    </strong>
                                                                                    <span class="text-dark">
                                                                                        @if (in_array($key, ['date_time', 'updated_at']))
                                                                                            {{ \Carbon\Carbon::parse($afterData[$key] ?? null)?->format('Y-m-d H:i:s') ?? 'N/A' }}
                                                                                        @elseif ($key === 'home_id')
                                                                                            ({{ $afterData['home_no'] ?? 'N/A' }}) {{ $record->footballMatch->home->name ?? 'N/A' }}
                                                                                        @elseif ($key === 'away_id')
                                                                                            ({{ $afterData['away_no'] ?? 'N/A' }}) {{ $record->footballMatch->away->name ?? 'N/A' }}
                                                                                        @elseif ($key === 'league_id')
                                                                                            {{ $record->footballMatch->league->name ?? 'N/A' }}
                                                                                        @else
                                                                                            {{ $afterData[$key] ?? 'N/A' }}
                                                                                        @endif
                                                                                    </span>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <thead class="table-dark">
                                                                    <tr>
                                                                        <th style="width: 50%;">Before</th>
                                                                        <th style="width: 50%;">After</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @php
                                                                        $changes = json_decode($record->data, true);
                                                                        $beforeData = $changes['before'] ?? [];
                                                                        $afterData = $changes['after'] ?? [];
                                                                        $allKeys = array_unique(
                                                                            array_merge(
                                                                                array_keys($beforeData),
                                                                                array_keys($afterData),
                                                                            ),
                                                                        );
                                                                    @endphp
                                                                    @foreach ($allKeys as $key)
                                                                        <tr>
                                                                            <td class="{{ isset($beforeData[$key]) && isset($afterData[$key]) && $beforeData[$key] != $afterData[$key] ? 'table-warning' : '' }}">
                                                                                <strong class="text-primary">{{ $key }}</strong>:
                                                                                <span class="text-muted">{{ $beforeData[$key] ?? 'N/A' }}</span>
                                                                            </td>
                                                                            <td class="{{ isset($beforeData[$key]) && isset($afterData[$key]) && $beforeData[$key] != $afterData[$key] ? 'table-success' : '' }}">
                                                                                <strong class="text-success">{{ $key }}</strong>:
                                                                                <span class="text-dark">{{ $afterData[$key] ?? 'N/A' }}</span>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END MODAL -->
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center">
                                {{ $actionRecords->links() }}
                            </div>

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
            $('#action_records_table').DataTable({
                paging: false,
                searching: true,
                responsive: true,
                info: false,
                columnDefs: [{
                        orderable: false,
                        targets: [6]
                    },
                    {
                        type: 'date',
                        targets: [7]
                    }
                ],
                order: [
                    [7, 'desc']
                ]
            });
        });
    </script>
@endsection

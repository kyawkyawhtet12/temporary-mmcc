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
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
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
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="matches" class="table table-bordered nowrap text-center">
                                            <thead>
                                                <tr>
                                                    <th>Round</th>
                                                    <th>Match</th>
                                                    <th>Date Time</th>
                                                    <th>Result</th>
                                                    <th colspan="2">Body</th>
                                                    <th>Goals</th>
                                                    <th>Home</th>
                                                    <th>Away</th>
                                                    <th>Over</th>
                                                    <th>Under</th>
                                                    <th>Add Result</th>
                                                    <th>Edit Fees</th>
                                                    <th>By</th>
                                                    <th>Action</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($data as $dt)
                                                    <tr class="{{ $dt->match_status }}">
                                                        <td>{{ $dt->match->round }}</td>
                                                        <td>
                                                            <a href="{{ route('match.body-report', [$dt->match->id, $dt->fee_id]) }}"
                                                                class="match-detail">

                                                                {{ $dt->match->match_format }}
                                                            </a>
                                                        </td>

                                                        <td>
                                                            {{ get_date_time_format($dt->match) }}
                                                        </td>

                                                        <td>
                                                            {{ $dt->match->calculate_body ? $dt->match->body_temp_score : '' }}
                                                        </td>

                                                        <td>
                                                            @if ($dt->up_team == 1)
                                                                {{ $dt->body }}
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if ($dt->up_team == 2)
                                                                {{ $dt->body }}
                                                            @endif
                                                        </td>

                                                        <td>
                                                            {{ $dt->goals }}
                                                        </td>

                                                        <td>
                                                            {{ $dt->get_result($dt->home) }}
                                                        </td>

                                                        <td>
                                                            {{ $dt->get_result($dt->away) }}
                                                        </td>

                                                        <td>
                                                            {{ $dt->get_result($dt->over) }}
                                                        </td>

                                                        <td>
                                                            {{ $dt->get_result($dt->under) }}
                                                        </td>

                                                        <td>
                                                            @if (!$dt->match->calculate_body && $dt->match->type == 1)
                                                                <a
                                                                    href="/admin/ballone-add-result/body/{{ $dt->match->id }}">
                                                                    <i class="fa fa-plus-square text-inverse m-r-10"></i>
                                                                </a>
                                                            @endif

                                                            @if ($dt->match->type == 0)
                                                                Refund Match
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if (!$dt->match->calculate_body && $dt->match->type == 1)
                                                                <a href="javascript:void(0)" class="editBodyFees mr-2"
                                                                    data-id=" {{ $dt->match->id }}"
                                                                    data-home="{{ $dt->match->home_team }}"
                                                                    data-away="{{ $dt->match->away_team }}"
                                                                    data-up_team= "{{ $dt->match->bodyfees->up_team }}"
                                                                    data-body-fees="{{ $dt->match->bodyfees->body }}"
                                                                    data-goal-fees="{{ $dt->match->bodyfees->goals }}">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            {{ $dt->by_user }}
                                                        </td>

                                                        <td>
                                                            @if ($dt->match->type == 1)
                                                                <a href="{{ route('ballone.match.edit', $dt->match->id) }}"
                                                                    class="text-success">
                                                                    <i class="fa fa-edit text-success m-1"></i>
                                                                </a>

                                                                @if ($dt->match->check_delete())
                                                                    <a href="javascript:void(0)"
                                                                        data-id="{{ $dt->match->id }}" class="deleteMatch">
                                                                        <i class="fa fa-trash text-danger m-1"></i></a>
                                                                @endif

                                                                @if (!$dt->match->score && $dt->match->type == 1)
                                                                    <a href="javascript:void(0)"
                                                                        data-id="{{ $dt->match->id }}" class="cancelMatch">
                                                                        <i class="far fa-times-circle text-danger m-1"></i>
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($dt->match->type == 1)
                                                                @if (!$dt->match->matchStatus?->all_close)
                                                                    <a href="javascript:void(0)"
                                                                        data-id=" {{ $dt->match->id }}" data-type="close"
                                                                        class="closeMatch text-danger">
                                                                        <i class="fa fa-power-off m-2"></i> Close
                                                                    </a>
                                                                @else
                                                                    <a href="javascript:void(0)"
                                                                        data-id=" {{ $dt->match->id }}" data-type="open"
                                                                        class="closeMatch text-info">
                                                                        <i class="fa fa-power-off m-2"></i> Open
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="14" class="text-center"> No Data Available. </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-3">
                                        {{ $data->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('backend.admin.ballone.match.partials.fees_action')

@endsection

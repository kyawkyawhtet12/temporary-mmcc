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

                        <th>Body Limit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $dt)
                        <tr class="{{ $dt->match_status }}">
                            <td>{{ $dt->match->round }}</td>
                            <td>
                                <a href="{{ $feesType == 'body' ? route('match.body-report', [$dt->match->id, $dt->fee_id]) : route('match.maung-report', [$dt->match->id, $dt->fee_id]) }}"
                                    class="match-detail">

                                    {{ $dt->match_format }}
                                </a>
                            </td>

                            <td>
                                {{ get_date_time_format($dt->match) }}
                            </td>

                            <td>
                                {{ $dt->match->get_result($feesType) }}
                            </td>

                            <td>
                                {{ $dt->up_team == 1 ? $dt->body : '' }}
                            </td>

                            <td>
                                {{ $dt->up_team == 2 ? $dt->body : '' }}
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
                                @if ($dt->match->check_action($feesType))
                                    <a href="/admin/ballone-add-result/{{ $feesType }}/{{ $dt->match->id }}">
                                        <i class="fa fa-plus-square text-inverse m-r-10"></i>
                                    </a>
                                @endif

                                @if ($dt->match->check_refund($feesType))
                                    Refund Match
                                @endif
                            </td>

                            <td>
                                @if ($dt->match->check_action($feesType))
                                    <a href="javascript:void(0)" class="editFees mr-2" data-type="{{ $feesType }}"
                                        data-id=" {{ $dt->match->id }}" data-home="{{ $dt->match->home_team }}"
                                        data-away="{{ $dt->match->away_team }}" data-up_team="{{ $dt->up_team }}"
                                        data-body-fees="{{ $dt->body }}" data-goal-fees="{{ $dt->goals }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                @endif
                            </td>

                            <td>
                               <p class="mb-0"> {{ $dt->by_user }}</p>
                               <small>{{ $dt->updated_at->format('d-m-Y g:i A') }} </small>  
                            </td>

                            <td>

                                    <a href="{{ route('ballone.match.edit', $dt->match->id) }}" class="text-success">
                                        <i class="fa fa-edit text-success m-1"></i>
                                    </a>

                                @if ($dt->match->check_action($feesType))
                                    @if ($dt->match->check_delete())
                                        <a href="javascript:void(0)" data-id="{{ $dt->match->id }}"
                                            class="deleteMatch">
                                            <i class="fa fa-trash text-danger m-1"></i></a>
                                    @endif

                                    @if (!$dt->match->check_refund($feesType))
                                        <a href="javascript:void(0)" data-type="{{ $feesType }}"
                                            data-id="{{ $dt->match->id }}" class="cancelMatch">
                                            <i class="far fa-times-circle text-danger m-1"></i>
                                        </a>
                                    @endif
                                @endif
                            </td>

                            <td>
                                @if ($dt->match->check_action($feesType))
                                    <a href="javascript:void(0)" data-id="{{ $dt->match->id }}"
                                        data-status="{{ $dt->match->getStatus() }}" data-type="all"
                                        class="closeMatch text-capitalize {{ $dt->match->getStatusColor() }}">
                                        <i class="fa fa-power-off m-2"></i>
                                        {{ $dt->match->getStatus() }}
                                    </a>
                                @endif
                            </td>

                            <td>
                                {!! $dt->match->body_limit_group !!}
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

@include('backend.admin.ballone.match.partials.fees_action')

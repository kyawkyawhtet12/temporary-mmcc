<div class="row mb-3">
    <div class="col-12">

        <div class="card">

            <div class="card-body">

                <form action="{{ route('2d.disable.store') }}" method="POST">

                    @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <select name="agent_id[]" id="agent_id" multiple="multiple" class="agentSelect form-control">
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}"> {{ $agent->name }} </option>
                                @endforeach
                            </select>
                            @error('agent_id')
                                <span class="mt-2 text-small text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <input type="date" class="form-control p-2" placeholder="Date" name="date"
                                value="{{ date('Y-m-d') }}">
                            @error('date')
                                <span class="mt-2 text-small text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <select name="time_id[]" id="time_id" class="form-control selectTime" multiple>
                                @foreach ($times as $time)
                                    <option value="{{ $time->id }}" selected>
                                        {{ $time->time }}
                                    </option>
                                @endforeach
                            </select>
                            @error('time_id')
                                <span class="mt-2 text-small text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row my-4">
                        <div class="col-md-6">
                            <select name="numbers[]" id="numberSelect" class="form-control" multiple="multiple">
                                @for ($i = 0; $i < 10; $i++)
                                    <optgroup label="{{ $i }}">
                                        @for ($dg = 0; $dg < 10; $dg++)
                                            <option value="{{ "$i$dg" }}">{{ "$i$dg" }}</option>
                                        @endfor
                                    </optgroup>
                                @endfor
                            </select>
                            @error('numbers')
                                <span class="mt-2 text-small text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <input type="number" class="form-control p-2" placeholder="Maximum Limit Amount"
                                name="amount">
                        </div>

                        <div class="col-md-3">
                            <input type="submit" class="form-control btn btn-success btn-sm" name="submit"
                                value="Add">
                        </div>
                    </div>
                </form>

            </div>

        </div>

    </div>
</div>


@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('.selectTime').multiselect({
                placeholder: {
                    id: 'all',
                    text: '-- All Time --'
                }
            });

            $('#numberSelect').multiselect({
                columns: 10,
                placeholder: 'Select Number',
                selectAll: true,
                selectGroup: true
            });

            $('.agentSelect').multiselect({
                columns: 2,
                placeholder: 'Select Agent',
                search: true,
                searchOptions: {
                    'default': 'Search Agents'
                },
                selectAll: true
            });

        });
    </script>
@endpush

<div class="row mb-3">
    <div class="col-12">


        <div class="card">
            <div class="card-header badge-dark text-white">
                Add Limit Numbers
            </div>
            <div class="card-body">

                <form action="{{ route('2d.disable.store') }}" method="POST">

                    @csrf

                    <div class="row">
                        <div class="col-md-3">

                            <select name="agent_id[]" id="agent_id" multiple="multiple"
                                class="3col active form-control">
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}"> {{ $agent->name }} </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <select name="user_id[]" id="userSelect" class="selectUser form-control"
                                multiple="multiple"style="width: 100%">
                                <option value="all">All</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <input type="date" class="form-control p-2" placeholder="Date" name="date"
                                value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="col-md-3">
                            <select name="time_id[]" id="time_id" multiple="multiple"
                                class="3col active form-control">
                                @foreach ($times as $time)
                                    <option value="{{ $time->id }}">
                                        {{ $time->time }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row my-4">
                        <div class="col-md-6">
                            <select name="number_id[]" id="numberSelect" class="3col active form-control"
                                multiple="multiple">

                                @for ($i = 0; $i < 100; $i++)
                                    <option>{{ sprintf('%02d', $i) }}</option>
                                @endfor
                            </select>

                            <select name="" multiple="multiple" class="ms-list-5 jqmsLoaded"
                                style="display: none;">
                                <optgroup label="Group 1">
                                    <option value="1">Option 1</option>
                                    <option value="2">Option 2</option>
                                    <option value="3">Option 3</option>
                                </optgroup>
                                <optgroup label="Group 2">
                                    <option value="4">Option 4</option>
                                    <option value="5">Option 5</option>
                                    <option value="6">Option 6</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <input type="number" class="form-control p-2" placeholder="Limit Amount"
                                name="limit_amount">
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

            $('#numberSelect').multiselect({
                columns: 5,
                placeholder: 'Select Number',
                selectAll: true
            });

            $('#time_id').multiselect({
                // columns: 3,
                placeholder: 'Select Time',
                selectAll: true
            });

            $('#agent_id').multiselect({
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

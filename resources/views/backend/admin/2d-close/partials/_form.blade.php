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
                            <select name="agent_id[]" id="agentSelect" class="selectAgent form-control"
                                multiple="multiple" style="width: 100%">
                                <option value="all">All</option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}">
                                        {{ $agent->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <select name="user_id[]" id="userSelect" class="selectUser form-control"
                                multiple="multiple"style="width: 95%">
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
                            <select name="time_id[]" id="timeSelect" class="selectTime form-control" multiple="multiple"
                                style="width: 95%">
                                <option value="all"> All Time </option>
                                @foreach ($times as $time)
                                    <option value="{{ $time->id }}">
                                        {{ $time->time }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>



                    <div class="row my-4">
                        <div class="col-md-6">
                            <select name="number_id[]" id="timeSelect" class="selectNumbers form-select p-1"
                                multiple="multiple" style="width: 95%">
                                @foreach ($two_digits as $digit)
                                    <option value="{{ $digit->id }}">
                                        {{ $digit->number }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <input type="number" class="form-control p-2" placeholder="Limit Amount"
                                name="limit_amount">
                        </div>

                        <div class="col-md-3">
                            <input type="checkbox" placeholder="Limit Amount" name="disable">
                            <label for="disable"> Close </label>
                        </div>
                    </div>

                    <div class="row my-3">
                        <div class="col-md-2">
                            <input type="submit" class="form-control btn btn-success btn-sm" name="submit"
                                value="Submit">
                        </div>

                        <div class="col-md-2">
                            <a href="#" class="btn btn-outline-danger btn-sm btn-block" id="clearall">
                                Clear </a>
                        </div>
                    </div>
                </form>

            </div>

        </div>

    </div>
</div>

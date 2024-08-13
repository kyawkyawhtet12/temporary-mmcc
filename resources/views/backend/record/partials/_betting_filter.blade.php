<div class="row">
    <div class="col-12">
        <div class="card  bg-light">
            <div class="card-body">

                <div class="row mb-3">

                    <div class="col-md-3 multiSelect">
                        <x-agent-select />
                    </div>

                    <div class="col">
                        <select name="type" id="type" class="form-control">
                            <option value=""> All Type </option>
                            @foreach (get_all_types() as $type)
                                <option value="{{ $type }}">
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 multiSelect">
                       <x-football-round-select />
                    </div>

                    <div class="col">
                        <div class="row">
                            <button type="button" class="btn btn-primary col mx-1 btn-sm" id="search">Filter</button>
                            <a href="#" class="btn btn-danger col mx-1 btn-sm" id="refresh">Refresh</a>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col">
                        <input type="text" class="form-control" placeholder="User ID" name="user_id" id="user_id">
                    </div>

                    <div class="col">
                        <input type="number" class="form-control" placeholder="Min Amount" name="min"
                            id="min_amount">
                    </div>

                    <div class="col">
                        <input type="number" class="form-control" placeholder="Max Amount" name="max"
                            id="max_amount">
                    </div>

                    <div class="col">
                        <input type="date" class="form-control" placeholder="Start Date" name="start_date"
                            id="start_date">
                    </div>

                    <div class="col">
                        <input type="date" class="form-control" placeholder="End Date" name="end_date"
                            id="end_date">
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

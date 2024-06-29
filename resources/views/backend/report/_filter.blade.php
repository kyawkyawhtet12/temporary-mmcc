
@section('css')
    <style>
        input {
            padding: 0.6rem !important;
        }

        .multiSelect button {
            padding: 0.6rem !important;
        }
    </style>
@endsection

<div class="row input-daterange align-items-center">

    <div class="col-md-3 multiSelect" id="{{ count($agents) > 1 ? 'allReport' : 'perReport' }}">
        <select name="agent_id[]" class="agentSelect form-control" id="agent_id" multiple="multiple">
            @foreach ($agents as $agent)
                <option value="{{ $agent->id }}" {{ count($agents) == 1 ? 'selected' : '' }}>
                    {{ $agent->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col">
        <div class="input-group">
            <input type="text" name="start_date" id="start_date" class="form-control"
                placeholder="From Date" readonly />
            <span class="input-group-addon input-group-append border-left">
                <span class="far fa-calendar input-group-text"></span>
            </span>
        </div>
    </div>
    <div class="col">
        <div class="input-group">
            <input type="text" name="end_date" id="end_date" class="form-control"
                placeholder="To Date" readonly />
            <span class="input-group-addon input-group-append border-left">
                <span class="far fa-calendar input-group-text"></span>
            </span>
        </div>
    </div>

    <div class="col-3 row">
        <div class="col-6">
            <button type="submit" class="btn btn-primary btn-block"
                id="filter">Filter</button>
        </div>

        <div class="col-6">
            <button type="button" class="btn btn-success btn-block"
                id="refresh">Refresh</button>
        </div>
    </div>

</div>

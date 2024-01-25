<div class="row mb-3">
    <div class="col-12">

        <div class="card">

            <div class="card-body">

                <form action="{{ route('2d.disable.store') }}" method="POST" id="limitForm">

                    @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <select name="agent_id[]" id="agent_id" multiple="multiple" class="agentSelect form-control">
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}"
                                        {{ in_array($agent->id, old('agent_id') ?? []) ? 'selected' : '' }}
                                        > {{ $agent->name }} </option>
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

                    <div class="row my-2">
                        <div class="col-12 d-flex gap-3" id="selectType">

                            @php $selectedType = old('type') ?? 1 ; @endphp

                            <div>
                                <input type="radio" id="for_numbers" name="type" value="1" {{ $selectedType == 1 ? 'checked' : '' }} >
                                <label class="ml-2 mb-0 fs-6 align-middle" for="for_numbers"> နံပါတ်ဖြင့်ပိတ်ရန်
                                </label>
                            </div>

                            <div>
                                <input type="radio" id="for_front_numbers" name="type" value="2" {{ $selectedType == 2 ? 'checked' : '' }}>
                                <label class="ml-2 mb-0 fs-6 align-middle" for="for_front_numbers"> ထိပ်စီးဖြင့်ပိတ်ရန်
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row {{ $selectedType == 2 ? 'd-none' : '' }}" id="allNumbers">
                        <div class="col-md-9">
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


                    </div>

                    <div class="row {{ $selectedType == 1 ? 'd-none' : '' }}" id="frontNumbers">

                        <div class="col-md-9">
                            <select name="frontNumbers[]" id="frontNumberSelect" class="form-control" multiple="multiple">
                                @for ($i = 0; $i < 10; $i++)
                                    <option value="{{ "$i" }}">{{ "$i" }}</option>
                                @endfor
                            </select>
                            @error('frontNumbers')
                                <span class="mt-2 text-small text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                </form>

            </div>

            <div class="card-footer d-flex justify-content-end">

                <button type="submit" class="btn btn-success" form="limitForm">
                    Add
                </button>

            </div>

        </div>

    </div>
</div>


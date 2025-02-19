<div class="row mb-3">
    <div class="col-12">


        <div class="card">

            <div class="card-body">

                <form action="{{ route('2d.disable.all.store') }}" method="POST">

                    @csrf

                    <div class="row my-4">
                        <div class="col-md-7">
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

                        <div class="col-md-2">
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

            $('#numberSelect').multiselect({
                columns: 10,
                placeholder: 'Select Number',
                selectAll: true,
                selectGroup: true
            });

        });
    </script>
@endpush

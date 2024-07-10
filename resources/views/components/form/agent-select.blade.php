
<select name="agent_id[]" id="agent_id" multiple="multiple" class="agentSelect form-control">
    @foreach ($agents as $id => $name)

        <option value="{{ $id }}"> {{ $name }} </option>

    @endforeach
</select>


@push('scripts')
    <script>
        $(function() {
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

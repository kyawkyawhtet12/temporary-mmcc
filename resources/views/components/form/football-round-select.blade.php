<select name="round[]" id="round" multiple="multiple" class="roundSelect form-control">
    @for ($i = $current_round; $i >= 1; $i--)
        <option value="{{ $i }}">
            {{ $i }}
        </option>
    @endfor
</select>


@push('scripts')
    <script>
        $(function() {

            $('.roundSelect').multiselect({
                columns: 3,
                placeholder: 'Select Round',
                search: true,
                searchOptions: {
                    'default': 'Search Rounds'
                },
                selectAll: true
            });
        });
    </script>
@endpush

@php

    $inputs = ['home', 'away', 'over', 'under' ];

    $route = route('manual.add.result', $result->id);

    $attr = $isCalculationDone ? 'readonly' : 'required' ;

    $isOldValue = Session::get("refresh") || $isCalculationDone ;

    $readonly = !is_admin() ? 'readonly' : '' ;

@endphp

<form action="{{ $isCalculationDone ? "#" : $route }}" class="">

    {{ $slot }}

    <div class="d-flex gap-3 resultGroup">

        @foreach ($inputs as $type)
            <input type='text' name="{{ $type }}" class='form-control'
                value="{{ $isOldValue ? $result?->getResult($type) : 0 }}" {{ $attr }} {{ $readonly }}>
        @endforeach

        @if ( !$isCalculationDone && is_admin() )
            <button type='submit' class='btn btn-primary btn-sm submit' disabled>
                Change
            </button>
        @endif

    </div>

</form>

{{-- Empty option --}}
@isset($emptyOption)

    <option class="d-none">
        {{ is_string($emptyOption) ? $emptyOption : '' }}
    </option>

@endisset

{{-- Other options --}}
@foreach($options as $key => $value)

    <option value="{{ $key }}"
        @if($isSelected($key)) selected @endif
        @if($isDisabled($key)) disabled @endif>
        {{ $value }}
    </option>

@endforeach

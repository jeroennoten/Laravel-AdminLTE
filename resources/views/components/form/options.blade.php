{{-- Empty option --}}
@if(isset($emptyOption))

    <option value>
        {{ is_string($emptyOption) ? $emptyOption : '' }}
    </option>

{{-- Placeholder option --}}
@elseif(isset($placeholder))

    <option class="d-none" value>
        {{ is_string($placeholder) ? $placeholder : '' }}
    </option>

@endif

{{-- Other options --}}
@foreach($options as $key => $value)

    <option value="{{ $key }}"
        @if($isSelected($key)) selected @endif
        @if($isDisabled($key)) disabled @endif>
        {{ $value }}
    </option>

@endforeach

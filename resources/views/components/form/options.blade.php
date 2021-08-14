@foreach($options as $key => $value)

    <option value="{{ $key }}"
        @if($isSelected($key)) selected @endif
        @if($isDisabled($key)) disabled @endif>
        {{ $value }}
    </option>

@endforeach

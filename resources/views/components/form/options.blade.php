@foreach($options as $key => $value)
    <option value="{{ $key }}" @if(in_array($key, $selected, $strict)) selected @endif>{{ $value }}</option>
@endforeach

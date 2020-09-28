<option value="{{$value}}" 
{{($disabled) ? 'disabled' : '' }} 
{{($selected) ? 'selected' : '' }}
@if($icon)
data-content="<i class='{{$content}}'></i> {{$slot}}"
@endif>
    {{$slot}}
</option>
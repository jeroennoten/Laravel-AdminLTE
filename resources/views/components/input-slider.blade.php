<div class="form-group {{$topclass}}">
    <label for="{{$id}}">{{$label}}</label>
    <div class="slider-{{$color}}">
    <input type="text" id="{{$id}}" name="{{$name}}"
    class="slider form-control {{$inputclass}}" 
    data-slider-min="{{$min}}" data-slider-max="{{$max}}"
    data-slider-step="{{$step}}" data-slider-value="{{$value}}" data-slider-orientation="{{$vertical ? 'vertical' : 'horizontal'}}"
    data-slider-selection="before" data-slider-tooltip="show"
    @if($tick)
    data-slider-ticks="{!!$ticks!!}"
    data-slider-ticks-labels='{!!$tickLabels!!}'
    @endif
    {{($required) ? 'required' : '' }} 
    {{($disabled) ? 'disabled' : '' }}>
    </div>
    @error($name)
        <span class="invalid-feedback d-block" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

@section('js')
    @parent
    <script>$(()=>{
        $('#{{$id}}').bootstrapSlider();
    })</script>
@endsection
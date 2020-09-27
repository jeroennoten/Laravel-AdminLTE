<div class="form-group {{$topclass}}">
    <label for="{{$id}}">{{$label}}</label>
    <select class="form-control {{$inputclass}} @error($name) is-invalid @enderror" 
        id="{{$id}}" name="{{$name}}" style="width:100%" 
        {{($required) ? 'required' : '' }} 
        {{($disabled) ? 'disabled' : '' }}
        {{($multiple) ? 'multiple' : '' }}>
        {{$slot}}
    </select>
    @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
<div class="form-group {{$topclass}}">
    <label>{{$label}}</label>
    <textarea class="form-control {{$inputclass}} @error($name) is-invalid @enderror" 
    rows="{{$rows}}" id="{{$id}}" name="{{$name}}" 
    placeholder="{{$placeholder}}" 
    {{($required) ? 'required' : '' }}
    {{($disabled) ? 'disabled' : '' }}
    >{{$slot}}</textarea>
    @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
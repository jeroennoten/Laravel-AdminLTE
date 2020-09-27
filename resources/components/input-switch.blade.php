<div class="form-group {{$topclass}}">
    <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input {{$inputclass}}" 
        id="{{$id}}" name="{{$name}}" 
        {{$checked ? 'checked' : ''}}
        {{$disabled ? 'disabled' : ''}}
        {{$required ? 'required' : ''}}>
        <label class="custom-control-label" for="{{$id}}">{{$label}}</label>
    </div>
</div>
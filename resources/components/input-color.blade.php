<div class="form-group {{$topclass}}">
    <label for="{{$id}}">{{$label}}</label>
    <div class="input-group" id="{{$id}}-picker">
        <input type="text" class="{{$inputclass}} form-control @error($name) is-invalid @enderror" 
        name="{{$name}}" id="{{$id}}" value="{{$value}}" placeholder="{{$placeholder}}"
        {{($required) ? 'required' : '' }}
        {{($disabled) ? 'disabled' : '' }}>

        <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-square"></i></span>
        </div>
    </div>
    @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

@section('js')
    @parent
    <script>
        $('#{{$id}}-picker').colorpicker();
        $('#{{$id}}-picker .fa-square').css('color', $('#{{$id}}').val());
        $('#{{$id}}-picker').on('colorpickerChange', function(event) {
            $('#{{$id}}-picker .fa-square').css('color', event.color.toString());
        });
    </script>
@endsection
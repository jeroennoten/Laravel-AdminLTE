<div class="form-group {{$topclass}}">
    <label for="{{$id}}">{{$label}}</label>
    <input type="text" class="form-control {{$inputclass}}" 
    data-role="tagsinput" id="{{$id}}" name="{{$name}}"
    {{($required) ? 'required' : '' }} 
    {{($disabled) ? 'disabled' : '' }} />

    @error($name)
        <span class="invalid-feedback d-block" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

@section('js')
    @parent
    <script>
        $('#{{$id}}').tagsinput({
            trimValue: true,
            maxTags: {{$max}},
        });
    </script>
@endsection

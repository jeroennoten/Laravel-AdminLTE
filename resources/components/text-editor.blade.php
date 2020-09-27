<div class="form-group {{$topclass}}">
    <label for="{{$id}}">{{$label}}</label>
    <textarea class="{{$inputclass}}" name="{{$name}}" id="{{$id}}"
    {{($required) ? 'required' : '' }}
    {{($disabled) ? 'disabled' : '' }}
    ></textarea>
</div>

@section('js')
@parent
<script>
    $(function(){
        $('#{{$id}}').summernote({
            placeholder: '{{$placeholder}}',
            height: {{$height}},
			dialogsInBody: true,
			dialogsFade: false,
            fontNames: {!!$fontarray!!}
        });
        @if(!is_null($body))
        $('#{{$id}}').summernote('code',`{!!$body!!}`);
        @endif
    })
</script>
@endsection
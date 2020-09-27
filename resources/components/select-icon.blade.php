<div class="form-group {{$topclass}}">
    <label for="{{$id}}">{{$label}}</label>
    <select class="show-tick form-control {{$inputclass}} @error($name) is-invalid @enderror" 
        id="{{$id}}" name="{{$name}}" 
        {{($required) ? 'required' : '' }} 
        {{($multiple) ? 'multiple data-actions-box="true"' : '' }} 
        data-live-search="true">
        {{$slot}}
    </select>
    @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

@section('js')
    @parent
    <script>
        $(()=>{ $('#{{$id}}').selectpicker(); })
    </script>
@endsection
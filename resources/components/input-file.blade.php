<div class="form-group {{$topclass}}">
    <label for="{{$id}}">{{$label}}</label>
    <div class="input-group">
        <div class="custom-file">
            <input type="file" 
            class="{{$inputclass}} custom-file-input @error($name) is-invalid @enderror" 
            id="{{$id}}" 
            name="{{$name}}" 
            {{($required) ? 'required' : '' }} 
            {{($multiple) ? 'multiple' : '' }}
            {{($disabled) ? 'disabled' : '' }}>
            <label class="custom-file-label" for="{{$id}}">{{$placeholder}}</label>
        </div>
        <div class="input-group-append">
            <span class="input-group-text" id="">Upload</span>
        </div>
    </div>
    @error($name)
        <span class="invalid-feedback d-block" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

@section('js')
    @parent
    <script>$(()=>{bsCustomFileInput.init();})</script>
@endsection
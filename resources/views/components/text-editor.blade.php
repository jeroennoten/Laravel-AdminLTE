@extends('adminlte::components.input-group-component')

@section('input_group_item')

    {{-- Summernote Textarea --}}
    <textarea id="{{ $name }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => $makeItemClass($errors->first($name))]) }}
    >{{ $slot }}</textarea>

@overwrite

{{-- Add plugin initialization and configuration code --}}

@push('js')
<script>

    $(() => {
        let usrCfg = @json($config);

        // Check for placeholder attribute.

        @isset($attributes['placeholder'])
            usrCfg['placeholder'] = "{{ $attributes['placeholder'] }}";
        @endisset

        // Initialize the plugin.

        $('#{{ $name }}').summernote(usrCfg);

        // Check for disabled attribute.

        @isset($attributes['disabled'])
            $('#{{ $name }}').summernote('disable');
        @endisset
    })

</script>
@endpush

{{-- Setup the font size of the plugin when using sm/lg sizes --}}
{{-- NOTE: this may change with newer plugin versions --}}

@once
@push('css')
<style type="text/css">

    {{-- SM size setup --}}
    .input-group-sm .note-editor {
        font-size: .875rem;
        line-height: 1;
    }

    {{-- LG size setup --}}
    .input-group-lg .note-editor {
        font-size: 1.25rem;
        line-height: 1.5;
    }

</style>
@endpush
@endonce

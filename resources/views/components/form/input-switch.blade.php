@extends('adminlte::components.form.input-group-component')

@section('input_group_item')

    {{-- Input Switch --}}
    <input type="checkbox" id="{{ $id }}" name="{{ $name }}" value="true"
        {{ $attributes->merge(['class' => $makeItemClass($errors->first($errorKey))]) }}>

@overwrite

{{-- Add plugin initialization and configuration code --}}

@push('js')
<script>

    $(() => {
        $('#{{ $id }}').bootstrapSwitch( @json($config) );
    })

</script>
@endpush

{{-- Setup the height/font of the plugin when using sm/lg sizes --}}
{{-- NOTE: this may change with newer plugin versions --}}

@once
@push('css')
<style type="text/css">

    {{-- MD (default) size setup --}}
    .input-group .bootstrap-switch-handle-on,
    .input-group .bootstrap-switch-handle-off {
        height: 2.25rem !important;
    }

    {{-- LG size setup --}}
    .input-group-lg .bootstrap-switch-handle-on,
    .input-group-lg .bootstrap-switch-handle-off {
        height: 2.875rem !important;
        font-size: 1.25rem !important;
    }

    {{-- SM size setup --}}
    .input-group-sm .bootstrap-switch-handle-on,
    .input-group-sm .bootstrap-switch-handle-off {
        height: 1.8125rem !important;
        font-size: .875rem !important;
    }

    {{-- Custom invalid style setup --}}

    .adminlte-invalid-iswgroup > .bootstrap-switch-wrapper,
    .adminlte-invalid-iswgroup > .input-group-prepend > *,
    .adminlte-invalid-iswgroup > .input-group-append > * {
        box-shadow: 0 .25rem 0.5rem rgba(255,0,0,.25);
    }

</style>
@endpush
@endonce

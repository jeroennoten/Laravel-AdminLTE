@extends('adminlte::components.form.input-group-component')

{{-- Set errors bag internallly --}}

@php($setErrorsBag($errors ?? null))

{{-- Set input group item section --}}

@section('input_group_item')

    {{-- Select --}}
    <select id="{{ $id }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => $makeItemClass()]) }}>
        {{ $slot }}
    </select>

@overwrite

{{-- Add plugin initialization and configuration code --}}

@push('js')
<script>

    $(() => {
        $('#{{ $id }}').selectpicker( @json($config) );

        // Add support to auto select old submitted values in case of
        // validation errors.

        @if($errors->any() && $enableOldSupport)
            let oldOptions = @json(collect($getOldValue($errorKey)));
            $('#{{ $id }}').selectpicker('val', oldOptions);
        @endif
    })

</script>
@endpush

{{-- Set of CSS workarounds for the plugin --}}
{{-- NOTE: this may change with newer plugin versions --}}

@once
@push('css')
<style type="text/css">

    {{-- Fix the invalid visual style --}}
    .bootstrap-select.is-invalid {
        padding-right: 0px !important;
    }

</style>
@endpush
@endonce

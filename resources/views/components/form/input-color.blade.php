@extends('adminlte::components.form.input-group-component')

{{-- Set errors bag internallly --}}

@php($setErrorsBag($errors ?? null))

{{-- Set input group item section --}}

@section('input_group_item')

    {{-- Input Color --}}
    <input id="{{ $id }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => $makeItemClass()]) }}>

@overwrite

{{-- Add plugin initialization and configuration code --}}

@push('js')
<script>

    $(() => {

        // Create a method to set the addon color.

        let setAddonColor = function()
        {
            let color = $('#{{ $id }}').data('colorpicker').getValue();

            $('#{{ $id }}').closest('.input-group')
                .find('.input-group-text > i')
                .css('color', color);
        }

        // Init the plugin and register the change event listener.

        $('#{{ $id }}').colorpicker( @json($config) )
            .on('change', setAddonColor);

        // Add support to auto select the previous submitted value in case
        // of validation errors.

        @if($errors->any() && $enableOldSupport)
            let oldColor = @json($getOldValue($errorKey, ""));
            $('#{{ $id }}').val(oldColor).change();
        @endif

        // Set the initial color for the addon.

        setAddonColor();
    })

</script>
@endpush

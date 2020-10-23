@extends('adminlte::components.input-group-component')

@section('input_group_item')

    {{-- Input Color --}}
    <input id="{{ $name }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => $makeItemClass($errors->first($name))]) }}>

@overwrite

{{-- Add plugin initialization and configuration code --}}

@push('js')
<script>

    $(() => {

        // Create a method to set the addon color.

        let setAddonColor = function()
        {
            let color = $('#{{ $name }}').data('colorpicker').getValue();

            $('#{{ $name }}').closest('.input-group')
                .find('.input-group-text > i')
                .css('color', color);
        }

        // Init the plugin and register the change event listener.

        $('#{{ $name }}').colorpicker( @json($config) )
            .on('change', setAddonColor);

        // Set the initial color for the addon.

        setAddonColor();
    })

</script>
@endpush

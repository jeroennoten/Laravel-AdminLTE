@extends('adminlte::components.form.input-group-component')

@section('input_group_item')

    {{-- Input Slider --}}
    <input id="{{ $id }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => $makeItemClass($errors->first($errorKey))]) }}>

@overwrite

{{-- Add plugin initialization and configuration code --}}

@push('js')
<script>

    $(() => {
        let usrCfg = @json($config);

        // Check for disabled attribute (alternative to data-slider-enable).

        @isset($attributes['disabled'])
            usrCfg.enabled = false;
        @endisset

        // Check for min, max and step attributes (alternatives to
        // data-slider-min, data-slider-max and data-slider-step).

        @isset($attributes['min'])
            usrCfg.min = {{ $attributes['min'] }};
        @endisset

        @isset($attributes['max'])
            usrCfg.max = {{ $attributes['max'] }};
        @endisset

        @isset($attributes['step'])
            usrCfg.step = {{ $attributes['step'] }};
        @endisset

        // Check for value attribute (alternative to data-slider-value).

        @isset($attributes['value'])
            usrCfg.value = {{ $attributes['value'] }};
        @endisset

        // Initialize the plugin.

        let slider = $('#{{ $id }}').bootstrapSlider(usrCfg);

        // Fix height conflict when orientation is vertical.

        let or = slider.bootstrapSlider('getAttribute', 'orientation');

        if (or == 'vertical') {
            $('#' + usrCfg.id).css('height', '210px');
            slider.bootstrapSlider('relayout');
        }
    })

</script>
@endpush

{{-- Add CSS workarounds for the plugin --}}
{{-- NOTE: this may change with newer plugin versions --}}

@push('css')
<style type="text/css">

    {{-- Setup plugin color --}}

    @isset($color)

        #{{ $config['id'] }} .slider-handle {
            background: {{ $color }};
        }
        #{{ $config['id'] }} .slider-selection {
            background: {{ $color }};
            opacity: 0.5;
        }
        #{{ $config['id'] }} .slider-tick.in-selection {
            background: {{ $color }};
            opacity: 0.9;
        }

    @endisset

    {{-- Set flex property when using addons slots --}}

    @if(isset($appendSlot) || isset($prependSlot))

        #{{ $config['id'] }} {
            flex: 1 1 0;
            align-self: center;
            @isset($appendSlot) margin-right: 5px; @endisset
            @isset($prependSlot) margin-left: 5px; @endisset
        }

    @endif

</style>
@endpush

{{-- Setup custom invalid style  --}}
{{-- NOTE: this may change with newer plugin versions --}}

@once
@push('css')
<style type="text/css">

    .adminlte-invalid-islgroup .slider-track,
    .adminlte-invalid-islgroup > .input-group-prepend > *,
    .adminlte-invalid-islgroup > .input-group-append > * {
        box-shadow: 0 .25rem 0.5rem rgba(255,0,0,.25);
    }

    .adminlte-invalid-islgroup .slider-vertical {
        margin-bottom: 1rem;
    }

</style>
@endpush
@endonce

@extends('adminlte::components.form.input-group-component')

{{-- Set errors bag internallly --}}

@php($setErrorsBag($errors ?? null))

{{-- Set input group item section --}}

@section('input_group_item')

    {{-- Input Slider. The 'noUiSlider' plugin renders into a plain DOM
         element, so the submitted value is held by a hidden input. --}}
    <input type="hidden" id="{{ $id }}" name="{{ $name }}"
        value="{{ implode(',', $makeStartValue()) }}"
        {{ $attributes->except(['value', 'class', 'type', 'min', 'max', 'step', 'disabled']) }}>

    <div id="{{ $config['id'] }}" class="{{ $makeSliderClass() }}" {{ $makeSliderAttributes() }}></div>

@overwrite

{{-- Add plugin initialization and configuration code --}}

@push('js')
<script>

    window._AdminLTE_Ready(function () {

        const input = document.getElementById(@json($id));
        const target = document.getElementById(@json($config['id']));

        if (! input || ! target || typeof window.noUiSlider === 'undefined') {
            return;
        }

        const usrCfg = @json((object) $makePluginConfig());

        {{-- Check for the min, max and step attributes (alternatives to the
             related plugin configuration properties). --}}

        @if($attributes->has('min'))
            usrCfg.range = usrCfg.range || {};
            usrCfg.range.min = Number( @json($attributes->get('min')) );
        @endif

        @if($attributes->has('max'))
            usrCfg.range = usrCfg.range || {};
            usrCfg.range.max = Number( @json($attributes->get('max')) );
        @endif

        @if($attributes->has('step'))
            usrCfg.step = Number( @json($attributes->get('step')) );
        @endif

        {{-- Check for the value attribute (alternative to the plugin 'start'
             property). Note the old submitted value has precedence and is
             already resolved on the php side. --}}

        @if($attributes->has('value') && ! ($errors->any() && $enableOldSupport))

            const attrValue = String( @json($attributes->get('value')) )
                .split(',')
                .map(Number)
                .filter(function (n) { return ! isNaN(n); });

            if (attrValue.length > 0) {
                usrCfg.start = attrValue;
            }

        @endif

        {{-- Keep the connect option consistent with the number of handles. --}}

        if (Array.isArray(usrCfg.start) && usrCfg.start.length > 1
            && ! Array.isArray(usrCfg.connect)) {
            usrCfg.connect = [false, true, false];
        }

        {{-- Initialize the plugin. --}}

        window.noUiSlider.create(target, usrCfg);

        {{-- Keep the hidden input in sync with the slider. --}}

        target.noUiSlider.on('update', function (values) {
            input.value = values.join(',');
            input.dispatchEvent(new Event('change', { bubbles: true }));
        });

        {{-- Check for the disabled attribute (alternative to the legacy
             'enabled' plugin property). --}}

        @if($attributes->has('disabled') || (isset($config['enabled']) && ! $config['enabled']))
            target.setAttribute('disabled', true);
        @endif
    });

</script>
@endpush

{{-- Add the style customizations for this particular slider --}}

@push('css')
<style type="text/css">

    {{-- Setup plugin color --}}

    @isset($color)

        #{{ $config['id'] }} .noUi-connect {
            background: {{ $color }};
        }
        #{{ $config['id'] }} .noUi-handle {
            border-color: {{ $color }};
        }

    @endisset

    {{-- Add some spacing when using the addons slots --}}

    @if(isset($appendSlot) || isset($prependSlot))

        #{{ $config['id'] }} {
            @isset($appendSlot) margin-inline-end: .5rem; @endisset
            @isset($prependSlot) margin-inline-start: .5rem; @endisset
        }

    @endif

</style>
@endpush

{{-- Setup the base and the custom invalid style for the slider --}}

@once
@push('css')
<style type="text/css">

    .adminlte-slider {
        margin: .75rem .5rem;
    }

    .adminlte-slider.adminlte-slider-vertical {
        height: 210px;
        flex: 0 0 auto;
        margin: .5rem auto 1.5rem auto;
    }

    .adminlte-slider[disabled] {
        opacity: .5;
    }

    .adminlte-invalid-islgroup .noUi-target,
    .adminlte-invalid-islgroup > .input-group-text,
    .adminlte-invalid-islgroup > .btn {
        box-shadow: 0 .25rem 0.5rem rgba(var(--bs-danger-rgb, 220, 53, 69), .25);
    }

</style>
@endpush
@endonce

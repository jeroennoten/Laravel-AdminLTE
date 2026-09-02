@extends('adminlte::components.form.input-group-component')

{{-- Set errors bag internallly --}}

@php($setErrorsBag($errors ?? null))

{{-- Set input group item section --}}

@section('input_group_item')

    {{-- Input Date --}}
    <input id="{{ $id }}" name="{{ $name }}"
        value="{{ $getOldValue($errorKey, $attributes->get('value')) }}"
        {{ $attributes->except('value')->merge($makeItemAttributes()) }}>

@overwrite

{{-- Add plugin initialization and configuration code --}}

@push('js')
<script>

    window._AdminLTE_Ready(function () {

        const el = document.getElementById(@json($id));

        if (! el || typeof window.flatpickr === 'undefined') {
            return;
        }

        const usrCfg = _AdminLTE_InputDate.parseCfg( @json((object) $makePluginConfig()) );

        el._flatpickr_instance = window.flatpickr(el, usrCfg);
    });

</script>
@endpush

{{-- Register Javascript utility class for this component --}}

@once
@push('js')
<script>

    class _AdminLTE_InputDate {

        /**
         * Parse the php plugin configuration and eval the javascript code.
         *
         * cfg: An object with the php side configuration.
         */
        static parseCfg(cfg)
        {
            for (const prop in cfg) {
                let v = cfg[prop];

                if (typeof v === 'string' && v.startsWith('js:')) {
                    cfg[prop] = eval(v.slice(3));
                } else if (v !== null && typeof v === 'object') {
                    cfg[prop] = _AdminLTE_InputDate.parseCfg(v);
                }
            }

            return cfg;
        }
    }

</script>
@endpush
@endonce

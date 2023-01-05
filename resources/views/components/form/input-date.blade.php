@extends('adminlte::components.form.input-group-component')

{{-- Set errors bag internallly --}}

@php($setErrorsBag($errors ?? null))

{{-- Set input group item section --}}

@section('input_group_item')

    {{-- Input Date --}}
    <input id="{{ $id }}" name="{{ $name }}" data-target="#{{ $id }}" data-toggle="datetimepicker"
        {{ $attributes->merge(['class' => $makeItemClass()]) }}>

@overwrite

{{-- Add plugin initialization and configuration code --}}

@push('js')
<script>

    $(() => {
        let usrCfg = _AdminLTE_InputDate.parseCfg( @json($config) );
        $('#{{ $id }}').datetimepicker(usrCfg);

        // Add support to auto display the old submitted value or values in case
        // of validation errors.

        let value = @json($getOldValue($errorKey, $attributes->get('value')));
        $('#{{ $id }}').val(value || "");
    })

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
         * cfg: A json with the php side configuration.
         */
        static parseCfg(cfg)
        {
            for (const prop in cfg) {
                let v = cfg[prop];

                if (typeof v === 'string' && v.startsWith('js:')) {
                    cfg[prop] = eval(v.slice(3));
                } else if (typeof v === 'object') {
                    cfg[prop] = _AdminLTE_InputDate.parseCfg(v);
                }
            }

            return cfg;
        }
    }

</script>
@endpush
@endonce

@extends('adminlte::components.input-group-component')

@section('input_group_item')

    {{-- Input Date --}}
    <input id="{{ $name }}" name="{{ $name }}" data-target="#{{ $name }}" data-toggle="datetimepicker"
        {{ $attributes->merge(['class' => $makeItemClass($errors->first($name))]) }}>

@overwrite

{{-- Add plugin initialization and configuration code --}}

@push('js')
<script>

    $(() => {
        let usrCfg = _adminlte_idUtils.parseCfg( @json($config) );
        $('#{{ $name }}').datetimepicker(usrCfg);
    })

</script>
@endpush

{{-- Add utility methods for the plugin --}}

@once
@push('js')
<script>

    function ID_Utils() {

        /**
         * Parse the php plugin configuration to eval javascript code.
         */
        this.parseCfg = function(cfg)
        {
            for (const prop in cfg) {
                let v = cfg[prop];

                if (typeof v === 'string' && v.startsWith('js:')) {
                    cfg[prop] = eval(v.slice(3));
                } else if (typeof v === 'object') {
                    cfg[prop] = this.parseCfg(v);
                }
            }

            return cfg;
        }
    }

    // Create the plugin utilities object.

    var _adminlte_idUtils = new ID_Utils();

</script>
@endpush
@endonce

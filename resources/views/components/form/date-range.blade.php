@extends('adminlte::components.form.input-group-component')

@section('input_group_item')

    {{-- Date Range Input --}}
    <input id="{{ $id }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => $makeItemClass()]) }}>

@overwrite

{{-- Add plugin initialization and configuration code --}}

@push('js')
<script>

    $(() => {
        let usrCfg = _AdminLTE_DateRange.parseCfg( @json($config) );

        // Check if default set of ranges should be enabled.

        @isset($enableDefaultRanges)
            usrCfg.ranges = usrCfg.ranges || _AdminLTE_DateRange.defaultRanges;
            let range = usrCfg.ranges['{{ $enableDefaultRanges }}'];

            if (Array.isArray(range)) {
                usrCfg.startDate = range[0];
                usrCfg.endDate = range[1];
            }
        @endisset

        $('#{{ $id }}').daterangepicker(usrCfg);
    })

</script>
@endpush

{{-- Register Javascript utility class for this component --}}

@once
@push('js')
<script>

    class _AdminLTE_DateRange {

        /**
         * A default set of ranges options.
         */
        static defaultRanges = {
            'Today': [
                moment(),
                moment()
            ],
            'Yesterday': [
                moment().subtract(1, 'days'),
                moment().subtract(1, 'days')
            ],
            'Last 7 Days': [
                moment().subtract(6, 'days'),
                moment()
            ],
            'Last 30 Days': [
                moment().subtract(29, 'days'),
                moment()
            ],
            'This Month': [
                moment().startOf('month'),
                moment().endOf('month')
            ],
            'Last Month': [
                moment().subtract(1, 'month').startOf('month'),
                moment().subtract(1, 'month').endOf('month')
            ]
        }

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
                    cfg[prop] = _AdminLTE_DateRange.parseCfg(v);
                }
            }

            return cfg;
        }
    }

</script>
@endpush
@endonce

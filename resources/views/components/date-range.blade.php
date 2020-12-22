@extends('adminlte::components.input-group-component')

@section('input_group_item')

    {{-- Date Range Input --}}
    <input id="{{ $name }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => $makeItemClass($errors->first($name))]) }}>

@overwrite

{{-- Add plugin initialization and configuration code --}}

@push('js')
<script>

    $(() => {
        let usrCfg = _adminlte_drUtils.parseCfg( @json($config) );

        // Check if default set of ranges should be enabled.

        @isset($enableDefaultRanges)
            usrCfg.ranges = usrCfg.ranges || _adminlte_drUtils.defRanges();
            let range = usrCfg.ranges['{{ $enableDefaultRanges }}'];

            if (Array.isArray(range)) {
                usrCfg.startDate = range[0];
                usrCfg.endDate = range[1];
            }
        @endisset

        $('#{{ $name }}').daterangepicker(usrCfg);
    })

</script>
@endpush

{{-- Add utility methods for the plugin --}}

@once
@push('js')
<script>

    function DR_Utils() {

        /**
         * Parse the php plugin configuration to eval javascript code.
         */
        this.parseCfg = function(cfg)
        {
            for (const prop in cfg) {
                let v = cfg[prop];

                if (typeof v === 'string' && v.startsWith('js:')) {
                    cfg[prop] = eval(v.slice(3));
                }
                else if (typeof v === 'object') {
                    cfg[prop] = this.parseCfg(v);
                }
            }

            return cfg;
        }

        /**
         * Creates a default set of ranges option for the plugin.
         */
        this.defRanges = function()
        {
            return {
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
            };
        }
    }

    // Create the plugin utilities object.

    var _adminlte_drUtils = new DR_Utils();

</script>
@endpush
@endonce

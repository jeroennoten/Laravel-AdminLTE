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

        // Add support to display a placeholder. In this situation, the related
        // input won't be updated automatically and the cancel button will be
        // used to clear the input.

        @if($attributes->has('placeholder'))

            usrCfg.autoUpdateInput = false;

            $('#{{ $id }}').on('apply.daterangepicker', function(ev, picker)
            {
                let startDate = picker.startDate.format(picker.locale.format);
                let endDate = picker.endDate.format(picker.locale.format);

                let value = picker.singleDatePicker
                    ? startDate
                    : startDate + picker.locale.separator + endDate;

                $(this).val(value);
            });

            $('#{{ $id }}').on('cancel.daterangepicker', function(ev, picker)
            {
                $(this).val('');
            });

        @endif

        // Check if the default set of ranges should be enabled, and if a
        // default range should be set at initialization.

        @isset($enableDefaultRanges)

            usrCfg.ranges = usrCfg.ranges || _AdminLTE_DateRange.defaultRanges;
            let range = usrCfg.ranges[ @json($enableDefaultRanges) ];

            if (Array.isArray(range) && range.length > 1) {
                usrCfg.startDate = range[0];
                usrCfg.endDate = range[1];
            }

        @endisset

        // Add support to auto select the previous submitted value in case
        // of validation errors. Note the previous value may be a date range or
        // a single date depending on the plugin configuration.

        @if($errors->any() && $enableOldSupport)

            let oldRange = @json($getOldValue($errorKey, ""));
            let separator = " - ";

            if (usrCfg.locale && usrCfg.locale.separator) {
                separator = usrCfg.locale.separator;
            }

            // Update the related input.

            if (! usrCfg.autoUpdateInput) {
                $('#{{ $id }}').val(oldRange);
            }

            // Update the internal plugin data.

            if (oldRange) {
                oldRange = oldRange.split(separator);
                usrCfg.startDate = oldRange.length > 0 ? oldRange[0] : null;
                usrCfg.endDate = oldRange.length > 1 ? oldRange[1] : null;
            }

        @endif

        // Setup the underlying date range plugin.

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
                moment().startOf('day'),
                moment().endOf('day')
            ],
            'Yesterday': [
                moment().subtract(1, 'days').startOf('day'),
                moment().subtract(1, 'days').endOf('day')
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

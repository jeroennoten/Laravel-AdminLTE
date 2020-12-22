<div {{ $attributes->merge(['class' => $makeProgressClass()]) }}>

    {{-- Progress bar --}}
    <div class="{{ $makeProgressBarClass() }}" role="progressbar"
        aria-valuenow="{{ $value }}" aria-valuemin="0" aria-valuemax="100"
        style="{{ $makeProgressBarStyle() }}">

        {{-- Progress bar label --}}
        @isset($withLabel)
            {{ $value }}%
        @else
            <span class="sr-only">{{ $value }}% Progress</span>
        @endisset

    </div>

</div>

{{-- Add JS utility methods for this component --}}

@once
@push('js')
<script>

    function PB_Utils() {

        /**
         * Gets the target progress bar current value.
         */
        this.getValue = function(target)
        {
            // Check if target exists.

            let t = $('#' + target);

            if (t.length <= 0) {
                return;
            }

            // Return the progress bar current value (casted to number).

            return +(t.find('.progress-bar').attr('aria-valuenow'));
        }

        /**
         * Update the target progress bar with a new value.
         */
        this.setValue = function(target, value)
        {
            // Check if target exists.

            let t = $('#' + target);

            if (t.length <= 0) {
                return;
            }

            // Update progress bar.

            value = +value;

            t.find('.progress-bar').css('width', value + '%')
                .attr('aria-valuenow', value);

            if (t.find('span.sr-only').length >= 0) {
                t.find('span.sr-only').text(value + '% Progress');
            } else {
                t.find('.progress-bar').text(value + '%');
            }
        }
    }

    // Create the plugin utilities object.

    var _adminlte_pbUtils = new PB_Utils();

</script>
@endpush
@endonce

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

{{-- Register Javascript utility class for this component --}}

@once
@push('js')
<script>

    class _AdminLTE_Progress {

        /**
         * Constructor.
         *
         * target: The id of the target progress bar.
         */
        constructor(target)
        {
            this.target = target;
        }

        /**
         * Get the current progress bar value.
         */
        getValue()
        {
            // Check if target exists.

            let t = $(`#${this.target}`);

            if (t.length <= 0) {
                return;
            }

            // Return the progress bar current value (casted to number).

            return +(t.find('.progress-bar').attr('aria-valuenow'));
        }

        /**
         * Set the new progress bar value.
         */
        setValue(value)
        {
            // Check if target exists.

            let t = $(`#${this.target}`);

            if (t.length <= 0) {
                return;
            }

            // Update progress bar.

            value = +value;

            t.find('.progress-bar').css('width', value + '%')
                .attr('aria-valuenow', value);

            if (t.find('span.sr-only').length > 0) {
                t.find('span.sr-only').text(value + '% Progress');
            } else {
                t.find('.progress-bar').text(value + '%');
            }
        }
    }

</script>
@endpush
@endonce

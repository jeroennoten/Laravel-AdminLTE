{{-- Bootstrap v5.3 markup: the aria attributes live on the .progress wrapper --}}
<div {{ $attributes->merge([
    'class' => $makeProgressClass(),
    'role' => 'progressbar',
    'aria-valuenow' => $value,
    'aria-valuemin' => 0,
    'aria-valuemax' => 100,
    'aria-label' => 'Progress',
]) }}>

    {{-- Progress bar --}}
    <div class="{{ $makeProgressBarClass() }}" style="{{ $makeProgressBarStyle() }}"@if(isset($withLabel) && ! isset($labelSlot)) data-progress-label="auto"@endif>

        {{-- Progress bar label --}}
        @isset($labelSlot){{ $labelSlot }}@elseif(isset($withLabel)){{ $value }}%@endisset

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
         * target: The id of the target progress element.
         */
        constructor(target)
        {
            this.target = target;
        }

        /**
         * Get the underlying .progress element.
         */
        getProgress()
        {
            const t = document.getElementById(this.target);

            if (! t) {
                return null;
            }

            return t.classList.contains('progress')
                ? t
                : t.querySelector('.progress');
        }

        /**
         * Get the current progress value.
         */
        getValue()
        {
            const p = this.getProgress();

            if (! p) {
                return;
            }

            return +(p.getAttribute('aria-valuenow'));
        }

        /**
         * Update the current progress value.
         *
         * value: The new percentage value (between 0 and 100).
         */
        setValue(value)
        {
            const p = this.getProgress();

            if (! p) {
                return;
            }

            value = Math.max(Math.min(+value, 100), 0);
            const bar = p.querySelector('.progress-bar');

            p.setAttribute('aria-valuenow', value);

            if (! bar) {
                return;
            }

            if (p.classList.contains('vertical')) {
                bar.style.height = value + '%';
            } else {
                bar.style.width = value + '%';
            }

            // Refresh the label when the progress bar is showing the built-in
            // percentage one. A label provided through the 'labelSlot' is
            // owned by the application and is left untouched.

            if (bar.dataset.progressLabel === 'auto') {
                bar.textContent = value + '%';
            }
        }
    }

</script>
@endpush
@endonce

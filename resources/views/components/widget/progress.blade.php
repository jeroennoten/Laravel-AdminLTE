@if($isStacked())

{{-- Bootstrap v5.3 stacked markup: every segment is a track of its own --}}
<div {{ $attributes->merge(['class' => $makeProgressClass()]) }}>

    {{-- Progress segments --}}
    @foreach($segments as $segment)
        <div class="{{ $makeSegmentClass() }}" style="{{ $makeSegmentStyle($segment) }}"
            role="progressbar" aria-valuenow="{{ $segment['value'] }}" aria-valuemin="0"
            aria-valuemax="100" aria-label="{{ $makeSegmentAriaLabel($segment) }}">

            {{-- Segment bar --}}
            <div class="{{ $makeSegmentBarClass($segment) }}"@if($isSegmentLabelAuto($segment)) data-progress-label="auto"@endif>

                {{-- Segment label --}}
                {{ $makeSegmentLabel($segment) }}

            </div>

        </div>
    @endforeach

</div>

@else

{{-- Bootstrap v5.3 markup: the aria attributes live on the .progress wrapper --}}
<div {{ $attributes->merge([
    'class' => $makeProgressClass(),
    'role' => 'progressbar',
    'aria-valuenow' => $value,
    'aria-valuemin' => 0,
    'aria-valuemax' => 100,
    'aria-label' => __('adminlte::adminlte.progress'),
]) }}>

    {{-- Progress bar --}}
    <div class="{{ $makeProgressBarClass() }}" style="{{ $makeProgressBarStyle() }}"@if(isset($withLabel) && ! isset($labelSlot)) data-progress-label="auto"@endif>

        {{-- Progress bar label --}}
        @isset($labelSlot){{ $labelSlot }}@elseif(isset($withLabel)){{ $value }}%@endisset

    </div>

</div>

@endif

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
         *
         * index: The segment index, for a stacked progress bar.
         */
        getProgress(index = 0)
        {
            const t = document.getElementById(this.target);

            if (! t) {
                return null;
            }

            return t.classList.contains('progress')
                ? t
                : t.querySelectorAll('.progress')[index] ?? null;
        }

        /**
         * Get the current progress value.
         *
         * index: The segment index, for a stacked progress bar.
         */
        getValue(index = 0)
        {
            const p = this.getProgress(index);

            if (! p) {
                return;
            }

            return +(p.getAttribute('aria-valuenow'));
        }

        /**
         * Update the current progress value.
         *
         * value: The new percentage value (between 0 and 100).
         * index: The segment index, for a stacked progress bar.
         */
        setValue(value, index = 0)
        {
            const p = this.getProgress(index);

            if (! p) {
                return;
            }

            value = Math.max(Math.min(+value, 100), 0);
            const bar = p.querySelector('.progress-bar');

            p.setAttribute('aria-valuenow', value);

            if (! bar) {
                return;
            }

            // On the Bootstrap stacked markup the percentage lives on the
            // track, the inner bar always fills it.

            if (p.parentElement?.classList.contains('progress-stacked')) {
                p.style.width = value + '%';
            } else if (p.classList.contains('vertical')) {
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

<div {{ $attributes->merge(['class' => $makeBoxClass()]) }}>

    {{-- Box icon --}}
    @isset($icon)
        <span class="{{ $makeIconClass() }}">
            <i class="{{ $icon }}"></i>
        </span>
    @endisset

    {{-- Box content --}}
    <div class="info-box-content">

        {{-- Box title --}}
        @isset($title)
            <span class="info-box-text">{{ $title }}</span>
        @endisset

        {{-- Box short text --}}
        @isset($text)
            <span class="info-box-number">{{ $text }}</span>
        @endisset

        {{-- Box progress bar --}}
        @if(isset($progress) && isset($attributes['id']))
            <x-adminlte-progress value="{{ $progress }}" theme="{{ $progressTheme }}"
                id="progress-{{ $attributes['id'] }}"/>
        @elseif(isset($progress))
            <x-adminlte-progress value="{{ $progress }}" theme="{{ $progressTheme }}"/>
        @endif

        {{-- Box long description --}}
        @isset($description)
            <span class="progress-description">{{ $description }}</span>
        @endisset

    </div>

</div>

{{-- Register Javascript utility class for this component --}}

@once
@push('js')
<script>

    class _AdminLTE_InfoBox {

        /**
         * Constructor.
         *
         * target: The id of the target info box.
         */
        constructor(target)
        {
            this.target = target;
        }

        /**
         * Update the info box.
         *
         * data: An object with the new data.
         */
        update(data)
        {
            // Check if target and data exists.

            let t = $(`#${this.target}`);

            if (t.length <= 0 || ! data) {
                return;
            }

            // Update available data.

            if (data.title) {
                t.find('.info-box-text').html(data.title);
            }

            if (data.text) {
                t.find('.info-box-number').html(data.text);
            }

            if (data.icon) {
                t.find('.info-box-icon i').attr('class', data.icon);
            }

            if (data.description) {
                t.find('.progress-description').html(data.description);
            }

            // Update progress bar.

            if (data.progress) {
                let pBar = new _AdminLTE_Progress(`progress-${this.target}`);
                pBar.setValue(data.progress);
            }
        }
    }

</script>
@endpush
@endonce

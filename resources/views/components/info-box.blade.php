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

{{-- Add JS utility methods for this component --}}

@once
@push('js')
<script>

    function IB_Utils() {

        /**
         * Update the box data.
         */
        this.update = function(target, data)
        {
            // Check if target exists.

            let t = $('#' + target);

            if (t.length <= 0) {
                return;
            }

            // Update available data.

            if (data && data.title) {
                t.find('.info-box-text').html(data.title);
            }

            if (data && data.text) {
                t.find('.info-box-number').html(data.text);
            }

            if (data && data.icon) {
                t.find('.info-box-icon i').attr('class', data.icon);
            }

            if (data && data.description) {
                t.find('.progress-description').html(data.description);
            }

            // Update progress bar.

            if (data && data.progress) {
                _adminlte_pbUtils.setValue('progress-' + target, data.progress);
            }
        }
    }

    // Create the plugin utilities object.

    var _adminlte_ibUtils = new IB_Utils();

</script>
@endpush
@endonce

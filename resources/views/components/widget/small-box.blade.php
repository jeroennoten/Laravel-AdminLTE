<div {{ $attributes->merge(['class' => $makeBoxClass()]) }}>

    {{-- Box title and description --}}
    <div class="inner">
        @isset($title)
            <h3>{{ $title }}</h3>
        @endisset

        @isset($text)
            <h5>{{ $text }}</h5>
        @endisset
    </div>

    {{-- Box icon --}}
    @isset($icon)
        <div class="icon">
            <i class="{{ $icon }}"></i>
        </div>
    @endisset

    {{-- Box link --}}
    @isset($url)
        <a href="{{ $url }}" class="small-box-footer">

            @if(! empty($urlText))
                {{ $urlText }}
            @endif

            <i class="fas fa-lg fa-arrow-circle-right"></i>
        </a>
    @endisset

    {{-- Box overlay --}}
    <div class="{{ $makeOverlayClass() }}">
        <i class="fas fa-2x fa-spin fa-sync-alt text-gray"></i>
    </div>

</div>

{{-- Add JS utility methods for this component --}}

@once
@push('js')
<script>

    function SB_Utils() {

        /**
         * Toggle the loading animation.
         */
        this.toggleLoading = function(target)
        {
            // Check if target exists.

            let t = $('#' + target);

            if (t.length <= 0) {
                return;
            }

            // Toggle the loading overlay.

            t.find('.overlay').toggleClass('d-none');
        }

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
                t.find('.inner h3').html(data.title);
            }

            if (data && data.text) {
                t.find('.inner h5').html(data.text);
            }

            if (data && data.icon) {
                t.find('.icon i').attr('class', data.icon);
            }

            if (data && data.url) {
                t.find('.small-box-footer').attr('href', data.url);
            }
        }
    }

    // Create the plugin utilities object.

    var _adminlte_sbUtils = new SB_Utils();

</script>
@endpush
@endonce

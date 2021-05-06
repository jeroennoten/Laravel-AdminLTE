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

{{-- Register Javascript utility class for this component --}}

@once
@push('js')
<script>

    class _AdminLTE_SmallBox {

        /**
         * Constructor.
         *
         * target: The id of the target small box.
         */
        constructor(target)
        {
            this.target = target;
        }

        /**
         * Update the small box.
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
                t.find('.inner h3').html(data.title);
            }

            if (data.text) {
                t.find('.inner h5').html(data.text);
            }

            if (data.icon) {
                t.find('.icon i').attr('class', data.icon);
            }

            if (data.url) {
                t.find('.small-box-footer').attr('href', data.url);
            }
        }

        /**
         * Toggle the loading animation of the small box.
         */
        toggleLoading()
        {
            // Check if target exists.

            let t = $(`#${this.target}`);

            if (t.length <= 0) {
                return;
            }

            // Toggle the loading overlay.

            t.find('.overlay').toggleClass('d-none');
        }
    }

</script>
@endpush
@endonce

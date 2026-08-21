<div {{ $attributes->merge(['class' => $makeBoxClass()]) }}>

    {{-- Box title and description --}}
    <div class="inner">
        @isset($title)
            <h3>{{ $title }}</h3>
        @endisset

        @isset($text)
            <p>{{ $text }}</p>
        @endisset
    </div>

    {{-- Box icon --}}
    @isset($icon)
        <i class="{{ $makeIconClass() }}" aria-hidden="true"></i>
    @endisset

    {{-- Box link --}}
    @isset($url)
        <a href="{{ $url }}"
            class="small-box-footer text-reset link-underline-opacity-0 link-underline-opacity-50-hover">

            @if(! empty($urlText))
                {{ $urlText }}
            @endif

            <i class="bi bi-arrow-right-circle" aria-hidden="true"></i>
        </a>
    @endisset

    {{-- Box loading overlay --}}
    <div class="{{ $makeOverlayClass() }}" style="z-index:20;">
        <div class="spinner-border text-body-secondary" role="status">
            <span class="visually-hidden">Loading</span>
        </div>
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
            const t = document.getElementById(this.target);

            if (! t || ! data) {
                return;
            }

            if (data.title) {
                t.querySelector('.inner h3').innerHTML = data.title;
            }

            if (data.text) {
                t.querySelector('.inner p').innerHTML = data.text;
            }

            if (data.icon) {
                const icon = t.querySelector('.small-box-icon');

                if (icon) {
                    icon.className = 'small-box-icon ' + data.icon;
                }
            }

            if (data.url) {
                t.querySelector('.small-box-footer').href = data.url;
            }
        }

        /**
         * Toggle the loading overlay of the small box.
         */
        toggleLoading()
        {
            const t = document.getElementById(this.target);

            if (! t) {
                return;
            }

            t.querySelector('.small-box-overlay').classList.toggle('d-none');
        }
    }

</script>
@endpush
@endonce

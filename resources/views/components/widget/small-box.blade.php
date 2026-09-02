<div {{ $attributes->merge(['class' => $makeBoxClass()]) }}>

    {{-- Box title and description --}}
    <div class="inner">
        @if(isset($titleSlot) || isset($title))
            <h3>{{ $titleSlot ?? $title }}</h3>
        @endif

        @if(isset($textSlot) || isset($text))
            <p>{{ $textSlot ?? $text }}</p>
        @endif
    </div>

    {{-- Box icon --}}
    @isset($icon)
        <i class="{{ $makeIconClass() }}" aria-hidden="true"></i>
    @endisset

    {{-- Box footer --}}
    @if(isset($footerSlot) && isset($url))
        <a href="{{ $url }}"
            class="{{ $makeFooterLinkClass() }}">{{ $footerSlot }}</a>
    @elseif(isset($footerSlot))
        <div class="small-box-footer">{{ $footerSlot }}</div>
    @elseif(isset($url))
        <a href="{{ $url }}"
            class="{{ $makeFooterLinkClass() }}">

            @if(! empty($urlText))
                {{ $urlText }}
            @endif

            @if(! empty($footerIcon))
                <i class="{{ $footerIcon }}" aria-hidden="true"></i>
            @endif
        </a>
    @endif

    {{-- Box loading overlay --}}
    <div class="{{ $makeOverlayClass() }}" style="z-index:20;">
        <div class="spinner-border text-body-secondary" role="status">
            <span class="visually-hidden">{{ __('adminlte::adminlte.loading') }}</span>
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
                const title = t.querySelector('.inner h3');
                if (title) { title.textContent = data.title; }
            }

            if (data.text) {
                const text = t.querySelector('.inner p');
                if (text) { text.textContent = data.text; }
            }

            if (data.icon) {
                const icon = t.querySelector('.small-box-icon');

                if (icon) {
                    icon.className = 'small-box-icon lh-1 ' + data.icon;
                }
            }

            if (data.url) {
                const footer = t.querySelector('a.small-box-footer');
                if (footer) { footer.href = data.url; }
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

            const overlay = t.querySelector('.small-box-overlay');

            if (overlay) {
                overlay.classList.toggle('d-none');
            }
        }
    }

</script>
@endpush
@endonce

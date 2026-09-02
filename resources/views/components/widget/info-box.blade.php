<div {{ $attributes->merge(['class' => $makeBoxClass()]) }}>

    {{-- Box icon --}}
    @isset($icon)
        <span class="{{ $makeIconClass() }}">
            <i class="{{ $icon }}" aria-hidden="true"></i>
        </span>
    @endisset

    {{-- Box content --}}
    <div class="info-box-content">

        {{-- Box title --}}
        @if(isset($titleSlot) || isset($title))
            <span class="info-box-text">

                @if(isset($url) && $urlTarget == 'title')
                    <a class="info-box-url link-underline link-underline-opacity-25 link-underline-opacity-100-hover text-reset"
                        href="{{ $url }}">{{ $titleSlot ?? $title }}</a>
                @else
                    {{ $titleSlot ?? $title }}
                @endif

            </span>
        @endif

        {{-- Box short text --}}
        @if(isset($textSlot) || isset($text))
            <span class="info-box-number">

                @if(isset($url) && $urlTarget == 'text')
                    <a class="info-box-url link-underline link-underline-opacity-25 link-underline-opacity-100-hover text-reset"
                        href="{{ $url }}">{{ $textSlot ?? $text }}</a>
                @else
                    {{ $textSlot ?? $text }}
                @endif

            </span>
        @endif

        {{-- Box progress bar --}}
        @if(isset($progress) && isset($attributes['id']))
            <x-adminlte-progress value="{{ $progress }}" theme="{{ $makeProgressTheme() }}"
                id="progress-{{ $attributes['id'] }}"/>
        @elseif(isset($progress))
            <x-adminlte-progress value="{{ $progress }}" theme="{{ $makeProgressTheme() }}"/>
        @endif

        {{-- Box long description --}}
        @isset($description)
            <span class="progress-description">{{ $description }}</span>
        @endisset

        {{-- Box extra line --}}
        @if(isset($moreSlot) || isset($more))
            <span class="info-box-more">{{ $moreSlot ?? $more }}</span>
        @endif

        {{-- Box free content --}}
        @if(! $slot->isEmpty())
            {{ $slot }}
        @endif

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
            const t = document.getElementById(this.target);

            if (! t || ! data) {
                return;
            }

            if (data.title) {
                const title = t.querySelector('.info-box-text');
                if (title) { title.textContent = data.title; }
            }

            if (data.text) {
                const text = t.querySelector('.info-box-number');
                if (text) { text.textContent = data.text; }
            }

            if (data.icon) {
                const icon = t.querySelector('.info-box-icon i');
                if (icon) { icon.className = data.icon; }
            }

            if (data.description) {
                const desc = t.querySelector('.progress-description');
                if (desc) { desc.textContent = data.description; }
            }

            if (data.url) {
                const url = t.querySelector('.info-box-url');
                if (url) { url.href = data.url; }
            }

            if (data.progress) {
                const pBar = new _AdminLTE_Progress(`progress-${this.target}`);
                pBar.setValue(data.progress);
            }
        }
    }

</script>
@endpush
@endonce

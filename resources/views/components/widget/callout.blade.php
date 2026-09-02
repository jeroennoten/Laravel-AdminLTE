<div {{ $attributes->merge(['class' => $makeCalloutClass()]) }}>

    {{-- Callout title --}}
    @if(! empty($title) || ! empty($icon))
        <h5 class="{{ $titleClass ?? 'mb-1' }}">
            @isset($icon) <i class="{{ $icon }} me-2" aria-hidden="true"></i> @endisset
            @isset($title) {{ $title }} @endisset
        </h5>
    @endif

    {{-- Callout content --}}
    {{ $slot }}

    {{-- Callout link --}}
    @isset($url)
        <a href="{{ $url }}" class="callout-link">
            @isset($linkSlot){{ $linkSlot }}@else{{ $urlText ?? $url }}@endisset
        </a>
    @endisset

</div>

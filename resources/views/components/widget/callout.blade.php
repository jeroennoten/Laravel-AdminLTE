<div {{ $attributes->merge(['class' => $makeCalloutClass()]) }}>

    {{-- Callout title --}}
    @if(! empty($title) || ! empty($icon))
        <h5 @isset($titleClass) class="{{ $titleClass }}" @endisset>
            @isset($icon) <i class="{{ $icon }} mr-2"></i> @endisset
            @isset($title) {{ $title }} @endisset
        </h5>
    @endif

    {{-- Callout content --}}
    {{ $slot }}

</div>

<div {{ $attributes->merge(['class' => $makeWrapperClass()]) }}>

    {{-- Ribbon banner --}}
    <div class="{{ $makeRibbonClass() }}">

        @isset($url)
            <a href="{{ $url }}">{{ $slot->isEmpty() ? $label : $slot }}</a>
        @else
            {{ $slot->isEmpty() ? $label : $slot }}
        @endisset

    </div>

</div>

<div {{ $attributes->merge(['class' => "adminlte-widget-item col-sm-{$size}"]) }}>

    <div class="description-block">

        {{-- Icon --}}
        @isset($icon)
            <i class="{{ $icon }}"></i>
        @endisset

        {{-- Header --}}
        @isset($title)
            <h5 class="description-header">{{ $title }}</h5>
        @endisset

        {{-- Text --}}
        @isset($text)
            <p class="description-text">{{ $text }}</p>
        @endisset

    </div>

</div>

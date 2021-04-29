<div {{ $attributes->merge(['class' => "col-{$size}"]) }}>

    <div class="description-block">

        {{-- Icon --}}
        @isset($icon)
            <i class="{{ $icon }}"></i>
        @endisset

        {{-- Header --}}
        @isset($title)
            <h5 class="description-header">
                @if(! empty($url))
                    <a href="{{ $url }}">{{ $title }}</a>
                @else
                    {{ $title }}
                @endif
            </h5>
        @endisset

        {{-- Text --}}
        @isset($text)
            <p class="description-text">
                <span class="{{ $makeTextWrapperClass() }}">
                    {{ $text }}
                </span>
            </p>
        @endisset

    </div>

</div>

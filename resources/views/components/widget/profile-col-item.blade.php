<div {{ $attributes->merge(['class' => "col-{$size}"]) }}>

    <div class="description-block">

        {{-- Icon --}}
        @isset($icon)
            <i class="{{ $icon }}"></i>
        @endisset

        {{-- Header --}}
        @isset($title)
            <h5 class="description-header">
                @if(! empty($url) && $urlTarget === 'title')
                    <a href="{{ $url }}">{{ $title }}</a>
                @else
                    {{ $title }}
                @endif
            </h5>
        @endisset

        {{-- Text --}}
        @isset($text)
            <p class="description-text">
                <span class="{{ $makeTextWrapperClass() }}"
                    @isset($textTooltip) title="{{ $textTooltip }}" style="cursor:help;" @endisset>
                    @if(! empty($url) && $urlTarget === 'text')
                        <a href="{{ $url }}">{{ $text }}</a>
                    @else
                        {{ $text }}
                    @endif
                </span>
            </p>
        @endisset

    </div>

</div>

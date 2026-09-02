<div {{ $attributes->merge(['class' => "col-{$size}"]) }}>

    <div class="description-block">

        {{-- Icon --}}
        @isset($icon)
            <i class="{{ $icon }}" aria-hidden="true"></i>
        @endisset

        {{-- Header --}}
        @isset($title)
            <p class="description-header">
                @if(! empty($url) && $urlTarget === 'title')
                    <a href="{{ $url }}">{{ $title }}</a>
                @else
                    {{ $title }}
                @endif
            </p>
        @endisset

        {{-- Text --}}
        @if(isset($textSlot) || isset($text))
            <span class="description-text">
                <span class="{{ $makeTextWrapperClass() }}"
                    @isset($textTooltip) title="{{ $textTooltip }}" style="cursor:help;" @endisset>
                    @if(! empty($url) && $urlTarget === 'text')
                        <a href="{{ $url }}">{{ $textSlot ?? $text }}</a>
                    @else
                        {{ $textSlot ?? $text }}
                    @endif
                </span>
            </span>
        @endif

    </div>

</div>

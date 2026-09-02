@if($layoutType === 'nav')

{{-- Reference (AdminLTE v4) markup. The item is a list item, so it has to be
     placed inside an 'ul.nav.flex-column' element. --}}
<li {{ $attributes->merge(['class' => 'nav-item']) }}>

    @if(! empty($url))
        <a href="{{ $url }}" class="{{ $makeNavLinkClass() }}">
    @else
        <span class="{{ $makeNavLinkClass() }}">
    @endif

        {{-- Icon --}}
        @isset($icon)
            <i class="{{ $icon }} me-1" aria-hidden="true"></i>
        @endisset

        {{-- Header --}}
        @isset($title)
            {{ $title }}
        @endisset

        {{-- Text --}}
        @if(isset($textSlot) || isset($text))
            <span class="{{ $makeTextWrapperClass() }}"
                @isset($textTooltip) title="{{ $textTooltip }}" style="cursor:help;" @endisset>
                {{ $textSlot ?? $text }}
            </span>
        @endif

    @if(! empty($url))
        </a>
    @else
        </span>
    @endif

</li>

@else

<div {{ $attributes->merge(['class' => "p-0 col-{$size}"]) }}>

    <span class="nav-link">

        {{-- Icon --}}
        @isset($icon)
            <i class="{{ $icon }} me-1" aria-hidden="true"></i>
        @endisset

        {{-- Header --}}
        @isset($title)
            @if(! empty($url) && $urlTarget === 'title')
                <a href="{{ $url }}">{{ $title }}</a>
            @else
                {{ $title }}
            @endif
        @endisset

        {{-- Text --}}
        @if(isset($textSlot) || isset($text))
            <span class="{{ $makeTextWrapperClass() }}"
                @isset($textTooltip) title="{{ $textTooltip }}" style="cursor:help;" @endisset>
                @if(! empty($url) && $urlTarget === 'text')
                    <a href="{{ $url }}">{{ $textSlot ?? $text }}</a>
                @else
                    {{ $textSlot ?? $text }}
                @endif
            </span>
        @endif

    </span>

</div>

@endif

{{-- Navbar dropdown item (AdminLTE v4) --}}

<a {{ $attributes->merge($makeAnchorDefaultAttrs()) }}>

    @if(trim((string) $slot) !== '')

        {{-- Custom item content --}}
        {{ $slot }}

    @elseif($isMediaItem())

        {{-- Media item --}}
        <div class="d-flex">

            @isset($img)
                <div class="flex-shrink-0">
                    <img src="{{ $img }}" alt="{{ $imgAlt }}" class="{{ $makeImageClass() }}">
                </div>
            @endisset

            <div class="flex-grow-1">

                @isset($title)
                    <p class="dropdown-item-title">
                        {{ $title }}
                        @isset($marker)
                            <span class="{{ $makeMarkerClass() }}">
                                <i class="{{ $marker }}" aria-hidden="true"></i>
                            </span>
                        @endisset
                    </p>
                @endisset

                @isset($text)
                    <p class="fs-7">{{ $text }}</p>
                @endisset

                @isset($time)
                    <p class="fs-7 text-secondary">
                        <i class="bi bi-clock-fill me-1" aria-hidden="true"></i> {{ $time }}
                    </p>
                @endisset

            </div>

        </div>

    @else

        {{-- Inline item --}}
        @isset($icon)
            <i class="{{ $makeIconClass() }}" aria-hidden="true"></i>
        @endisset

        @isset($text)
            {{ $text }}
        @endisset

        @isset($time)
            <span class="float-end text-secondary fs-7">{{ $time }}</span>
        @endisset

    @endif

</a>

@if($divider)
    <div class="dropdown-divider"></div>
@endif

<div {{ $attributes->merge() }}>

    {{-- Item marker --}}
    <i class="{{ $makeIconClass() }}" aria-hidden="true"></i>

    <div class="timeline-item">

        {{-- Item time --}}
        @isset($time)
            <span class="time">

                @if(! empty($timeIcon))
                    <i class="{{ $timeIcon }}" aria-hidden="true"></i>
                @endif

                <span class="visually-hidden">{{ __('adminlte::adminlte.timeline_time') }}</span>

                @if(isset($url) && $urlTarget === 'time')
                    <a href="{{ $url }}">{{ $time }}</a>
                @else
                    {{ $time }}
                @endif

            </span>
        @endisset

        {{-- Item header --}}
        @if(! $isHeaderEmpty(isset($headerSlot)))
            <h3 class="{{ $makeHeaderClass() }}">

                @if(isset($headerSlot))
                    {{ $headerSlot }}
                @elseif(isset($url) && $urlTarget === 'header')
                    <a href="{{ $url }}">{{ $header }}</a>
                @else
                    {{ $header }}
                @endif

            </h3>
        @endif

        {{-- Item body --}}
        @if(! $slot->isEmpty())
            <div class="timeline-body">
                {{ $slot }}
            </div>
        @endif

        {{-- Item footer --}}
        @isset($footerSlot)
            <div class="timeline-footer">
                {{ $footerSlot }}
            </div>
        @endisset

    </div>

</div>

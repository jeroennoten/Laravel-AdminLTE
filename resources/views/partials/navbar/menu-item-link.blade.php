<li @if(isset($item['id'])) id="{{ $item['id'] }}" @endif class="nav-item">

    <a class="nav-link {{ $item['class'] }}" href="{{ $item['href'] }}"
       @if(isset($item['target'])) target="{{ $item['target'] }}" @endif
       {!! $item['data-compiled'] ?? '' !!}>

        {{-- Icon (optional) --}}
        @if(isset($item['icon']))
            <i class="{{ $item['icon'] }} {{
                isset($item['icon_color']) ? 'text-' . $item['icon_color'] : ''
            }}"></i>
        @endif

        {{-- Text --}}
        {{ $item['text'] }}

        {{-- Label (optional) --}}
        @if(isset($item['label']))
            <span class="badge badge-{{ $item['label_color'] ?? 'primary' }}">
                {{ $item['label'] }}
            </span>
        @endif

    </a>

</li>
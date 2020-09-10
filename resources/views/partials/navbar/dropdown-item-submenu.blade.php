<li @if(isset($item['id'])) id="{{ $item['id'] }}" @endif class="dropdown-submenu dropdown-hover">

    {{-- Menu toggler --}}
    <a class="dropdown-item dropdown-toggle" href="" data-toggle="dropdown"
       {!! $item['data-compiled'] ?? '' !!}>

        {{-- Icon (optional) --}}
        @if(isset($item['icon']))
            <i class="{{ $item['icon'] ?? '' }} {{
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

    {{-- Menu items --}}
    <ul class="dropdown-menu border-0 shadow">
        @each('adminlte::partials.navbar.dropdown-item', $item['submenu'], 'item')
    </ul>

</li>

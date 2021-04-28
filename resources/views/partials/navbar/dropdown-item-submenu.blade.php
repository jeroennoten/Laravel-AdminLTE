<li @isset($item['id']) id="{{ $item['id'] }}" @endisset class="dropdown-submenu dropdown-hover">

    {{-- Menu toggler --}}
    <a class="dropdown-item dropdown-toggle" href="" data-toggle="dropdown"
       {!! $item['data-compiled'] ?? '' !!}>

        {{-- Icon (optional) --}}
        @isset($item['icon'])
            <i class="{{ $item['icon'] ?? '' }} {{
                isset($item['icon_color']) ? 'text-' . $item['icon_color'] : ''
            }}"></i>
        @endisset

        {{-- Text --}}
        {{ $item['text'] }}

        {{-- Label (optional) --}}
        @isset($item['label'])
            <span class="badge badge-{{ $item['label_color'] ?? 'primary' }}">
                {{ $item['label'] }}
            </span>
        @endisset

    </a>

    {{-- Menu items --}}
    <ul class="dropdown-menu border-0 shadow">
        @each('adminlte::partials.navbar.dropdown-item', $item['submenu'], 'item')
    </ul>

</li>

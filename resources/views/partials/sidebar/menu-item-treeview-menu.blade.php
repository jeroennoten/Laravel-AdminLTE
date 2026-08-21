<li @isset($item['id']) id="{{ $item['id'] }}" @endisset class="nav-item {{ $item['submenu_class'] }}">

    {{-- Menu toggler --}}
    <a class="nav-link {{ $item['class'] }} @isset($item['shift']) {{ $item['shift'] }} @endisset"
       href="#" {!! $item['data-compiled'] ?? '' !!}>

        <i class="nav-icon {{ $item['icon'] ?? 'bi bi-circle' }} {{
            isset($item['icon_color']) ? 'text-'.$item['icon_color'] : ''
        }}"></i>

        <p>
            {{ $item['text'] }}

            @isset($item['label'])
                <span class="nav-badge badge text-bg-{{ $item['label_color'] ?? 'primary' }} me-3">
                    {{ $item['label'] }}
                </span>
            @endisset

            <i class="nav-arrow bi bi-chevron-right"></i>
        </p>

    </a>

    {{-- Menu items --}}
    <ul class="nav nav-treeview">
        @each('adminlte::partials.sidebar.menu-item', $item['submenu'], 'item')
    </ul>

</li>

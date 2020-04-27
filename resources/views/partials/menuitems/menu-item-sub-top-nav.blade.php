@if (is_array($item))
    <li @if (isset($item['id'])) id="{{ $item['id'] }}" @endif class="@if (isset($item['submenu']))dropdown-submenu @endif">
        <a class="dropdown-item @if (isset($item['submenu']))dropdown-toggle @endif" href="{{ $item['href'] }}"
           @if (isset($item['submenu'])) data-toggle="dropdown" @endif
           @if (isset($item['target'])) target="{{ $item['target'] }}" @endif
           {!! $item['data-compiled'] ?? '' !!}
        >
            <i class="{{ $item['icon'] ?? 'far fa-fw fa-circle' }} {{ isset($item['icon_color']) ? 'text-' . $item['icon_color'] : '' }}"></i>
            {{ $item['text'] }}

            @if (isset($item['label']))
                <span class="badge badge-{{ $item['label_color'] ?? 'primary' }}">{{ $item['label'] }}</span>
            @endif
        </a>
        @if (isset($item['submenu']))
            <ul class="dropdown-menu border-0 shadow">
                @each('adminlte::partials.menuitems.menu-item-sub-top-nav', $item['submenu'], 'item')
            </ul>
        @endif
    </li>
@endif

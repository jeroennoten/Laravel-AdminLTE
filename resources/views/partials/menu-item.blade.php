@if (is_string($item))
    <li class="header">{{ $item }}</li>
@else
    <li {{ isset($item['submenu']) ? 'class="treeview"' : '' }}>
        <a href="{{ isset($item['url']) ? url($item['url']) : '#' }}">
            <i class="fa fa-fw fa-{{ $item['icon'] or 'circle-o' }} {{ isset($item['icon_color']) ? 'text-' . $item['icon_color'] : '' }}"></i>
            <span>{{ $item['text'] }}</span>
            @if (isset($item['label']))
                <span class="label label-{{ $item['label_color'] or 'primary' }} pull-right">{{ $item['label'] }}</span>
            @elseif (isset($item['submenu']))
                <i class="fa fa-angle-left pull-right"></i>
            @endif
        </a>
        @if (isset($item['submenu']))
            <ul class="treeview-menu">
                @each('adminlte::partials.menu-item', $item['submenu'], 'item')
            </ul>
        @endif
    </li>
@endif

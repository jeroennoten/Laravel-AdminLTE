@if (is_string($item))
    <li class="header">{{ trans('adminlte::menu.'.$item) }}</li>
@else
    <li class="{{ $item['class'] }}">
        <a href="{{ $item['href'] }}"
           @if (isset($item['target'])) target="{{ $item['target'] }}" @endif
        >
            <i class="fa fa-fw fa-{{ $item['icon'] or 'circle-o' }} {{ isset($item['icon_color']) ? 'text-' . $item['icon_color'] : '' }}"></i>
            <span>{{ trans('adminlte::menu.'.$item['text']) }}</span>
            @if (isset($item['label']))
                <span class="pull-right-container">
                    <span class="label label-{{ $item['label_color'] or 'primary' }} pull-right">{{ trans('adminlte::menu.'.$item['label']) }}</span>
                </span>
            @elseif (isset($item['submenu']))
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
                </span>
            @endif
        </a>
        @if (isset($item['submenu']))
            <ul class="{{ $item['submenu_class'] }}">
                @each('adminlte::partials.menu-item', $item['submenu'], 'item')
            </ul>
        @endif
    </li>
@endif

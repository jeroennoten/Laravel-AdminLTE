@if (is_array($item))
    <li class="{{ $item['top_nav_class'] }}">
        <a href="{{ $item['href'] }}"
           @if (isset($item['submenu'])) class="dropdown-toggle" data-toggle="dropdown" @endif
           @if (isset($item['target'])) target="{{ $item['target'] }}" @endif
        >
            <i class="fa fa-fw fa-{{ $item['icon'] or 'circle-o' }} {{ isset($item['icon_color']) ? 'text-' . $item['icon_color'] : '' }}"></i>
            @if (isset($item['text']))
                $item['text']
            @else
                {{ trans('adminlte::menu.'.$item['lang']) }}
            @endif

            @if (isset($item['label']))
                <span class="label label-{{ isset($item['label_color']) ? $item['label_color'] : 'primary' }}">{{ $item['label'] }}</span>
            @elseif (isset($item['submenu']))
                <span class="caret"></span>
            @endif
        </a>
        @if (isset($item['submenu']))
            <ul class="dropdown-menu" role="menu">
                @foreach($item['submenu'] as $subitem)
                    @if (is_string($subitem))
                        @if($subitem == '-')
                            <li role="separator" class="divider"></li>
                        @else
                            <li class="dropdown-header">{{ trans('adminlte::menu.'.$subitem) }}</li>
                        @endif
                    @else
                    <li class="{{ $subitem['top_nav_class'] }}">
                        <a href="{{ $subitem['href'] }}">

                            <i class="fa fa-{{ $subitem['icon'] or 'circle-o' }} {{ isset($subitem['icon_color']) ? 'text-' . $subitem['icon_color'] : '' }}"></i>
                            @if (isset($subitem['text']))
                                $subitem['text']
                            @else
                                {{ trans('adminlte::menu.'.$subitem['lang']) }}
                            @endif

                            @if (isset($subitem['label']))
                                <span class="label label-{{ isset($subitem['label_color']) ? $subitem['label_color'] : 'primary' }}">{{ $subitem['label'] }}</span>
                            @endif
                        </a>
                    </li>
                    @endif
                @endforeach
            </ul>
        @endif
    </li>
@endif

@if (is_array($item))
    <li class="{{ $item['top_nav_class'] }}">
        <a href="{{ $item['href'] }}"
           @if (isset($item['submenu'])) class="dropdown-toggle" data-toggle="dropdown" @endif
           @if (isset($item['target'])) target="{{ $item['target'] }}" @endif
        >
            <i class="{{ $item['icon_class'] or 'fa fw fa-circle-o'] }}"></i>
            {{ $item['text'] }}
            @if (isset($item['label']))
                <span class="label label-{{ $item['label_color'] or 'primary' }}">{{ $item['label'] }}</span>
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
                            <li class="dropdown-header">{{ $subitem }}</li>
                        @endif
                    @else
                    <li class="{{ $subitem['top_nav_class'] }}">
                        <a href="{{ $subitem['href'] }}">
                            <i class="{{ $item['icon_class'] or 'fa fw fa-circle-o' }}"></i>
                            {{ $subitem['text'] }}
                            @if (isset($subitem['label']))
                                <span class="label label-{{ $subitem['label_color'] or 'primary' }}">{{ $subitem['label'] }}</span>
                            @endif
                        </a>
                    </li>
                    @endif
                @endforeach
            </ul>
        @endif
    </li>
@endif

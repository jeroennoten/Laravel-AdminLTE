@if ((!isset($item['topnav']) || (isset($item['topnav']) && !$item['topnav'])) && (!isset($item['topnav_right']) || (isset($item['topnav_right']) && !$item['topnav_right'])) && (!isset($item['topnav_user']) || (isset($item['topnav_user']) && !$item['topnav_user'])))
    @if (is_string($item))
        <li @if (isset($item['id'])) id="{{ $item['id'] }}" @endif class="nav-header">{{ $item }}</li>
    @elseif (isset($item['header']))
        <li @if (isset($item['id'])) id="{{ $item['id'] }}" @endif class="nav-header">{{ $item['header'] }}</li>
    @elseif (isset($item['search']) && $item['search'])
        <li @if (isset($item['id'])) id="{{ $item['id'] }}" @endif>
            <form action="{{ $item['href'] }}" method="{{ $item['method'] }}" class="form-inline">
              <div class="input-group">
                <input class="form-control form-control-sidebar" type="search" name="{{ $item['input_name'] }}" placeholder="{{ $item['text'] }}" aria-label="{{ $item['aria-label'] ?? $item['text'] }}">
                <div class="input-group-append">
                  <button class="btn btn-sidebar" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </form>
        </li>
    @else
        <li @if (isset($item['id'])) id="{{ $item['id'] }}" @endif class="nav-item @if (isset($item['submenu'])){{ $item['submenu_class'] }}@endif">
            <a class="nav-link {{ $item['class'] }} @if(isset($item['shift'])) {{ $item['shift'] }} @endif" href="{{ $item['href'] }}"
               @if (isset($item['target'])) target="{{ $item['target'] }}" @endif
               {!! $item['data-compiled'] ?? '' !!}
            >
                <i class="{{ $item['icon'] ?? 'far fa-fw fa-circle' }} {{ isset($item['icon_color']) ? 'text-' . $item['icon_color'] : '' }}"></i>
                <p>
                    {{ $item['text'] }}

                    @if (isset($item['submenu']))
                        <i class="fas fa-angle-left right"></i>
                    @endif
                    @if (isset($item['label']))
                        <span class="badge badge-{{ $item['label_color'] ?? 'primary' }} right">{{ $item['label'] }}</span>
                    @endif
                </p>
            </a>
            @if (isset($item['submenu']))
                <ul class="nav nav-treeview">
                    @each('adminlte::partials.menuitems.menu-item', $item['submenu'], 'item')
                </ul>
            @endif
        </li>
    @endif
@endif

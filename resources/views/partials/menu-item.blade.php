@if (is_string($item))
    <li class="nav-header">{{ $item }}</li>
@elseif (isset($item['header']))
    <li class="nav-header">{{ $item['header'] }}</li>
@elseif (isset($item['search']) && $item['search'])
    <!-- <form action="{{ $item['href'] }}" method="{{ $item['method'] }}">
        <div class="input-group">
          <input type="text" name="{{ $item['input_name'] }}" class="form-control" placeholder="
            {{ $item['text'] }}
          ">
            <div class="input-group-append">
                <button type="submit" name="search" id="search-btn" class="btn btn-light">
                  <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
      </form> -->
@else
    <li class="nav-item">
        <a class="nav-link {{ $item['class'] }} @if (isset($item['submenu'])){{ $item['submenu_class'] }}@endif" href="{{ $item['href'] }}"
           @if (isset($item['target'])) target="{{ $item['target'] }}" @endif
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
                @each('adminlte::partials.menu-item', $item['submenu'], 'item')
            </ul>
        @endif
    </li>
@endif

@if (is_string($item))

    {{-- Single header --}}
    <li class="nav-header">{{ $item }}</li>

@elseif (isset($item['header']))

    {{-- Advanced header --}}
    <li @if(isset($item['id'])) id="{{ $item['id'] }}" @endif class="nav-header">
        {{ $item['header'] }}
    </li>

@elseif (isset($item['search']) && $item['search'])

    {{-- Search form --}}
    @include('adminlte::partials.sidebar.menu-item-search-form')

@elseif (isset($item['submenu']))

    {{-- Treeview menu --}}
    @include('adminlte::partials.sidebar.menu-item-treeview-menu')

@else

    {{-- Link --}}
    @include('adminlte::partials.sidebar.menu-item-link')

@endif

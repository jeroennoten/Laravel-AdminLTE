@if (isset($item['search']) && $item['search'])

    {{-- Search form --}}
    @include('adminlte::partials.navbar.menu-item-search-form')

@elseif (isset($item['submenu']))

    {{-- Dropdown menu --}}
    @include('adminlte::partials.navbar.menu-item-dropdown-menu')

@else

    {{-- Link --}}
    @include('adminlte::partials.navbar.menu-item-link')

@endif

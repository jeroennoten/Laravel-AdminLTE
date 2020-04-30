@if (isset($item['submenu']))

    {{-- Dropdown submenu --}}
    @include('adminlte::partials.navbar.dropdown-item-submenu')

@elseif (is_array($item))

    {{-- Dropdown link --}}
    @include('adminlte::partials.navbar.dropdown-item-link')

@endif
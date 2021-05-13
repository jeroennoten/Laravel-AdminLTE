@inject('navbarItemHelper', 'JeroenNoten\LaravelAdminLte\Helpers\NavbarItemHelper')

@if ($navbarItemHelper->isSubmenu($item))

    {{-- Dropdown submenu --}}
    @include('adminlte::partials.navbar.dropdown-item-submenu')

@elseif ($navbarItemHelper->isLink($item))

    {{-- Dropdown link --}}
    @include('adminlte::partials.navbar.dropdown-item-link')

@endif

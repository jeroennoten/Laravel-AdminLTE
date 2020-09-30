@inject('menuItemHelper', \JeroenNoten\LaravelAdminLte\Helpers\MenuItemHelper)

@if ($menuItemHelper->isSearchBar($item) && !$item['topnav'])

    {{-- Search form --}}
    @include('adminlte::partials.navbar.menu-item-search-form')

@elseif ($menuItemHelper->isSearchBar($item) && $item['topnav'])

    {{-- Omit search form --}}

@elseif ($menuItemHelper->isSubmenu($item))

    {{-- Dropdown menu --}}
    @include('adminlte::partials.navbar.menu-item-dropdown-menu')

@elseif ($menuItemHelper->isLink($item))

    {{-- Link --}}
    @include('adminlte::partials.navbar.menu-item-link')

@endif

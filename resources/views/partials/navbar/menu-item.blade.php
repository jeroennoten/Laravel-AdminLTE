@inject('navbarItemHelper', 'JeroenNoten\LaravelAdminLte\Helpers\NavbarItemHelper')

@if ($navbarItemHelper->isSearch($item))

    {{-- Search form --}}
    @include('adminlte::partials.navbar.menu-item-search-form')

@elseif ($navbarItemHelper->isNotification($item))

    {{-- Notification link (using blade component) --}}
    <x-adminlte-navbar-notification
        :id="$item['id']"
        :href="$item['href']"
        :icon="$item['icon']"
        :icon-color="$item['icon_color'] ?? null"
        :badge-label="$item['label'] ?? null"
        :badge-color="$item['label_color'] ?? null"
        :update-cfg="$item['update_cfg'] ?? null"
        :enable-dropdown-mode="$item['dropdown_mode'] ?? null"
        :dropdown-footer-label="$item['dropdown_flabel'] ?? null"
    />

@elseif ($navbarItemHelper->isFullscreen($item))

    {{-- Fullscreen toggle widget --}}
    @include('adminlte::partials.navbar.menu-item-fullscreen-widget')

@elseif ($navbarItemHelper->isSubmenu($item))

    {{-- Dropdown menu --}}
    @include('adminlte::partials.navbar.menu-item-dropdown-menu')

@elseif ($navbarItemHelper->isLink($item))

    {{-- Link --}}
    @include('adminlte::partials.navbar.menu-item-link')

@endif

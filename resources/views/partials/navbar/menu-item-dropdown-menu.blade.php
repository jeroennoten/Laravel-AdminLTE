@inject('navbarItemHelper', 'JeroenNoten\LaravelAdminLte\Helpers\NavbarItemHelper')

{{--
    When the dropdown contains nested submenus, the dropdown is configured to
    stay open while clicking inside it (Bootstrap 5 closes the entire dropdown
    otherwise, and the nested menu would never become visible).
--}}
@php($hasSubmenus = ! empty(array_filter($item['submenu'] ?? [], [$navbarItemHelper, 'isSubmenu'])))

<li @isset($item['id']) id="{{ $item['id'] }}" @endisset class="nav-item dropdown">

    {{-- Menu toggler --}}
    <a class="nav-link dropdown-toggle {{ $item['class'] ?? '' }}" href="#" role="button"
       data-bs-toggle="dropdown" aria-expanded="false"
       @if($hasSubmenus) data-bs-auto-close="outside" @endif
       {!! $item['data-compiled'] ?? '' !!}>

        {{-- Icon (optional) --}}
        @isset($item['icon'])
            <i class="{{ $item['icon'] }} {{
                isset($item['icon_color']) ? 'text-' . $item['icon_color'] : ''
            }}"></i>
        @endisset

        {{-- Text --}}
        {{ $item['text'] }}

        {{-- Label (optional) --}}
        @isset($item['label'])
            <span class="badge text-bg-{{ $item['label_color'] ?? 'primary' }}">
                {{ $item['label'] }}
            </span>
        @endisset

    </a>

    {{-- Menu items --}}
    {{-- Note only the dropdowns of the right side of the navbar are aligned
         to the end, as on the AdminLTE v4 reference layouts. --}}
    <ul class="dropdown-menu @if(! empty($item['topnav_right'])) dropdown-menu-end @endif shadow">
        @each('adminlte::partials.navbar.dropdown-item', $item['submenu'], 'item')
    </ul>

</li>

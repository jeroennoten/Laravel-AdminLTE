@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

{{--
    Top navigation layout (AdminLTE v4). There is no <aside class="app-sidebar">
    on this layout, so the navigation lives entirely on the header, using a
    plain Bootstrap 5 navbar (Collapse plugin + toggler on small screens). A
    breakpoint is mandatory here, hence the 'navbar-expand' value (used by the
    sidebar layout) is upgraded to 'navbar-expand-lg'.
--}}

@php($topnavNavClass = trim(config('adminlte.classes_topnav_nav', 'navbar-expand-lg')))
@php($topnavNavClass = $topnavNavClass === 'navbar-expand' ? 'navbar-expand-lg' : $topnavNavClass)

<nav class="app-header navbar {{ $topnavNavClass }} {{ config('adminlte.classes_topnav', 'bg-body') }}">

    <div class="{{ config('adminlte.classes_topnav_container', 'container-fluid') }}">

        {{-- Navbar brand logo --}}
        @if(config('adminlte.logo_img_xl'))
            @include('adminlte::partials.common.brand-logo-xl')
        @else
            @include('adminlte::partials.common.brand-logo-xs')
        @endif

        {{-- Navbar toggler button (Bootstrap collapse, not PushMenu) --}}
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false"
                aria-label="{{ __('adminlte::adminlte.toggle_navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Navbar collapsible menu --}}
        <div class="collapse navbar-collapse" id="navbarCollapse">

            {{-- Navbar left links --}}
            <ul class="navbar-nav">
                {{-- Configured left links --}}
                @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

                {{-- Custom left links --}}
                @yield('content_top_nav_left')
            </ul>

            {{-- Navbar right links --}}
            <ul class="navbar-nav ms-auto">
                {{-- Custom right links --}}
                @yield('content_top_nav_right')

                {{-- Configured right links --}}
                @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

                {{-- User menu link --}}
                @if(Auth::user())
                    @if(config('adminlte.usermenu_enabled'))
                        @include('adminlte::partials.navbar.menu-item-dropdown-user-menu')
                    @else
                        @include('adminlte::partials.navbar.menu-item-logout-link')
                    @endif
                @endif

                {{-- Right sidebar (offcanvas) toggler link --}}
                @if($layoutHelper->isRightSidebarEnabled())
                    @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
                @endif
            </ul>

        </div>

    </div>

</nav>

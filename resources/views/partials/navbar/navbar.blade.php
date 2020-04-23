<nav class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand-md') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left links --}}
    <ul class="navbar-nav">
        {{-- Left sidebar toggler link --}}
        @include('adminlte::partials.navbar.left-sidebar-link')

        @each('adminlte::partials.menuitems.menu-item-top-nav-left', $adminlte->menu(), 'item')
        @yield('content_top_nav_left')
    </ul>

    {{-- Navbar right links --}}
    <ul class="navbar-nav ml-auto">
        @yield('content_top_nav_right')
        @each('adminlte::partials.menuitems.menu-item-top-nav-right', $adminlte->menu(), 'item')

        {{-- User menu links --}}
        @if(Auth::user())
            @if(config('adminlte.usermenu_enabled'))
                @include('adminlte::partials.navbar.dropdown-user-menu')
            @else
                @include('adminlte::partials.navbar.logout-link')
            @endif
        @endif

        {{-- Right sidebar toggler link --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.navbar.right-sidebar-link')
        @endif
    </ul>

</nav>

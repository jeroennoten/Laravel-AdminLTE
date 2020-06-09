<nav class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    <div class="{{ config('adminlte.classes_topnav_container', 'container') }}">

        {{-- Navbar brand logo --}}
        @if(config('adminlte.logo_img_xl'))
            @include('adminlte::partials.common.brand-logo-xl')
        @else
            @include('adminlte::partials.common.brand-logo-xs')
        @endif

        {{-- Navbar toggler button --}}
        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Navbar collapsible menu --}}
        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
            {{-- Navbar left links --}}
            <ul class="nav navbar-nav">
                {{-- Configured left links --}}
                @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

                {{-- Custom left links --}}
                @yield('content_top_nav_left')
            </ul>
        </div>

        {{-- Navbar right links --}}
        <ul class="navbar-nav ml-auto order-1 order-md-3 navbar-no-expand">
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

            {{-- Right sidebar toggler link --}}
            @if(config('adminlte.right_sidebar'))
                @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
            @endif
        </ul>

    </div>

</nav>

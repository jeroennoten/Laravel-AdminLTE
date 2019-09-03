@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet"
          href="{{ asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css')}} ">
    @stack('css')
    @yield('css')
@stop

@section('body_class', 'sidebar-mini ' . (config('adminlte.layout') ? [
    'boxed' => 'layout-boxed',
    'fixed' => 'layout-fixed',
    'top-nav' => 'layout-top-nav'
][config('adminlte.layout')] : '') . (config('adminlte.sidebar_collapse') ? ' sidebar-collapse ' : '') . ' ' . config('adminlte.body_class'))

@section('body_data', (config('adminlte.sidebar_scrollbar_theme', 'os-theme-light') != 'os-theme-light' ? 'data-scrollbar-theme=' . config('adminlte.sidebar_scrollbar_theme')  : '') . ' ' . (config('adminlte.sidebar_scrollbar_auto_hide', 'l') != 'l' ? 'data-scrollbar-auto-hide=' . config('adminlte.sidebar_scrollbar_auto_hide')   : ''))

@section('body')
    <div class="wrapper">
        @if(config('adminlte.layout') == 'top-nav')
        <nav class="main-header navbar navbar-expand {{config('adminlte.topnav_color', 'navbar-white navbar-light')}}">
            <div class="{{config('adminlte.topnav_container', 'container')}}">
                <nav class="navbar navbar-static-top">
                    <div class="navbar-header">
                        <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="navbar-brand">
                            {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
                        </a>
                    </div>

                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                        <ul class="nav navbar-nav">
                            @each('adminlte::partials.menu-item-top-nav', $adminlte->menu(), 'item')
                        </ul>
                    </div>
                </nav>
            @else
            <nav class="main-header navbar navbar-expand {{config('adminlte.topnav_classes', 'navbar-white navbar-light')}}">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" @if(config('adminlte.sidebar_collapse_remember')) data-enable-remember="true" @endif @if(!config('adminlte.sidebar_collapse_remember_no_transition')) data-no-transition-after-reload="false" @endif @if(config('adminlte.sidebar_collapse_screen_collapse_size', 768) != 768) data-screen-collapse-size="{{config('adminlte.sidebar_collapse_screen_collapse_size')}}" @endif @if(config('adminlte.sidebar_collapse_auto_size')) data-auto-collapse-size="{{config('adminlte.sidebar_collapse_auto_size')}}" @endif>
                            <i class="fas fa-bars"></i>
                            <span class="sr-only">{{ trans('adminlte::adminlte.toggle_navigation') }}</span>
                        </a>
                    </li>
                </ul>
            @endif
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        @if(config('adminlte.logout_method') == 'GET' || !config('adminlte.logout_method') && version_compare(\Illuminate\Foundation\Application::VERSION, '5.3.0', '<'))
                            <a class="nav-link" href="{{ url(config('adminlte.logout_url', 'auth/logout')) }}">
                                <i class="fa fa-fw fa-power-off"></i> {{ trans('adminlte::adminlte.log_out') }}
                            </a>
                        @else
                            <a class="nav-link" href="#"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            >
                                <i class="fa fa-fw fa-power-off"></i> {{ trans('adminlte::adminlte.log_out') }}
                            </a>
                            <form id="logout-form" action="{{ url(config('adminlte.logout_url', 'auth/logout')) }}" method="POST" style="display: none;">
                                @if(config('adminlte.logout_method'))
                                    {{ method_field(config('adminlte.logout_method')) }}
                                @endif
                                {{ csrf_field() }}
                            </form>
                        @endif
                    </li>
                    @if(config('adminlte.right_sidebar') and (config('adminlte.layout') != 'top-nav'))
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-widget="control-sidebar" @if(!config('adminlte.right_sidebar_slide')) data-controlsidebar-slide="false" @endif @if(config('adminlte.right_sidebar_scrollbar_theme', 'os-theme-light') != 'os-theme-light') data-scrollbar-theme="{{config('adminlte.right_sidebar_scrollbar_theme')}}" @endif @if(config('adminlte.right_sidebar_scrollbar_auto_hide', 'l') != 'l') data-scrollbar-auto-hide="{{config('adminlte.right_sidebar_scrollbar_auto_hide')}}" @endif>
                                <i class="{{config('adminlte.right_sidebar_icon')}}"></i>
                            </a>
                        </li>
                    @endif
                </ul>
                @if(config('adminlte.layout') == 'top-nav')
                </nav>
                @endif
            </nav>
        </header>
        @if(config('adminlte.layout') != 'top-nav')
        <aside class="main-sidebar {{config('adminlte.sidebar_classes', 'sidebar-dark-primary elevation-4')}}">
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="brand-link {{config('adminlte.brand_classes', '')}}">
                <span class="brand-text font-weight-light">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</span>
            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column {{config('adminlte.sidebar_nav_classes', '')}}" data-widget="treeview" role="menu" @if(config('adminlte.sidebar_nav_animation_speed') != 300) data-animation-speed="{{config('adminlte.sidebar_nav_animation_speed')}}" @endif @if(!config('adminlte.sidebar_nav_accordion')) data-accordion="false" @endif>
                        @each('adminlte::partials.menu-item', $adminlte->menu(), 'item')
                    </ul>
                </nav>
            </div>
        </aside>
        @endif

        <div class="content-wrapper">
            @if(config('adminlte.layout') == 'top-nav')
            <div class="container">
            @endif

            <div class="content-header">
                @yield('content_header')
            </div>

            <div class="content">
                @yield('content')
            </div>
            @if(config('adminlte.layout') == 'top-nav')
            </div>
            @endif
        </div>

        @hasSection('footer')
        <footer class="main-footer">

            @yield('footer')
        </footer>
        @endif

        @if(config('adminlte.right_sidebar') and (config('adminlte.layout') != 'top-nav'))
            <aside class="control-sidebar control-sidebar-{{config('adminlte.right_sidebar_theme')}}">
                @yield('right-sidebar')
            </aside>
        @endif

    </div>
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @stack('js')
    @yield('js')
@stop

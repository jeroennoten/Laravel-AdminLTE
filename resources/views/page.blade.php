@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@if($layoutHelper->isLayoutTopnavEnabled())
    @php( $def_container_class = 'container' )
@else
    @php( $def_container_class = 'container-fluid' )
@endif

@section('adminlte_css')
    @stack('css')

    <style>
        @media (max-width: 991px) {

            body:not(.sidebar-open) .main-sidebar,
            body:not(.sidebar-open) .main-sidebar::before {
                margin-left: -{{ config('adminlte.sidebar_width') ?? '250px' }};
                width: {{ config('adminlte.sidebar_width') ?? '250px' }};
            }
        }

        .sidebar-collapse .main-sidebar,
        .sidebar-collapse .main-sidebar::before {
            margin-left: -{{ config('adminlte.sidebar_width') ?? '250px' }};
        }

        .sidebar-mini .main-sidebar .nav-link,
        .sidebar-mini-md .main-sidebar .nav-link,
        .sidebar-mini-xs .main-sidebar .nav-link {
            width: calc({{ config('adminlte.sidebar_width') ?? '250px' }} - .5rem * 2);
            transition: width ease-in-out .3s;
        }

        @media (min-width: 992px) {

            .main-sidebar,
            .main-sidebar::before {
                width: {{ config('adminlte.sidebar_width') ?? '250px' }};
            }

            body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .content-wrapper,
            body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-footer,
            body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-header {
                margin-left: {{ config('adminlte.sidebar_width') ?? '250px' }};
            }
        }
    </style>

    @yield('css')
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')
    <div class="wrapper">

        {{-- Top Navbar --}}
        @if($layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.navbar.navbar-layout-topnav')
        @else
            @include('adminlte::partials.navbar.navbar')
        @endif

        {{-- Left Main Sidebar --}}
        @if(!$layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.sidebar.left-sidebar')
        @endif

        {{-- Content Wrapper --}}
        <div class="content-wrapper {{ config('adminlte.classes_content_wrapper') ?? '' }}">

            {{-- Content Header --}}
            @hasSection('content_header')
                <div class="content-header">
                    <div class="{{ config('adminlte.classes_content_header') ?: $def_container_class }}">
                        @yield('content_header')
                    </div>
                </div>
            @endif

            {{-- Main Content --}}
            <div class="content">
                <div class="{{ config('adminlte.classes_content') ?: $def_container_class }}">
                    @yield('content')
                </div>
            </div>

        </div>

        {{-- Footer --}}
        @hasSection('footer')
            @include('adminlte::partials.footer.footer')
        @endif

        {{-- Right Control Sidebar --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.sidebar.right-sidebar')
        @endif

    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop

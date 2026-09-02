@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')
@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper')

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@php
    // The footer is rendered when a 'footer' section is available, or when the
    // fixed footer layout is enabled (the layout reserves the related space).

    $fixedFooter = $layoutHelper->isFixedFooterEnabled();
@endphp

@section('body')
    {{-- Skip Links. The AdminLTE accessibility script injects its own English
         container when the document has no '.skip-links' element, so emitting
         a localized one here (as the first child of the body) replaces it.
         The script still stamps the '#main' and '#navigation' targets. --}}
    <div class="skip-links">
        <a href="#main" class="skip-link">{{ __('adminlte::adminlte.skip_to_content') }}</a>
        <a href="#navigation" class="skip-link">{{ __('adminlte::adminlte.skip_to_navigation') }}</a>
    </div>

    <div class="{{ $layoutHelper->makeWrapperClasses() }}">

        {{-- Preloader Animation (fullscreen mode) --}}
        @if($preloaderHelper->isPreloaderEnabled())
            @include('adminlte::partials.common.preloader')
        @endif

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
        @empty($iFrameEnabled)
            @include('adminlte::partials.cwrapper.cwrapper-default')
        @else
            @include('adminlte::partials.cwrapper.cwrapper-iframe')
        @endempty

        {{-- Footer --}}
        @if($fixedFooter || View::hasSection('footer'))
            @include('adminlte::partials.footer.footer')
        @endif

        {{-- Right Sidebar (Bootstrap offcanvas) --}}
        @if($layoutHelper->isRightSidebarEnabled())
            @include('adminlte::partials.sidebar.right-sidebar')
        @endif

    </div>
@stop

{{-- Note the stacks are yielded after the body section, otherwise the
     content pushed from the body (for example by the iframe mode) would be
     snapshotted before it exists. --}}

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop

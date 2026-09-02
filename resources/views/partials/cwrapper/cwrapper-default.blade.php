@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')
@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper')

@php
    // On the topnav layout the content uses the same container as the navbar,
    // otherwise the brand and the content would not share their left edge.

    $def_container_class = $layoutHelper->isLayoutTopnavEnabled()
        ? config('adminlte.classes_topnav_container', 'container-fluid')
        : 'container-fluid';
@endphp

{{-- Default Content Wrapper (AdminLTE v4: app-main) --}}
<main class="{{ $layoutHelper->makeContentWrapperClasses() }}">

    {{-- Preloader Animation (cwrapper mode) --}}
    @if($preloaderHelper->isPreloaderEnabled('cwrapper'))
        @include('adminlte::partials.common.preloader')
    @endif

    {{-- Content Top Area --}}
    @hasSection('content_top_area')
        <div class="app-content-top-area">
            <div class="{{ config('adminlte.classes_content_top_area') ?: $def_container_class }}">
                @yield('content_top_area')
            </div>
        </div>
    @endif

    {{-- Content Header --}}
    @hasSection('content_header')
        <div class="app-content-header">
            <div class="{{ config('adminlte.classes_content_header') ?: $def_container_class }}">
                @yield('content_header')
            </div>
        </div>
    @endif

    {{-- Main Content. Note the AdminLTE v4 reference layouts always provide a
         content header, and the top spacing of the content comes from it. So,
         when there is no content header, the spacing is added here. --}}
    <div class="app-content @unless(View::hasSection('content_header') || View::hasSection('content_top_area')) pt-3 @endunless">
        <div class="{{ config('adminlte.classes_content') ?: $def_container_class }}">
            @stack('content')
            @yield('content')
        </div>
    </div>

    {{-- Content Bottom Area --}}
    @hasSection('content_bottom_area')
        <div class="app-content-bottom-area">
            <div class="{{ config('adminlte.classes_content_bottom_area') ?: $def_container_class }}">
                @yield('content_bottom_area')
            </div>
        </div>
    @endif

</main>

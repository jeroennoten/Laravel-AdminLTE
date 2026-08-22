@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')
@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper')

@php
    // On the topnav layout the content is centered on a fixed width container.

    $def_container_class = $layoutHelper->isLayoutTopnavEnabled()
        ? 'container'
        : 'container-fluid';
@endphp

{{-- Default Content Wrapper (AdminLTE v4: app-main) --}}
<main class="{{ $layoutHelper->makeContentWrapperClasses() }}">

    {{-- Preloader Animation (cwrapper mode) --}}
    @if($preloaderHelper->isPreloaderEnabled('cwrapper'))
        @include('adminlte::partials.common.preloader')
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
    <div class="app-content @unless(View::hasSection('content_header')) pt-3 @endunless">
        <div class="{{ config('adminlte.classes_content') ?: $def_container_class }}">
            @stack('content')
            @yield('content')
        </div>
    </div>

</main>

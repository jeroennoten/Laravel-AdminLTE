@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')
@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper')

{{-- IFrame Content Wrapper (AdminLTE v4: app-main) --}}
<main class="{{ $layoutHelper->makeContentWrapperClasses() }} iframe-mode"
      data-lte-toggle="iframe"
      data-auto-show-new-tab="{{ config('adminlte.iframe.options.auto_show_new_tab', true) }}"
      data-loading-screen="{{ config('adminlte.iframe.options.loading_screen', 1000) }}"
      data-use-navbar-items="{{ config('adminlte.iframe.options.use_navbar_items', true) }}">

    {{-- Preloader Animation (cwrapper mode) --}}
    @if($preloaderHelper->isPreloaderEnabled('cwrapper'))
        @include('adminlte::partials.common.preloader')
    @endif

    {{-- IFrame Navbar --}}
    <div class="nav navbar navbar-expand bg-body border-bottom p-0">

        {{-- Close Buttons --}}
        @if(config('adminlte.iframe.buttons.close_all', true) || config('adminlte.iframe.buttons.close_all_other', true))

            <div class="nav-item dropdown">
                <a class="nav-link bg-danger dropdown-toggle" data-bs-toggle="dropdown" href="#"
                   role="button" aria-haspopup="true" aria-expanded="false">
                    {{ __('adminlte::iframe.btn_close') }}
                </a>
                <div class="dropdown-menu mt-0">
                    @if(config('adminlte.iframe.buttons.close', true))
                        <a class="dropdown-item" href="#" data-lte-toggle="iframe-close">
                            {{ __('adminlte::iframe.btn_close_active') }}
                        </a>
                    @endif
                    @if(config('adminlte.iframe.buttons.close_all', true))
                        <a class="dropdown-item" href="#" data-lte-toggle="iframe-close" data-type="all">
                            {{ __('adminlte::iframe.btn_close_all') }}
                        </a>
                    @endif
                    @if(config('adminlte.iframe.buttons.close_all_other', true))
                        <a class="dropdown-item" href="#" data-lte-toggle="iframe-close" data-type="all-other">
                            {{ __('adminlte::iframe.btn_close_all_other') }}
                        </a>
                    @endif
                </div>
            </div>

        @elseif(config('adminlte.iframe.buttons.close', true))

            <a class="nav-link bg-danger" href="#" data-lte-toggle="iframe-close">
                {{ __('adminlte::iframe.btn_close') }}
            </a>

        @endif

        {{-- Scroll Left Button --}}
        @if(config('adminlte.iframe.buttons.scroll_left', true))
            <a class="nav-link bg-body-secondary" href="#" data-lte-toggle="iframe-scrollleft">
                <i class="bi bi-chevron-double-left"></i>
            </a>
        @endif

        {{-- Tab List --}}
        <ul class="navbar-nav" role="tablist">

            {{-- Default Tab --}}
            @if(! empty(config('adminlte.iframe.default_tab.url')))
                <li class="nav-item active d-flex align-items-center" role="presentation">
                    <a id="tab-default" class="nav-link active" data-lte-toggle="iframe-tab" href="#panel-default"
                       role="tab" aria-controls="panel-default" aria-selected="true">
                        {{ config('adminlte.iframe.default_tab.title') ?: __('adminlte::iframe.tab_home') }}
                    </a>
                    <button type="button" class="btn-close btn-iframe-close me-2"
                            data-lte-toggle="iframe-close" data-type="only-this"
                            aria-label="{{ __('adminlte::iframe.btn_close_active') }}"></button>
                </li>
            @endif

        </ul>

        {{-- Scroll Right Button --}}
        @if(config('adminlte.iframe.buttons.scroll_right', true))
            <a class="nav-link bg-body-secondary" href="#" data-lte-toggle="iframe-scrollright">
                <i class="bi bi-chevron-double-right"></i>
            </a>
        @endif

        {{-- Fullscreen Button --}}
        @if(config('adminlte.iframe.buttons.fullscreen', true))
            <a class="nav-link bg-body-secondary" href="#" data-lte-toggle="iframe-fullscreen">
                <i class="bi bi-arrows-fullscreen"></i>
            </a>
        @endif

    </div>

    {{-- IFrame Tab Content --}}
    <div class="tab-content">

        {{-- Loading Overlay --}}
        <div class="tab-loading">
            <div>
                <h2 class="display-4 text-center">
                    <i class="bi bi-arrow-repeat text-secondary"></i>
                    <br>
                    {{ __('adminlte::iframe.tab_loading') }}
                </h2>
            </div>
        </div>

        {{-- Default Tab Content --}}
        @if(! empty(config('adminlte.iframe.default_tab.url')))
            <div id="panel-default" class="tab-pane fade" role="tabpanel" aria-labelledby="tab-default">
                <iframe src="{{ config('adminlte.iframe.default_tab.url') }}"
                        title="{{ config('adminlte.iframe.default_tab.title') ?: __('adminlte::iframe.tab_home') }}"></iframe>
            </div>
        @endif

        {{-- Empty Tab --}}
        <div class="tab-empty">
            <h2 class="display-4 text-center">
                {{ __('adminlte::iframe.tab_empty') }}
            </h2>
        </div>

    </div>

</main>

{{-- IFrame mode styles and behavior --}}
@include('adminlte::partials.cwrapper.iframe-assets')

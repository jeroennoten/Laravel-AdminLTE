{{-- IFrame Content Wrapper --}}
<div class="content-wrapper iframe-mode {{ config('adminlte.classes_content_wrapper', '') }}" data-widget="iframe"
     data-auto-show-new-tab="{{ config('adminlte.iframe.options.auto_show_new_tab', true) }}"
     data-loading-screen="{{ config('adminlte.iframe.options.loading_screen', true) }}"
     data-use-navbar-items="{{ config('adminlte.iframe.options.use_navbar_items', true) }}">

    {{-- IFrame Navbar --}}
    <div class="nav navbar navbar-expand navbar-white navbar-light border-bottom p-0">

        {{-- Close Buttons --}}
        @if(config('adminlte.iframe.buttons.close_all', true) || config('adminlte.iframe.buttons.close_all_other', true))

            <div class="nav-item dropdown">
                <a class="nav-link bg-danger dropdown-toggle" data-toggle="dropdown" href="#"
                   role="button" aria-haspopup="true" aria-expanded="false">
                    {{ __('adminlte::iframe.btn_close') }}
                </a>
                <div class="dropdown-menu mt-0">
                    @if(config('adminlte.iframe.buttons.close', false))
                        <a class="dropdown-item" href="#" data-widget="iframe-close">
                            {{ __('adminlte::iframe.btn_close_active') }}
                        </a>
                    @endif
                    @if(config('adminlte.iframe.buttons.close_all', true))
                        <a class="dropdown-item" href="#" data-widget="iframe-close" data-type="all">
                            {{ __('adminlte::iframe.btn_close_all') }}
                        </a>
                    @endif
                    @if(config('adminlte.iframe.buttons.close_all_other', true))
                        <a class="dropdown-item" href="#" data-widget="iframe-close" data-type="all-other">
                            {{ __('adminlte::iframe.btn_close_all_other') }}
                        </a>
                    @endif
                </div>
            </div>

        @elseif(config('adminlte.iframe.buttons.close', false))

            <a class="nav-link bg-danger" href="#" data-widget="iframe-close">
                 {{ __('adminlte::iframe.btn_close') }}
            </a>

        @endif

        {{-- Scroll Left Button --}}
        @if(config('adminlte.iframe.buttons.scroll_left', true))
            <a class="nav-link bg-light" href="#" data-widget="iframe-scrollleft">
                <i class="fas fa-angle-double-left"></i>
            </a>
        @endif

        {{-- Tab List --}}
        <ul class="navbar-nav" role="tablist">

            {{-- Default Tab --}}
            @if(! empty(config('adminlte.iframe.default_tab.url')))
                <li class="nav-item active" role="presentation">
                    <a id="tab-default" class="nav-link active" data-toggle="row" href="#panel-default"
                       role="tab" aria-controls="panel-default" aria-selected="true">
                        {{-- TODO: How to translate the configured title? --}}
                        {{ config('adminlte.iframe.default_tab.title') ?: __('adminlte::iframe.tab_home') }}
                    </a>
                </li>
            @endif

        </ul>

        {{-- Scroll Right Button --}}
        @if(config('adminlte.iframe.buttons.scroll_right', true))
            <a class="nav-link bg-light" href="#" data-widget="iframe-scrollright">
                <i class="fas fa-angle-double-right"></i>
            </a>
        @endif

        {{-- Fullscreen Button --}}
        @if(config('adminlte.iframe.buttons.fullscreen', true))
            <a class="nav-link bg-light" href="#" data-widget="iframe-fullscreen">
                <i class="fas fa-expand"></i>
            </a>
        @endif

    </div>

    {{-- IFrame Tab Content --}}
    <div class="tab-content">

        {{-- Default Tab Content --}}
        @if(! empty(config('adminlte.iframe.default_tab.url')))
            <div id="panel-default" class="tab-pane fade active show" role="tabpanel" aria-labelledby="tab-default">
                {{-- TODO: Height can't be harcoded, because it depends on user screen size --}}
                {{-- TODO: This should be fixed on the underlying AdminLTE package --}}
                <iframe src="{{ config('adminlte.iframe.default_tab.url') }}" style="height: 671px;"></iframe>
            </div>
        @endif

        {{-- Empty Tab --}}
        <div class="tab-empty">
            <h2 class="display-4 text-center">
                {{ __('adminlte::iframe.tab_empty') }}
            </h2>
        </div>

        {{-- Loading Overlay --}}
        <div class="tab-loading">
        <div>
            <h2 class="display-4 text-center">
                <i class="fa fa-sync fa-spin text-secondary"></i>
                <br/>
                {{ __('adminlte::iframe.tab_loading') }}
            </h2>
        </div>
        </div>

    </div>

</div>

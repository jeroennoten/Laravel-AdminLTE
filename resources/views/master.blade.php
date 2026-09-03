@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')
@inject('assetHelper', 'JeroenNoten\LaravelAdminLte\Helpers\AssetHelper')

@php
    // Resolve the Laravel asset bundling mode.

    $bundling = config('adminlte.laravel_asset_bundling', false);

    // When the user bundles the assets on its own, the AdminLTE core stylesheet
    // and script are expected to be part of the generated bundle.

    $bundlesAdminlte = in_array($bundling, ['mix', 'vite', 'vite_js_only'], true);

    // Resolve the location of every base asset. A null value means the asset
    // is disabled on the configuration file.

    $fontsCss = $assetHelper->fontsCss();
    $overlayScrollbarsCss = $assetHelper->overlayScrollbarsCss();
    $bootstrapIconsCss = $assetHelper->bootstrapIconsCss();
    $adminlteCss = $bundlesAdminlte ? null : $assetHelper->adminlteCss();
    $colorsCss = $bundlesAdminlte ? null : $assetHelper->colorsCss();

    $overlayScrollbarsJs = $assetHelper->overlayScrollbarsJs();
    $bootstrapJs = $assetHelper->bootstrapJs();
    $adminlteJs = $bundlesAdminlte ? null : $assetHelper->adminlteJs();

    // Bridge the Livewire navigation events to the AdminLTE lifecycle. The
    // template binds Turbo on its own, but it knows nothing about Livewire.

    $spaNavigation = config('adminlte.spa_navigation', true) !== false;

    // Resolve the color mode setup. The 'authored' color mode is the one the
    // page declares by itself, the 'auto' mode declares nothing and lets the
    // client resolve the mode from the OS preference.

    $colorMode = $layoutHelper->getColorMode();
    $authoredColorMode = $colorMode === 'auto' ? null : $colorMode;
    $rememberColorMode = (bool) config('adminlte.color_mode.remember', true);

    // The OverlayScrollbars setup only makes sense when there is a sidebar.

    $setupScrollbars = $overlayScrollbarsJs && ! $layoutHelper->isLayoutTopnavEnabled();

    // Extra options for the OverlayScrollbars instance of the sidebar. They
    // are merged into the 'scrollbars' object of its setup, so they may also
    // override the ones the dedicated options above resolve.

    $scrollbarOptions = config('adminlte.sidebar_scrollbar_options', []);

    $scrollbarExtraOptions = is_array($scrollbarOptions) && ! empty($scrollbarOptions)
        ? "\n".str_repeat(' ', 32).'...'.json_encode(
            $scrollbarOptions,
            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
        ).','
        : '';

    // The viewport width (in pixels) at or below which the OverlayScrollbars
    // instance is not created, so touch scrolling is not disturbed.

    $scrollbarDisableBelow = config('adminlte.sidebar_scrollbar_disable_below', 992);

    $scrollbarDisableBelow = is_numeric($scrollbarDisableBelow)
        ? 0 + $scrollbarDisableBelow
        : 992;

    // The theme (a class name) of the sidebar scrollbars. OverlayScrollbars
    // expects a string here, anything else would be dropped by the plugin
    // together with the rest of its 'scrollbars' options.

    $scrollbarTheme = config('adminlte.sidebar_scrollbar_theme', 'os-theme-light');

    $scrollbarTheme = is_string($scrollbarTheme) && trim($scrollbarTheme) !== ''
        ? trim($scrollbarTheme)
        : 'os-theme-light';

    // The event that hides the sidebar scrollbars again. Only the tokens of
    // the OverlayScrollbars plugin are accepted, so an unsupported one does
    // not end up rejected (and reported on the console) by the plugin.

    $scrollbarAutoHide = config('adminlte.sidebar_scrollbar_auto_hide', 'leave');

    $scrollbarAutoHide = in_array($scrollbarAutoHide, ['never', 'scroll', 'leave', 'move'], true)
        ? $scrollbarAutoHide
        : 'leave';

    // The 'crossorigin' attribute is only required on the assets served from
    // an external origin (usually a CDN).

    $crossOrigin = static function ($url) {
        return preg_match('#^(https?:)?//#', (string) $url) ? ' crossorigin="anonymous"' : '';
    };
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {!! $layoutHelper->makeHtmlData() !!}>

<head>

    {{-- Base Meta Tags --}}
    <meta charset="utf-8">

    @if(config('adminlte.color_mode.no_flash_script', true))
        {{-- Theme Init (prevents a flash of the incorrect color mode on load) --}}
        <script>
            (() => {
                'use strict';
                const root = document.documentElement;

                // Applications with their own theming opt out of AdminLTE's
                // color mode entirely, here as well as in the bundle.
                if (root.getAttribute('data-lte-color-mode') === 'off') {
                    return;
                }

                const STORAGE_KEY = 'lte-theme';
                const authored = @json($authoredColorMode);
                let stored = null;

                @if($rememberColorMode)
                    try {
                        stored = localStorage.getItem(STORAGE_KEY);
                    } catch (e) {
                        // localStorage may be unavailable (private mode, sandboxed iframe).
                    }
                @endif

                // Mirror the precedence in color-mode.ts: the visitor's stored
                // choice wins, then a theme this page declared itself, then the
                // OS preference.
                let resolved = 'light';

                if (stored === 'dark' || stored === 'light') {
                    resolved = stored;
                } else if (authored === 'dark' || authored === 'light') {
                    resolved = authored;
                } else if (globalThis.matchMedia('(prefers-color-scheme: dark)').matches) {
                    resolved = 'dark';
                }

                root.setAttribute('data-bs-theme', resolved);
                root.style.colorScheme = resolved;

                // Flag values computed here, so the bundle does not mistake
                // them for a theme the page declared and stop following the OS
                // preference.
                if (resolved !== authored) {
                    root.setAttribute('data-lte-theme-resolved', '');
                }
            })();
        </script>
    @endif

    {{-- Accessibility Meta Tags --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    @php($lightThemeColor = config('adminlte.color_mode.theme_color.light'))
    @php($darkThemeColor = config('adminlte.color_mode.theme_color.dark'))

    @if($lightThemeColor)
        <meta name="theme-color" content="{{ $lightThemeColor }}" media="(prefers-color-scheme: light)">
    @endif

    @if($darkThemeColor)
        <meta name="theme-color" content="{{ $darkThemeColor }}" media="(prefers-color-scheme: dark)">
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')

    {{-- Title --}}
    <title>
        @yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'AdminLTE 4'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))
    </title>

    {{-- Custom stylesheets (pre AdminLTE) --}}
    @yield('adminlte_css_pre')

    {{-- Web Fonts --}}
    @isset($fontsCss)
        <link rel="stylesheet" href="{{ $fontsCss }}"{!! $crossOrigin($fontsCss) !!}>
    @endisset

    {{-- Third Party Plugin (OverlayScrollbars) --}}
    @isset($overlayScrollbarsCss)
        <link rel="stylesheet" href="{{ $overlayScrollbarsCss }}"{!! $crossOrigin($overlayScrollbarsCss) !!}>
    @endisset

    {{-- Third Party Plugin (Bootstrap Icons) --}}
    @isset($bootstrapIconsCss)
        <link rel="stylesheet" href="{{ $bootstrapIconsCss }}"{!! $crossOrigin($bootstrapIconsCss) !!}>
    @endisset

    {{-- Base Stylesheets (depends on the Laravel asset bundling tool) --}}
    @switch($bundling)
        @case('mix')
            <link rel="stylesheet" href="{{ mix(config('adminlte.laravel_css_path', 'css/app.css')) }}">
        @break

        @case('vite')
            @vite([config('adminlte.laravel_css_path', 'resources/css/app.css'), config('adminlte.laravel_js_path', 'resources/js/app.js')])
        @break

        @case('vite_js_only')
            @vite(config('adminlte.laravel_js_path', 'resources/js/app.js'))
        @break

        @default
            {{-- Required Plugin (AdminLTE) --}}
            @isset($adminlteCss)
                <link rel="stylesheet" href="{{ $adminlteCss }}">
            @endisset

            {{-- Optional AdminLTE extended colors --}}
            @isset($colorsCss)
                <link rel="stylesheet" href="{{ $colorsCss }}">

                {{-- The palette stylesheet provides no alert and no button
                     families, so they are generated from its own tokens. --}}
                @include('adminlte::partials.common.extended-colors')
            @endisset
    @endswitch

    {{-- Extra Configured Plugins Stylesheets --}}
    @include('adminlte::plugins', ['type' => 'css'])

    {{-- Livewire Styles --}}
    @if(config('adminlte.livewire'))
        @livewireStyles
    @endif

    {{-- Custom CSS variables --}}
    @include('adminlte::partials.common.css-variables')

    {{-- Custom Stylesheets (post AdminLTE) --}}
    @yield('adminlte_css')

    {{-- Favicon --}}
    @if(config('adminlte.use_ico_only'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
    @elseif(config('adminlte.use_full_favicon'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicons/android-icon-192x192.png') }}">
        <link rel="manifest" crossorigin="use-credentials" href="{{ asset('favicons/manifest.json') }}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('favicons/ms-icon-144x144.png') }}">
    @endif

</head>

<body class="@yield('classes_body')" @yield('body_data')>

    {{-- Body Content --}}
    @yield('body')

    {{-- Third Party Plugin (OverlayScrollbars) --}}
    @isset($overlayScrollbarsJs)
        <script src="{{ $overlayScrollbarsJs }}"{!! $crossOrigin($overlayScrollbarsJs) !!}></script>
    @endisset

    {{-- Required Plugin (Bootstrap 5) --}}
    @isset($bootstrapJs)
        <script src="{{ $bootstrapJs }}"{!! $crossOrigin($bootstrapJs) !!}></script>
    @endisset

    {{-- Base Scripts (depends on the Laravel asset bundling tool) --}}
    @switch($bundling)
        @case('mix')
            <script src="{{ mix(config('adminlte.laravel_js_path', 'js/app.js')) }}"></script>
        @break

        @case('vite')
        @case('vite_js_only')
        @break

        @default
            {{-- Required Plugin (AdminLTE) --}}
            @isset($adminlteJs)
                <script src="{{ $adminlteJs }}"></script>
            @endisset
    @endswitch

    {{-- Lifecycle helpers used by the inline scripts of the package --}}
    @include('adminlte::partials.common.lifecycle')

    @if($setupScrollbars)
        {{-- OverlayScrollbars Configuration (main sidebar) --}}
        <script>
            (() => {
                'use strict';
                const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
                const Default = {
                    scrollbarTheme: @json($scrollbarTheme),
                    scrollbarAutoHide: @json($scrollbarAutoHide),
                    scrollbarClickScroll: @json((bool) config('adminlte.sidebar_scrollbar_click_scroll', true)),
                };

                window._AdminLTE_Ready(function () {
                    const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);

                    // Disable OverlayScrollbars on mobile devices to prevent
                    // touch interference.
                    const isMobile = window.innerWidth <= @json($scrollbarDisableBelow);

                    if (
                        sidebarWrapper &&
                        typeof OverlayScrollbarsGlobal !== 'undefined' &&
                        OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
                        !isMobile
                    ) {
                        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                            scrollbars: {
                                theme: Default.scrollbarTheme,
                                autoHide: Default.scrollbarAutoHide,
                                clickScroll: Default.scrollbarClickScroll,{!! $scrollbarExtraOptions !!}
                            },
                        });
                    }
                });
            })();
        </script>
    @endif

    {{-- Extra Configured Plugins Scripts --}}
    @include('adminlte::plugins', ['type' => 'js'])

    {{-- Livewire Script --}}
    @if(config('adminlte.livewire'))
        @livewireScripts
    @endif

    {{-- Custom Scripts --}}
    @yield('adminlte_js')

</body>

</html>

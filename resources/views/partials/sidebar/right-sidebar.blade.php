@php
    // The AdminLTE v3 control sidebar was removed in v4. So, the right sidebar
    // is now built on top of the Bootstrap 5.3 offcanvas component.

    $rsPlacement = config('adminlte.right_sidebar_placement', 'end');

    if (! in_array($rsPlacement, ['start', 'end', 'top', 'bottom'])) {
        $rsPlacement = 'end';
    }

    $rsTheme = config('adminlte.right_sidebar_theme');

    if (! in_array($rsTheme, ['light', 'dark'])) {
        $rsTheme = null;
    }

    $rsTitle = config('adminlte.right_sidebar_title');
    $rsHasTitle = is_string($rsTitle) && $rsTitle !== '';
    $rsBackdrop = config('adminlte.right_sidebar_backdrop', true) ? 'true' : 'false';
    $rsScroll = config('adminlte.right_sidebar_scroll', false) ? 'true' : 'false';
@endphp

<div class="offcanvas offcanvas-{{ $rsPlacement }} {{ config('adminlte.right_sidebar_classes', '') }}"
     id="adminlte-right-sidebar" tabindex="-1"
     aria-labelledby="adminlte-right-sidebar-title"
     @isset($rsTheme) data-bs-theme="{{ $rsTheme }}" @endisset
     data-bs-backdrop="{{ $rsBackdrop }}" data-bs-scroll="{{ $rsScroll }}">

    {{-- Right sidebar header --}}
    <div class="offcanvas-header">

        <h5 class="offcanvas-title @unless($rsHasTitle) visually-hidden @endunless"
            id="adminlte-right-sidebar-title">
            {{ $rsHasTitle ? $rsTitle : config('adminlte.title', 'AdminLTE 4') }}
        </h5>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                aria-label="{{ __('adminlte::adminlte.close') }}"></button>

    </div>

    {{-- Right sidebar content --}}
    <div class="offcanvas-body">
        @yield('right_sidebar')
    </div>

</div>

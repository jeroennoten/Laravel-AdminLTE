@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@php
    $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home');
    $dashboard_url = $layoutHelper->makeUrl($dashboard_url);

    $logoImg = asset(config('adminlte.logo_img', 'vendor/adminlte/dist/assets/img/AdminLTELogo.png'));
    $logoImgXl = asset(config('adminlte.logo_img_xl'));
    $logoImgAlt = config('adminlte.logo_img_alt', 'Admin Logo');
    $brandClass = config('adminlte.classes_brand', '');

    // On AdminLTE v4 the logo switch places both images absolutely, and the
    // related offsets are only defined for the 'brand-image-xs' and the
    // 'brand-image-xl' size classes. So, ensure one of them is always present
    // by replacing (or completing) the generic 'brand-image' class.

    $sizeClass = static function ($classes, $size) {
        $classes = trim((string) $classes);

        if (preg_match('/\bbrand-image-(xs|xl)\b/', $classes)) {
            return $classes;
        }

        if (preg_match('/\bbrand-image\b/', $classes)) {
            return preg_replace('/\bbrand-image\b/', $size, $classes);
        }

        return trim($classes.' '.$size);
    };

    $logoImgClass = $sizeClass(
        config('adminlte.logo_img_class', 'brand-image opacity-75 shadow'), 'brand-image-xl'
    );

    $logoImgXlClass = $sizeClass(
        config('adminlte.logo_img_xl_class', 'brand-image-xs opacity-75'), 'brand-image-xs'
    );
@endphp

@if($layoutHelper->isLayoutTopnavEnabled())

    {{-- Navbar Brand (topnav layout, the logo switch requires a sidebar) --}}
    <a href="{{ $dashboard_url }}" class="navbar-brand d-flex align-items-center {{ $brandClass }}">
        <img src="{{ $logoImgXl }}" alt="{{ $logoImgAlt }}" height="30" class="opacity-75">
    </a>

@else

    {{-- Sidebar Brand (logo switch variant) --}}
    <div class="sidebar-brand">
        <a href="{{ $dashboard_url }}" class="brand-link logo-switch {{ $brandClass }}">

            {{-- Small brand logo (shown when the sidebar is collapsed) --}}
            <img src="{{ $logoImg }}" alt="{{ $logoImgAlt }}" class="{{ $logoImgClass }} logo-xs">

            {{-- Large brand logo (shown when the sidebar is expanded) --}}
            <img src="{{ $logoImgXl }}" alt="{{ $logoImgAlt }}" class="{{ $logoImgXlClass }} logo-xl">

        </a>
    </div>

@endif

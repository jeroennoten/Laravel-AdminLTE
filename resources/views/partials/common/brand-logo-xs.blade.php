@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@php
    $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home');
    $dashboard_url = $layoutHelper->makeUrl($dashboard_url);

    $logoImg = asset(config('adminlte.logo_img', 'vendor/adminlte/dist/assets/img/AdminLTELogo.png'));
    $logoImgAlt = config('adminlte.logo_img_alt', 'Admin Logo');
    $logoImgClass = config('adminlte.logo_img_class', 'brand-image opacity-75 shadow');
    $logoText = config('adminlte.logo', '<b>Admin</b>LTE');
    $brandClass = config('adminlte.classes_brand', '');
    $brandTextClass = config('adminlte.classes_brand_text', 'fw-light');
@endphp

@if($layoutHelper->isLayoutTopnavEnabled())

    {{-- Navbar Brand (topnav layout) --}}
    <a href="{{ $dashboard_url }}" class="navbar-brand d-flex align-items-center {{ $brandClass }}">

        {{-- Brand logo --}}
        <img src="{{ $logoImg }}" alt="{{ $logoImgAlt }}" width="30" height="30"
             class="{{ $logoImgClass }} me-2">

        {{-- Brand text --}}
        <span class="{{ $brandTextClass }}">{!! $logoText !!}</span>

    </a>

@else

    {{-- Sidebar Brand --}}
    <div class="sidebar-brand">
        <a href="{{ $dashboard_url }}" class="brand-link {{ $brandClass }}">

            {{-- Brand logo --}}
            <img src="{{ $logoImg }}" alt="{{ $logoImgAlt }}" class="{{ $logoImgClass }}">

            {{-- Brand text --}}
            <span class="brand-text {{ $brandTextClass }}">{!! $logoText !!}</span>

        </a>
    </div>

@endif

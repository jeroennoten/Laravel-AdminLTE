@php( $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home') )

@if (config('adminlte.use_route_url', false))
    @php( $dashboard_url = $dashboard_url ? route($dashboard_url) : '' )
@else
    @php( $dashboard_url = $dashboard_url ? url($dashboard_url) : '' )
@endif

<a href="{{ $dashboard_url }}"
    @if(config('adminlte.layout_topnav') || View::getSection('layout_topnav'))
        class="navbar-brand logo-switch"
    @else
        class="brand-link logo-switch"
    @endif>

    {{-- Small brand logo --}}
    <img src="{{ asset(config('adminlte.logo_img', 'vendor/adminlte/dist/img/AdminLTELogo.png')) }}"
         alt="{{ config('adminlte.logo_img_alt', 'AdminLTE') }}"
         class="{{ config('adminlte.logo_img_class', 'brand-image-xl') }} logo-xs">

    {{-- Large brand logo --}}
    <img src="{{ asset(config('adminlte.logo_img_xl')) }}"
         alt="{{ config('adminlte.logo_img_alt', 'AdminLTE') }}"
         class="{{ config('adminlte.logo_img_xl_class', 'brand-image-xs') }} logo-xl">

</a>

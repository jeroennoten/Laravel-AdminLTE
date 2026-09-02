@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')
@inject('sidebarItemHelper', 'JeroenNoten\LaravelAdminLte\Helpers\SidebarItemHelper')

@php
    // The HTML id of the sidebar menu. The sidebar search box targets it.
    $sidebarMenuId = 'adminlte-sidebar-menu';

    // In AdminLTE v4, the search box lives outside of the scrollable menu
    // wrapper. So, we split the search items from the regular menu items.
    $sidebarMenu = $adminlte->menu('sidebar');

    $sidebarSearchItems = array_filter($sidebarMenu, function ($item) use ($sidebarItemHelper) {
        return $sidebarItemHelper->isSearch($item);
    });

    $sidebarMenuItems = array_filter($sidebarMenu, function ($item) use ($sidebarItemHelper) {
        return ! $sidebarItemHelper->isSearch($item);
    });
@endphp

<aside class="{{ $layoutHelper->makeSidebarWrapperClasses() }}" {!! $layoutHelper->makeSidebarData() !!}>

    {{-- Sidebar brand logo --}}
    @if(config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar search box (kept outside of the sidebar wrapper) --}}
    @foreach($sidebarSearchItems as $item)
        @include('adminlte::partials.sidebar.menu-item')
    @endforeach

    {{-- Sidebar menu --}}
    <div class="sidebar-wrapper">
        <nav class="mt-2" aria-label="{{ config('adminlte.sidebar_nav_aria_label') ?: __('adminlte::adminlte.main_navigation') }}">
            <ul class="{{ $layoutHelper->makeSidebarNavClasses() }}"
                id="{{ $sidebarMenuId }}"
                data-lte-toggle="treeview"
                @if(config('adminlte.sidebar_nav_animation_speed', 300) != 300)
                    data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed', 300) }}"
                @endif
                @if(! config('adminlte.sidebar_nav_accordion', true))
                    data-accordion="false"
                @endif>
                {{-- Configured sidebar links --}}
                @each('adminlte::partials.sidebar.menu-item', $sidebarMenuItems, 'item')
            </ul>
        </nav>
    </div>

</aside>

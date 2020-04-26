@if((isset($item['topnav_user']) && $item['topnav_user']))
  @include('adminlte::partials.menuitems.menu-item-top-nav', $item)
@endif

@if((config('adminlte.layout_topnav') or (isset($item['topnav']) && $item['topnav'])) && (!isset($item['topnav_right']) || (isset($item['topnav_right']) && !$item['topnav_right'])))
  @include('adminlte::partials.menu-item-top-nav', $item)
@endif

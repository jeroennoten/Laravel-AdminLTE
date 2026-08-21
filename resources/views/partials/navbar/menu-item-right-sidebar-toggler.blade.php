{{--
    Right sidebar toggler. On AdminLTE v4 the old control sidebar no longer
    exists, the right sidebar is now a Bootstrap 5 offcanvas panel with the
    'adminlte-right-sidebar' identifier.
--}}

@php($rsTitle = config('adminlte.right_sidebar_title'))
@php($rsLabel = $rsTitle ?: (Lang::has('adminlte::adminlte.toggle_right_sidebar') ? __('adminlte::adminlte.toggle_right_sidebar') : 'Toggle right sidebar'))

<li class="nav-item">
    <a class="nav-link" href="#" role="button"
       data-bs-toggle="offcanvas" data-bs-target="#adminlte-right-sidebar"
       aria-controls="adminlte-right-sidebar" aria-label="{{ $rsLabel }}">
        <i class="{{ config('adminlte.right_sidebar_icon', 'bi bi-gear') }}"></i>
    </a>
</li>

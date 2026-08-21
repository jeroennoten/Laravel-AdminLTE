{{--
    Left sidebar toggler (AdminLTE v4 PushMenu). The v4 PushMenu plugin reads
    its configuration (persistence and breakpoint) from the .app-sidebar
    element, so the legacy 'sidebar_collapse_*' data attributes are no longer
    placed on this toggler.
--}}

<li class="nav-item">
    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"
       aria-label="{{ __('adminlte::adminlte.toggle_navigation') }}">
        <i class="bi bi-list"></i>
        <span class="visually-hidden">{{ __('adminlte::adminlte.toggle_navigation') }}</span>
    </a>
</li>

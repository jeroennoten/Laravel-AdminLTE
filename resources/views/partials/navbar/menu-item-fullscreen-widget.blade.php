{{-- Fullscreen toggle widget (AdminLTE v4 FullScreen plugin) --}}

@php($fsLabel = Lang::has('adminlte::adminlte.toggle_fullscreen') ? __('adminlte::adminlte.toggle_fullscreen') : 'Toggle fullscreen')

<li class="nav-item">
    <a class="nav-link" href="#" role="button" data-lte-toggle="fullscreen"
       aria-label="{{ $fsLabel }}">
        <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
        <i data-lte-icon="minimize" class="bi bi-fullscreen-exit d-none"></i>
    </a>
</li>

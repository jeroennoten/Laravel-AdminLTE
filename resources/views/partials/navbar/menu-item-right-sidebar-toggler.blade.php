<li class="nav-item">
    <a class="nav-link" href="#" data-widget="control-sidebar"
        @if(!config('adminlte.right_sidebar_slide'))
            data-controlsidebar-slide="false"
        @endif
        @if(config('adminlte.right_sidebar_scrollbar_theme', 'os-theme-light') != 'os-theme-light')
            data-scrollbar-theme="{{ config('adminlte.right_sidebar_scrollbar_theme') }}"
        @endif
        @if(config('adminlte.right_sidebar_scrollbar_auto_hide', 'l') != 'l')
            data-scrollbar-auto-hide="{{ config('adminlte.right_sidebar_scrollbar_auto_hide') }}"
        @endif>
        <i class="{{ config('adminlte.right_sidebar_icon') }}"></i>
    </a>
</li>
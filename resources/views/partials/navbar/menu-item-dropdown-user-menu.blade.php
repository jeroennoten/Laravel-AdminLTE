@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

{{--
    Resolve the data of the authenticated user. Note the 'adminlte_*' methods
    are optional additions to the user model, so they are only called when the
    model provides them, otherwise enabling one of the related options would
    break every page of the panel.
--}}
@php
    $umUser = Auth::user();

    $umValueOf = static function ($method) use ($umUser) {
        return is_object($umUser) && method_exists($umUser, $method)
            ? $umUser->{$method}()
            : null;
    };

    $umName = $umUser->name ?? '';
    $umImage = config('adminlte.usermenu_image') ? $umValueOf('adminlte_image') : null;
    $umDesc = config('adminlte.usermenu_desc') ? $umValueOf('adminlte_desc') : null;

    $logout_url = View::getSection('logout_url') ?? config('adminlte.logout_url', 'logout');
    $profile_url = View::getSection('profile_url') ?? config('adminlte.profile_url', false);

    if (config('adminlte.usermenu_profile_url', false)) {
        $profile_url = $umValueOf('adminlte_profile_url');
    }

    $profile_url = $layoutHelper->makeUrl($profile_url);
    $logout_url = $layoutHelper->makeUrl($logout_url);
@endphp

{{--
    Setup the user menu header classes. The legacy 'bg-{color}' values are
    translated to the Bootstrap 5.3 'text-bg-{color}' helpers, so the header
    text keeps a proper contrast color on AdminLTE v4.
--}}
@php
    $umBsColors = [
        'primary', 'secondary', 'success', 'danger', 'warning', 'info',
        'light', 'dark',
    ];

    $umHeaderCfg = config('adminlte.usermenu_header_class', 'bg-primary');
    $umHeaderClass = collect(preg_split('/\s+/', trim((string) $umHeaderCfg)))
        ->map(function ($class) use ($umBsColors) {
            $color = str_starts_with($class, 'bg-') ? substr($class, 3) : null;

            return in_array($color, $umBsColors, true) ? "text-bg-{$color}" : $class;
        })
        ->filter()
        ->implode(' ');
@endphp

<li class="nav-item dropdown user-menu">

    {{-- User menu toggler --}}
    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
       aria-expanded="false">
        @if($umImage)
            <img src="{{ $umImage }}"
                 class="user-image rounded-circle shadow"
                 alt="{{ $umName }}">
        @endif
        <span @if($umImage) class="d-none d-md-inline" @endif>
            {{ $umName }}
        </span>
    </a>

    {{-- User menu dropdown --}}
    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">

        {{-- User menu header --}}
        @if(!View::hasSection('usermenu_header') && config('adminlte.usermenu_header'))
            <li class="user-header {{ $umHeaderClass }}"
                @if(! $umImage) style="min-height:auto" @endif>
                @if($umImage)
                    <img src="{{ $umImage }}"
                         class="rounded-circle shadow"
                         alt="{{ $umName }}">
                @endif
                <p class="@if(! $umImage) mt-0 @endif">
                    {{ $umName }}
                    @if($umDesc)
                        <small>{{ $umDesc }}</small>
                    @endif
                </p>
            </li>
        @else
            @yield('usermenu_header')
        @endif

        {{-- Configured user menu links --}}
        @each('adminlte::partials.navbar.dropdown-item', $adminlte->menu("navbar-user"), 'item')

        {{-- User menu body --}}
        @hasSection('usermenu_body')
            <li class="user-body">
                @yield('usermenu_body')
            </li>
        @endif

        {{-- User menu footer --}}
        <li class="user-footer">
            @if($profile_url)
                <a href="{{ $profile_url }}" class="btn btn-outline-secondary">
                    <i class="bi bi-person me-1"></i>
                    {{ __('adminlte::menu.profile') }}
                </a>
            @endif
            <a class="btn btn-outline-danger float-end @if(!$profile_url) w-100 @endif"
               href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-power me-1"></i>
                {{ __('adminlte::adminlte.log_out') }}
            </a>
            <form id="logout-form" action="{{ $logout_url }}" method="POST" style="display: none;">
                @if(config('adminlte.logout_method'))
                    {{ method_field(config('adminlte.logout_method')) }}
                @endif
                {{ csrf_field() }}
            </form>
        </li>

    </ul>

</li>

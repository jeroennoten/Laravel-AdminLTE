{{--
    Nested dropdown submenu. Bootstrap 5 has no submenu plugin, so the nesting
    relies on the AdminLTE v4 '.dropdown-submenu' / '.dropdown-hover' styles
    (open on hover, positioned by CSS). The 'data-bs-toggle' attribute adds the
    click/keyboard support, and 'data-bs-display="static"' keeps Popper away so
    the CSS placement of the nested menu is preserved.
--}}

@once
@push('css')
<style>
    /* AdminLTE v4 sets 'overflow: hidden' on every dropdown menu, which clips
       the nested submenus (they are placed at 'left: 100%' of their parent).
       So, the clipping is lifted on the menus that contain a submenu. */
    .dropdown-menu:has(> .dropdown-submenu) {
        overflow: visible;
    }
</style>
@endpush
@endonce

<li @isset($item['id']) id="{{ $item['id'] }}" @endisset class="dropdown-submenu dropdown-hover">

    {{-- Menu toggler --}}
    <a class="dropdown-item dropdown-toggle {{ $item['class'] ?? '' }}" href="#" role="button"
       data-bs-toggle="dropdown" data-bs-display="static" data-bs-auto-close="outside"
       aria-expanded="false" {!! $item['data-compiled'] ?? '' !!}>

        {{-- Icon (optional) --}}
        @isset($item['icon'])
            <i class="{{ $item['icon'] ?? '' }} {{
                isset($item['icon_color']) ? 'text-' . $item['icon_color'] : ''
            }}"></i>
        @endisset

        {{-- Text --}}
        {{ $item['text'] }}

        {{-- Label (optional) --}}
        @isset($item['label'])
            <span class="badge text-bg-{{ $item['label_color'] ?? 'primary' }}">
                {{ $item['label'] }}
            </span>
        @endisset

    </a>

    {{-- Menu items --}}
    <ul class="dropdown-menu shadow">
        @each('adminlte::partials.navbar.dropdown-item', $item['submenu'], 'item')
    </ul>

</li>

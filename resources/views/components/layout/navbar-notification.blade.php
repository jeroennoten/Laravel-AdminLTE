{{-- Navbar notification --}}

@php
    // The badge is announced by assistive technology when it is polled, so the
    // live region is only required on that variant.

    $isPolled = ! is_null($makeUpdateUrl()) && $makeUpdatePeriod() > 0;
@endphp

<li class="{{ $makeListItemClass() }}" id="{{ $id }}">

    {{-- Link --}}
    <a {{ $attributes->merge($makeAnchorDefaultAttrs()) }}>

        {{-- Icon --}}
        <i class="{{ $makeIconClass() }}" aria-hidden="true"></i>

        {{-- Badge. The visually hidden suffix is a sibling of the badge, so
             the periodic update (which rewrites the badge text) keeps it. --}}
        @isset($badgeLabel)
            <span class="{{ $makeBadgeClass() }}"@if($isPolled) aria-live="polite"@endif>{{ $badgeLabel }}</span>
            <span class="visually-hidden">{{ __('adminlte::adminlte.notifications') }}</span>
        @endisset

    </a>

    {{-- Dropdown Menu --}}
    @if($enableDropdownMode)

        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">

            {{-- Custom dropdown content provided by external source --}}
            <div class="adminlte-dropdown-content"></div>

            {{-- Dropdown divider --}}
            <div class="dropdown-divider"></div>

            {{-- Dropdown footer with link --}}
            <a href="{{ $attributes->get('href') ?: '#' }}" class="dropdown-item dropdown-footer">
                @isset($dropdownFooterLabel)
                    {{ $dropdownFooterLabel }}
                @else
                    <i class="bi bi-search"></i>
                @endisset
            </a>

        </div>

    @endif

</li>

{{-- If required, update the notification periodically --}}

@if ($isPolled)
@push('js')
<script>

    window._AdminLTE_Ready(() => {

        const updateNotification = (nLink) => {
            fetch("{{ $makeUpdateUrl() }}")
                .then((response) => response.json())
                .then((data) => nLink.update(data))
                .catch((err) => console.warn('AdminLTE: the notification "{{ $id }}" could not be updated.', err));
        };

        const nLink = new _AdminLTE_NavbarNotification("{{ $id }}");
        updateNotification(nLink);

        setInterval(updateNotification, {{ $makeUpdatePeriod() }}, nLink);

    });

</script>
@endpush
@endif

{{-- Register Javascript utility class for this component --}}

@once
@push('js')
<script>

    class _AdminLTE_NavbarNotification {

        constructor(target)
        {
            this.target = target;
        }

        update(data)
        {
            const t = document.querySelector(`li#${this.target}`);

            if (! t || ! data) {
                return;
            }

            const badge = t.querySelector('.navbar-badge');
            const icon = t.querySelector('.nav-link > i');
            const dropdown = t.querySelector('.adminlte-dropdown-content');

            // Update the badge label.

            if (badge) {
                badge.textContent = (data.label && data.label > 0) ? data.label : '';
            }

            // Update the badge color (Bootstrap 5: bg-* instead of badge-*).

            if (data.label_color && badge) {
                badge.className = badge.className.replace(/\bbg-\S+/g, '');
                badge.classList.add(`bg-${data.label_color}`);
            }

            // Update the icon color.

            if (data.icon_color && icon) {
                icon.className = icon.className.replace(/\btext-\S+/g, '');
                icon.classList.add(`text-${data.icon_color}`);
            }

            // Update the dropdown content.

            if (data.dropdown && dropdown) {
                dropdown.innerHTML = data.dropdown;
            }
        }
    }

</script>
@endpush
@endonce

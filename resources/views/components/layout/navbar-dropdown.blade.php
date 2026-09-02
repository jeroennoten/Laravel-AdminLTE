{{-- Navbar dropdown (AdminLTE v4) --}}

@php
    $hasHeader = isset($headerSlot) || ! empty($header);
    $hasFooter = isset($footerSlot) || ! empty($footer);

    $itemAttrs = ['class' => $makeListItemClass()];

    if (! empty($id)) {
        $itemAttrs['id'] = $id;
    }
@endphp

<li {{ $attributes->merge($itemAttrs) }}>

    {{-- Dropdown toggle --}}
    <a class="{{ $makeToggleClass() }}" href="#" role="button" id="{{ $toggleId }}"
       data-bs-toggle="dropdown" aria-expanded="false"
       @isset($label) aria-label="{{ $label }}" @endisset>

        {{-- Icon --}}
        @isset($icon)
            <i class="{{ $makeIconClass() }}" aria-hidden="true"></i>
        @endisset

        {{-- Text --}}
        @isset($text)
            {{ $text }}
        @endisset

        {{-- Badge --}}
        @isset($badge)
            <span class="{{ $makeBadgeClass() }}">{{ $badge }}</span>
        @endisset

    </a>

    {{-- Dropdown menu --}}
    <div class="{{ $makeMenuClass() }}" aria-labelledby="{{ $toggleId }}">

        {{-- Dropdown header --}}
        @if($hasHeader)
            <span class="dropdown-item dropdown-header">
                @isset($headerSlot)
                    {{ $headerSlot }}
                @else
                    {{ $header }}
                @endisset
            </span>
            <div class="dropdown-divider"></div>
        @endif

        {{-- Dropdown items --}}
        {{ $slot }}

        {{-- Dropdown footer --}}
        @if($hasFooter)
            <div class="dropdown-divider"></div>
            <a href="{{ $footerUrl }}" class="dropdown-item dropdown-footer">
                @isset($footerSlot)
                    {{ $footerSlot }}
                @else
                    {{ $footer }}
                @endisset
            </a>
        @endif

    </div>

</li>

{{-- Register the bridge for the dropdown menu animation --}}

@if($animated)
@once
@push('js')
<script>

    window._AdminLTE_Ready(() => {

        // The '.animated-dropdown-menu' rule of the AdminLTE stylesheet is
        // keyed on an '.open' class over the dropdown wrapper, which is a
        // Bootstrap 4 leftover. Bootstrap 5 flags the shown state with its
        // own events, so the class is mirrored from them.

        const syncOpenState = (event, isOpen) => {

            const toggle = event.target;

            if (! toggle || typeof toggle.closest !== 'function') {
                return;
            }

            const wrapper = toggle.closest('.nav-item.dropdown');

            if (! wrapper || ! wrapper.querySelector('.animated-dropdown-menu')) {
                return;
            }

            wrapper.classList.toggle('open', isOpen);
        };

        window._AdminLTE_Once('navbar-dropdown-animation', () => {
            document.addEventListener('show.bs.dropdown', (e) => syncOpenState(e, true));
            document.addEventListener('hidden.bs.dropdown', (e) => syncOpenState(e, false));
        });

    });

</script>
@endpush
@endonce
@endif

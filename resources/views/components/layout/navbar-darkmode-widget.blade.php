{{-- Navbar color mode widget (AdminLTE v4) --}}

@if($dropdownMode)

    {{-- Color mode selector. The AdminLTE color mode plugin takes care of the
         click events, the icon swapping and the client side persistence. --}}

    <li class="nav-item dropdown adminlte-darkmode-widget">

        <a class="nav-link" href="#" id="adminlte-color-mode" role="button"
           data-bs-toggle="dropdown" aria-expanded="false"
           aria-label="{{ __('adminlte::adminlte.toggle_color_mode') }}">

            <i class="{{ implode(' ', $makeIconDisabledClasses()) }} @if($currentColorMode() !== 'light') d-none @endif"
               data-lte-theme-icon="light"></i>

            <i class="{{ implode(' ', $makeIconEnabledClasses()) }} @if($currentColorMode() !== 'dark') d-none @endif"
               data-lte-theme-icon="dark"></i>

            <i class="{{ implode(' ', $makeIconAutoClasses()) }} @if($currentColorMode() !== 'auto') d-none @endif"
               data-lte-theme-icon="auto"></i>

        </a>

        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminlte-color-mode"
            style="--bs-dropdown-min-width: 8rem;">

            <li>
                <button type="button" class="dropdown-item d-flex align-items-center @if($currentColorMode() === 'light') active @endif"
                        data-bs-theme-value="light" aria-pressed="{{ $currentColorMode() === 'light' ? 'true' : 'false' }}">
                    <i class="{{ implode(' ', $makeIconDisabledClasses()) }} me-2"></i>
                    {{ __('adminlte::adminlte.color_mode_light') }}
                    <i class="bi bi-check-lg ms-auto d-none"></i>
                </button>
            </li>

            <li>
                <button type="button" class="dropdown-item d-flex align-items-center @if($currentColorMode() === 'dark') active @endif"
                        data-bs-theme-value="dark" aria-pressed="{{ $currentColorMode() === 'dark' ? 'true' : 'false' }}">
                    <i class="{{ implode(' ', $makeIconEnabledClasses()) }} me-2"></i>
                    {{ __('adminlte::adminlte.color_mode_dark') }}
                    <i class="bi bi-check-lg ms-auto d-none"></i>
                </button>
            </li>

            <li>
                <button type="button" class="dropdown-item d-flex align-items-center @if($currentColorMode() === 'auto') active @endif"
                        data-bs-theme-value="auto" aria-pressed="{{ $currentColorMode() === 'auto' ? 'true' : 'false' }}">
                    <i class="{{ implode(' ', $makeIconAutoClasses()) }} me-2"></i>
                    {{ __('adminlte::adminlte.color_mode_auto') }}
                    <i class="bi bi-check-lg ms-auto d-none"></i>
                </button>
            </li>

        </ul>

    </li>

@else

    {{-- Legacy toggle. The color mode is not persisted on the browser, the
         preference is stored on the server side instead. --}}

    <li class="nav-item adminlte-darkmode-widget">

        <a class="nav-link" href="#" role="button"
           aria-label="{{ __('adminlte::adminlte.toggle_color_mode') }}">
            <i class="{{ $makeIconClass() }}"></i>
        </a>

    </li>

    @once
    @push('js')
    <script>

        window._AdminLTE_Ready(() => {

            const root = document.documentElement;
            const widget = document.querySelector('li.adminlte-darkmode-widget');
            const widgetIcon = widget.querySelector('i');

            // Get the set of classes to be toggled on the widget icon.

            const iconClasses = [
                ...@json($makeIconEnabledClasses()),
                ...@json($makeIconDisabledClasses())
            ];

            // Add 'click' event listener for the darkmode widget.

            widget.addEventListener('click', (event) => {

                event.preventDefault();

                // Toggle the color mode on the html element (AdminLTE v4 uses
                // the Bootstrap 5.3 native color modes).

                const isDark = root.getAttribute('data-bs-theme') === 'dark';
                const newMode = isDark ? 'light' : 'dark';

                root.setAttribute('data-bs-theme', newMode);
                root.style.colorScheme = newMode;

                // Toggle the classes on the widget icon.

                iconClasses.forEach((c) => widgetIcon.classList.toggle(c));

                // Notify the server to persist the dark mode preference.

                fetch("{{ route('adminlte.darkmode.toggle') }}", {
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    method: 'POST',
                })
                .catch((error) => {
                    console.log(
                        'Failed to notify server that dark mode was toggled',
                        error
                    );
                });
            });

        });

    </script>
    @endpush
    @endonce

@endif

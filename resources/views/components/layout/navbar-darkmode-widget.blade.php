{{-- Navbar darkmode widget --}}

<li class="nav-item adminlte-darkmode-widget">

    <a class="nav-link" href="#" role="button">
        <i class="{{ $makeIconClass() }}"></i>
    </a>

</li>

{{-- Add Javascript listener for the click event --}}

@once
@push('js')
<script>

    $(() => {

        const body = document.querySelector('body');
        const widget = document.querySelector('li.adminlte-darkmode-widget');
        const widgetIcon = widget.querySelector('i');

        // Get the set of classes to be toggled on the widget icon.

        const iconClasses = [
            ...@json($makeIconEnabledClass()),
            ...@json($makeIconDisabledClass())
        ];

        // Add 'click' event listener for the darkmode widget.

        widget.addEventListener('click', () => {

            // Toggle dark-mode class on the body tag.

            body.classList.toggle('dark-mode');

            // Toggle the classes on the widget icon.

            iconClasses.forEach((c) => widgetIcon.classList.toggle(c));

            // TODO: Save the new state on the configuration. We may need
            // to define a controller for this, to catch request from
            // client side and change the configuration value.
        });
    })

</script>
@endpush
@endonce

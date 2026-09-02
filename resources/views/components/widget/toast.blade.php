{{-- Shared toast container. There is one container per screen position, the
     toasts are moved into it by the javascript helper below --}}

@once($makeContainerId())
<div id="{{ $makeContainerId() }}" class="{{ $makeContainerClass() }}"></div>
@endonce

{{-- Toast --}}
<div {{ $attributes->merge($makeToastDefaultAttrs()) }}>

    @if($hasHeader())

        {{-- Toast header --}}
        <div class="toast-header">

            @isset($icon)
                <i class="{{ $icon }} me-2" aria-hidden="true"></i>
            @endisset

            @isset($title)
                <strong class="me-auto">{{ $title }}</strong>
            @endisset

            @if(isset($time) && empty($title))
                <small class="me-auto">{{ $time }}</small>
            @elseif(isset($time))
                <small>{{ $time }}</small>
            @endif

            <button type="button" class="btn-close" data-bs-dismiss="toast"
                aria-label="{{ __('adminlte::adminlte.close') }}"></button>

        </div>

        {{-- Toast body --}}
        <div class="toast-body">{{ $slot }}</div>

    @else

        {{-- Toast body with an inline dismiss button (Bootstrap v5 markup for
             the headerless toasts) --}}
        <div class="d-flex">

            <div class="toast-body">{{ $slot }}</div>

            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                aria-label="{{ __('adminlte::adminlte.close') }}"></button>

        </div>

    @endif

</div>

{{-- Register Javascript utility class for this component --}}

@once
@push('js')
<script>

    class _AdminLTE_Toast {

        /**
         * Constructor.
         *
         * target: The id of the target toast element, with or without the
         * leading '#' of the Bootstrap 'data-bs-target' convention.
         */
        constructor(target)
        {
            this.target = String(target || '').replace(/^#/, '');
        }

        /**
         * Get the underlying .toast element.
         */
        getToast()
        {
            return document.getElementById(this.target);
        }

        /**
         * Get the Bootstrap toast instance of the target element.
         */
        getInstance()
        {
            const t = this.getToast();

            if (! t || typeof bootstrap === 'undefined') {
                return null;
            }

            return bootstrap.Toast.getOrCreateInstance(t);
        }

        /**
         * Update the toast content.
         *
         * data: An object with the new data.
         */
        update(data)
        {
            const t = this.getToast();

            if (! t || ! data) {
                return;
            }

            if (data.title) {
                const title = t.querySelector('.toast-header strong');

                if (title) {
                    title.textContent = data.title;
                }
            }

            if (data.time) {
                const time = t.querySelector('.toast-header small');

                if (time) {
                    time.textContent = data.time;
                }
            }

            if (data.icon) {
                const icon = t.querySelector('.toast-header i');

                if (icon) {
                    icon.className = data.icon + ' me-2';
                }
            }

            if (data.body) {
                const body = t.querySelector('.toast-body');

                if (body) {
                    body.textContent = data.body;
                }
            }
        }

        /**
         * Show the toast.
         *
         * data: An optional object with new data for the toast.
         */
        show(data)
        {
            this.update(data);

            const i = this.getInstance();

            if (i) {
                i.show();
            }
        }

        /**
         * Hide the toast.
         */
        hide()
        {
            const i = this.getInstance();

            if (i) {
                i.hide();
            }
        }
    }

    window._AdminLTE_Ready(() => {

        // Move every toast into the shared container of its screen position.

        document.querySelectorAll('[data-adminlte-toast-container]').forEach((t) => {
            const c = document.getElementById(t.getAttribute('data-adminlte-toast-container'));

            if (c && t.parentElement !== c) {
                c.appendChild(t);
            }
        });

    });

    // Bootstrap provides no declarative trigger for the toasts, so the
    // 'data-bs-toggle="toast"' controls are wired with a delegated listener.

    window._AdminLTE_Once('toast-trigger', () => {
        document.addEventListener('click', (event) => {

            const target = event.target instanceof Element ? event.target : null;
            const trigger = target ? target.closest('[data-bs-toggle="toast"]') : null;

            if (! trigger) {
                return;
            }

            event.preventDefault();

            new _AdminLTE_Toast(trigger.getAttribute('data-bs-target')).show();
        });
    });

</script>
@endpush
@endonce

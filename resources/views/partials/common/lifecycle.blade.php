{{-- Lifecycle helpers for the inline scripts of the package.

     A script that binds on 'DOMContentLoaded' never runs again after an in
     app navigation: Turbo Drive and the Livewire 'wire:navigate' visits swap
     the body and re-execute its scripts, but the document is already loaded
     by then, so the event never fires a second time. --}}

<script>
    (() => {
        'use strict';

        // Runs the callback against the document as it stands right now, or
        // as soon as it is parsed on the very first load.

        window._AdminLTE_Ready = (callback) => {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', callback, { once: true });
            } else {
                callback();
            }
        };

        // Runs the callback at most once per javascript context. Use it for
        // the listeners bound to the document itself, which survive a body
        // swap and would otherwise pile up on every navigation.

        window._AdminLTE_Once = (key, callback) => {
            window._AdminLTE_OnceKeys = window._AdminLTE_OnceKeys || {};

            if (window._AdminLTE_OnceKeys[key]) {
                return;
            }

            window._AdminLTE_OnceKeys[key] = true;
            callback();
        };

        @if($spaNavigation)
        // AdminLTE re-initializes its own plugins on 'turbo:load', but it
        // knows nothing about Livewire, so the sidebar, the treeview and the
        // card tools would stay dead after a 'wire:navigate' visit. Its
        // 'initialize()' tears the previous cycle down first, so calling it
        // again is safe.

        window._AdminLTE_Once('spa-navigation', () => {
            document.addEventListener('livewire:navigated', () => {
                if (typeof adminlte !== 'undefined' && typeof adminlte.initialize === 'function') {
                    adminlte.initialize();
                }
            });
        });
        @endif
    })();
</script>

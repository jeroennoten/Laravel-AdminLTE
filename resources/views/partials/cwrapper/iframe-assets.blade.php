{{--
    Styles and behavior of the IFrame mode. AdminLTE v4 removed the IFrame
    plugin that was bundled with AdminLTE v3, so the package provides its own
    (jQuery free) implementation here. Everything is scoped to the
    '.iframe-mode' element and built on top of the Bootstrap 5.3 variables, so
    it follows both the active color mode and the text direction.
--}}

@once
@push('css')
<style>
    .iframe-mode {
        display: flex;
        flex-direction: column;
        min-height: 0;
        padding-bottom: 0;
        overflow: hidden;
    }

    .iframe-mode > .navbar {
        flex: 0 0 auto;
        flex-wrap: nowrap;
        min-height: 2.5rem;
    }

    .iframe-mode > .navbar .navbar-nav {
        flex-direction: row;
        flex-wrap: nowrap;
        overflow-x: auto;
        scrollbar-width: none;
        scroll-behavior: smooth;
    }

    .iframe-mode > .navbar .navbar-nav::-webkit-scrollbar {
        display: none;
    }

    .iframe-mode > .navbar .nav-item {
        flex: 0 0 auto;
        border-inline-end: 1px solid var(--bs-border-color);
    }

    .iframe-mode > .navbar .nav-item .nav-link {
        white-space: nowrap;
    }

    .iframe-mode > .navbar .nav-item .nav-link.active {
        background-color: var(--bs-secondary-bg);
        font-weight: 600;
    }

    .iframe-mode .btn-iframe-close {
        --bs-btn-close-focus-shadow: none;
        flex: 0 0 auto;
        padding: 0;
        font-size: 0.6rem;
    }

    .iframe-mode > .tab-content {
        position: relative;
        flex: 1 1 auto;
        min-height: 0;
        background-color: var(--bs-body-bg);
    }

    .iframe-mode > .tab-content > .tab-pane {
        position: absolute;
        inset: 0;
        display: none;
        height: 100%;
    }

    .iframe-mode > .tab-content > .tab-pane.active {
        display: block;
    }

    .iframe-mode > .tab-content > .tab-pane > iframe {
        display: block;
        width: 100%;
        height: 100%;
        border: 0;
    }

    .iframe-mode > .tab-content > .tab-loading,
    .iframe-mode > .tab-content > .tab-empty {
        position: absolute;
        inset: 0;
        display: none;
        align-items: center;
        justify-content: center;
        background-color: var(--bs-body-bg);
    }

    .iframe-mode > .tab-content > .tab-loading.show,
    .iframe-mode > .tab-content > .tab-empty.show {
        display: flex;
    }

    .iframe-mode > .tab-content > .tab-loading {
        z-index: 2;
    }

    .iframe-mode > .tab-content > .tab-loading .bi {
        display: inline-block;
        animation: adminlte-iframe-spin 1.4s linear infinite;
    }

    @keyframes adminlte-iframe-spin {
        to { transform: rotate(360deg); }
    }

    @media (prefers-reduced-motion: reduce) {
        .iframe-mode > .navbar .navbar-nav { scroll-behavior: auto; }
        .iframe-mode > .tab-content > .tab-loading .bi { animation: none; }
    }

    .iframe-mode.iframe-fullscreen {
        position: fixed;
        inset: 0;
        z-index: 1040;
        max-width: 100vw;
    }
</style>
@endpush

@push('js')
<script>
(() => {
    'use strict';

    const SELECTOR_ROOT = '.iframe-mode[data-lte-toggle="iframe"]';

    // Reads a boolean written by blade into a data attribute.
    const asBool = (value) => ! ['', '0', 'false', 'null'].includes(String(value ?? ''));

    // The menu links are caught on the document itself, so the listener is
    // bound at most once per javascript context: it survives the body swap of
    // a single page navigation, and binding it per instance would pile up one
    // listener on every visit. The live instance is resolved from the element.

    const setupMenuLinks = () => window._AdminLTE_Once('iframe-menu-links', () => {
        document.addEventListener('click', (event) => {
            const link = event.target.closest('a[href]');

            if (! link) {
                return;
            }

            const iframe = document.querySelector(SELECTOR_ROOT)?._adminlteIFrame;

            if (! iframe || ! iframe.isMenuLink(link)) {
                return;
            }

            event.preventDefault();
            iframe.open(link.getAttribute('href'), iframe.readLinkTitle(link));
        });
    });

    class AdminLteIFrame {

        constructor(root) {
            this.root = root;

            // The document level listener resolves the live instance from the
            // element, so it keeps working after a single page navigation
            // replaced the body (and this element with it).
            root._adminlteIFrame = this;

            this.tabList = root.querySelector('[role="tablist"]');
            this.tabContent = root.querySelector('.tab-content');
            this.loading = root.querySelector('.tab-loading');
            this.empty = root.querySelector('.tab-empty');

            this.autoShowNewTab = asBool(root.dataset.autoShowNewTab);
            this.useNavbarItems = asBool(root.dataset.useNavbarItems);
            this.loadingTime = Number.parseInt(root.dataset.loadingScreen ?? '0', 10) || 0;

            setupMenuLinks();
            this.setupControls();
            this.setupKeyboard();

            // Activate the default tab when the layout provides one.
            const first = this.tabList?.querySelector('[data-lte-toggle="iframe-tab"]');

            if (first) {
                this.activate(first.getAttribute('href'));
            }

            this.refreshEmptyState();
        }

        // ------------------------------------------------------------- setup

        setupControls() {
            this.root.addEventListener('click', (event) => {
                const control = event.target.closest('[data-lte-toggle]');

                if (! control || control === this.root) {
                    return;
                }

                const action = control.dataset.lteToggle;

                if (action === 'iframe-tab') {
                    event.preventDefault();
                    this.activate(control.getAttribute('href'));
                } else if (action === 'iframe-close') {
                    event.preventDefault();
                    this.close(control);
                } else if (action === 'iframe-scrollleft') {
                    event.preventDefault();
                    this.scrollTabs(-1);
                } else if (action === 'iframe-scrollright') {
                    event.preventDefault();
                    this.scrollTabs(1);
                } else if (action === 'iframe-fullscreen') {
                    event.preventDefault();
                    this.toggleFullscreen(control);
                }
            });
        }

        setupKeyboard() {
            this.tabList?.addEventListener('keydown', (event) => {
                if (! ['ArrowRight', 'ArrowLeft'].includes(event.key)) {
                    return;
                }

                const tabs = [...this.tabList.querySelectorAll('[data-lte-toggle="iframe-tab"]')];
                const current = tabs.indexOf(event.target.closest('[data-lte-toggle="iframe-tab"]'));

                if (current < 0) {
                    return;
                }

                event.preventDefault();

                const rtl = document.documentElement.dir === 'rtl';
                const forward = (event.key === 'ArrowRight') !== rtl;
                const next = tabs[(current + (forward ? 1 : -1) + tabs.length) % tabs.length];

                next.focus();
                this.activate(next.getAttribute('href'));
            });
        }

        // ------------------------------------------------------------- links

        isMenuLink(link) {
            const inSidebar = link.closest('.app-sidebar');
            const inNavbar = this.useNavbarItems && link.closest('.app-header');

            if (! inSidebar && ! inNavbar) {
                return false;
            }

            // Skip the links that are not meant to load a page.
            if (link.dataset.lteToggle || link.dataset.bsToggle || link.target) {
                return false;
            }

            const href = link.getAttribute('href');

            if (! href || href === '#' || href.startsWith('#') || href.startsWith('javascript:')) {
                return false;
            }

            // Skip the links pointing to another origin.
            return new URL(href, window.location.href).origin === window.location.origin;
        }

        readLinkTitle(link) {
            const text = link.querySelector('p')?.childNodes[0]?.textContent ?? link.textContent;

            return text.trim() || link.getAttribute('href');
        }

        // -------------------------------------------------------------- tabs

        makeTabId(url) {
            return 'iframe-tab-'.concat(
                url.replace(/[^\w-]+/g, '-').replace(/^-+|-+$/g, '').toLowerCase()
            );
        }

        open(url, title) {
            const id = this.makeTabId(url);

            if (document.getElementById(id)) {
                this.activate('#'.concat(id));

                return;
            }

            // Create the tab.

            const item = document.createElement('li');
            item.className = 'nav-item d-flex align-items-center';
            item.setAttribute('role', 'presentation');

            const tab = document.createElement('a');
            tab.className = 'nav-link';
            tab.id = id.concat('-tab');
            tab.href = '#'.concat(id);
            tab.dataset.lteToggle = 'iframe-tab';
            tab.setAttribute('role', 'tab');
            tab.setAttribute('aria-controls', id);
            tab.setAttribute('aria-selected', 'false');
            tab.textContent = title;

            const close = document.createElement('button');
            close.type = 'button';
            close.className = 'btn-close btn-iframe-close me-2';
            close.dataset.lteToggle = 'iframe-close';
            close.dataset.type = 'only-this';
            close.setAttribute('aria-label', @json(__('adminlte::iframe.btn_close_active')));

            item.append(tab, close);
            this.tabList?.append(item);

            // Create the tab panel.

            const panel = document.createElement('div');
            panel.id = id;
            panel.className = 'tab-pane fade';
            panel.setAttribute('role', 'tabpanel');
            panel.setAttribute('aria-labelledby', tab.id);

            const frame = document.createElement('iframe');
            frame.title = title;
            frame.src = url;
            frame.addEventListener('load', () => this.hideLoading());

            panel.append(frame);
            this.tabContent?.append(panel);

            if (this.autoShowNewTab) {
                this.activate('#'.concat(id));
                this.showLoading();
            }

            this.refreshEmptyState();
        }

        activate(target) {
            if (! target) {
                return;
            }

            const panel = document.getElementById(target.replace('#', ''));

            if (! panel) {
                return;
            }

            this.tabContent.querySelectorAll('.tab-pane').forEach((pane) => {
                pane.classList.remove('active', 'show');
            });

            this.tabList?.querySelectorAll('[data-lte-toggle="iframe-tab"]').forEach((tab) => {
                const isActive = tab.getAttribute('href') === target;

                tab.classList.toggle('active', isActive);
                tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
                tab.closest('.nav-item')?.classList.toggle('active', isActive);

                if (isActive) {
                    tab.scrollIntoView?.({block: 'nearest', inline: 'nearest'});
                }
            });

            panel.classList.add('active', 'show');
            this.refreshEmptyState();
        }

        close(control) {
            const type = control.dataset.type ?? 'active';

            if (type === 'all') {
                this.tabList?.querySelectorAll('.nav-item').forEach((i) => this.removeTab(i));
            } else if (type === 'all-other') {
                this.tabList?.querySelectorAll('.nav-item:not(.active)').forEach((i) => this.removeTab(i));
            } else if (type === 'only-this') {
                this.removeTab(control.closest('.nav-item'));
            } else {
                this.removeTab(this.tabList?.querySelector('.nav-item.active'));
            }

            // Activate the last remaining tab, if any.

            const remaining = [...(this.tabList?.querySelectorAll('[data-lte-toggle="iframe-tab"]') ?? [])];

            if (remaining.length && ! this.tabList.querySelector('.nav-link.active')) {
                this.activate(remaining[remaining.length - 1].getAttribute('href'));
            }

            this.refreshEmptyState();
        }

        removeTab(item) {
            if (! item) {
                return;
            }

            const tab = item.querySelector('[data-lte-toggle="iframe-tab"]');
            const panel = tab && document.getElementById(tab.getAttribute('href').replace('#', ''));

            panel?.remove();
            item.remove();
        }

        // ---------------------------------------------------------- controls

        scrollTabs(direction) {
            if (! this.tabList) {
                return;
            }

            const rtl = document.documentElement.dir === 'rtl';
            const amount = Math.max(this.tabList.clientWidth / 2, 120);

            this.tabList.scrollBy({left: (rtl ? -direction : direction) * amount});
        }

        toggleFullscreen(control) {
            const enabled = this.root.classList.toggle('iframe-fullscreen');
            const icon = control.querySelector('i');

            icon?.classList.toggle('bi-arrows-fullscreen', ! enabled);
            icon?.classList.toggle('bi-fullscreen-exit', enabled);
        }

        // ------------------------------------------------------------ states

        showLoading() {
            if (! this.loading || this.loadingTime <= 0) {
                return;
            }

            this.loading.classList.add('show');
            clearTimeout(this.loadingTimer);
            this.loadingTimer = setTimeout(() => this.hideLoading(), this.loadingTime);
        }

        hideLoading() {
            clearTimeout(this.loadingTimer);
            this.loading?.classList.remove('show');
        }

        refreshEmptyState() {
            const hasTabs = Boolean(this.tabContent?.querySelector('.tab-pane'));

            this.empty?.classList.toggle('show', ! hasTabs);
        }
    }

    window._AdminLTE_Ready(() => {
        document.querySelectorAll(SELECTOR_ROOT).forEach((root) => new AdminLteIFrame(root));
    });
})();
</script>
@endpush
@endonce

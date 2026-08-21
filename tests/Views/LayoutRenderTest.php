<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ViewErrorBag;

class LayoutRenderTest extends TestCase
{
    /**
     * Renders the main layout of the package and returns the resulting html.
     *
     * @return string
     */
    protected function renderPage()
    {
        View::flushSections();

        return View::make('adminlte::page')->render();
    }

    public function testRenderDefaultLayout()
    {
        $html = $this->renderPage();

        // Check the AdminLTE v4 layout skeleton is rendered.

        $this->assertStringContainsString('<div class="app-wrapper"', $html);
        $this->assertStringContainsString('class="app-header navbar', $html);
        $this->assertStringContainsString('app-sidebar', $html);
        $this->assertStringContainsString('class="app-main', $html);
        $this->assertStringContainsString('app-content', $html);

        // Check the default body classes.

        $this->assertStringContainsString('sidebar-expand-lg', $html);
        $this->assertStringContainsString('bg-body-tertiary', $html);

        // Check no AdminLTE v3 layout class survived.

        foreach (['content-wrapper', 'main-header', 'main-sidebar', 'main-footer', 'control-sidebar'] as $legacy) {
            $this->assertStringNotContainsString($legacy, $html);
        }
    }

    public function testRenderWithoutFontAwesomeAndBootstrap4Markup()
    {
        $html = $this->renderPage();

        foreach (['fas fa-', 'far fa-', 'fab fa-', 'data-widget=', 'data-toggle=', 'data-dismiss='] as $legacy) {
            $this->assertStringNotContainsString($legacy, $html);
        }
    }

    public function testRenderTopnavLayout()
    {
        config(['adminlte.layout_topnav' => true]);

        $html = $this->renderPage();

        // The topnav layout has no sidebar.

        $this->assertStringContainsString('app-header navbar', $html);
        $this->assertStringNotContainsString('app-sidebar', $html);
        $this->assertStringNotContainsString('sidebar-expand', $html);
    }

    public function testRenderWithRtlMode()
    {
        config(['adminlte.rtl.enabled' => true]);

        $html = $this->renderPage();

        // The direction is declared on the html element and the RTL variant of
        // the stylesheet is loaded.

        $this->assertStringContainsString('dir="rtl"', $html);
        $this->assertStringContainsString('adminlte.rtl.min.css', $html);
    }

    public function testRenderWithColorModes()
    {
        // The automatic mode is resolved on the client side, so the html
        // element declares no color mode. Note the sidebar declares its own
        // color mode, hence the check is done over the html element only.

        config(['adminlte.color_mode.default' => 'auto']);
        $html = $this->renderPage();
        $this->assertDoesNotMatchRegularExpression('/<html[^>]*data-bs-theme=/', $html);

        // An explicit mode is declared on the html element.

        config(['adminlte.color_mode.default' => 'dark']);
        $html = $this->renderPage();
        $this->assertMatchesRegularExpression('/<html[^>]*data-bs-theme="dark"/', $html);

        // The no flash script can be disabled.

        config(['adminlte.color_mode.no_flash_script' => false]);
        $html = $this->renderPage();
        $this->assertStringNotContainsString('lte-theme', $html);
    }

    public function testRenderWithRightSidebar()
    {
        config(['adminlte.right_sidebar' => true]);

        $html = $this->renderPage();

        // The right sidebar is a Bootstrap 5 offcanvas now.

        $this->assertStringContainsString('id="adminlte-right-sidebar"', $html);
        $this->assertStringContainsString('offcanvas offcanvas-end', $html);
        $this->assertStringNotContainsString('control-sidebar', $html);
    }

    public function testRenderWithFixedLayoutOptions()
    {
        config([
            'adminlte.layout_fixed_navbar' => true,
            'adminlte.layout_fixed_footer' => true,
        ]);

        $html = $this->renderPage();

        $this->assertStringContainsString('fixed-header', $html);
        $this->assertStringContainsString('fixed-footer', $html);
    }

    public function testRenderWithCdnAssets()
    {
        config(['adminlte.assets.mode' => 'cdn']);

        $html = $this->renderPage();

        $this->assertStringContainsString('cdn.jsdelivr.net/npm/admin-lte', $html);
        $this->assertStringContainsString('bootstrap-icons', $html);
    }

    public function testRenderWithExtendedColors()
    {
        config(['adminlte.assets.extended_colors' => true]);

        $html = $this->renderPage();

        $this->assertStringContainsString('adminlte-colors', $html);
    }

    public function testRenderWithAssetBundling()
    {
        // On the vite mode, the AdminLTE core assets are not emitted. Note the
        // vite directive requires a manifest to resolve the bundled assets.

        config([
            'adminlte.laravel_asset_bundling' => 'vite_js_only',
            'adminlte.laravel_js_path' => 'resources/js/app.js',
        ]);

        $manifest = public_path('build/manifest.json');

        File::ensureDirectoryExists(File::dirname($manifest));
        File::put($manifest, json_encode([
            'resources/js/app.js' => [
                'file' => 'assets/app.js',
                'src' => 'resources/js/app.js',
                'isEntry' => true,
            ],
        ]));

        try {
            $html = $this->renderPage();
        } finally {
            File::deleteDirectory(public_path('build'));
        }

        $this->assertStringContainsString('assets/app.js', $html);
        $this->assertStringNotContainsString('adminlte.min.css', $html);
        $this->assertStringNotContainsString('adminlte.min.js', $html);
    }

    public function testRenderAllMenuItemTypes()
    {
        // Build a menu exercising every supported item type.

        config(['adminlte.menu' => [
            ['type' => 'navbar-search', 'text' => 'search', 'topnav_right' => true],
            ['type' => 'fullscreen-widget', 'topnav_right' => true],
            [
                'type' => 'darkmode-widget',
                'topnav_right' => true,
                'icon_auto' => 'bi bi-circle-half',
                'color_auto' => 'info',
                'dropdown_mode' => true,
            ],
            ['type' => 'sidebar-menu-search', 'text' => 'search'],
            [
                'type' => 'navbar-notification',
                'id' => 'test-notification',
                'icon' => 'bi bi-bell-fill',
                'icon_color' => 'warning',
                'label' => 4,
                'label_color' => 'danger',
                'url' => '#',
                'topnav_right' => true,
            ],
            ['header' => 'test_header'],
            [
                'text' => 'test_link',
                'url' => '#',
                'icon' => 'bi bi-file-earmark',
                'label' => 7,
                'label_color' => 'success',
            ],
            [
                'text' => 'test_treeview',
                'icon' => 'bi bi-share',
                'submenu' => [
                    ['text' => 'test_child', 'url' => '#'],
                ],
            ],
            [
                'text' => 'test_navbar_dropdown',
                'topnav' => true,
                'submenu' => [
                    ['text' => 'test_child', 'url' => '#'],
                ],
            ],
        ]]);

        $html = $this->renderPage();

        // The sidebar items.

        $this->assertStringContainsString('nav-header', $html);
        $this->assertStringContainsString('nav-icon bi bi-file-earmark', $html);
        $this->assertStringContainsString('nav-treeview', $html);
        $this->assertStringContainsString('nav-arrow', $html);
        $this->assertStringContainsString('data-lte-toggle="treeview"', $html);
        $this->assertStringContainsString('data-lte-toggle="sidebar-search"', $html);

        // The navbar items.

        $this->assertStringContainsString('data-lte-toggle="fullscreen"', $html);
        $this->assertStringContainsString('data-bs-theme-value="dark"', $html);
        $this->assertStringContainsString('data-lte-theme-icon="auto"', $html);
        $this->assertStringContainsString('bi bi-circle-half text-info', $html);
        $this->assertStringContainsString('navbar-search', $html);
        $this->assertStringContainsString('id="test-notification"', $html);
        $this->assertStringContainsString('data-bs-toggle="dropdown"', $html);

        // No AdminLTE v3 attribute survived.

        $this->assertStringNotContainsString('data-widget=', $html);
        $this->assertStringNotContainsString('data-toggle=', $html);
    }

    public function testRenderIframeMode()
    {
        $html = View::make('adminlte::page', ['iFrameEnabled' => true])->render();

        $this->assertStringContainsString('iframe-mode', $html);
        $this->assertStringContainsString('data-lte-toggle="iframe"', $html);
        $this->assertStringNotContainsString('data-widget=', $html);
    }

    public function testRenderAuthViews()
    {
        // On a real request the error bag is shared by the web middleware.

        View::share('errors', new ViewErrorBag());

        // The email verification view links to a route of the authentication
        // scaffolding, which is not part of this package.

        Route::post('email/resend', fn () => '')->name('verification.resend');

        // Every authentication view provides its own '{type}-box' wrapper.

        $views = ['login' => 'login-box', 'register' => 'register-box', 'verify' => 'login-box'];

        foreach ($views as $view => $boxClass) {
            $html = View::make("adminlte::auth.{$view}")->render();

            $this->assertStringContainsString($boxClass, $html);
            $this->assertStringContainsString('card-body', $html);
            $this->assertStringNotContainsString('input-group-append', $html);
            $this->assertStringNotContainsString('input-group-prepend', $html);
            $this->assertStringNotContainsString('fas fa-', $html);
        }
    }
}

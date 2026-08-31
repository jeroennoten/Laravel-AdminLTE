<?php

require_once __DIR__.'/ComponentTestHelpers.php';

use JeroenNoten\LaravelAdminLte\View\Components;

class LayoutComponentsTest extends TestCase
{
    use ComponentTestHelpers;

    /**
     * Return array with the available blade components.
     */
    protected function getComponents()
    {
        $base = 'adminlte::components.layout';

        return [
            "{$base}.navbar-notification" => new Components\Layout\NavbarNotification('id', 'icon'),
            "{$base}.navbar-darkmode-widget" => new Components\Layout\NavbarDarkmodeWidget(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | General components tests.
    |--------------------------------------------------------------------------
    */

    public function testAllComponentsRender()
    {
        foreach ($this->getComponents() as $viewName => $component) {
            $view = $component->render();
            $this->assertEquals($view->getName(), $viewName);
        }
    }

    public function testAllComponentsRenderFreeOfLegacyMarkup()
    {
        $templates = [
            '<x-adminlte-navbar-notification id="n" icon="bi bi-bell"
                icon-color="danger" badge-label="5" badge-color="primary"
                :update-cfg="[\'url\' => \'/upd\', \'period\' => 10]"
                enable-dropdown-mode dropdown-footer-label="See all"/>',
            '<x-adminlte-navbar-darkmode-widget icon-disabled="bi bi-sun"
                icon-enabled="bi bi-moon" icon-auto="bi bi-circle"
                color-disabled="warning" color-enabled="info" color-auto="dark"/>',
            '<x-adminlte-navbar-darkmode-widget :dropdown-mode="false"/>',
        ];

        foreach ($templates as $template) {
            $html = $this->renderComponent($template);

            $this->assertV4Markup($html);
            $this->assertV4Markup($this->renderPushedAssets());

            // The layout components are fully jQuery free on AdminLTE v4.

            $this->assertFreeOfJquery($html);
            $this->assertFreeOfJquery($this->renderPushedAssets());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Navbar notification component tests.
    |--------------------------------------------------------------------------
    */

    public function testNavbarNotificationClasses()
    {
        // Test basic component.

        $component = new Components\Layout\NavbarNotification('id', 'icon');

        $iClass = $component->makeIconClass();
        $bClass = $component->makeBadgeClass();
        $liClass = $component->makeListItemClass();
        $aAttrs = $component->makeAnchorDefaultAttrs();

        $this->assertStringContainsString('icon', $iClass);
        $this->assertStringContainsString('badge', $bClass);
        $this->assertStringContainsString('navbar-badge', $bClass);
        $this->assertStringContainsString('nav-item', $liClass);
        $this->assertStringContainsString('nav-link', $aAttrs['class']);

        // Test advanced component.
        // $id, $icon, $iconColor, $badgeLabel, $badgeColor, $updateCfg,
        // $enableDropdownMode, $dropdownFooterLabel

        $component = new Components\Layout\NavbarNotification(
            'id', 'icon', 'danger', null, 'primary', null, true, null
        );

        $iClass = $component->makeIconClass();
        $bClass = $component->makeBadgeClass();
        $liClass = $component->makeListItemClass();
        $aAttrs = $component->makeAnchorDefaultAttrs();
        $uUrl = $component->makeUpdateUrl();

        $this->assertStringContainsString('text-danger', $iClass);
        $this->assertStringContainsString('text-bg-primary', $bClass);
        $this->assertStringContainsString('nav-item', $liClass);
        $this->assertStringContainsString('dropdown', $liClass);
        $this->assertStringContainsString('nav-link', $aAttrs['class']);
        $this->assertStringContainsString('dropdown', $aAttrs['data-bs-toggle']);
        $this->assertEquals(null, $uUrl);
    }

    public function testNavbarNotificationUrls()
    {
        // Register a test route.

        Route::any('test/url')->name('test.url');

        // Test using basic update cfg url.
        // $id, $icon, $iconColor, $badgeLabel, $badgeColor, $updateCfg

        $updateCfg = ['url' => 'test/url', 'period' => 10];
        $component = new Components\Layout\NavbarNotification(
            'id', 'icon', null, null, null, $updateCfg
        );

        $uPeriod = $component->makeUpdatePeriod();
        $uUrl = $component->makeUpdateUrl();

        $this->assertEquals(10000, $uPeriod);
        $this->assertStringContainsString('test/url', $uUrl);

        // Test using update url with params.

        $updateCfg = ['url' => ['test/url', ['p1', 'p2']]];
        $component = new Components\Layout\NavbarNotification(
            'id', 'icon', null, null, null, $updateCfg
        );

        $uPeriod = $component->makeUpdatePeriod();
        $uUrl = $component->makeUpdateUrl();

        $this->assertEquals(0, $uPeriod);
        $this->assertStringContainsString('test/url/p1/p2', $uUrl);

        // Test using basic update route.

        $updateCfg = ['route' => 'test.url'];
        $component = new Components\Layout\NavbarNotification(
            'id', 'icon', null, null, null, $updateCfg
        );

        $uUrl = $component->makeUpdateUrl();
        $this->assertStringContainsString('test/url', $uUrl);

        // Test using update route with params.

        $updateCfg = ['route' => ['test.url', ['param1' => 'p1']]];
        $component = new Components\Layout\NavbarNotification(
            'id', 'icon', null, null, null, $updateCfg
        );

        $uUrl = $component->makeUpdateUrl();
        $this->assertStringContainsString('test/url?param1=p1', $uUrl);

        // Test using update route with invalid params.

        $updateCfg = ['route' => ['test.url', 'invalid_param']];
        $component = new Components\Layout\NavbarNotification(
            'id', 'icon', null, null, null, $updateCfg
        );

        $uUrl = $component->makeUpdateUrl();
        $this->assertStringContainsString('test/url', $uUrl);

        // Test using update route with invalid config.

        $updateCfg = ['route' => 66];
        $component = new Components\Layout\NavbarNotification(
            'id', 'icon', null, null, null, $updateCfg
        );

        $uUrl = $component->makeUpdateUrl();
        $this->assertEquals(null, $uUrl);
    }

    public function testNavbarNotificationRendersEveryAttribute()
    {
        // Test all the constructor arguments at once:
        // $id, $icon, $iconColor, $badgeLabel, $badgeColor, $updateCfg,
        // $enableDropdownMode, $dropdownFooterLabel.

        $html = $this->renderComponent(
            '<x-adminlte-navbar-notification id="notif" icon="bi bi-bell-fill"
                icon-color="danger" badge-label="12" badge-color="primary"
                :update-cfg="[\'url\' => \'/upd\', \'period\' => 30]"
                enable-dropdown-mode dropdown-footer-label="See all"/>'
        );

        $this->assertStringContainsString('<li class="nav-item dropdown" id="notif">', $html);
        $this->assertStringContainsString('class="nav-link"', $html);
        $this->assertStringContainsString('data-bs-toggle="dropdown"', $html);
        $this->assertStringContainsString('bi bi-bell-fill text-danger', $html);
        $this->assertStringContainsString('>12</span>', $html);
        $this->assertStringContainsString('dropdown-menu dropdown-menu-lg dropdown-menu-end', $html);
        $this->assertStringContainsString('adminlte-dropdown-content', $html);
        $this->assertStringContainsString('dropdown-divider', $html);
        $this->assertStringContainsString('dropdown-item dropdown-footer', $html);
        $this->assertStringContainsString('See all', $html);

        $this->assertV4Markup($html);
    }

    public function testNavbarNotificationWithoutDropdownMode()
    {
        $html = $this->renderComponent(
            '<x-adminlte-navbar-notification id="notif" icon="bi bi-bell" href="/n"/>'
        );

        $this->assertStringContainsString('<li class="nav-item" id="notif">', $html);
        $this->assertStringContainsString('href="/n"', $html);
        $this->assertStringNotContainsString('dropdown', $html);
    }

    public function testNavbarNotificationDropdownFooterFallsBackToAnIcon()
    {
        $html = $this->renderComponent(
            '<x-adminlte-navbar-notification id="notif" icon="bi bi-bell" enable-dropdown-mode/>'
        );

        // The AdminLTE v4 icons are Bootstrap Icons.

        $this->assertStringContainsString('<i class="bi bi-search"></i>', $html);
    }

    public function testNavbarNotificationBadgeIsNotOverStyled()
    {
        // Regression: the '.navbar-badge' class already provides the size, the
        // weight and the position of the badge on AdminLTE v4, so no extra
        // typography utility should be added.

        $component = new Components\Layout\NavbarNotification(
            'id', 'bi bi-bell', null, '5', 'danger'
        );

        $this->assertEquals(
            'navbar-badge badge text-bg-danger',
            $component->makeBadgeClass()
        );

        $html = $this->renderComponent(
            '<x-adminlte-navbar-notification id="notif" icon="bi bi-bell"
                badge-label="5" badge-color="danger"/>'
        );

        $this->assertStringContainsString(
            '<span class="navbar-badge badge text-bg-danger">5</span>',
            $html
        );

        $this->assertStringNotContainsString('fs-7', $html);
        $this->assertStringNotContainsString('fw-bold', $html);
    }

    public function testNavbarNotificationRendersTheUpdateScript()
    {
        $this->renderComponent(
            '<x-adminlte-navbar-notification id="notif" icon="bi bi-bell"
                :update-cfg="[\'url\' => \'test/url\', \'period\' => 15]"/>'
        );

        $js = $this->renderPushedAssets();

        $this->assertStringContainsString('test/url', $js);
        $this->assertStringContainsString('setInterval(updateNotification, 15000', $js);
        $this->assertStringContainsString('_AdminLTE_NavbarNotification', $js);

        // The AdminLTE v4 helper uses the fetch API instead of jQuery.

        $this->assertStringContainsString('fetch(', $js);
        $this->assertFreeOfJquery($js);
    }

    public function testNavbarNotificationWithoutPeriodDoesNotPollTheServer()
    {
        $this->renderComponent(
            '<x-adminlte-navbar-notification id="notif" icon="bi bi-bell"
                :update-cfg="[\'url\' => \'test/url\']"/>'
        );

        $js = $this->renderPushedAssets();

        // Only the javascript helper class is registered.

        $this->assertStringContainsString('_AdminLTE_NavbarNotification', $js);
        $this->assertStringNotContainsString('setInterval', $js);
    }

    /*
    |--------------------------------------------------------------------------
    | Navbar darkmode widget component tests.
    |--------------------------------------------------------------------------
    */

    public function testNavbarDarkmodeWidgetClasses()
    {
        // Test the default icons with the light color mode. On AdminLTE v4 the
        // default icons are Bootstrap Icons.

        config([
            'adminlte.layout_dark_mode' => null,
            'adminlte.color_mode.default' => 'light',
        ]);

        $component = new Components\Layout\NavbarDarkmodeWidget(
            null, null, null, 'color-off', 'color-on', 'color-auto'
        );

        $iClass = $component->makeIconClass();

        $this->assertStringContainsString('bi bi-sun-fill', $iClass);
        $this->assertStringContainsString('text-color-off', $iClass);

        // Test the custom icons with the light color mode.

        $component = new Components\Layout\NavbarDarkmodeWidget(
            'icon-off', 'icon-on', 'icon-auto', 'color-off', 'color-on', 'color-auto'
        );

        $iClass = $component->makeIconClass();

        $this->assertStringContainsString('icon-off', $iClass);
        $this->assertStringContainsString('text-color-off', $iClass);

        // Test the icon of the automatic color mode.

        $iAutoClass = implode(' ', $component->makeIconAutoClass());

        $this->assertStringContainsString('icon-auto', $iAutoClass);
        $this->assertStringContainsString('text-color-auto', $iAutoClass);

        // Test the default icons with the dark color mode.

        config(['adminlte.color_mode.default' => 'dark']);

        $component = new Components\Layout\NavbarDarkmodeWidget(
            null, null, null, 'color-off', 'color-on', 'color-auto'
        );

        $iClass = $component->makeIconClass();

        $this->assertStringContainsString('bi bi-moon-fill', $iClass);
        $this->assertStringContainsString('text-color-on', $iClass);

        // Test the legacy 'layout_dark_mode' configuration.

        config([
            'adminlte.color_mode.default' => 'light',
            'adminlte.layout_dark_mode' => true,
        ]);

        $component = new Components\Layout\NavbarDarkmodeWidget(
            'icon-off', 'icon-on', 'icon-auto', 'color-off', 'color-on', 'color-auto'
        );

        $iClass = $component->makeIconClass();

        $this->assertStringContainsString('icon-on', $iClass);
        $this->assertStringContainsString('text-color-on', $iClass);
    }

    public function testNavbarDarkmodeWidgetMode()
    {
        // The color mode selector is used when the client side persistence is
        // enabled (the AdminLTE v4 default behavior).

        config(['adminlte.color_mode.remember' => true]);

        $component = new Components\Layout\NavbarDarkmodeWidget();
        $this->assertTrue($component->dropdownMode);

        // Otherwise, the legacy toggle is used.

        config(['adminlte.color_mode.remember' => false]);

        $component = new Components\Layout\NavbarDarkmodeWidget();
        $this->assertFalse($component->dropdownMode);

        // The mode can be forced through the component attribute.

        $component = new Components\Layout\NavbarDarkmodeWidget(
            null, null, null, null, null, null, true
        );

        $this->assertTrue($component->dropdownMode);
    }

    public function testNavbarDarkmodeWidgetRendersTheColorModeSelector()
    {
        config([
            'adminlte.color_mode.remember' => true,
            'adminlte.color_mode.default' => 'light',
            'adminlte.layout_dark_mode' => null,
        ]);

        $html = $this->renderComponent('<x-adminlte-navbar-darkmode-widget/>');

        $this->assertStringContainsString(
            '<li class="nav-item dropdown adminlte-darkmode-widget">',
            $html
        );

        $this->assertStringContainsString('data-bs-toggle="dropdown"', $html);

        // The AdminLTE v4 color mode plugin swaps the icons on the client side
        // through the 'data-lte-theme-icon' hooks.

        foreach (['light', 'dark', 'auto'] as $mode) {
            $this->assertStringContainsString("data-lte-theme-icon=\"{$mode}\"", $html);
            $this->assertStringContainsString("data-bs-theme-value=\"{$mode}\"", $html);
        }

        // The active color mode is the only visible icon and the only pressed
        // entry of the menu.

        $this->assertStringContainsString('bi bi-sun-fill', $html);
        $this->assertStringContainsString('bi bi-moon-fill', $html);
        $this->assertStringContainsString('bi bi-circle-half', $html);
        $this->assertEquals(1, substr_count($html, 'aria-pressed="true"'));
        $this->assertEquals(2, substr_count($html, 'aria-pressed="false"'));

        $this->assertV4Markup($html);
        $this->assertFreeOfJquery($html);
    }

    public function testNavbarDarkmodeWidgetRendersTheLegacyToggle()
    {
        config([
            'adminlte.color_mode.remember' => false,
            'adminlte.color_mode.default' => 'light',
            'adminlte.layout_dark_mode' => null,
        ]);

        $html = $this->renderComponent('<x-adminlte-navbar-darkmode-widget/>');

        $this->assertStringContainsString(
            '<li class="nav-item adminlte-darkmode-widget">',
            $html
        );

        $this->assertStringNotContainsString('dropdown', $html);
        $this->assertStringContainsString('<i class="bi bi-sun-fill"></i>', $html);

        // The legacy toggle persists the preference on the server side and
        // switches the Bootstrap 5.3 native color mode attribute.

        $js = $this->renderPushedAssets();

        $this->assertStringContainsString("root.setAttribute('data-bs-theme', newMode)", $js);
        $this->assertStringContainsString('adminlte/darkmode/toggle', $js);
        $this->assertStringContainsString('X-CSRF-TOKEN', $js);
        $this->assertFreeOfJquery($js);
    }

    public function testNavbarDarkmodeWidgetRendersTheCustomIconsAndColors()
    {
        // Test all the constructor arguments at once:
        // $iconDisabled, $iconEnabled, $iconAuto, $colorDisabled,
        // $colorEnabled, $colorAuto, $dropdownMode.

        config(['adminlte.color_mode.default' => 'auto']);

        $html = $this->renderComponent(
            '<x-adminlte-navbar-darkmode-widget icon-disabled="bi bi-brightness-high"
                icon-enabled="bi bi-moon-stars" icon-auto="bi bi-circle-half"
                color-disabled="warning" color-enabled="info" color-auto="secondary"
                :dropdown-mode="true"/>'
        );

        $this->assertStringContainsString('bi bi-brightness-high text-warning', $html);
        $this->assertStringContainsString('bi bi-moon-stars text-info', $html);
        $this->assertStringContainsString('bi bi-circle-half text-secondary', $html);

        // The automatic mode is the active one, so its icon is the visible.

        $this->assertMatchesRegularExpression(
            '/class="bi bi-circle-half text-secondary\s*"\s+data-lte-theme-icon="auto"/',
            $html
        );

        // The icons of the inactive color modes are hidden.

        $this->assertEquals(2, substr_count($html, 'd-none "'));
    }
}

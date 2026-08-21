<?php

use JeroenNoten\LaravelAdminLte\View\Components;

class LayoutComponentsTest extends TestCase
{
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
}

<?php

use JeroenNoten\LaravelAdminLte\Components;

class LayoutComponentsTest extends TestCase
{
    /**
     * Get package providers.
     */
    protected function getPackageProviders($app)
    {
        // Register our service provider into the Laravel's application.

        return ['JeroenNoten\LaravelAdminLte\AdminLteServiceProvider'];
    }

    /**
     * Return array with the available blade components.
     */
    protected function getComponents()
    {
        $base = 'adminlte::components.layout';

        return [
            "{$base}.navbar-notification-link" => new Components\Layout\NavbarNotificationLink('id', 'icon'),
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
    | Individual layout components tests.
    |--------------------------------------------------------------------------
    */

    public function testNavbarNotificationLinkComponent()
    {
        // Test basic component.
        $component = new Components\Layout\NavbarNotificationLink('id', 'icon');

        $iClass = $component->makeIconClass();
        $bClass = $component->makeBadgeClass();

        $this->assertStringContainsString('icon', $iClass);
        $this->assertStringContainsString('badge', $bClass);
        $this->assertStringContainsString('navbar-badge', $bClass);

        // Test advanced component.
        // $id, $icon, $iconColor, $badgeLabel, $badgeColor, $updateUrl,
        // $updateTime
        $component = new Components\Layout\NavbarNotificationLink(
            'id', 'icon', 'danger', null, 'primary', null, null
        );

        $iClass = $component->makeIconClass();
        $bClass = $component->makeBadgeClass();

        $this->assertStringContainsString('text-danger', $iClass);
        $this->assertStringContainsString('badge-primary', $bClass);
    }
}

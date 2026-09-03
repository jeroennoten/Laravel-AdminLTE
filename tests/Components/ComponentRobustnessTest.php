<?php

require_once __DIR__.'/ComponentTestHelpers.php';

use JeroenNoten\LaravelAdminLte\View\Components\Layout\NavbarNotification;
use JeroenNoten\LaravelAdminLte\View\Components\Tool\Datatable;

/**
 * Checks the components degrade gracefully on the malformed (but plausible)
 * values a user may hand over to them. None of these may abort the render of
 * the page that holds the component.
 */
class ComponentRobustnessTest extends TestCase
{
    use ComponentTestHelpers;

    public function testTheDatatableAcceptsANonArraySetOfHeads()
    {
        $this->assertEquals([], (new Datatable('t', null))->heads);
        $this->assertEquals(['Name'], (new Datatable('t', 'Name'))->heads);
        $this->assertEquals([['label' => 'Name']], (new Datatable('t', [['label' => 'Name']]))->heads);
    }

    public function testTheDatatableRendersWithoutHeads()
    {
        $html = $this->renderComponent(
            '<x-adminlte-datatable id="t" :heads="null"/>'
        );

        $this->assertStringContainsString('<table id="t"', $html);
    }

    public function testTheNotificationIgnoresAnUnknownUpdateRoute()
    {
        $cfg = ['route' => 'this.route.does.not.exist', 'period' => 30];
        $notification = new NavbarNotification('n', 'bi bi-bell', updateCfg: $cfg);

        $this->assertNull($notification->makeUpdateUrl());
        $this->assertEquals(30000, $notification->makeUpdatePeriod());
    }

    public function testTheNotificationIgnoresAMalformedUpdateConfiguration()
    {
        foreach ([['url' => ['a' => 'b']], ['route' => 5], ['url' => []]] as $cfg) {
            $notification = new NavbarNotification('n', 'bi bi-bell', updateCfg: $cfg);

            $this->assertNull($notification->makeUpdateUrl());
        }
    }

    public function testTheNotificationIgnoresANonNumericUpdatePeriod()
    {
        $cfg = ['url' => 'notifications', 'period' => 'soon'];
        $notification = new NavbarNotification('n', 'bi bi-bell', updateCfg: $cfg);

        $this->assertEquals(0, $notification->makeUpdatePeriod());
    }
}

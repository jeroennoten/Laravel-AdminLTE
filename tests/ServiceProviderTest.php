<?php

use Illuminate\Config\Repository;
use Illuminate\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class ServiceProviderTest extends TestCase
{
    public function testRegisterMenu()
    {
        $events = new Dispatcher();
        $config = new Repository(
            ['adminlte.menu' => ['item']]
        );

        $menuBuilder = $this->makeMenuBuilder();

        ServiceProvider::registerMenu($events, $config);

        if (method_exists($events, 'dispatch')) {
            $events->dispatch(new BuildingMenu($menuBuilder));
        } else {
            $events->fire(new BuildingMenu($menuBuilder));
        }

        $this->assertEquals(['item'], $menuBuilder->menu);
    }
}

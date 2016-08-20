<?php


use Illuminate\Config\Repository;
use Illuminate\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use JeroenNoten\LaravelAdminLte\Menu\Builder;
use JeroenNoten\LaravelAdminLte\ServiceProvider;

class ServiceProviderTest extends TestCase
{
    public function testRegisterMenu()
    {
        $events = new Dispatcher();
        $config = new Repository([
            'adminlte.menu' => ['item']
        ]);

        $menuBuilder = $this->makeMenuBuilder();

        ServiceProvider::registerMenu($events, $config);

        $events->fire(new BuildingMenu($menuBuilder));

        $this->assertEquals(['item'], $menuBuilder->menu);
    }
}
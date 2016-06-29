<?php


use Illuminate\Config\Repository;
use Illuminate\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use JeroenNoten\LaravelAdminLte\Menu\Builder;
use JeroenNoten\LaravelAdminLte\ServiceProvider;

class ServiceProviderTest extends PHPUnit_Framework_TestCase
{
    public function testRegisterMenu()
    {
        $events = new Dispatcher();
        $config = new Repository([
            'adminlte.menu' => ['item']
        ]);
        $menuBuilder = new Builder();

        ServiceProvider::registerMenu($events, $config);

        $events->fire(new BuildingMenu($menuBuilder));

        $this->assertEquals(['item'], $menuBuilder->menu);
    }
}
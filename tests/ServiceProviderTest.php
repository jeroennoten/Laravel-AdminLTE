<?php

use Illuminate\Config\Repository;
// use Illuminate\View\Factory as View;
use Illuminate\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\AdminLteServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class ServiceProviderTest extends TestCase
{
    // public function testPackagePath() {
    //     $class = new AdminLteServiceProvider(app());
    //     $method = new ReflectionMethod($class, 'packagePath');
    //     $method->setAccessible(TRUE);
    //     $this->assertStringContainsString('../test', $method->invoke($class, 'test'));
    // }

    public function testRegisterMenu()
    {
        $events = new Dispatcher();
        $config = new Repository(
            ['adminlte.menu' => ['item']]
        );

        $menuBuilder = $this->makeMenuBuilder();

        AdminLteServiceProvider::registerMenu($events, $config);

        $events->dispatch(new BuildingMenu($menuBuilder));

        $this->assertEquals(['item'], $menuBuilder->menu);
    }
}

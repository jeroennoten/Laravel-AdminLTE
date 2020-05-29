<?php

use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class AdminLteTest extends TestCase
{
    public function __construct()
    {
        parent::__construct();

        // Register a listener to 'BuildingMenu' event in order to add items
        // to the menu.

        $this->getDispatcher()->listen(
            BuildingMenu::class,
            [$this, 'addMenuItems']
        );
    }

    public function addMenuItems(BuildingMenu $event)
    {
        // Add (5) items to the sidebar menu.

        $event->menu->add(['header' => 'header']);
        $event->menu->add(['text' => 'sidebar', 'url' => 'url']);
        $event->menu->add(['text' => 'topnavLF', 'url' => 'url', 'topnav' => false]);
        $event->menu->add(['text' => 'topnavRF', 'url' => 'url', 'topnav_right' => false]);
        $event->menu->add(['text' => 'topnavUF', 'url' => 'url', 'topnav_user' => false]);

        // Add (1) item to the navbar menu (left section).

        $event->menu->add(['text' => 'topnavLT', 'url' => 'url', 'topnav' => true]);

        // Add (1) item to the navbar menu (right section).

        $event->menu->add(['text' => 'topnavRT', 'url' => 'url', 'topnav_right' => true]);

        // Add (1) item to the navbar menu (user menu section).

        $event->menu->add(['text' => 'topnavUT', 'url' => 'url', 'topnav_user' => true]);

        // Add (1) search bar to the navbar menu.

        $event->menu->add(['text' => 'searchLT', 'search' => true, 'topnav' => true]);

        // Add (1) submenu to the sidebar menu.

        $event->menu->add(['text' => 'submenu', 'submenu' => []]);

        // Add (1) invalid item.

        $event->menu->add(['text' => 'invalid']);
    }

    public function testMenuWithoutFilters()
    {
        $menu = $this->makeAdminLte()->menu();
        $this->assertCount(11, $menu);
        $this->assertEquals('header', $menu[0]['header']);
        $this->assertEquals('sidebar', $menu[1]['text']);
        $this->assertEquals('topnavLF', $menu[2]['text']);
        $this->assertEquals('topnavRF', $menu[3]['text']);
        $this->assertEquals('topnavUF', $menu[4]['text']);
        $this->assertEquals('topnavLT', $menu[5]['text']);
        $this->assertEquals('topnavRT', $menu[6]['text']);
        $this->assertEquals('topnavUT', $menu[7]['text']);
        $this->assertEquals('searchLT', $menu[8]['text']);
        $this->assertEquals('submenu', $menu[9]['text']);
        $this->assertEquals('invalid', $menu[10]['text']);
    }

    public function testMenuSidebarFilter()
    {
        $menu = $this->makeAdminLte()->menu('sidebar');
        $this->assertCount(6, $menu);
        $this->assertArrayNotHasKey(5, $menu);
        $this->assertArrayNotHasKey(6, $menu);
        $this->assertArrayNotHasKey(7, $menu);
        $this->assertArrayNotHasKey(8, $menu);
        $this->assertArrayNotHasKey(10, $menu);
        $this->assertEquals('header', $menu[0]['header']);
        $this->assertEquals('sidebar', $menu[1]['text']);
        $this->assertEquals('topnavLF', $menu[2]['text']);
        $this->assertEquals('topnavRF', $menu[3]['text']);
        $this->assertEquals('topnavUF', $menu[4]['text']);
        $this->assertEquals('submenu', $menu[9]['text']);
    }

    public function testMenuNavbarLeftFilter()
    {
        // Test with config 'adminlte.layout_topnav' => null.

        config(['adminlte.layout_topnav' => null]);

        $menu = $this->makeAdminLte()->menu('navbar-left');
        $this->assertCount(2, $menu);
        $this->assertArrayNotHasKey(0, $menu);
        $this->assertArrayNotHasKey(1, $menu);
        $this->assertArrayNotHasKey(2, $menu);
        $this->assertArrayNotHasKey(3, $menu);
        $this->assertArrayNotHasKey(4, $menu);
        $this->assertArrayNotHasKey(6, $menu);
        $this->assertArrayNotHasKey(7, $menu);
        $this->assertArrayNotHasKey(9, $menu);
        $this->assertArrayNotHasKey(10, $menu);
        $this->assertEquals('topnavLT', $menu[5]['text']);
        $this->assertEquals('searchLT', $menu[8]['text']);

        // Set with config 'adminlte.layout_topnav' => true.

        config(['adminlte.layout_topnav' => true]);

        $menu = $this->makeAdminLte()->menu('navbar-left');
        $this->assertCount(7, $menu);
        $this->assertArrayNotHasKey(0, $menu);
        $this->assertArrayNotHasKey(6, $menu);
        $this->assertArrayNotHasKey(7, $menu);
        $this->assertArrayNotHasKey(10, $menu);
        $this->assertEquals('sidebar', $menu[1]['text']);
        $this->assertEquals('topnavLF', $menu[2]['text']);
        $this->assertEquals('topnavRF', $menu[3]['text']);
        $this->assertEquals('topnavUF', $menu[4]['text']);
        $this->assertEquals('topnavLT', $menu[5]['text']);
        $this->assertEquals('searchLT', $menu[8]['text']);
        $this->assertEquals('submenu', $menu[9]['text']);
    }

    public function testMenuNavbarRightFilter()
    {
        $menu = $this->makeAdminLte()->menu('navbar-right');
        $this->assertCount(1, $menu);
        $this->assertArrayNotHasKey(0, $menu);
        $this->assertArrayNotHasKey(1, $menu);
        $this->assertArrayNotHasKey(2, $menu);
        $this->assertArrayNotHasKey(3, $menu);
        $this->assertArrayNotHasKey(4, $menu);
        $this->assertArrayNotHasKey(5, $menu);
        $this->assertArrayNotHasKey(7, $menu);
        $this->assertArrayNotHasKey(8, $menu);
        $this->assertArrayNotHasKey(9, $menu);
        $this->assertArrayNotHasKey(10, $menu);
        $this->assertEquals('topnavRT', $menu[6]['text']);
    }

    public function testMenuNavbarUserFilter()
    {
        $menu = $this->makeAdminLte()->menu('navbar-user');
        $this->assertCount(1, $menu);
        $this->assertArrayNotHasKey(0, $menu);
        $this->assertArrayNotHasKey(1, $menu);
        $this->assertArrayNotHasKey(2, $menu);
        $this->assertArrayNotHasKey(3, $menu);
        $this->assertArrayNotHasKey(4, $menu);
        $this->assertArrayNotHasKey(5, $menu);
        $this->assertArrayNotHasKey(6, $menu);
        $this->assertArrayNotHasKey(8, $menu);
        $this->assertArrayNotHasKey(9, $menu);
        $this->assertArrayNotHasKey(10, $menu);
        $this->assertEquals('topnavUT', $menu[7]['text']);
    }
}

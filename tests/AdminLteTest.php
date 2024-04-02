<?php

use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class AdminLteTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // Setup the static menu configuration.

        $staticMenu = [
            ['header' => 'header1'],
            ['text' => 'sidebar1', 'url' => 'url'],
        ];

        config(['adminlte.menu' => $staticMenu]);

        // Register a listener for the 'BuildingMenu' event in order to
        // dynamically add more items to the menu.

        Event::listen(BuildingMenu::class, [$this, 'addMenuItems']);
    }

    public function addMenuItems(BuildingMenu $event)
    {
        // Add (5) items to the sidebar menu.

        $event->menu->add(['header' => 'header2']);
        $event->menu->add(['text' => 'sidebar2', 'url' => 'url']);
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

        $event->menu->add(['text' => 'searchLT', 'type' => 'navbar-search', 'topnav' => true]);

        // Add (1) empty submenu to the sidebar menu. This item should be
        // filtered out of the menu.

        $event->menu->add(['text' => 'submenu1', 'submenu' => []]);

        // Add (1) non empty submenu to the sidebar menu.

        $event->menu->add(['text' => 'submenu2', 'submenu' => [
            ['text' => 'subitem', 'url' => 'url'],
        ]]);

        // Add (1) invalid item.

        $event->menu->add(['text' => 'invalid']);

        // Add (1) search bar to the sidebar menu.

        $event->menu->add(['text' => 'search', 'search' => true]);
    }

    public function testGetAllMenuItems()
    {
        $menu = $this->makeAdminLte()->menu();

        $this->assertCount(14, $menu);
        $this->assertEquals('header1', $menu[0]['header']);
        $this->assertEquals('sidebar1', $menu[1]['text']);
        $this->assertEquals('header2', $menu[2]['header']);
        $this->assertEquals('sidebar2', $menu[3]['text']);
        $this->assertEquals('topnavLF', $menu[4]['text']);
        $this->assertEquals('topnavRF', $menu[5]['text']);
        $this->assertEquals('topnavUF', $menu[6]['text']);
        $this->assertEquals('topnavLT', $menu[7]['text']);
        $this->assertEquals('topnavRT', $menu[8]['text']);
        $this->assertEquals('topnavUT', $menu[9]['text']);
        $this->assertEquals('searchLT', $menu[10]['text']);
        $this->assertEquals('submenu2', $menu[11]['text']);
        $this->assertEquals('invalid', $menu[12]['text']);
        $this->assertEquals('search', $menu[13]['text']);
    }

    public function testGetSidebarItems()
    {
        $menu = $this->makeAdminLte()->menu('sidebar');

        $this->assertCount(9, $menu);
        $this->assertEquals('header1', $menu[0]['header']);
        $this->assertEquals('sidebar1', $menu[1]['text']);
        $this->assertEquals('header2', $menu[2]['header']);
        $this->assertEquals('sidebar2', $menu[3]['text']);
        $this->assertEquals('topnavLF', $menu[4]['text']);
        $this->assertEquals('topnavRF', $menu[5]['text']);
        $this->assertEquals('topnavUF', $menu[6]['text']);
        $this->assertEquals('submenu2', $menu[11]['text']);
        $this->assertEquals('search', $menu[13]['text']);
    }

    public function testGetNavbarLeftItems()
    {
        // Test with config 'adminlte.layout_topnav' => null.

        config(['adminlte.layout_topnav' => null]);
        $menu = $this->makeAdminLte()->menu('navbar-left');

        $this->assertCount(2, $menu);
        $this->assertEquals('topnavLT', $menu[7]['text']);
        $this->assertEquals('searchLT', $menu[10]['text']);

        // Test with config 'adminlte.layout_topnav' => true.

        config(['adminlte.layout_topnav' => true]);
        $menu = $this->makeAdminLte()->menu('navbar-left');

        $this->assertCount(9, $menu);
        $this->assertEquals('sidebar1', $menu[1]['text']);
        $this->assertEquals('sidebar2', $menu[3]['text']);
        $this->assertEquals('topnavLF', $menu[4]['text']);
        $this->assertEquals('topnavRF', $menu[5]['text']);
        $this->assertEquals('topnavUF', $menu[6]['text']);
        $this->assertEquals('topnavLT', $menu[7]['text']);
        $this->assertEquals('searchLT', $menu[10]['text']);
        $this->assertEquals('submenu2', $menu[11]['text']);
        $this->assertEquals('search', $menu[13]['text']);
    }

    public function testGetNavbarRightItems()
    {
        $menu = $this->makeAdminLte()->menu('navbar-right');

        $this->assertCount(1, $menu);
        $this->assertEquals('topnavRT', $menu[8]['text']);
    }

    public function testGetNavbarUserMenuItems()
    {
        $menu = $this->makeAdminLte()->menu('navbar-user');

        $this->assertCount(1, $menu);
        $this->assertEquals('topnavUT', $menu[9]['text']);
    }
}

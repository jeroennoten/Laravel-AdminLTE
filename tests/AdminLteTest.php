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
    }

    public function testMenuWithoutFilters()
    {
        $menu = $this->makeAdminLte()->menu();
        $this->assertCount(8, $menu);
        $this->assertEquals('header', $menu[0]['header']);
        $this->assertEquals('sidebar', $menu[1]['text']);
        $this->assertEquals('topnavLF', $menu[2]['text']);
        $this->assertEquals('topnavRF', $menu[3]['text']);
        $this->assertEquals('topnavUF', $menu[4]['text']);
        $this->assertEquals('topnavLT', $menu[5]['text']);
        $this->assertEquals('topnavRT', $menu[6]['text']);
        $this->assertEquals('topnavUT', $menu[7]['text']);
    }

    public function testMenuSidebarFilter()
    {
        $menu = $this->makeAdminLte()->menu('sidebar');
        $this->assertCount(5, $menu);
        $this->assertArrayNotHasKey(5, $menu);
        $this->assertArrayNotHasKey(6, $menu);
        $this->assertArrayNotHasKey(7, $menu);
        $this->assertEquals('header', $menu[0]['header']);
        $this->assertEquals('sidebar', $menu[1]['text']);
        $this->assertEquals('topnavLF', $menu[2]['text']);
        $this->assertEquals('topnavRF', $menu[3]['text']);
        $this->assertEquals('topnavUF', $menu[4]['text']);
    }

    public function testMenuNavbarLeftFilter()
    {
        // Test with config 'adminlte.layout_topnav' => null.

        config(['adminlte.layout_topnav' => null]);

        $menu = $this->makeAdminLte()->menu('navbar-left');
        $this->assertCount(1, $menu);
        $this->assertArrayNotHasKey(0, $menu);
        $this->assertArrayNotHasKey(1, $menu);
        $this->assertArrayNotHasKey(2, $menu);
        $this->assertArrayNotHasKey(3, $menu);
        $this->assertArrayNotHasKey(4, $menu);
        $this->assertArrayNotHasKey(6, $menu);
        $this->assertArrayNotHasKey(7, $menu);
        $this->assertEquals('topnavLT', $menu[5]['text']);

        // Set with config 'adminlte.layout_topnav' => true.

        config(['adminlte.layout_topnav' => true]);

        $menu = $this->makeAdminLte()->menu('navbar-left');
        $this->assertCount(5, $menu);
        $this->assertArrayNotHasKey(0, $menu);
        $this->assertArrayNotHasKey(6, $menu);
        $this->assertArrayNotHasKey(7, $menu);
        $this->assertEquals('sidebar', $menu[1]['text']);
        $this->assertEquals('topnavLF', $menu[2]['text']);
        $this->assertEquals('topnavRF', $menu[3]['text']);
        $this->assertEquals('topnavUF', $menu[4]['text']);
        $this->assertEquals('topnavLT', $menu[5]['text']);
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
        $this->assertEquals('topnavUT', $menu[7]['text']);
    }

    public function testGetBodyData()
    {
        // Test without config.

        $data = $this->makeAdminLte()->getBodyData();
        $this->assertEquals('', $data);

        // Test with default config values.

        config([
            'adminlte.sidebar_scrollbar_theme' => 'os-theme-light',
            'adminlte.sidebar_scrollbar_auto_hide' => 'l',
        ]);

        $data = $this->makeAdminLte()->getBodyData();
        $this->assertEquals('', $data);

        // Test with non-default 'sidebar_scrollbar_theme' config value.

        config([
            'adminlte.sidebar_scrollbar_theme' => 'os-theme-dark',
            'adminlte.sidebar_scrollbar_auto_hide' => 'l',
        ]);

        $data = $this->makeAdminLte()->getBodyData();
        $this->assertStringContainsString('data-scrollbar-theme=os-theme-dark', $data);

        // Test with non-default 'sidebar_scrollbar_auto_hide' config value.

        config([
            'adminlte.sidebar_scrollbar_theme' => 'os-theme-light',
            'adminlte.sidebar_scrollbar_auto_hide' => 'm',
        ]);

        $data = $this->makeAdminLte()->getBodyData();
        $this->assertStringContainsString('data-scrollbar-auto-hide=m', $data);

        // Test with non-default config values.

        config([
            'adminlte.sidebar_scrollbar_theme' => 'os-theme-dark',
            'adminlte.sidebar_scrollbar_auto_hide' => 's',
        ]);

        $data = $this->makeAdminLte()->getBodyData();
        $this->assertStringContainsString('data-scrollbar-theme=os-theme-dark', $data);
        $this->assertStringContainsString('data-scrollbar-auto-hide=s', $data);
    }

    public function testGetBodyClasses()
    {
        // Test without config.

        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertEquals('sidebar-mini', $data);

        // Test config 'sidebar_mini' => false.

        config(['adminlte.sidebar_mini' => false]);
        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringNotContainsString('sidebar-mini', $data);

        // Test config 'sidebar_mini' => true.

        config(['adminlte.sidebar_mini' => true]);
        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringContainsString('sidebar-mini', $data);

        // Test config 'sidebar_mini' => 'md'.

        config(['adminlte.sidebar_mini' => 'md']);
        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringContainsString('sidebar-mini sidebar-mini-md', $data);

        // Test config 'layout_topnav' => null.

        config(['adminlte.layout_topnav' => null]);
        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringNotContainsString('layout-top-nav', $data);

        // Test config 'layout_topnav' => false.

        config(['adminlte.layout_topnav' => false]);
        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringNotContainsString('layout-top-nav', $data);

        // Test config 'layout_topnav' => true.

        config(['adminlte.layout_topnav' => true]);
        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringContainsString('layout-top-nav', $data);

        // Test config 'layout_boxed' => null.

        config(['adminlte.layout_boxed' => null]);
        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringNotContainsString('layout-boxed', $data);

        // Test config 'layout_boxed' => false.

        config(['adminlte.layout_boxed' => false]);
        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringNotContainsString('layout-boxed', $data);

        // Test config 'layout_boxed' => true.

        config(['adminlte.layout_boxed' => true]);
        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringContainsString('layout-boxed', $data);

        // Test config 'sidebar_collapse' => null.

        config(['adminlte.sidebar_collapse' => null]);
        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringNotContainsString('sidebar-collapse', $data);

        // Test config 'sidebar_collapse' => false.

        config(['adminlte.sidebar_collapse' => false]);
        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringNotContainsString('sidebar-collapse', $data);

        // Test config 'sidebar_collapse' => true.

        config(['adminlte.sidebar_collapse' => true]);
        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringContainsString('sidebar-collapse', $data);

        // Test config 'right_sidebar_push' => false.

        config(['adminlte.right_sidebar_push' => false]);
        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringNotContainsString('control-sidebar-push', $data);

        // Test config 'right_sidebar_push' => true, 'right_sidebar' => true.

        config([
            'adminlte.right_sidebar' => true,
            'adminlte.right_sidebar_push' => true,
        ]);

        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringContainsString('control-sidebar-push', $data);

        // Test config 'right_sidebar_push' => true, 'right_sidebar' => false.

        config([
            'adminlte.right_sidebar' => false,
            'adminlte.right_sidebar_push' => true,
        ]);

        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringNotContainsString('control-sidebar-push', $data);

        // Test config 'layout_fixed_sidebar' => null.

        config(['adminlte.layout_fixed_sidebar' => null]);
        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringNotContainsString('layout-fixed', $data);

        // Test config 'layout_fixed_sidebar' => true, 'layout_topnav' => true.

        config([
            'adminlte.layout_fixed_sidebar' => true,
            'adminlte.layout_topnav' => true,
        ]);

        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringNotContainsString('layout-fixed', $data);

        // Test config 'layout_fixed_sidebar' => true, 'layout_topnav' => null.

        config([
            'adminlte.layout_fixed_sidebar' => true,
            'adminlte.layout_topnav' => null,
        ]);

        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringContainsString('layout-fixed', $data);

        // Test config 'layout_fixed_navbar' => null.

        config(['adminlte.layout_fixed_navbar' => null]);
        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringNotContainsString('layout-navbar-fixed', $data);

        // Test config 'layout_fixed_navbar' => true, 'layout_boxed' => true.

        config([
            'adminlte.layout_fixed_navbar' => true,
            'adminlte.layout_boxed' => true,
        ]);

        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringNotContainsString('layout-navbar-fixed', $data);

        // Test config 'layout_fixed_navbar' => true, 'layout_boxed' => null.

        config([
            'adminlte.layout_fixed_navbar' => true,
            'adminlte.layout_boxed' => null,
        ]);

        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringContainsString('layout-navbar-fixed', $data);

        // Test config 'layout_fixed_navbar' => ['xs' => true, 'lg' => false],
        // 'layout_boxed' => null.

        config([
            'adminlte.layout_fixed_navbar' => ['xs' => true, 'lg' => false],
            'adminlte.layout_boxed' => null,
        ]);

        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringContainsString('layout-navbar-fixed', $data);
        $this->assertStringContainsString('layout-lg-navbar-not-fixed', $data);

        // Test config 'layout_fixed_footer' => null.

        config(['adminlte.layout_fixed_footer' => null]);
        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringNotContainsString('layout-footer-fixed', $data);

        // Test config 'layout_fixed_footer' => true, 'layout_boxed' => true.

        config([
            'adminlte.layout_fixed_footer' => true,
            'adminlte.layout_boxed' => true,
        ]);

        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringNotContainsString('layout-footer-fixed', $data);

        // Test config 'layout_fixed_footer' => true, 'layout_boxed' => null.

        config([
            'adminlte.layout_fixed_footer' => true,
            'adminlte.layout_boxed' => null,
        ]);

        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringContainsString('layout-footer-fixed', $data);

        // Test config 'layout_fixed_footer' => ['md' => true, 'lg' => false],
        // 'layout_boxed' => null.

        config([
            'adminlte.layout_fixed_footer' => ['md' => true, 'lg' => false],
            'adminlte.layout_boxed' => null,
        ]);

        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringContainsString('layout-md-footer-fixed', $data);
        $this->assertStringContainsString('layout-lg-footer-not-fixed', $data);

        // Test config 'classes_body' => custom-body-class.

        config(['adminlte.classes_body' => 'custom-body-class']);
        $data = $this->makeAdminLte()->getBodyClasses();
        $this->assertStringContainsString('custom-body-class', $data);
    }
}

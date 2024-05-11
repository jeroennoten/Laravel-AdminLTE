<?php

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;

class ClassesFilterTest extends TestCase
{
    public function testActiveClassIsAdded()
    {
        // Emulate a request.

        $this->get('http://example.com/about');

        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(
            ['text' => 'About', 'url' => 'about'],
            ['text' => 'Profile', 'url' => 'profile'],
        );

        // Make assertions.

        $menu = $builder->menu;
        $this->assertStringContainsString('active', $menu[0]['class']);
        $this->assertStringNotContainsString('active', $menu[1]['class']);
    }

    public function testActiveClassIsAddedWhenUsingRoute()
    {
        // Define a route name and emulate a request.

        RouteFacade::getRoutes()->add(
            new Route('GET', 'about', ['as' => 'pages.about'])
        );

        $this->get('http://example.com/about');

        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'About', 'route' => 'pages.about']);
        $builder->add(['text' => 'Profile', 'url' => 'profile']);

        // Make assertions.

        $menu = $builder->menu;
        $this->assertEquals('active', $menu[0]['class']);
        $this->assertEquals('', $menu[1]['class']);
    }

    public function testActiveClassIsAddedOnSubmenu()
    {
        // Emulate a request.

        $this->get('http://example.com/about');

        // Build the menu.

        $builder = $this->makeMenuBuilder();

        $builder->add(
            [
                'text' => 'Menu',
                'submenu' => [
                    ['text' => 'About', 'url' => 'about'],
                ],
            ]
        );

        // Make assertions.

        $menu = $builder->menu;
        $this->assertStringContainsString('active', $menu[0]['class']);
        $this->assertStringContainsString('menu-open', $menu[0]['submenu_class']);
        $this->assertStringContainsString('active', $menu[0]['submenu'][0]['class']);
    }

    public function testActiveClassIsAddedOnSubmenuUsingRoute()
    {
        // Define a route name and emulate a request.

        RouteFacade::getRoutes()->add(
            new Route('GET', 'about', ['as' => 'pages.about'])
        );

        $this->get('http://example.com/about');

        // Build the menu.

        $builder = $this->makeMenuBuilder();

        $builder->add(
            [
                'text' => 'Menu',
                'submenu' => [
                    ['text' => 'About', 'route' => 'pages.about'],
                ],
            ]
        );

        // Make assertions.

        $menu = $builder->menu;
        $this->assertStringContainsString('active', $menu[0]['class']);
        $this->assertStringContainsString('menu-open', $menu[0]['submenu_class']);
        $this->assertStringContainsString('active', $menu[0]['submenu'][0]['class']);
    }

    public function testActiveClassIsAddedOnSubmenuUsingHashUrl()
    {
        // Emulate a request.

        $this->get('http://example.com/home');

        // Build the menu.

        $builder = $this->makeMenuBuilder();

        $builder->add(
            [
                'text' => 'Menu',
                'url' => '#',
                'submenu' => [
                    ['url' => 'home'],
                ],
            ]
        );

        // Make assertions.

        $menu = $builder->menu;
        $this->assertTrue($menu[0]['active']);
        $this->assertStringContainsString('active', $menu[0]['class']);
        $this->assertStringContainsString('active', $menu[0]['submenu'][0]['class']);
        $this->assertStringContainsString('menu-open', $menu[0]['submenu_class']);
    }

    public function testActiveClassIsAddedOnTopNavItem()
    {
        // Emulate a request.

        $this->get('http://example.com/about');

        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'About', 'url' => 'about', 'topnav' => true]);

        // Make assertions.

        $menu = $builder->menu;
        $this->assertStringContainsString('active', $menu[0]['class']);
    }

    public function testActiveClassIsAddedOnTopNavRightItem()
    {
        // Emulate a request.

        $this->get('http://example.com/about');

        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(
            ['text' => 'About', 'url' => 'about', 'topnav_right' => true]
        );

        // Make assertions.

        $menu = $builder->menu;
        $this->assertStringContainsString('active', $menu[0]['class']);
    }

    public function testSubmenuIsActiveWhenAddInAnActiveItem()
    {
        // Emulate a request.

        $this->get('http://example.com/about');

        // Build the menu. Add a new link, and then add new elements inside
        // this one, including one active item. An active submenu item should
        // be created after this sequence.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->addIn('home', ['text' => 'Profile', 'url' => '/profile']);
        $builder->addIn('home', ['text' => 'About', 'url' => '/about']);

        // Make assertions.

        $menu = $builder->menu;
        $this->assertStringContainsString('active', $menu[0]['class']);
        $this->assertStringContainsString('menu-open', $menu[0]['submenu_class']);
    }

    public function testSubmenuIsNotActiveWhenRemovingTheActiveItem()
    {
        // Emulate a request.

        $this->get('http://example.com/about');

        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add([
            'text' => 'Submenu',
            'submenu' => [
                ['text' => 'Profile', 'url' => 'profile'],
                ['text' => 'About', 'url' => 'about', 'key' => 'about'],
            ],
        ]);

        // Make assertions.

        $menu = $builder->menu;
        $this->assertStringContainsString('active', $menu[0]['class']);
        $this->assertStringContainsString('menu-open', $menu[0]['submenu_class']);

        // Remove the active item from the submenu.

        $builder->remove('about');

        // Make assertions.

        $menu = $builder->menu;
        $this->assertStringNotContainsString('active', $menu[0]['class']);
        $this->assertStringNotContainsString('menu-open', $menu[0]['submenu_class']);
    }

    public function testSubmenuIsNotActiveWhenRemovingNestedActiveItem()
    {
        // Emulate a request.

        $this->get('http://example.com/about');

        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add([
            'text' => 'Submenu1',
            'submenu' => [
                ['text' => 'linkA', 'url' => 'linkA'],
                [
                    'text' => 'Submenu2',
                    'submenu' => [
                        ['text' => 'linkB', 'url' => 'linkB'],
                        ['text' => 'About', 'url' => 'about', 'key' => 'about'],
                    ],
                ],
            ],
        ]);

        // Make assertions.

        $menu = $builder->menu;
        $this->assertStringContainsString('active', $menu[0]['class']);
        $this->assertStringContainsString('menu-open', $menu[0]['submenu_class']);

        // Remove the active item from the nested submenu.

        $builder->remove('about');

        // Make assertions.

        $menu = $builder->menu;
        $this->assertStringNotContainsString('active', $menu[0]['class']);
        $this->assertStringNotContainsString('menu-open', $menu[0]['submenu_class']);
    }

    public function testAddingCustomClassesAttributes()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'About', 'classes' => 'foo bar']);

        // Make assertions.

        $menu = $builder->menu;
        $this->assertStringContainsString('foo', $menu[0]['class']);
        $this->assertStringContainsString('bar', $menu[0]['class']);
    }

    public function testAddingCustomClassesAttributesOnActiveItem()
    {
        // Emulate a request.

        $this->get('http://example.com/about');

        // Build the menu.

        $builder = $this->makeMenuBuilder();

        $builder->add(
            ['text' => 'About', 'url' => 'about', 'classes' => 'foo bar']
        );

        // Make assertions.

        $menu = $builder->menu;
        $this->assertStringContainsString('active', $menu[0]['class']);
        $this->assertStringContainsString('foo', $menu[0]['class']);
        $this->assertStringContainsString('bar', $menu[0]['class']);
    }
}

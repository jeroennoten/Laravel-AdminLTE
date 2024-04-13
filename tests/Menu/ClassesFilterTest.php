<?php

use Illuminate\Routing\Route;

class ClassesFilterTest extends TestCase
{
    public function testActiveClassIsAdded()
    {
        $builder = $this->makeMenuBuilder('http://example.com/about');
        $builder->add(
            ['text' => 'About', 'url' => 'about'],
            ['text' => 'Profile', 'url' => 'profile'],
        );

        $menu = $builder->menu;
        $this->assertStringContainsString('active', $menu[0]['class']);
        $this->assertStringNotContainsString('active', $menu[1]['class']);
    }

    public function testActiveClassIsAddedWhenUsingRoute()
    {
        $builder = $this->makeMenuBuilder('http://example.com/about');

        $this->getRouteCollection()->add(
            new Route('GET', 'about', ['as' => 'pages.about'])
        );

        $builder->add(['text' => 'About', 'route' => 'pages.about']);
        $builder->add(['text' => 'Profile', 'url' => 'profile']);

        $menu = $builder->menu;
        $this->assertEquals('active', $menu[0]['class']);
        $this->assertEquals('', $menu[1]['class']);
    }

    public function testActiveClassIsAddedOnSubmenu()
    {
        $builder = $this->makeMenuBuilder('http://example.com/about');

        $builder->add(
            [
                'text' => 'Menu',
                'submenu' => [
                    [
                        'text' => 'About',
                        'url' => 'about',
                    ],
                ],
            ]
        );

        $menu = $builder->menu;
        $this->assertStringContainsString('active', $menu[0]['class']);
        $this->assertStringContainsString('menu-open', $menu[0]['submenu_class']);
        $this->assertStringContainsString('active', $menu[0]['submenu'][0]['class']);
    }

    public function testActiveClassIsAddedOnSubmenuUsingRoute()
    {
        $builder = $this->makeMenuBuilder('http://example.com/about');

        $this->getRouteCollection()->add(
            new Route('GET', 'about', ['as' => 'pages.about'])
        );

        $builder->add(
            [
                'text' => 'Menu',
                'submenu' => [
                    [
                        'text' => 'About',
                        'route' => 'pages.about',
                    ],
                ],
            ]
        );

        $menu = $builder->menu;
        $this->assertStringContainsString('active', $menu[0]['class']);
        $this->assertStringContainsString('menu-open', $menu[0]['submenu_class']);
        $this->assertStringContainsString('active', $menu[0]['submenu'][0]['class']);
    }

    public function testActiveClassIsAddedOnSubmenuUsingHashUrl()
    {
        $builder = $this->makeMenuBuilder('http://example.com/home');

        $builder->add(
            [
                'text' => 'Menu',
                'url' => '#',
                'submenu' => [
                    ['url' => 'home'],
                ],
            ]
        );

        $menu = $builder->menu;
        $this->assertTrue($menu[0]['active']);
        $this->assertStringContainsString('active', $menu[0]['class']);
        $this->assertStringContainsString('active', $menu[0]['submenu'][0]['class']);
        $this->assertStringContainsString('menu-open', $menu[0]['submenu_class']);
    }

    public function testActiveClassIsAddedOnTopNavItem()
    {
        $builder = $this->makeMenuBuilder('http://example.com/about');
        $builder->add(['text' => 'About', 'url' => 'about', 'topnav' => true]);

        $menu = $builder->menu;
        $this->assertStringContainsString('active', $menu[0]['class']);
    }

    public function testActiveClassIsAddedOnTopNavRightItem()
    {
        $builder = $this->makeMenuBuilder('http://example.com/about');
        $builder->add(['text' => 'About', 'url' => 'about', 'topnav_right' => true]);

        $menu = $builder->menu;
        $this->assertStringContainsString('active', $menu[0]['class']);
    }

    public function testSubmenuClassIsAddedWhenAddInMultipleItems()
    {
        $builder = $this->makeMenuBuilder('http://example.com');

        // Add a new link item.

        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);

        // Add elements inside the previous one, now it will be a submenu item.

        $builder->addIn('home', ['text' => 'Profile', 'url' => '/profile']);
        $builder->addIn('home', ['text' => 'About', 'url' => '/about']);

        // Check the "submenu_class" attribute is added.

        $menu = $builder->menu;
        $this->assertStringContainsString('menu-open', $menu[0]['submenu_class']);
    }

    public function testAddingCustomClassesAttributes()
    {
        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'About', 'classes' => 'foo bar']);

        $menu = $builder->menu;
        $this->assertStringContainsString('foo', $menu[0]['class']);
        $this->assertStringContainsString('bar', $menu[0]['class']);
    }

    public function testAddingCustomClassesAttributesOnActiveItem()
    {
        $builder = $this->makeMenuBuilder('http://example.com/about');

        $builder->add([
            'text' => 'About',
            'url' => 'about',
            'classes' => 'foo bar',
        ]);

        $menu = $builder->menu;
        $this->assertStringContainsString('active', $menu[0]['class']);
        $this->assertStringContainsString('foo', $menu[0]['class']);
        $this->assertStringContainsString('bar', $menu[0]['class']);
    }
}

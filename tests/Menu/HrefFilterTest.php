<?php

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;

class HrefFilterTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // Setup Laravel base url.

        url()->forceRootUrl('http://example.com');
    }

    public function testHrefWillBeAdded()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'Home', 'url' => '/']);
        $builder->add(['text' => 'About', 'url' => '/about']);

        // Make assertions.

        $menu = $builder->menu;
        $this->assertEquals('http://example.com', $menu[0]['href']);
        $this->assertEquals('http://example.com/about', $menu[1]['href']);
    }

    public function testDefaultHrefWillBeAdded()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'Home']);

        // Make assertions.

        $this->assertEquals('#', $builder->menu[0]['href']);
    }

    public function testHrefOnSubmenu()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();

        $builder->add(
            [
                'text' => 'Home',
                'submenu' => [
                    ['text' => 'About', 'url' => '/about'],
                ],
            ]
        );

        // Make assertions.

        $this->assertEquals(
            'http://example.com/about',
            $builder->menu[0]['submenu'][0]['href']
        );
    }

    public function testHrefOnMultiLevelSubmenu()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();

        $builder->add(
            [
                'text' => 'Home',
                'submenu' => [
                    [
                        'text' => 'About',
                        'url' => '/about',
                        'submenu' => [
                            ['text' => 'Test', 'url' => '/test'],
                        ],
                    ],
                ],
            ]
        );

        // Make assertions.

        $this->assertEquals(
            'http://example.com/test',
            $builder->menu[0]['submenu'][0]['submenu'][0]['href']
        );
    }

    public function testHrefWhenUsingRoute()
    {
        // Define the route names.

        RouteFacade::getRoutes()->add(
            new Route('GET', 'about', ['as' => 'pages.about'])
        );

        RouteFacade::getRoutes()->add(
            new Route('GET', 'profile', ['as' => 'pages.profile'])
        );

        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'About', 'route' => 'pages.about']);

        $builder->add([
            'text' => 'Profile',
            'route' => ['pages.profile', ['user' => 'data']],
        ]);

        // Make assertions.

        $this->assertEquals(
            'http://example.com/about',
            $builder->menu[0]['href']
        );
        $this->assertEquals(
            'http://example.com/profile?user=data',
            $builder->menu[1]['href']
        );
    }

    public function testHrefWhenUsingInvalidRoutesValues()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'Invalid1', 'route' => null]);
        $builder->add(['text' => 'Invalid2', 'route' => 1]);
        $builder->add(['text' => 'Invalid3', 'route' => []]);

        // Make assertions.

        $this->assertEquals('#', $builder->menu[0]['href']);
        $this->assertEquals('#', $builder->menu[1]['href']);
        $this->assertEquals('#', $builder->menu[2]['href']);
    }
}

<?php

use Illuminate\Routing\Route;

class BuilderTest extends TestCase
{
    public function testAddOneItem()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/']);

        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
    }

    public function testAddMultipleItems()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/']);
        $builder->add(['text' => 'About', 'url' => '/about']);

        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
        $this->assertEquals('About', $builder->menu[1]['text']);
        $this->assertEquals('/about', $builder->menu[1]['url']);
    }

    public function testAddMultipleItemsAtOnce()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(
            ['text' => 'Home', 'url' => '/'],
            ['text' => 'About', 'url' => '/about']
        );

        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
        $this->assertEquals('About', $builder->menu[1]['text']);
        $this->assertEquals('/about', $builder->menu[1]['url']);
    }

    public function testHrefWillBeAdded()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/']);
        $builder->add(['text' => 'About', 'url' => '/about']);

        $this->assertEquals('http://example.com', $builder->menu[0]['href']);
        $this->assertEquals(
            'http://example.com/about',
            $builder->menu[1]['href']
        );
    }

    public function testDefaultHref()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home']);

        $this->assertEquals('#', $builder->menu[0]['href']);
    }

    public function testSubmenuHref()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(
            [
                'text'    => 'Home',
                'submenu' => [
                    ['text' => 'About', 'url' => '/about'],
                ],
            ]
        );

        $this->assertEquals(
            'http://example.com/about',
            $builder->menu[0]['submenu'][0]['href']
        );
    }

    public function testMultiLevelSubmenuHref()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(
            [
                'text'    => 'Home',
                'submenu' => [
                    [
                        'text'    => 'About',
                        'url'     => '/about',
                        'submenu' => [
                            ['text' => 'Test', 'url' => '/test'],
                        ],
                    ],
                ],
            ]
        );

        $this->assertEquals(
            'http://example.com/test',
            $builder->menu[0]['submenu'][0]['submenu'][0]['href']
        );
    }

    public function testRouteHref()
    {
        $builder = $this->makeMenuBuilder();
        $this->getRouteCollection()->add(new Route('GET', 'about', ['as' => 'pages.about']));

        $builder->add(['text' => 'About', 'route' => 'pages.about']);

        $this->assertEquals('http://example.com/about', $builder->menu[0]['href']);
    }

    public function testActiveClass()
    {
        $builder = $this->makeMenuBuilder('http://example.com/about');

        $builder->add(['text' => 'About', 'url' => 'about']);

        $this->assertContains('active', $builder->menu[0]['classes']);
    }

    public function testActiveRoute()
    {
        $builder = $this->makeMenuBuilder('http://example.com/about');
        $this->getRouteCollection()->add(new Route('GET', 'about', ['as' => 'pages.about']));

        $builder->add(['text' => 'About', 'route' => 'pages.about']);

        $this->assertContains('active', $builder->menu[0]['classes']);
    }

    public function testSubmenuActiveWithHash()
    {
        $builder = $this->makeMenuBuilder('http://example.com/home');

        $builder->add(
            [
                'url'     => '#',
                'submenu' => [
                    ['url' => 'home'],
                ],
            ]
        );

        $this->assertTrue($builder->menu[0]['active']);
    }

    public function testTreeviewClass()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'About', 'submenu' => []]);

        $this->assertContains('treeview', $builder->menu[0]['classes']);
        $this->assertContains('dropdown', $builder->menu[0]['top_nav_classes']);
    }

    public function testTreeviewMenuSubmenuClasses()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'About', 'submenu' => []]);

        $this->assertContains(
            'treeview-menu',
            $builder->menu[0]['submenu_classes']
        );
    }

    public function testSubmenuClass()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'About', 'submenu' => []]);

        $this->assertEquals(
            'treeview-menu',
            $builder->menu[0]['submenu_class']
        );
    }

    public function testClass()
    {
        $builder = $this->makeMenuBuilder('http://example.com/about');

        $builder->add(['text' => 'About', 'url' => 'about']);

        $this->assertEquals('active', $builder->menu[0]['class']);
    }

    public function testTopNavClass()
    {
        $builder = $this->makeMenuBuilder('http://example.com/about');

        $builder->add(['text' => 'About', 'url' => 'about']);

        $this->assertEquals('active', $builder->menu[0]['top_nav_class']);
    }

    public function testCan()
    {
        $gate = $this->makeGate();
        $gate->define(
            'show-about',
            function () {
                return true;
            }
        );
        $gate->define(
            'show-home',
            function () {
                return false;
            }
        );

        $builder = $this->makeMenuBuilder('http://example.com', $gate);

        $builder->add(
            [
                'text' => 'About',
                'url'  => 'about',
                'can'  => 'show-about',
            ],
            [
                'text' => 'Home',
                'url'  => '/',
                'can'  => 'show-home',
            ]
        );

        $this->assertCount(1, $builder->menu);
        $this->assertEquals('About', $builder->menu[0]['text']);
    }

    public function testCanHeaders()
    {
        $gate = $this->makeGate();
        $gate->define(
            'show-header',
            function () {
                return true;
            }
        );
        $gate->define(
            'show-settings',
            function () {
                return false;
            }
        );

        $builder = $this->makeMenuBuilder('http://example.com', $gate);

        $builder->add(
            [
                'header' => 'HEADER',
                'can'  => 'show-header',
            ],
            [
                'header' => 'SETTINGS',
                'can'  => 'show-settings',
            ]
        );

        $this->assertCount(1, $builder->menu);
        $this->assertEquals('HEADER', $builder->menu[0]);
    }

    public function testLangTranslate()
    {
        $builder = $this->makeMenuBuilder('http://example.com');
        $builder->add(['header' => 'main_navigation']);
        $builder->add(['text' => 'profile', 'url' => '/profile']);
        $builder->add(['header' => 'profile']);
        $builder->add(['text' => 'blog', 'url' => '/blog']);
        $builder->add(['header' => 'TEST']);
        $this->assertCount(5, $builder->menu);
        $this->assertEquals('MAIN NAVIGATION', $builder->menu[0]);
        $this->assertEquals('Profile', $builder->menu[2]);
        $this->assertEquals('Profile', $builder->menu[1]['text']);
        $this->assertEquals('Blog', $builder->menu[3]['text']);
        $this->assertEquals('TEST', $builder->menu[4]);

        $builder = $this->makeMenuBuilder('http://example.com', null, 'de');
        $builder->add(['header' => 'main_navigation']);
        $builder->add(['text' => 'profile', 'url' => '/profile']);
        $builder->add(['header' => 'profile']);
        $builder->add(['text' => 'blog', 'url' => '/blog']);
        $builder->add(['header' => 'TEST']);
        $this->assertCount(5, $builder->menu);
        $this->assertEquals('HAUPTMENÜ', $builder->menu[0]);
        $this->assertEquals('Profil', $builder->menu[2]);
        $this->assertEquals('Profil', $builder->menu[1]['text']);
        $this->assertEquals('Blog', $builder->menu[3]['text']);
        $this->assertEquals('TEST', $builder->menu[4]);
    }
}

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

    public function testAddAfterOneItem()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->addAfter('home', ['text' => 'Profile', 'url' => '/profile']);
        $this->assertEquals('Profile', $builder->menu[1]['text']);
        $this->assertEquals('/profile', $builder->menu[1]['url']);
    }

    public function testAddAfterMultipleItems()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->addAfter('home', ['text' => 'About', 'url' => '/about']);
        $builder->addAfter('home', ['text' => 'Profile', 'url' => '/profile']);

        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
        $this->assertEquals('Profile', $builder->menu[1]['text']);
        $this->assertEquals('/profile', $builder->menu[1]['url']);
        $this->assertEquals('About', $builder->menu[2]['text']);
        $this->assertEquals('/about', $builder->menu[2]['url']);
    }

    public function testAddAfterMultipleItemsAtOnce()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);

        $builder->addAfter('home',
            ['text' => 'Profile', 'url' => '/profile'],
            ['text' => 'About', 'url' => '/about']
        );

        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
        $this->assertEquals('Profile', $builder->menu[1]['text']);
        $this->assertEquals('/profile', $builder->menu[1]['url']);
        $this->assertEquals('About', $builder->menu[2]['text']);
        $this->assertEquals('/about', $builder->menu[2]['url']);
    }

    public function testAddAfterOneSubItem()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(
            [
                'text' => 'Home',
                'url' => '/',
                'key' => 'home',
                'submenu' => [
                    [
                        'text' => 'Test',
                        'url' => '/test',
                        'key' => 'test',
                    ],
                ],
            ]
        );
        $builder->addAfter('test', ['text' => 'Profile', 'url' => '/profile']);
        $this->assertEquals('Profile', $builder->menu[0]['submenu'][1]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['submenu'][1]['url']);
    }

    public function testAddBeforeOneItem()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Profile', 'url' => '/profile', 'key' => 'profile']);
        $builder->addBefore('profile', ['text' => 'Home', 'url' => '/']);
        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
    }

    public function testAddBeforeOneSubItem()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(
            [
                'text' => 'Home',
                'url' => '/',
                'key' => 'home',
                'submenu' => [
                    [
                        'text' => 'Test',
                        'url' => '/test',
                        'key' => 'test',
                    ],
                ],
            ]
        );
        $builder->addBefore('test', ['text' => 'Profile', 'url' => '/profile']);
        $this->assertEquals('Profile', $builder->menu[0]['submenu'][0]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['submenu'][0]['url']);
    }

    public function testAddBeforeMultipleItems()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Profile', 'url' => '/profile', 'key' => 'profile']);
        $builder->addBefore('profile', ['text' => 'Home', 'url' => '/']);
        $builder->addBefore('profile', ['text' => 'About', 'url' => '/about']);

        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
        $this->assertEquals('About', $builder->menu[1]['text']);
        $this->assertEquals('/about', $builder->menu[1]['url']);
        $this->assertEquals('Profile', $builder->menu[2]['text']);
        $this->assertEquals('/profile', $builder->menu[2]['url']);
    }

    public function testAddBeforeMultipleItemsAtOnce()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Profile', 'url' => '/profile', 'key' => 'profile']);

        $builder->addBefore('profile',
            ['text' => 'Home', 'url' => '/'],
            ['text' => 'About', 'url' => '/about']
        );

        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
        $this->assertEquals('About', $builder->menu[1]['text']);
        $this->assertEquals('/about', $builder->menu[1]['url']);
        $this->assertEquals('Profile', $builder->menu[2]['text']);
        $this->assertEquals('/profile', $builder->menu[2]['url']);
    }

    public function testAddInOneItem()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->addIn('home', ['text' => 'Profile', 'url' => '/profile']);
        $this->assertEquals('Profile', $builder->menu[0]['submenu'][0]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['submenu'][0]['url']);
    }

    public function testAddInMultipleItems()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->addIn('home', ['text' => 'Profile', 'url' => '/profile']);
        $builder->addIn('home', ['text' => 'About', 'url' => '/about']);

        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
        $this->assertEquals('Profile', $builder->menu[0]['submenu'][0]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['submenu'][0]['url']);
        $this->assertEquals('About', $builder->menu[0]['submenu'][1]['text']);
        $this->assertEquals('/about', $builder->menu[0]['submenu'][1]['url']);
    }

    public function testAddInMultipleItemsAtOnce()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);

        $builder->addIn('home',
            ['text' => 'Profile', 'url' => '/profile'],
            ['text' => 'About', 'url' => '/about']
        );

        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
        $this->assertEquals('Profile', $builder->menu[0]['submenu'][0]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['submenu'][0]['url']);
        $this->assertEquals('About', $builder->menu[0]['submenu'][1]['text']);
        $this->assertEquals('/about', $builder->menu[0]['submenu'][1]['url']);
    }

    public function testRemoveOneItem()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->add(['text' => 'Profile', 'url' => '/profile', 'key' => 'profile']);

        $builder->remove('home');

        $this->assertEquals('Profile', $builder->menu[0]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['url']);
    }

    public function testRemoveMultipleItem()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->add(['text' => 'About', 'url' => '/about', 'key' => 'about']);
        $builder->add(['text' => 'Profile', 'url' => '/profile', 'key' => 'profile']);

        $builder->remove('home');
        $builder->remove('about');

        $this->assertEquals('Profile', $builder->menu[0]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['url']);
    }

    public function testRemoveOneSubItem()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home', 'submenu' => [
            ['text' => 'About', 'url' => '/about', 'key' => 'about'],
            ['text' => 'Profile', 'url' => '/profile', 'key' => 'profile'],
        ],
        ]);

        $builder->remove('about');

        $this->assertEquals('Profile', $builder->menu[0]['submenu'][0]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['submenu'][0]['url']);
    }

    public function testRemoveMultipleSubItem()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home', 'submenu' => [
            ['text' => 'About', 'url' => '/about', 'key' => 'about'],
            ['text' => 'Profile', 'url' => '/profile', 'key' => 'profile'],
            ['text' => 'Demos', 'url' => '/demos', 'key' => 'demos'],
        ],
        ]);

        $builder->remove('about');
        $builder->remove('demos');

        $this->assertEquals('Profile', $builder->menu[0]['submenu'][0]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['submenu'][0]['url']);
    }

    public function testItemKeyExists()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);

        $this->assertTrue($builder->itemKeyExists('home'));
    }

    public function testItemSubKeyExists()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home', 'submenu' => [
            ['text' => 'About', 'url' => '/about', 'key' => 'about'],
            ['text' => 'Profile', 'url' => '/profile', 'key' => 'profile'],
        ],
        ]);

        $this->assertTrue($builder->itemKeyExists('about'));
        $this->assertFalse($builder->itemKeyExists('demos'));
    }

    public function testItemSubSubKeyExists()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home', 'submenu' => [
            ['text' => 'About', 'url' => '/about', 'key' => 'about', 'submenu' => [
                ['text' => 'Profile', 'url' => '/profile', 'key' => 'profile'],
            ],
            ],
        ],
        ]);

        $this->assertTrue($builder->itemKeyExists('about'));
        $this->assertFalse($builder->itemKeyExists('demos'));
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

        $this->assertContainsEquals('active', $builder->menu[0]['classes']);
    }

    public function testActiveRoute()
    {
        $builder = $this->makeMenuBuilder('http://example.com/about');
        $this->getRouteCollection()->add(new Route('GET', 'about', ['as' => 'pages.about']));

        $builder->add(['text' => 'About', 'route' => 'pages.about']);

        $this->assertContainsEquals('active', $builder->menu[0]['classes']);
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

        $this->assertEquals('has-treeview', $builder->menu[0]['submenu_class']);
        $this->assertEquals('dropdown', $builder->menu[0]['top_nav_class']);
    }

    public function testTreeviewMenuSubmenuClasses()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'About', 'submenu' => []]);

        $this->assertContainsEquals(
            'has-treeview',
            $builder->menu[0]['submenu_classes']
        );
    }

    public function testSubmenuClass()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'About', 'submenu' => []]);

        $this->assertEquals(
            'has-treeview',
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

    public function testMultipleCan()
    {
        $gate = $this->makeGate();
        $gate->define(
            'show-users',
            function () {
                return true;
            }
        );
        $gate->define(
            'edit-user',
            function () {
                return false;
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
                'text' => 'Users',
                'url'  => 'users',
                'can'  => ['show-users', 'edit-user'],
            ],
            [
                'text' => 'Settings',
                'url'  => 'settings',
                'can'  => ['show-settings'],
            ]
        );

        $this->assertCount(1, $builder->menu);
        $this->assertEquals('Users', $builder->menu[0]['text']);
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
        $this->assertContains('HEADER', $builder->menu[0]['header']);
    }

    public function testLangTranslate()
    {
        $builder = $this->makeMenuBuilder('http://example.com');
        $builder->add(['header' => 'profile']);
        $builder->add(['text' => 'profile', 'url' => '/profile', 'label' => 'labels']);
        $builder->add(['text' => 'blog', 'url' => '/blog']);
        $builder->add(['header' => 'TEST']);
        $this->assertCount(4, $builder->menu);
        $this->assertEquals('Profile', $builder->menu[0]['header']);
        $this->assertEquals('Profile', $builder->menu[1]['text']);
        $this->assertEquals('LABELS', $builder->menu[1]['label']);
        $this->assertEquals('Blog', $builder->menu[2]['text']);
        $this->assertEquals('TEST', $builder->menu[3]['header']);

        $builder = $this->makeMenuBuilder('http://example.com', null, 'de');
        $builder->add(['header' => 'profile']);
        $builder->add(['text' => 'profile', 'url' => '/profile', 'label' => 'labels']);
        $builder->add(['text' => 'blog', 'url' => '/blog']);
        $builder->add(['header' => 'TEST']);
        $this->assertCount(4, $builder->menu);
        $this->assertEquals('Profil', $builder->menu[0]['header']);
        $this->assertEquals('Profil', $builder->menu[1]['text']);
        $this->assertEquals('Beschriftungen', $builder->menu[1]['label']);
        $this->assertEquals('Blog', $builder->menu[2]['text']);
        $this->assertEquals('TEST', $builder->menu[3]['header']);
    }
}

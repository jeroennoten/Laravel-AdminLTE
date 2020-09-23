<?php

use Illuminate\Routing\Route;

class BuilderTest extends TestCase
{
    public function testAddOneItem()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/']);

        $this->assertCount(1, $builder->menu);
        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
    }

    public function testAddMultipleItems()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add('MENU');
        $builder->add(['text' => 'Home', 'url' => '/']);
        $builder->add(['text' => 'About', 'url' => '/about']);

        $this->assertCount(3, $builder->menu);
        $this->assertEquals('MENU', $builder->menu[0]);
        $this->assertEquals('Home', $builder->menu[1]['text']);
        $this->assertEquals('/', $builder->menu[1]['url']);
        $this->assertEquals('About', $builder->menu[2]['text']);
        $this->assertEquals('/about', $builder->menu[2]['url']);
    }

    public function testAddMultipleItemsAtOnce()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(
            ['text' => 'Home', 'url' => '/'],
            ['text' => 'About', 'url' => '/about']
        );

        $this->assertCount(2, $builder->menu);
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

        $this->assertCount(2, $builder->menu);
        $this->assertEquals('Profile', $builder->menu[1]['text']);
        $this->assertEquals('/profile', $builder->menu[1]['url']);
    }

    public function testAddAfterOneNotFoundItem()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->addAfter('foo', ['text' => 'Profile', 'url' => '/profile']);

        $this->assertCount(1, $builder->menu);
        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
    }

    public function testAddAfterMultipleItems()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->addAfter('home', ['text' => 'About', 'url' => '/about']);
        $builder->addAfter('home', ['text' => 'Profile', 'url' => '/profile']);

        $this->assertCount(3, $builder->menu);
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

        $this->assertCount(3, $builder->menu);
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

        $this->assertCount(1, $builder->menu);
        $this->assertCount(2, $builder->menu[0]['submenu']);
        $this->assertEquals('Profile', $builder->menu[0]['submenu'][1]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['submenu'][1]['url']);
    }

    public function testAddBeforeOneItem()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Profile', 'url' => '/profile', 'key' => 'profile']);
        $builder->addBefore('profile', ['text' => 'Home', 'url' => '/']);

        $this->assertCount(2, $builder->menu);
        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
    }

    public function testAddBeforeOneNotFoundItem()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->addBefore('foo', ['text' => 'Profile', 'url' => '/profile']);

        $this->assertCount(1, $builder->menu);
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

        $this->assertCount(1, $builder->menu);
        $this->assertCount(2, $builder->menu[0]['submenu']);
        $this->assertEquals('Profile', $builder->menu[0]['submenu'][0]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['submenu'][0]['url']);
    }

    public function testAddBeforeMultipleItems()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Profile', 'url' => '/profile', 'key' => 'profile']);
        $builder->addBefore('profile', ['text' => 'Home', 'url' => '/']);
        $builder->addBefore('profile', ['text' => 'About', 'url' => '/about']);

        $this->assertCount(3, $builder->menu);
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

        $this->assertCount(3, $builder->menu);
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

        $this->assertCount(1, $builder->menu);
        $this->assertCount(1, $builder->menu[0]['submenu']);
        $this->assertEquals('Profile', $builder->menu[0]['submenu'][0]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['submenu'][0]['url']);
    }

    public function testAddInOneNotFoundItem()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->addIn('foo', ['text' => 'Profile', 'url' => '/profile']);

        $this->assertCount(1, $builder->menu);
        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
    }

    public function testAddInMultipleItems()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->addIn('home', ['text' => 'Profile', 'url' => '/profile']);
        $builder->addIn('home', ['text' => 'About', 'url' => '/about']);

        $this->assertCount(1, $builder->menu);
        $this->assertCount(2, $builder->menu[0]['submenu']);
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

        $this->assertCount(1, $builder->menu);
        $this->assertCount(2, $builder->menu[0]['submenu']);
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

        $this->assertCount(1, $builder->menu);
        $this->assertEquals('Profile', $builder->menu[0]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['url']);
    }

    public function testRemoveOneNotFoundItem()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->remove('foo');

        $this->assertCount(1, $builder->menu);
        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
    }

    public function testRemoveMultipleItem()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->add(['text' => 'About', 'url' => '/about', 'key' => 'about']);
        $builder->add(['text' => 'Profile', 'url' => '/profile', 'key' => 'profile']);

        $builder->remove('home');
        $builder->remove('about');

        $this->assertCount(1, $builder->menu);
        $this->assertEquals('Profile', $builder->menu[0]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['url']);
    }

    public function testRemoveOneSubItem()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add([
            'text' => 'Home',
            'url' => '/',
            'key' => 'home',
            'submenu' => [
                ['text' => 'About', 'url' => '/about', 'key' => 'about'],
                ['text' => 'Profile', 'url' => '/profile', 'key' => 'profile'],
            ],
        ]);

        $builder->remove('about');

        $this->assertCount(1, $builder->menu);
        $this->assertCount(1, $builder->menu[0]['submenu']);
        $this->assertEquals('Profile', $builder->menu[0]['submenu'][0]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['submenu'][0]['url']);
    }

    public function testRemoveMultipleSubItem()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add([
            'text' => 'Home',
            'url' => '/',
            'key' => 'home',
            'submenu' => [
                ['text' => 'About', 'url' => '/about', 'key' => 'about'],
                ['text' => 'Profile', 'url' => '/profile', 'key' => 'profile'],
                ['text' => 'Demos', 'url' => '/demos', 'key' => 'demos'],
            ],
        ]);

        $builder->remove('about');
        $builder->remove('demos');

        $this->assertCount(1, $builder->menu);
        $this->assertCount(1, $builder->menu[0]['submenu']);
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

        $builder->add([
            'text' => 'Home',
            'url' => '/',
            'key' => 'home',
            'submenu' => [
                ['text' => 'About', 'url' => '/about', 'key' => 'about'],
                ['text' => 'Profile', 'url' => '/profile', 'key' => 'profile'],
            ],
        ]);

        $this->assertTrue($builder->itemKeyExists('home'));
        $this->assertTrue($builder->itemKeyExists('about'));
        $this->assertTrue($builder->itemKeyExists('profile'));
        $this->assertFalse($builder->itemKeyExists('demos'));
    }

    public function testItemSubSubKeyExists()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add([
            'text' => 'Home',
            'url' => '/',
            'key' => 'home',
            'submenu' => [
                [
                    'text' => 'About',
                    'url' => '/about',
                    'key' => 'about',
                    'submenu' => [
                        ['text' => 'Profile', 'url' => '/profile', 'key' => 'profile'],
                    ],
                ],
            ],
        ]);

        $this->assertTrue($builder->itemKeyExists('home'));
        $this->assertTrue($builder->itemKeyExists('about'));
        $this->assertTrue($builder->itemKeyExists('profile'));
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
        $this->getRouteCollection()->add(new Route('GET', 'profile', ['as' => 'pages.profile']));

        $builder->add(['text' => 'About', 'route' => 'pages.about']);
        $builder->add(
            [
                'text' => 'Profile',
                'route' => ['pages.profile', ['user' => 'data']],
            ]
        );

        $this->assertEquals('http://example.com/about', $builder->menu[0]['href']);
        $this->assertEquals('http://example.com/profile?user=data', $builder->menu[1]['href']);
    }

    public function testActiveClass()
    {
        $builder = $this->makeMenuBuilder('http://example.com/about');

        $builder->add(['text' => 'About', 'url' => 'about']);
        $builder->add(['text' => 'Profile', 'url' => 'profile']);

        $this->assertStringContainsString('active', $builder->menu[0]['class']);
        $this->assertStringNotContainsString('active', $builder->menu[1]['class']);
    }

    public function testActiveClassWithRoute()
    {
        $builder = $this->makeMenuBuilder('http://example.com/about');
        $this->getRouteCollection()->add(new Route('GET', 'about', ['as' => 'pages.about']));

        $builder->add(['text' => 'About', 'route' => 'pages.about']);
        $builder->add(['text' => 'Profile', 'url' => 'profile']);

        $this->assertStringContainsString('active', $builder->menu[0]['class']);
        $this->assertStringNotContainsString('active', $builder->menu[1]['class']);
    }

    public function testSubmenuActiveWithHash()
    {
        $builder = $this->makeMenuBuilder('http://example.com/home');

        $builder->add(
            [
                'text'    => 'Menu',
                'url'     => '#',
                'submenu' => [
                    ['url' => 'home'],
                ],
            ]
        );

        $this->assertTrue($builder->menu[0]['active']);
        $this->assertEquals('active', $builder->menu[0]['class']);
        $this->assertEquals('menu-open', $builder->menu[0]['submenu_class']);
    }

    public function testTopNavActiveClass()
    {
        $builder = $this->makeMenuBuilder('http://example.com/about');

        $builder->add(['text' => 'About', 'url' => 'about', 'topnav' => true]);

        $this->assertEquals('active', $builder->menu[0]['class']);
    }

    public function testTopNavRightActiveClass()
    {
        $builder = $this->makeMenuBuilder('http://example.com/about');

        $builder->add(['text' => 'About', 'url' => 'about', 'topnav_right' => true]);

        $this->assertEquals('active', $builder->menu[0]['class']);
    }

    public function testSubmenuClassWhenAddInMultipleItems()
    {
        $builder = $this->makeMenuBuilder();

        // Add a new link item.

        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);

        // Add elements inside the previous one, now it will be a submenu item.

        $builder->addIn('home', ['text' => 'Profile', 'url' => '/profile']);
        $builder->addIn('home', ['text' => 'About', 'url' => '/about']);

        // Check the "submenu_class" attribute is added.

        $this->assertTrue(isset($builder->menu[0]['submenu_class']));
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

    public function testCanAddOneRestrictedItem()
    {
        $gate = $this->makeGate();
        $gate->define(
            'show-home',
            function () {
                return false;
            }
        );

        $builder = $this->makeMenuBuilder('http://example.com', $gate);

        $builder->add(
            [
                'text' => 'Home',
                'url'  => '/',
                'can'  => 'show-home',
            ]
        );

        $this->assertCount(0, $builder->menu);
    }

    public function testCanWithInvalidValues()
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
            ['text' => 'LinkA', 'url'  => 'link_a', 'can'  => false],
            ['text' => 'LinkB', 'url'  => 'link_b', 'can'  => 1024],
            ['text' => 'LinkC', 'url'  => 'link_c', 'can'  => ''],
            ['text' => 'LinkD', 'url'  => 'link_d', 'can'  => []],
            ['text' => 'LinkE', 'url'  => 'link_e']
        );

        $this->assertCount(5, $builder->menu);
        $this->assertEquals('LinkA', $builder->menu[0]['text']);
        $this->assertEquals('LinkB', $builder->menu[1]['text']);
        $this->assertEquals('LinkC', $builder->menu[2]['text']);
        $this->assertEquals('LinkD', $builder->menu[3]['text']);
        $this->assertEquals('LinkE', $builder->menu[4]['text']);
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
        $this->assertStringContainsString('HEADER', $builder->menu[0]['header']);
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

    public function testLangTranslateWithExtraParams()
    {
        $builder = $this->makeMenuBuilder('http://example.com', null, 'es');

        $lines = [
            'menu.header_with_params' => 'MENU :cat / :subcat',
            'menu.profile_with_params' => 'Perfil de :name',
            'menu.label_with_params' => 'Etiqueta :type',
        ];

        $translator = $this->getTranslator();
        $translator->addLines($lines, 'es', 'adminlte');

        $builder->add(
            [
                'header' => [
                    'header_with_params',
                    ['cat' => 'CAT', 'subcat' => 'SUBCAT'],
                ],
            ],
            [
                'text' => ['profile_with_params', ['name' => 'Diego']],
                'url' => '/profile',
                'label' => ['label_with_params', ['type' => 'Tipo']],
            ],
            [
                // Test case with partial parameters.
                'header' => ['header_with_params', ['subcat' => 'SUBCAT']],
            ],
            [
                // Test case with empty parameters.
                'header' => ['header_with_params'],
            ],
            [
                // Test case with non-array parameters.
                'header' => ['header_with_params', 'non-array-value'],
            ],
        );

        $this->assertCount(5, $builder->menu);
        $this->assertEquals('MENU CAT / SUBCAT', $builder->menu[0]['header']);
        $this->assertEquals('Perfil de Diego', $builder->menu[1]['text']);
        $this->assertEquals('Etiqueta Tipo', $builder->menu[1]['label']);
        $this->assertEquals('MENU :cat / SUBCAT', $builder->menu[2]['header']);
        $this->assertEquals('MENU :cat / :subcat', $builder->menu[3]['header']);
        $this->assertEquals('MENU :cat / :subcat', $builder->menu[4]['header']);
    }

    public function testDataAttributes()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'About', 'data' => [
            'test-one' => 'content-one',
            'test-two' => 'content-two',
        ]]);

        $this->assertEquals(
            'data-test-one="content-one" data-test-two="content-two"',
            $builder->menu[0]['data-compiled']
        );
    }

    public function testSearchBarDefaultMethod()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'search', 'search' => true]);
        $builder->add(['text' => 'Search', 'search' => true, 'method' => 'foo']);
        $builder->add(['text' => 'Search', 'search' => true, 'method' => 'post']);

        $this->assertEquals('get', $builder->menu[0]['method']);
        $this->assertEquals('get', $builder->menu[1]['method']);
        $this->assertEquals('post', $builder->menu[2]['method']);
    }

    public function testSearchBarDefaultName()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'search', 'search' => true]);
        $builder->add(['text' => 'Search', 'search' => true, 'input_name' => 'foo']);

        $this->assertEquals('q', $builder->menu[0]['input_name']);
        $this->assertEquals('foo', $builder->menu[1]['input_name']);
    }

    public function testClassesAttribute()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add([
            'text' => 'About',
            'classes' => 'foo-class',
        ]);

        $this->assertStringContainsString('foo-class', $builder->menu[0]['class']);
    }

    public function testClassesAttributeWithActiveClass()
    {
        $builder = $this->makeMenuBuilder('http://example.com/about');

        $builder->add([
            'text' => 'About',
            'url' => 'about',
            'classes' => 'foo-class bar-class',
        ]);

        $this->assertStringContainsString('active', $builder->menu[0]['class']);
        $this->assertStringContainsString('foo-class', $builder->menu[0]['class']);
        $this->assertStringContainsString('bar-class', $builder->menu[0]['class']);
    }
}

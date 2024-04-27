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
                'text' => 'Home',
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
}

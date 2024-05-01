<?php

class BuilderTest extends TestCase
{
    public function testAddOneItem()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'Home', 'url' => '/']);

        // Make assertions.

        $this->assertCount(1, $builder->menu);
        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
    }

    public function testAddMultipleItems()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add('MENU');
        $builder->add(['text' => 'Home', 'url' => '/']);
        $builder->add(['text' => 'About', 'url' => '/about']);

        // Make assertions.

        $this->assertCount(3, $builder->menu);
        $this->assertEquals('MENU', $builder->menu[0]);
        $this->assertEquals('Home', $builder->menu[1]['text']);
        $this->assertEquals('/', $builder->menu[1]['url']);
        $this->assertEquals('About', $builder->menu[2]['text']);
        $this->assertEquals('/about', $builder->menu[2]['url']);
    }

    public function testAddMultipleItemsAtOnce()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();

        $builder->add(
            ['text' => 'Home', 'url' => '/'],
            ['text' => 'About', 'url' => '/about']
        );

        // Make assertions.

        $this->assertCount(2, $builder->menu);
        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
        $this->assertEquals('About', $builder->menu[1]['text']);
        $this->assertEquals('/about', $builder->menu[1]['url']);
    }

    public function testAddAfterOneItem()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->addAfter('home', ['text' => 'Profile', 'url' => '/profile']);

        // Make assertions.

        $this->assertCount(2, $builder->menu);
        $this->assertEquals('Profile', $builder->menu[1]['text']);
        $this->assertEquals('/profile', $builder->menu[1]['url']);
    }

    public function testAddAfterOneNotFoundItem()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->addAfter('foo', ['text' => 'Profile', 'url' => '/profile']);

        // Make assertions.

        $this->assertCount(1, $builder->menu);
        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
    }

    public function testAddAfterMultipleTimes()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->addAfter('home', ['text' => 'About', 'url' => '/about']);
        $builder->addAfter('home', ['text' => 'Profile', 'url' => '/profile']);

        // Make assertions.

        $this->assertCount(3, $builder->menu);
        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
        $this->assertEquals('Profile', $builder->menu[1]['text']);
        $this->assertEquals('/profile', $builder->menu[1]['url']);
        $this->assertEquals('About', $builder->menu[2]['text']);
        $this->assertEquals('/about', $builder->menu[2]['url']);
    }

    public function testAddAfterWithMultipleItemsAtOnce()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);

        $builder->addAfter('home',
            ['text' => 'Profile', 'url' => '/profile'],
            ['text' => 'About', 'url' => '/about']
        );

        // Make assertions.

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
        // Build the menu.

        $builder = $this->makeMenuBuilder();

        $builder->add([
            'text' => 'Home',
            'url' => '/',
            'key' => 'home',
            'submenu' => [
                ['text' => 'Test', 'url' => '/test', 'key' => 'test'],
            ],
        ]);

        $builder->addAfter('test', ['text' => 'Profile', 'url' => '/profile']);

        // Make assertions.

        $this->assertCount(1, $builder->menu);
        $this->assertCount(2, $builder->menu[0]['submenu']);
        $this->assertEquals('Profile', $builder->menu[0]['submenu'][1]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['submenu'][1]['url']);
    }

    public function testAddBeforeOneItem()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();

        $builder->add(
            ['text' => 'Profile', 'url' => '/profile', 'key' => 'profile']
        );

        $builder->addBefore('profile', ['text' => 'Home', 'url' => '/']);

        // Make assertions.

        $this->assertCount(2, $builder->menu);
        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
    }

    public function testAddBeforeOneNotFoundItem()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->addBefore('foo', ['text' => 'Profile', 'url' => '/profile']);

        // Make assertions.

        $this->assertCount(1, $builder->menu);
        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
    }

    public function testAddBeforeOneSubItem()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();

        $builder->add([
            'text' => 'Home',
            'url' => '/',
            'key' => 'home',
            'submenu' => [
                ['text' => 'Test', 'url' => '/test', 'key' => 'test'],
            ],
        ]);

        $builder->addBefore('test', ['text' => 'Profile', 'url' => '/profile']);

        // Make assertions.

        $this->assertCount(1, $builder->menu);
        $this->assertCount(2, $builder->menu[0]['submenu']);
        $this->assertEquals('Profile', $builder->menu[0]['submenu'][0]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['submenu'][0]['url']);
    }

    public function testAddBeforeMultipleTimes()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();

        $builder->add(
            ['text' => 'Profile', 'url' => '/profile', 'key' => 'profile']
        );

        $builder->addBefore('profile', ['text' => 'Home', 'url' => '/']);
        $builder->addBefore('profile', ['text' => 'About', 'url' => '/about']);

        // Make assertions.

        $this->assertCount(3, $builder->menu);
        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
        $this->assertEquals('About', $builder->menu[1]['text']);
        $this->assertEquals('/about', $builder->menu[1]['url']);
        $this->assertEquals('Profile', $builder->menu[2]['text']);
        $this->assertEquals('/profile', $builder->menu[2]['url']);
    }

    public function testAddBeforeWithMultipleItemsAtOnce()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();

        $builder->add(
            ['text' => 'Profile', 'url' => '/profile', 'key' => 'profile']
        );

        $builder->addBefore('profile',
            ['text' => 'Home', 'url' => '/'],
            ['text' => 'About', 'url' => '/about']
        );

        // Make assertions.

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
        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->addIn('home', ['text' => 'Profile', 'url' => '/profile']);

        // Make assertions.

        $this->assertCount(1, $builder->menu);
        $this->assertCount(1, $builder->menu[0]['submenu']);
        $this->assertEquals('Profile', $builder->menu[0]['submenu'][0]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['submenu'][0]['url']);
    }

    public function testAddInOneNotFoundItem()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->addIn('foo', ['text' => 'Profile', 'url' => '/profile']);

        // Make assertions.

        $this->assertCount(1, $builder->menu);
        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
    }

    public function testAddInMultipleTimes()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->addIn('home', ['text' => 'Profile', 'url' => '/profile']);
        $builder->addIn('home', ['text' => 'About', 'url' => '/about']);

        // Make assertions.

        $this->assertCount(1, $builder->menu);
        $this->assertCount(2, $builder->menu[0]['submenu']);
        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
        $this->assertEquals('Profile', $builder->menu[0]['submenu'][0]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['submenu'][0]['url']);
        $this->assertEquals('About', $builder->menu[0]['submenu'][1]['text']);
        $this->assertEquals('/about', $builder->menu[0]['submenu'][1]['url']);
    }

    public function testAddInWithMultipleItemsAtOnce()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);

        $builder->addIn('home',
            ['text' => 'Profile', 'url' => '/profile'],
            ['text' => 'About', 'url' => '/about']
        );

        // Make assertions.

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
        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->add(
            ['text' => 'Profile', 'url' => '/profile', 'key' => 'profile']
        );

        $builder->remove('home');

        // Make assertions.

        $this->assertCount(1, $builder->menu);
        $this->assertEquals('Profile', $builder->menu[0]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['url']);
    }

    public function testRemoveOneNotFoundItem()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->remove('foo');

        // Make assertions.

        $this->assertCount(1, $builder->menu);
        $this->assertEquals('Home', $builder->menu[0]['text']);
        $this->assertEquals('/', $builder->menu[0]['url']);
    }

    public function testRemoveMultipleItems()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);
        $builder->add(['text' => 'About', 'url' => '/about', 'key' => 'about']);
        $builder->add(
            ['text' => 'Profile', 'url' => '/profile', 'key' => 'profile']
        );

        $builder->remove('home');
        $builder->remove('about');

        // Make assertions.

        $this->assertCount(1, $builder->menu);
        $this->assertEquals('Profile', $builder->menu[0]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['url']);
    }

    public function testRemoveOneSubItem()
    {
        // Build the menu.

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

        // Make assertions.

        $this->assertCount(1, $builder->menu);
        $this->assertCount(1, $builder->menu[0]['submenu']);
        $this->assertEquals('Profile', $builder->menu[0]['submenu'][0]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['submenu'][0]['url']);
    }

    public function testRemoveMultipleSubItems()
    {
        // Build the menu.

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

        // Make assertions.

        $this->assertCount(1, $builder->menu);
        $this->assertCount(1, $builder->menu[0]['submenu']);
        $this->assertEquals('Profile', $builder->menu[0]['submenu'][0]['text']);
        $this->assertEquals('/profile', $builder->menu[0]['submenu'][0]['url']);
    }

    public function testItemKeyExists()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'Home', 'url' => '/', 'key' => 'home']);

        // Make assertions.

        $this->assertTrue($builder->itemKeyExists('home'));
    }

    public function testItemKeyExistsOnSubItem()
    {
        // Build the menu.

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

        // Make assertions.

        $this->assertTrue($builder->itemKeyExists('home'));
        $this->assertTrue($builder->itemKeyExists('about'));
        $this->assertTrue($builder->itemKeyExists('profile'));
        $this->assertFalse($builder->itemKeyExists('foo'));
    }

    public function testItemKeyExistsOnNestedSubItem()
    {
        // Build the menu.

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

        // Make assertions.

        $this->assertTrue($builder->itemKeyExists('home'));
        $this->assertTrue($builder->itemKeyExists('about'));
        $this->assertTrue($builder->itemKeyExists('profile'));
        $this->assertFalse($builder->itemKeyExists('foo'));
    }
}

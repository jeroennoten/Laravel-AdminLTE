<?php

class ActiveCheckerTest extends TestCase
{
    /**
     * Holds an active checker instance.
     *
     * @var ActiveChecker
     */
    protected $checker;

    public function setUp(): void
    {
        parent::setUp();

        // Create the active checker instance.

        $this->checker = $this->makeActiveChecker();
    }

    public function testWithLinkItem()
    {
        // Emulate a request.

        $this->get('http://example.com/about');

        // Make assertions.

        $this->assertTrue($this->checker->isActive(['url' => 'about']));
    }

    public function testWithLinkItemUsingRootUrl()
    {
        // Emulate a request.

        $this->get('http://example.com');

        // Make assertions.

        $this->assertTrue($this->checker->isActive(['url' => '/']));
    }

    public function testWithLinkItemUsingNotActiveUrl()
    {
        // Emulate a request.

        $this->get('http://example.com/about');

        // Make assertions.

        $this->assertFalse($this->checker->isActive(['url' => 'home']));
    }

    public function testHeaderItemWillNotBeActive()
    {
        // Emulate a request.

        $this->get('http://example.com');

        // Make assertions.

        $this->assertFalse($this->checker->isActive('HEADER'));
    }

    public function testWithLinkItemUsingSubUrl()
    {
        // Emulate a request.

        $this->get('http://example.com/about/sub');

        // Make assertions.

        $this->assertTrue($this->checker->isActive(['url' => 'about/sub']));
    }

    public function testWithSubmenuItem()
    {
        // Emulate a request.

        $this->get('http://example.com/home');

        // Make assertions.

        $isActive = $this->checker->isActive([
            'submenu' => [
                ['url' => 'foo'],
                ['url' => 'home'],
            ],
        ]);

        $this->assertTrue($isActive);
    }

    public function testWithMultiLevelSubmenu()
    {
        // Emulate a request.

        $this->get('http://example.com/home');

        // Make assertions.

        $isActive = $this->checker->isActive([
            'text' => 'Level 0',
            'submenu' => [
                [
                    'text' => 'Level 1',
                    'submenu' => [['url' => 'foo'], ['url' => 'home']],
                ],
            ],
        ]);

        $this->assertTrue($isActive);
    }

    public function testLinkWithExplicitActiveAttribute()
    {
        // Emulate a request.

        $this->get('http://example.com/home');

        // Make assertions.

        $isActive = $this->checker->isActive(['active' => ['home']]);
        $this->assertTrue($isActive);

        $isActive = $this->checker->isActive(['active' => ['about']]);
        $this->assertFalse($isActive);
    }

    public function testLinkWithExplicitActiveAttributeAndWildcards()
    {
        // Emulate a request.

        $this->get('http://example.com/home/sub');

        // Make assertions.

        $isActive = $this->checker->isActive(['active' => ['home/*']]);
        $this->assertTrue($isActive);

        $isActive = $this->checker->isActive(['active' => ['home/su*']]);
        $this->assertTrue($isActive);

        $isActive = $this->checker->isActive(['active' => ['hom*']]);
        $this->assertTrue($isActive);

        $isActive = $this->checker->isActive(['active' => ['home/t*']]);
        $this->assertFalse($isActive);
    }

    public function testLinkWithExplicitActiveOverridesUrl()
    {
        // Emulate a request.

        $this->get('http://example.com/admin/users');

        // Make assertions.

        $isActive = $this->checker->isActive(['url' => 'admin']);
        $this->assertFalse($isActive);

        $isActive = $this->checker->isActive([
            'url' => 'admin',
            'active' => ['admin*'],
        ]);

        $this->assertTrue($isActive);
    }

    public function testLinkWithFullUrl()
    {
        // Emulate a request.

        $this->get('http://example.com/about');

        // Make assertions.

        $isActive = $this->checker->isActive(
            ['url' => 'http://example.com/about']
        );

        $this->assertTrue($isActive);
    }

    public function testLinkWithFullSubUrl()
    {
        // Emulate a request.

        $this->get('http://example.com/about/sub');

        // Make assertions.

        $isActive = $this->checker->isActive(
            ['url' => 'http://example.com/about/sub']
        );

        $this->assertTrue($isActive);
    }

    public function testLinkWithFullUrlAndDifferentSchemas()
    {
        // Emulate a request.

        $this->get('https://example.com/about');

        // Make assertions. Note schema should be ignored.

        $isActive = $this->checker->isActive(
            ['url' => 'http://example.com/about']
        );

        $this->assertTrue($isActive);

        $isActive = $this->checker->isActive(
            ['url' => 'https://example.com/about']
        );

        $this->assertTrue($isActive);

        $isActive = $this->checker->isActive(['url' => 'about']);
        $this->assertTrue($isActive);
    }

    public function testLinkUrlWithQueryParams()
    {
        // Emulate a request.

        $this->get('http://example.com/menu?param=value');

        // Make assertions.

        $this->assertTrue($this->checker->isActive(['url' => 'menu']));
        $this->assertTrue($this->checker->isActive(['active' => ['menu']]));

        $this->assertTrue(
            $this->checker->isActive(['active' => ['menu?param=value']])
        );

        $this->assertFalse(
            $this->checker->isActive(['active' => ['menu?param=foo']])
        );
    }

    public function testLinkSubUrlWithQueryParams()
    {
        // Emulate a request.

        $this->get('http://example.com/menu/item1?param=option');

        // Make assertions.

        $this->assertTrue($this->checker->isActive(['url' => 'menu/item1']));
        $this->assertTrue($this->checker->isActive(['active' => ['menu/item1']]));
        $this->assertTrue($this->checker->isActive(['active' => ['menu/*']]));
    }

    public function testLinkWithExplicitActiveAndRegex()
    {
        // Emulate a request.

        $this->get('http://example.com/posts/1');

        // Make assertions.

        $this->assertTrue(
            $this->checker->isActive(['active' => ['regex:@^posts/[0-9]+$@']])
        );

        $this->assertFalse(
            $this->checker->isActive(['active' => ['regex:@^post/[0-9]+$@']])
        );
    }

    public function testActiveWillfallbackToUrlAttribute()
    {
        // Emulate a request.

        $this->get('http://example.com/home');

        // Make assertions.

        $isActive = $this->checker->isActive([
            'url' => 'home',
            'active' => ['about', 'no-home'],
            'submenu' => [],
        ]);

        $this->assertTrue($isActive);
    }

    public function testLinkWhenSchemeIsForced()
    {
        // Force HTTPS schema and emulate a request.

        url()->forceScheme('https');
        $this->get('http://example.com/about');

        // Make assertions.

        $isActive = $this->checker->isActive(['url' => 'about']);
        $this->assertTrue($isActive);

        $isActive = $this->checker->isActive(
            ['url' => 'http://example.com/about']
        );

        $this->assertTrue($isActive);

        $isActive = $this->checker->isActive(
            ['url' => 'https://example.com/about']
        );

        $this->assertTrue($isActive);
    }
}

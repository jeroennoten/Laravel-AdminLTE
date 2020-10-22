<?php

class ActiveCheckerTest extends TestCase
{
    public function testExact()
    {
        $checker = $this->makeActiveChecker('http://example.com/about');

        $this->assertTrue($checker->isActive(['url' => 'about']));
    }

    public function testRoot()
    {
        $checker = $this->makeActiveChecker('http://example.com');

        $this->assertTrue($checker->isActive(['url' => '/']));
    }

    public function testNotActive()
    {
        $checker = $this->makeActiveChecker('http://example.com/about');

        $this->assertFalse($checker->isActive(['url' => 'home']));
    }

    public function testStringNotActive()
    {
        $checker = $this->makeActiveChecker();

        $this->assertFalse($checker->isActive('HEADER'));
    }

    public function testSub()
    {
        $checker = $this->makeActiveChecker('http://example.com/about/sub');

        $this->assertTrue($checker->isActive(['url' => 'about/sub']));
    }

    public function testSubmenu()
    {
        $checker = $this->makeActiveChecker('http://example.com/home');

        $isActive = $checker->isActive(
            [
                'submenu' => [
                    ['url' => 'foo'],
                    ['url' => 'home'],
                ],
            ]
        );

        $this->assertTrue($isActive);
    }

    public function testMultiLevelSubmenu()
    {
        $checker = $this->makeActiveChecker('http://example.com/home');

        $isActive = $checker->isActive(
            [
                'text' => 'Level 0',
                'submenu' => [
                    [
                        'text' => 'Level 1',
                        'submenu' => [
                            ['url' => 'foo'],
                            ['url' => 'home'],
                        ],
                    ],
                ],
            ]
        );

        $this->assertTrue($isActive);
    }

    public function testExplicitActive()
    {
        $checker = $this->makeActiveChecker('http://example.com/home');

        $isActive = $checker->isActive(['active' => ['home']]);
        $this->assertTrue($isActive);

        $isActive = $checker->isActive(['active' => ['about']]);
        $this->assertFalse($isActive);
    }

    public function testExplicitActiveRegex()
    {
        $checker = $this->makeActiveChecker('http://example.com/home/sub');

        $isActive = $checker->isActive(['active' => ['home/*']]);
        $this->assertTrue($isActive);

        $isActive = $checker->isActive(['active' => ['home/su*']]);
        $this->assertTrue($isActive);

        $isActive = $checker->isActive(['active' => ['hom*']]);
        $this->assertTrue($isActive);

        $isActive = $checker->isActive(['active' => ['home/t*']]);
        $this->assertFalse($isActive);
    }

    public function testExplicitOverridesDefault()
    {
        $checker = $this->makeActiveChecker('http://example.com/admin/users');

        $isActive = $checker->isActive(['active' => ['admin']]);
        $this->assertFalse($isActive);
    }

    public function testFullUrl()
    {
        $checker = $this->makeActiveChecker('http://example.com/about');

        $isActive = $checker->isActive(['url' => 'http://example.com/about']);
        $this->assertTrue($isActive);
    }

    public function testFullUrlSub()
    {
        $checker = $this->makeActiveChecker('http://example.com/about/sub');

        $isActive = $checker->isActive(['url' => 'http://example.com/about/sub']);
        $this->assertTrue($isActive);
    }

    public function testHttps()
    {
        $checker = $this->makeActiveChecker('https://example.com/about');

        $isActive = $checker->isActive(['url' => 'https://example.com/about']);
        $this->assertTrue($isActive);

        $isActive = $checker->isActive(['url' => 'about']);
        $this->assertTrue($isActive);
    }

    public function testParams()
    {
        $checker = $this->makeActiveChecker('http://example.com/menu?param=option');

        $this->assertTrue($checker->isActive(['url' => 'menu']));
        $this->assertTrue($checker->isActive(['active' => ['menu']]));
        $this->assertTrue($checker->isActive(['active' => ['menu?param=option']]));
        $this->assertFalse($checker->isActive(['active' => ['menu?param=foo']]));
    }

    public function testSubParams()
    {
        $checker = $this->makeActiveChecker('http://example.com/menu/item1?param=option');

        $this->assertTrue($checker->isActive(['url' => 'menu/item1']));
        $this->assertTrue($checker->isActive(['active' => ['menu/*']]));
    }

    public function testExplicitActiveRegexEvaluation()
    {
        $checker = $this->makeActiveChecker('http://example.com/posts/1');

        $this->assertTrue($checker->isActive(['active' => ['regex:@^posts/[0-9]+$@']]));
        $this->assertFalse($checker->isActive(['active' => ['regex:@^post/[0-9]+$@']]));
    }

    public function testActivefallbackToUrl()
    {
        $checker = $this->makeActiveChecker('http://example.com/home');

        $isActive = $checker->isActive(
            [
                'url' => 'home',
                'active' => ['about', 'no-home'],
                'submenu' => [],
            ]
        );

        $this->assertTrue($isActive);
    }

    public function testWithForcedScheme()
    {
        $checker = $this->makeActiveChecker('http://example.com/about', 'https');

        $isActive = $checker->isActive(['url' => 'about']);
        $this->assertTrue($isActive);

        $isActive = $checker->isActive(['url' => 'http://example.com/about']);
        $this->assertTrue($isActive);

        $isActive = $checker->isActive(['url' => 'https://example.com/about']);
        $this->assertTrue($isActive);
    }
}

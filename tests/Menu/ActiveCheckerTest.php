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

        $this->assertTrue($checker->isActive(['url' => 'about']));
    }

    public function testSubmenu()
    {
        $checker = $this->makeActiveChecker('http://example.com/home');

        $isActive = $checker->isActive(
            [
                'submenu' => [
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
    }

    public function testExplicitActiveRegex()
    {
        $checker = $this->makeActiveChecker('http://example.com/home/sub');

        $isActive = $checker->isActive(['active' => ['home/*']]);

        $this->assertTrue($isActive);
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

        $isActive = $checker->isActive(['url' => 'http://example.com/about']);

        $this->assertTrue($isActive);
    }

    public function testHttps()
    {
        $checker = $this->makeActiveChecker('https://example.com/about');

        $isActive = $checker->isActive(['url' => 'https://example.com/about']);

        $this->assertTrue($isActive);
    }
}

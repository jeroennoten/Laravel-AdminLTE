<?php

use Illuminate\Auth\GenericUser;
use Illuminate\Support\Facades\Gate;

class GateFilterTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // Setup acting as a generic user on all tests here.

        $this->actingAs(new GenericUser([]));
    }

    public function testCanProperty()
    {
        // Define some Gate rules.

        Gate::define('show-about', function () {
            return true;
        });
        Gate::define('show-home', function () {
            return false;
        });

        // Create the menu.

        $builder = $this->makeMenuBuilder();

        $builder->add(
            ['text' => 'About', 'url' => 'about', 'can' => 'show-about'],
            ['text' => 'Home', 'url' => '/', 'can' => 'show-home']
        );

        // Make test assertions.

        $this->assertCount(1, $builder->menu);
        $this->assertEquals('About', $builder->menu[0]['text']);
    }

    public function testCanPropertyWithOneRestrictedItem()
    {
        // Define some Gate rules.

        Gate::define('show-home', function () {
            return false;
        });

        // Create the menu.

        $builder = $this->makeMenuBuilder();

        $builder->add(
            ['text' => 'Home', 'url' => '/', 'can' => 'show-home']
        );

        // Make test assertions.

        $this->assertCount(0, $builder->menu);
    }

    public function testCanPropertyWithInvalidValues()
    {
        // Define some Gate rules.

        Gate::define('show-about', function () {
            return true;
        });
        Gate::define('show-home', function () {
            return false;
        });

        // Create the menu.

        $builder = $this->makeMenuBuilder();

        $builder->add(
            ['text' => 'LinkA', 'url' => 'link_a', 'can' => false],
            ['text' => 'LinkB', 'url' => 'link_b', 'can' => 1024],
            ['text' => 'LinkC', 'url' => 'link_c', 'can' => ''],
            ['text' => 'LinkD', 'url' => 'link_d', 'can' => []],
            ['text' => 'LinkE', 'url' => 'link_e']
        );

        // Make test assertions.

        $this->assertCount(5, $builder->menu);
        $this->assertEquals('LinkA', $builder->menu[0]['text']);
        $this->assertEquals('LinkB', $builder->menu[1]['text']);
        $this->assertEquals('LinkC', $builder->menu[2]['text']);
        $this->assertEquals('LinkD', $builder->menu[3]['text']);
        $this->assertEquals('LinkE', $builder->menu[4]['text']);
    }

    public function testCanPropertyWithMultipleValues()
    {
        // Define some Gate rules.

        Gate::define('show-users', function () {
            return true;
        });
        Gate::define('edit-user', function () {
            return false;
        });
        Gate::define('show-settings', function () {
            return false;
        });

        // Create the menu.

        $builder = $this->makeMenuBuilder();

        $builder->add(
            [
                'text' => 'Users',
                'url' => 'users',
                'can' => ['show-users', 'edit-user'],
            ],
            [
                'text' => 'Settings',
                'url' => 'settings',
                'can' => ['show-settings'],
            ]
        );

        // Make test assertions.

        $this->assertCount(1, $builder->menu);
        $this->assertEquals('Users', $builder->menu[0]['text']);
    }

    public function testCanPropertyOnHeaders()
    {
        // Define some Gate rules.

        Gate::define('show-header', function () {
            return true;
        });
        Gate::define('show-settings', function () {
            return false;
        });

        // Create the menu.

        $builder = $this->makeMenuBuilder();

        $builder->add(
            ['header' => 'HEADER', 'can' => 'show-header'],
            ['header' => 'SETTINGS', 'can' => 'show-settings']
        );

        // Make test assertions.

        $this->assertCount(1, $builder->menu);
        $this->assertStringContainsString('HEADER', $builder->menu[0]['header']);
    }

    public function testCanPropertyOnSubmenu()
    {
        // Define some Gate rules.

        Gate::define('show-about', function () {
            return true;
        });
        Gate::define('show-home', function () {
            return false;
        });

        // Create the menu.

        $builder = $this->makeMenuBuilder();

        $builder->add(
            [
                'text' => 'Submenu',
                'submenu' => [
                    [
                        'text' => 'About',
                        'url' => 'about',
                        'can' => 'show-about',
                    ],
                    [
                        'text' => 'Home',
                        'url' => '/',
                        'can' => 'show-home',
                    ],
                ],
            ]
        );

        // Make test assertions.

        $this->assertCount(1, $builder->menu);
        $this->assertCount(1, $builder->menu[0]['submenu']);
        $this->assertEquals('About', $builder->menu[0]['submenu'][0]['text']);
    }

    public function testCanPropertyOnWholeRestrictedSubmenu()
    {
        // Define some Gate rules.

        Gate::define('show-about', function () {
            return false;
        });
        Gate::define('show-home', function () {
            return false;
        });

        // Create the menu.

        $builder = $this->makeMenuBuilder();

        $builder->add(
            [
                'text' => 'Submenu1',
                'submenu' => [
                    [
                        'text' => 'About',
                        'url' => 'about',
                        'can' => 'show-about',
                    ],
                    [
                        'text' => 'Home',
                        'url' => '/',
                        'can' => 'show-home',
                    ],
                    [
                        'text' => 'Submenu2',
                        'submenu' => [
                            [
                                'text' => 'Home1',
                                'url' => '/home1',
                                'can' => 'show-home',
                            ],
                            [
                                'text' => 'Home2',
                                'url' => '/home2',
                                'can' => 'show-home',
                            ],
                        ],
                    ],
                ],
            ]
        );

        // Make test assertions.

        $this->assertCount(0, $builder->menu);
    }
}

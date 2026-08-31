<?php

use Illuminate\Support\Facades\Route;

class MenuFromConfigTest extends TestCase
{
    /**
     * Setup this testing class.
     */
    public function setUp(): void
    {
        parent::setUp();

        // Register a route to be used by the menu items that are defined
        // through the 'route' property.

        Route::get('/pages', function () {
            return 'pages';
        })->name('pages.index');

        // Setup a static menu configuration exercising every item type that
        // is supported by the package.

        config(['adminlte.menu' => $this->makeMenuConfig()]);
    }

    /**
     * Makes a menu configuration with every supported item type.
     *
     * @return array
     */
    protected function makeMenuConfig()
    {
        return [
            // A plain string header (sidebar).

            'MAIN NAVIGATION',

            // An array header (sidebar).

            ['key' => 'labels-header', 'header' => 'LABELS'],

            // A link defined by an url (sidebar).

            ['key' => 'dashboard', 'text' => 'dashboard', 'url' => '/', 'icon' => 'bi-speedometer'],

            // A link defined by a route (sidebar).

            ['key' => 'pages', 'text' => 'pages', 'route' => 'pages.index'],

            // A link with custom classes, data attributes and a label.

            [
                'key' => 'decorated',
                'text' => 'decorated',
                'url' => 'decorated',
                'classes' => 'custom-class',
                'label' => 'new',
                'label_color' => 'success',
                'data' => ['toggle' => 'tooltip', 'placement' => 'right'],
            ],

            // A submenu with a nested submenu (sidebar).

            [
                'key' => 'multilevel',
                'text' => 'multilevel',
                'icon' => 'bi-folder',
                'submenu' => [
                    ['key' => 'level-one', 'text' => 'level-one', 'url' => 'level/one'],
                    [
                        'key' => 'level-two',
                        'text' => 'level-two',
                        'submenu' => [
                            ['key' => 'level-three', 'text' => 'level-three', 'url' => 'level/three'],
                        ],
                    ],
                ],
            ],

            // An empty submenu, it should be removed from the menu.

            ['key' => 'empty-submenu', 'text' => 'empty-submenu', 'submenu' => []],

            // A restricted item, it should be removed from the menu.

            ['key' => 'restricted', 'text' => 'restricted', 'url' => 'restricted', 'can' => 'manage-admins'],

            // The three flavors of a sidebar search box.

            ['key' => 'legacy-search', 'text' => 'legacy-search', 'search' => true],
            ['key' => 'custom-search', 'text' => 'custom-search', 'type' => 'sidebar-custom-search'],
            ['key' => 'menu-search', 'text' => 'menu-search', 'type' => 'sidebar-menu-search'],

            // A link on the left section of the navbar.

            ['key' => 'navbar-left-link', 'text' => 'navbar-left-link', 'url' => 'left', 'topnav' => true],

            // A submenu on the right section of the navbar.

            [
                'key' => 'navbar-right-submenu',
                'text' => 'navbar-right-submenu',
                'topnav_right' => true,
                'submenu' => [
                    ['key' => 'navbar-subitem', 'text' => 'navbar-subitem', 'url' => 'navbar/subitem'],
                ],
            ],

            // The navbar search box.

            [
                'key' => 'navbar-search',
                'text' => 'navbar-search',
                'type' => 'navbar-search',
                'topnav_right' => true,
            ],

            // The navbar widgets.

            ['key' => 'fullscreen-widget', 'type' => 'fullscreen-widget', 'topnav_right' => true],
            ['key' => 'darkmode-widget', 'type' => 'darkmode-widget', 'topnav_right' => true],

            // A navbar notification.

            [
                'key' => 'notification',
                'id' => 'my-notification',
                'type' => 'navbar-notification',
                'icon' => 'bi-bell',
                'url' => 'notifications',
                'topnav_right' => true,
            ],

            // An item of the user menu.

            ['key' => 'profile', 'text' => 'profile', 'url' => 'profile', 'topnav_user' => true],
        ];
    }

    /**
     * Compiles the configured menu and returns the requested section. The
     * menu is compiled with the default set of filters of the package.
     *
     * @param  string  $section  The menu section to get
     * @return array
     */
    protected function compileMenu($section = null)
    {
        $filters = config('adminlte.filters', []);

        return $this->makeAdminLte($filters)->menu($section);
    }

    /**
     * Gets the menu items indexed by their key property. Note the text of the
     * items may be translated by the language filter, so the key property is
     * a more reliable index.
     *
     * @param  string  $section  The menu section to get
     * @return array
     */
    protected function getMenuByKey($section = null)
    {
        $menu = [];

        foreach ($this->compileMenu($section) as $item) {
            $key = is_string($item) ? $item : ($item['key'] ?? null);

            if (isset($key)) {
                $menu[$key] = $item;
            }
        }

        return $menu;
    }

    public function testTheInvalidItemsAreRemovedFromTheMenu()
    {
        $menu = $this->getMenuByKey();

        // The empty submenu and the restricted item are not compiled.

        $this->assertArrayNotHasKey('empty-submenu', $menu);
        $this->assertArrayNotHasKey('restricted', $menu);

        // All the other items are available on the compiled menu.

        $this->assertCount(16, $this->compileMenu());
    }

    public function testTheSidebarItemsAreDetected()
    {
        $menu = $this->getMenuByKey('sidebar');

        $expected = [
            'MAIN NAVIGATION', 'labels-header', 'dashboard', 'pages', 'decorated',
            'multilevel', 'legacy-search', 'custom-search', 'menu-search',
        ];

        foreach ($expected as $label) {
            $this->assertArrayHasKey($label, $menu);
        }

        $this->assertCount(count($expected), $menu);
    }

    public function testTheNavbarLeftItemsAreDetected()
    {
        $menu = $this->getMenuByKey('navbar-left');

        $this->assertCount(1, $menu);
        $this->assertArrayHasKey('navbar-left-link', $menu);
    }

    public function testTheNavbarRightItemsAreDetected()
    {
        $menu = $this->getMenuByKey('navbar-right');

        $expected = [
            'navbar-right-submenu', 'navbar-search', 'fullscreen-widget',
            'darkmode-widget', 'notification',
        ];

        foreach ($expected as $label) {
            $this->assertArrayHasKey($label, $menu);
        }

        $this->assertCount(5, $menu);
        $this->assertEquals('my-notification', $menu['notification']['id']);
    }

    public function testTheNavbarUserMenuItemsAreDetected()
    {
        $menu = $this->getMenuByKey('navbar-user');

        $this->assertCount(1, $menu);
        $this->assertArrayHasKey('profile', $menu);
    }

    public function testTheHrefFilterIsAppliedOnTheConfiguredMenu()
    {
        $menu = $this->getMenuByKey();

        $this->assertEquals(url('/'), $menu['dashboard']['href']);
        $this->assertEquals(route('pages.index'), $menu['pages']['href']);

        // A submenu item without a location gets the default href.

        $this->assertEquals('#', $menu['multilevel']['href']);

        // The nested items are compiled too.

        $submenu = $menu['multilevel']['submenu'];

        $this->assertEquals(url('level/one'), $submenu[0]['href']);
        $this->assertEquals(url('level/three'), $submenu[1]['submenu'][0]['href']);

        // The headers have no href property.

        $this->assertArrayNotHasKey('href', $menu['labels-header']);
    }

    public function testTheClassesAndDataFiltersAreAppliedOnTheConfiguredMenu()
    {
        $menu = $this->getMenuByKey();

        $this->assertStringContainsString('custom-class', $menu['decorated']['class']);

        $this->assertEquals(
            'data-toggle="tooltip" data-placement="right"',
            $menu['decorated']['data-compiled']
        );

        // The items without data attributes are not affected.

        $this->assertArrayNotHasKey('data-compiled', $menu['dashboard']);
    }

    public function testTheSearchFilterIsAppliedOnTheConfiguredMenu()
    {
        $menu = $this->getMenuByKey();

        foreach (['legacy-search', 'custom-search', 'menu-search', 'navbar-search'] as $key) {
            $this->assertEquals('get', $menu[$key]['method']);
            $this->assertEquals('adminlteSearch', $menu[$key]['input_name']);
        }

        // The other items are not affected by the search filter.

        $this->assertArrayNotHasKey('method', $menu['dashboard']);
    }

    public function testTheActiveFilterIsAppliedOnTheConfiguredMenu()
    {
        // Emulate a request over one of the nested submenu items.

        $this->get('level/three');

        $menu = $this->getMenuByKey();

        // The item and all of its ancestors are active.

        $this->assertTrue($menu['multilevel']['active']);
        $this->assertTrue($menu['multilevel']['submenu'][1]['active']);
        $this->assertTrue($menu['multilevel']['submenu'][1]['submenu'][0]['active']);

        // The other items are not active.

        $this->assertFalse($menu['dashboard']['active']);
        $this->assertFalse($menu['multilevel']['submenu'][0]['active']);

        // The active sidebar submenus are opened.

        $this->assertStringContainsString(
            'menu-open',
            $menu['multilevel']['submenu_class']
        );
    }

    public function testTheMenuIsEmptyWithoutAConfiguration()
    {
        // An invalid or missing menu configuration produces an empty menu.

        config(['adminlte.menu' => null]);
        $this->assertCount(0, $this->compileMenu());

        config(['adminlte.menu' => 'invalid']);
        $this->assertCount(0, $this->compileMenu());

        config(['adminlte.menu' => []]);
        $this->assertCount(0, $this->compileMenu());
    }

    public function testAnUnknownSectionReturnsTheWholeMenu()
    {
        $all = $this->compileMenu();
        $dummy = $this->compileMenu('dummy-section');

        $this->assertEquals($all, $dummy);
    }

    public function testTheSidebarItemsMoveToTheNavbarOnTheTopnavLayout()
    {
        config(['adminlte.layout_topnav' => true]);

        $menu = $this->getMenuByKey('navbar-left');

        // The sidebar links and submenus are moved to the navbar, but the
        // headers are not accepted there.

        $this->assertArrayHasKey('dashboard', $menu);
        $this->assertArrayHasKey('multilevel', $menu);
        $this->assertArrayHasKey('navbar-left-link', $menu);
        $this->assertArrayNotHasKey('labels-header', $menu);
        $this->assertArrayNotHasKey('MAIN NAVIGATION', $menu);

        // The items of the other navbar sections are not moved.

        $this->assertArrayNotHasKey('profile', $menu);
        $this->assertArrayNotHasKey('navbar-right-submenu', $menu);
    }
}

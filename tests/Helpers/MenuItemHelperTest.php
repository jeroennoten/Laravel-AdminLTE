<?php

use JeroenNoten\LaravelAdminLte\Helpers\MenuItemHelper;

class MenuItemHelperTest extends TestCase
{
    public function testIsHeader()
    {
        // A plain string is a header.

        $this->assertTrue(MenuItemHelper::isHeader('MAIN NAVIGATION'));

        // An item with the 'header' property is a header too.

        $this->assertTrue(MenuItemHelper::isHeader(['header' => 'LABELS']));

        // Other items are not headers.

        $this->assertFalse(MenuItemHelper::isHeader(['text' => 'Home', 'url' => '/']));
        $this->assertFalse(MenuItemHelper::isHeader(['text' => 'Home']));
        $this->assertFalse(MenuItemHelper::isHeader([]));
    }

    public function testIsLink()
    {
        // A link requires a text and an url or a route.

        $this->assertTrue(MenuItemHelper::isLink(['text' => 'Home', 'url' => '/']));
        $this->assertTrue(MenuItemHelper::isLink(['text' => 'Home', 'route' => 'home']));

        // Without the text or the location it is not a link.

        $this->assertFalse(MenuItemHelper::isLink(['text' => 'Home']));
        $this->assertFalse(MenuItemHelper::isLink(['url' => '/']));
        $this->assertFalse(MenuItemHelper::isLink(['route' => 'home']));
        $this->assertFalse(MenuItemHelper::isLink(['header' => 'LABELS']));
        $this->assertFalse(MenuItemHelper::isLink('MAIN NAVIGATION'));
    }

    public function testIsSubmenu()
    {
        $submenu = [
            'text' => 'Pages',
            'submenu' => [
                ['text' => 'Page 1', 'url' => 'pages/1'],
            ],
        ];

        $this->assertTrue(MenuItemHelper::isSubmenu($submenu));

        // An empty submenu is still a submenu (it is filtered out later by
        // the isAllowed() method).

        $this->assertTrue(MenuItemHelper::isSubmenu(['text' => 'Pages', 'submenu' => []]));

        // A submenu requires an array on the 'submenu' property.

        $this->assertFalse(MenuItemHelper::isSubmenu(['text' => 'Pages', 'submenu' => 'invalid']));
        $this->assertFalse(MenuItemHelper::isSubmenu(['submenu' => []]));
        $this->assertFalse(MenuItemHelper::isSubmenu(['text' => 'Pages']));
    }

    public function testIsLegacySearch()
    {
        $this->assertTrue(MenuItemHelper::isLegacySearch(['text' => 'Search', 'search' => true]));

        // A falsy 'search' property is not a search bar.

        $this->assertFalse(MenuItemHelper::isLegacySearch(['text' => 'Search', 'search' => false]));
        $this->assertFalse(MenuItemHelper::isLegacySearch(['search' => true]));
        $this->assertFalse(MenuItemHelper::isLegacySearch(['text' => 'Search']));
    }

    public function testIsAllowed()
    {
        // A regular item without restrictions is allowed.

        $this->assertTrue(MenuItemHelper::isAllowed(['text' => 'Home', 'url' => '/']));
        $this->assertTrue(MenuItemHelper::isAllowed('MAIN NAVIGATION'));

        // A restricted item is not allowed.

        $item = ['text' => 'Home', 'url' => '/', 'restricted' => true];
        $this->assertFalse(MenuItemHelper::isAllowed($item));

        // An empty item is not allowed.

        $this->assertFalse(MenuItemHelper::isAllowed([]));
        $this->assertFalse(MenuItemHelper::isAllowed(null));
    }

    public function testIsAllowedOnSubmenuItems()
    {
        // A submenu with children is allowed.

        $submenu = [
            'text' => 'Pages',
            'submenu' => [
                ['text' => 'Page 1', 'url' => 'pages/1'],
            ],
        ];

        $this->assertTrue(MenuItemHelper::isAllowed($submenu));

        // An empty submenu is never allowed.

        $this->assertFalse(MenuItemHelper::isAllowed(['text' => 'Pages', 'submenu' => []]));

        // A restricted submenu is not allowed either.

        $submenu['restricted'] = true;
        $this->assertFalse(MenuItemHelper::isAllowed($submenu));
    }
}

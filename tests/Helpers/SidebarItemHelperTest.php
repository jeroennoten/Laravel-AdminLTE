<?php

use JeroenNoten\LaravelAdminLte\Helpers\SidebarItemHelper;

class SidebarItemHelperTest extends TestCase
{
    public function testIsCustomSearch()
    {
        $item = ['text' => 'Search', 'type' => 'sidebar-custom-search'];
        $this->assertTrue(SidebarItemHelper::isCustomSearch($item));

        // The menu search and the navbar search are not a custom search box.

        $item = ['text' => 'Search', 'type' => 'sidebar-menu-search'];
        $this->assertFalse(SidebarItemHelper::isCustomSearch($item));

        $item = ['text' => 'Search', 'type' => 'navbar-search'];
        $this->assertFalse(SidebarItemHelper::isCustomSearch($item));

        // The text property is required.

        $this->assertFalse(SidebarItemHelper::isCustomSearch(['type' => 'sidebar-custom-search']));
    }

    public function testIsMenuSearch()
    {
        $item = ['text' => 'Search', 'type' => 'sidebar-menu-search'];
        $this->assertTrue(SidebarItemHelper::isMenuSearch($item));

        $item = ['text' => 'Search', 'type' => 'sidebar-custom-search'];
        $this->assertFalse(SidebarItemHelper::isMenuSearch($item));

        $this->assertFalse(SidebarItemHelper::isMenuSearch(['type' => 'sidebar-menu-search']));
    }

    public function testIsSearch()
    {
        // The legacy and the two new search box definitions are accepted.

        $this->assertTrue(SidebarItemHelper::isSearch(['text' => 'Search', 'search' => true]));

        $item = ['text' => 'Search', 'type' => 'sidebar-custom-search'];
        $this->assertTrue(SidebarItemHelper::isSearch($item));

        $item = ['text' => 'Search', 'type' => 'sidebar-menu-search'];
        $this->assertTrue(SidebarItemHelper::isSearch($item));

        // The navbar search box does not belong to the sidebar.

        $item = ['text' => 'Search', 'type' => 'navbar-search'];
        $this->assertFalse(SidebarItemHelper::isSearch($item));

        $this->assertFalse(SidebarItemHelper::isSearch(['text' => 'Home', 'url' => '/']));
    }

    public function testIsAcceptedItem()
    {
        $accepted = [
            'MAIN NAVIGATION',
            ['header' => 'LABELS'],
            ['text' => 'Home', 'url' => '/'],
            ['text' => 'Home', 'route' => 'home'],
            ['text' => 'Pages', 'submenu' => [['text' => 'P1', 'url' => 'p1']]],
            ['text' => 'Search', 'search' => true],
            ['text' => 'Search', 'type' => 'sidebar-custom-search'],
            ['text' => 'Search', 'type' => 'sidebar-menu-search'],
        ];

        foreach ($accepted as $idx => $item) {
            $this->assertTrue(
                SidebarItemHelper::isAcceptedItem($item),
                "The sidebar item with index {$idx} was not accepted"
            );
        }

        // The navbar exclusive widgets are not accepted on the sidebar.

        $this->assertFalse(SidebarItemHelper::isAcceptedItem(['type' => 'fullscreen-widget']));
        $this->assertFalse(SidebarItemHelper::isAcceptedItem(['type' => 'darkmode-widget']));

        // An incomplete item is not accepted either.

        $this->assertFalse(SidebarItemHelper::isAcceptedItem(['text' => 'Home']));
    }

    public function testIsValidItem()
    {
        $item = ['text' => 'Home', 'url' => '/'];
        $this->assertTrue(SidebarItemHelper::isValidItem($item));

        // A header always belongs to the sidebar.

        $this->assertTrue(SidebarItemHelper::isValidItem(['header' => 'LABELS']));
        $this->assertTrue(SidebarItemHelper::isValidItem('MAIN NAVIGATION'));

        // An item flagged for any of the navbar sections is not valid.

        foreach (['topnav', 'topnav_right', 'topnav_user'] as $flag) {
            $navbarItem = array_merge($item, [$flag => true]);

            $this->assertFalse(
                SidebarItemHelper::isValidItem($navbarItem),
                "An item flagged with '{$flag}' was accepted on the sidebar"
            );

            // A falsy flag keeps the item on the sidebar.

            $navbarItem[$flag] = false;
            $this->assertTrue(SidebarItemHelper::isValidItem($navbarItem));
        }
    }
}

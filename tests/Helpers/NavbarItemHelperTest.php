<?php

use JeroenNoten\LaravelAdminLte\Helpers\NavbarItemHelper;

class NavbarItemHelperTest extends TestCase
{
    /**
     * Gets a menu item of the specified type, with the given extra data.
     *
     * @param  string  $type  The type of the item to make
     * @param  array  $extra  Extra properties for the item
     * @return array
     */
    protected function makeItem($type, $extra = [])
    {
        $items = [
            'link' => ['text' => 'Home', 'url' => '/'],
            'route-link' => ['text' => 'Home', 'route' => 'home'],
            'header' => ['header' => 'LABELS'],
            'submenu' => [
                'text' => 'Pages',
                'submenu' => [['text' => 'Page 1', 'url' => 'pages/1']],
            ],
            'legacy-search' => ['text' => 'Search', 'search' => true],
            'custom-search' => ['text' => 'Search', 'type' => 'navbar-search'],
            'fullscreen' => ['type' => 'fullscreen-widget'],
            'darkmode' => ['type' => 'darkmode-widget'],
            'notification' => [
                'id' => 'my-notification',
                'icon' => 'bi-bell',
                'type' => 'navbar-notification',
                'url' => 'notifications',
            ],
        ];

        return array_merge($items[$type], $extra);
    }

    public function testIsCustomSearch()
    {
        $this->assertTrue(NavbarItemHelper::isCustomSearch($this->makeItem('custom-search')));

        // The sidebar search types are not a navbar search box.

        $item = ['text' => 'Search', 'type' => 'sidebar-custom-search'];
        $this->assertFalse(NavbarItemHelper::isCustomSearch($item));

        // The text property is required.

        $this->assertFalse(NavbarItemHelper::isCustomSearch(['type' => 'navbar-search']));
        $this->assertFalse(NavbarItemHelper::isCustomSearch($this->makeItem('link')));
    }

    public function testIsFullscreen()
    {
        $this->assertTrue(NavbarItemHelper::isFullscreen($this->makeItem('fullscreen')));

        $this->assertFalse(NavbarItemHelper::isFullscreen($this->makeItem('darkmode')));
        $this->assertFalse(NavbarItemHelper::isFullscreen($this->makeItem('link')));
    }

    public function testIsDarkmode()
    {
        $this->assertTrue(NavbarItemHelper::isDarkmode($this->makeItem('darkmode')));

        $this->assertFalse(NavbarItemHelper::isDarkmode($this->makeItem('fullscreen')));
        $this->assertFalse(NavbarItemHelper::isDarkmode($this->makeItem('link')));
    }

    public function testIsNotification()
    {
        $this->assertTrue(NavbarItemHelper::isNotification($this->makeItem('notification')));

        // A notification may also be defined by a route.

        $item = $this->makeItem('notification');
        unset($item['url']);
        $item['route'] = 'notifications';

        $this->assertTrue(NavbarItemHelper::isNotification($item));

        // The id, the icon, the type and the location are all required.

        foreach (['id', 'icon', 'type', 'url'] as $prop) {
            $item = $this->makeItem('notification');
            unset($item[$prop]);

            $this->assertFalse(
                NavbarItemHelper::isNotification($item),
                "A notification without the '{$prop}' property was accepted"
            );
        }

        // An item with another type is not a notification.

        $item = $this->makeItem('notification', ['type' => 'navbar-search']);
        $this->assertFalse(NavbarItemHelper::isNotification($item));
    }

    public function testIsSearch()
    {
        // Both the legacy and the new search box definitions are accepted.

        $this->assertTrue(NavbarItemHelper::isSearch($this->makeItem('legacy-search')));
        $this->assertTrue(NavbarItemHelper::isSearch($this->makeItem('custom-search')));

        $this->assertFalse(NavbarItemHelper::isSearch($this->makeItem('link')));
    }

    public function testIsAcceptedItem()
    {
        $accepted = [
            'link', 'route-link', 'submenu', 'legacy-search', 'custom-search',
            'fullscreen', 'darkmode', 'notification',
        ];

        foreach ($accepted as $type) {
            $this->assertTrue(
                NavbarItemHelper::isAcceptedItem($this->makeItem($type)),
                "The item of type '{$type}' was not accepted on the navbar"
            );
        }

        // A header is not accepted on the navbar.

        $this->assertFalse(NavbarItemHelper::isAcceptedItem($this->makeItem('header')));
        $this->assertFalse(NavbarItemHelper::isAcceptedItem('MAIN NAVIGATION'));

        // An incomplete item is not accepted either.

        $this->assertFalse(NavbarItemHelper::isAcceptedItem(['text' => 'Home']));
    }

    public function testIsValidLeftItem()
    {
        // Only the items flagged with 'topnav' belong to the left section.

        $item = $this->makeItem('link', ['topnav' => true]);
        $this->assertTrue(NavbarItemHelper::isValidLeftItem($item));

        $item = $this->makeItem('link', ['topnav' => false]);
        $this->assertFalse(NavbarItemHelper::isValidLeftItem($item));

        $this->assertFalse(NavbarItemHelper::isValidLeftItem($this->makeItem('link')));

        // A not accepted item is never valid, even when flagged.

        $item = $this->makeItem('header', ['topnav' => true]);
        $this->assertFalse(NavbarItemHelper::isValidLeftItem($item));
    }

    public function testIsValidRightItem()
    {
        $item = $this->makeItem('link', ['topnav_right' => true]);
        $this->assertTrue(NavbarItemHelper::isValidRightItem($item));

        $item = $this->makeItem('link', ['topnav_right' => false]);
        $this->assertFalse(NavbarItemHelper::isValidRightItem($item));

        // The left flag does not make an item valid for the right section.

        $item = $this->makeItem('link', ['topnav' => true]);
        $this->assertFalse(NavbarItemHelper::isValidRightItem($item));

        $item = $this->makeItem('header', ['topnav_right' => true]);
        $this->assertFalse(NavbarItemHelper::isValidRightItem($item));
    }

    public function testIsValidUserMenuItem()
    {
        $item = $this->makeItem('link', ['topnav_user' => true]);
        $this->assertTrue(NavbarItemHelper::isValidUserMenuItem($item));

        $item = $this->makeItem('link', ['topnav_user' => false]);
        $this->assertFalse(NavbarItemHelper::isValidUserMenuItem($item));

        $item = $this->makeItem('link', ['topnav_right' => true]);
        $this->assertFalse(NavbarItemHelper::isValidUserMenuItem($item));

        $item = $this->makeItem('header', ['topnav_user' => true]);
        $this->assertFalse(NavbarItemHelper::isValidUserMenuItem($item));
    }

    public function testTheWidgetsAreValidOnEveryNavbarSection()
    {
        foreach (['fullscreen', 'darkmode', 'notification'] as $type) {
            $left = $this->makeItem($type, ['topnav' => true]);
            $right = $this->makeItem($type, ['topnav_right' => true]);
            $user = $this->makeItem($type, ['topnav_user' => true]);

            $this->assertTrue(NavbarItemHelper::isValidLeftItem($left));
            $this->assertTrue(NavbarItemHelper::isValidRightItem($right));
            $this->assertTrue(NavbarItemHelper::isValidUserMenuItem($user));
        }
    }
}

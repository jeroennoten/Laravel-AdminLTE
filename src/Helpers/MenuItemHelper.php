<?php

namespace JeroenNoten\LaravelAdminLte\Helpers;

/**
 * TODO: On the future, all menu items should have a type property. We can use
 * the type property to easy distinguish the item type and avoid guessing it by
 * they properties.
 */
class MenuItemHelper
{
    /**
     * Check if a menu item is a header.
     *
     * @param mixed $item
     * @return bool
     */
    public static function isHeader($item)
    {
        return is_string($item) || isset($item['header']);
    }

    /**
     * Check if a menu item is a link.
     *
     * @param mixed $item
     * @return bool
     */
    public static function isLink($item)
    {
        return isset($item['text']) &&
               (isset($item['url']) || isset($item['route']));
    }

    /**
     * Check if a menu item is a submenu.
     *
     * @param mixed $item
     * @return bool
     */
    public static function isSubmenu($item)
    {
        return isset($item['text']) &&
               isset($item['submenu']) &&
               is_array($item['submenu']);
    }

    /**
     * Check if a menu item is a legacy search bar.
     *
     * @param mixed $item
     * @return bool
     */
    public static function isLegacySearch($item)
    {
        return isset($item['text']) &&
               isset($item['search']) &&
               $item['search'];
    }

    /**
     * Check if a menu item is a navbar custom search bar.
     *
     * @param mixed $item
     * @return bool
     */
    public static function isNavbarCustomSearch($item)
    {
        return isset($item['text']) &&
               isset($item['type']) &&
               $item['type'] === 'navbar-search';
    }

    /**
     * Check if a menu item is a sidebar custom search bar.
     *
     * @param mixed $item
     * @return bool
     */
    public static function isSidebarCustomSearch($item)
    {
        return isset($item['text']) &&
               isset($item['type']) &&
               $item['type'] === 'sidebar-custom-search';
    }

    /**
     * Check if a menu item is a sidebar menu search bar.
     *
     * @param mixed $item
     * @return bool
     */
    public static function isSidebarMenuSearch($item)
    {
        return isset($item['text']) &&
               isset($item['type']) &&
               $item['type'] === 'sidebar-menu-search';
    }

    /**
     * Check if a menu item is a fullscreen toggle widget.
     *
     * @param mixed $item
     * @return bool
     */
    public static function isFullscreen($item)
    {
        return isset($item['type']) &&
               $item['type'] === 'fullscreen-widget';
    }

    /**
     * Check if a menu item is a navbar search item (legacy or new).
     *
     * @param mixed $item
     * @return bool
     */
    public static function isNavbarSearch($item)
    {
        return self::isLegacySearch($item) ||
               self::isNavbarCustomSearch($item);
    }

    /**
     * Check if a menu item is a sidebar search item (legacy or new).
     *
     * @param mixed $item
     * @return bool
     */
    public static function isSidebarSearch($item)
    {
        return self::isLegacySearch($item) ||
               self::isSidebarMenuSearch($item) ||
               self::isSidebarCustomSearch($item);
    }

    /**
     * Check if a menu item is allowed to be shown.
     *
     * @param mixed $item
     * @return bool
     */
    public static function isAllowed($item)
    {
        $isAllowed = ! (isset($item['restricted']) && $item['restricted']);

        return $item && $isAllowed;
    }

    /**
     * Check if a menu item is valid for the sidebar section.
     *
     * @param mixed $item
     * @return bool
     */
    public static function isValidSidebarItem($item)
    {
        return self::isHeader($item) ||
               self::isSidebarSearch($item) ||
               self::isSubmenu($item) ||
               self::isLink($item);
    }

    /**
     * Check if a menu item is valid for the navbar section.
     *
     * @param mixed $item
     * @return bool
     */
    public static function isValidNavbarItem($item)
    {
        return self::isNavbarSearch($item) ||
               self::isFullscreen($item) ||
               self::isSubmenu($item) ||
               self::isLink($item);
    }

    /**
     * Check if a menu item belongs to the left section of the navbar.
     *
     * @param mixed $item
     * @return bool
     */
    public static function isNavbarLeftItem($item)
    {
        return self::isValidNavbarItem($item) &&
               isset($item['topnav']) &&
               $item['topnav'];
    }

    /**
     * Check if a menu item belongs to the right section of the navbar.
     *
     * @param mixed $item
     * @return bool
     */
    public static function isNavbarRightItem($item)
    {
        return self::isValidNavbarItem($item) &&
               isset($item['topnav_right']) &&
               $item['topnav_right'];
    }

    /**
     * Check if a menu item belongs to the user menu section of the navbar.
     *
     * @param mixed $item
     * @return bool
     */
    public static function isNavbarUserItem($item)
    {
        return self::isValidNavbarItem($item) &&
               isset($item['topnav_user']) &&
               $item['topnav_user'];
    }

    /**
     * Check if a menu item belongs to the sidebar.
     *
     * @param mixed $item
     * @return bool
     */
    public static function isSidebarItem($item)
    {
        return self::isValidSidebarItem($item) &&
               ! self::isNavbarLeftItem($item) &&
               ! self::isNavbarRightItem($item) &&
               ! self::isNavbarUserItem($item);
    }
}

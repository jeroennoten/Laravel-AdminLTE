<?php

namespace JeroenNoten\LaravelAdminLte\Helpers;

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
     * Check if a menu item is a search bar.
     *
     * @param mixed $item
     * @return bool
     */
    public static function isSearchBar($item)
    {
        return isset($item['text']) &&
               isset($item['search']) &&
               $item['search'];
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
               self::isSearchBar($item) ||
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
        return self::isValidSidebarItem($item) && ! self::isHeader($item);
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

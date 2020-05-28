<?php

namespace JeroenNoten\LaravelAdminLte\Helpers;

class MenuItemHelper
{
    /**
     * Check if a menu item is a header.
     *
     * @param mixed $item
     * @return boolean
     */
    public static function isHeader($item)
    {
        return is_string($item) || isset($item['header']);
    }

    /**
     * Check if a menu item is a link.
     *
     * @param mixed $item
     * @return boolean
     */
    public static function isLink($item)
    {
        return isset($item['text']) && (isset($item['url']) || isset($item['route']));
    }

    /**
     * Check if a menu item is a search bar.
     *
     * @param mixed $item
     * @return boolean
     */
    public static function isSearchBar($item)
    {
        return isset($item['text']) && isset($item['search']) && $item['search'];
    }

    /**
     * Check if a menu item is a submenu.
     *
     * @param mixed $item
     * @return boolean
     */
    public static function isSubmenu($item)
    {
        return isset($item['text']) && isset($item['submenu']);
    }

    /**
     * Check if a menu item is valid for the navbar.
     *
     * @param mixed $item
     * @return boolean
     */
    public static function isValidNavbarItem($item)
    {
        if (self::isHeader($item)) {
            return false;
        }

        return self::isLink($item) ||
               self::isSearchBar($item) ||
               self::isSubmenu($item);
    }

    /**
     * Check if a menu item is valid for the sidebar.
     *
     * @param mixed $item
     * @return boolean
     */
    public static function isValidSidebarItem($item)
    {
        return self::isHeader($item) ||
               self::isLink($item) ||
               self::isSearchBar($item) ||
               self::isSubmenu($item);
    }

    /**
     * Check if a menu item belongs to the left section of the navbar.
     *
     * @param mixed $item
     * @return boolean
     */
    public static function isNavbarLeftItem($item)
    {
        if (! self::isValidNavbarItem($item)) {
            return false;
        }

        return isset($item['topnav']) && $item['topnav'];
    }

    /**
     * Check if a menu item belongs to the right section of the navbar.
     *
     * @param mixed $item
     * @return boolean
     */
    public static function isNavbarRightItem($item)
    {
        if (! self::isValidNavbarItem($item)) {
            return false;
        }

        return isset($item['topnav_right']) && $item['topnav_right'];
    }

    /**
     * Check if a menu item belongs to the user menu section of the navbar.
     *
     * @param mixed $item
     * @return boolean
     */
    public static function isNavbarUserItem($item)
    {
        if (! self::isValidNavbarItem($item)) {
            return false;
        }

        return isset($item['topnav_user']) && $item['topnav_user'];
    }

    /**
     * Check if a menu item belongs to the sidebar.
     *
     * @param mixed $item
     * @return boolean
     */
    public static function isSidebarItem($item)
    {
        if (! self::isValidSidebarItem($item)) {
            return false;
        }

        return ! self::isNavbarLeftItem($item) &&
               ! self::isNavbarRightItem($item) &&
               ! self::isNavbarUserItem($item);
    }
}
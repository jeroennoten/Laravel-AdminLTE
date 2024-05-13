<?php

namespace JeroenNoten\LaravelAdminLte\Helpers;

/**
 * TODO: In the future, all the menu items should have a type property. So, we
 * can use this property to easily distinguish the item type and avoid guessing
 * it by other properties.
 */
class MenuItemHelper
{
    /**
     * Checks if a menu item is a header.
     *
     * @param  mixed  $item
     * @return bool
     */
    public static function isHeader($item)
    {
        return is_string($item) || isset($item['header']);
    }

    /**
     * Checks if a menu item is a link.
     *
     * @param  mixed  $item
     * @return bool
     */
    public static function isLink($item)
    {
        return isset($item['text'])
            && (isset($item['url']) || isset($item['route']));
    }

    /**
     * Checks if a menu item is a submenu.
     *
     * @param  mixed  $item
     * @return bool
     */
    public static function isSubmenu($item)
    {
        return isset($item['text'], $item['submenu'])
            && is_array($item['submenu']);
    }

    /**
     * Checks if a menu item is a legacy search box.
     *
     * @param  mixed  $item
     * @return bool
     */
    public static function isLegacySearch($item)
    {
        return isset($item['text'], $item['search'])
            && ! empty($item['search']);
    }

    /**
     * Checks if a menu item is allowed to be shown (not restricted).
     *
     * @param  mixed  $item
     * @return bool
     */
    public static function isAllowed($item)
    {
        // We won't allow empty submenu items on the menu.

        if (self::isSubmenu($item) && ! count($item['submenu'])) {
            return false;
        }

        // In any other case, fallback to the restricted property.

        return $item && empty($item['restricted']);
    }
}

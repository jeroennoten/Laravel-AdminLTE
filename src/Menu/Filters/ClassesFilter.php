<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use JeroenNoten\LaravelAdminLte\Helpers\MenuItemHelper;
use JeroenNoten\LaravelAdminLte\Helpers\SidebarItemHelper;

class ClassesFilter implements FilterInterface
{
    /**
     * Transforms a menu item. Add some particular HTML classes when suitable.
     *
     * @param  array  $item  A menu item
     * @return array
     */
    public function transform($item)
    {
        $item['class'] = $this->makeClasses($item);

        if (MenuItemHelper::isSubmenu($item)) {
            $item['submenu_class'] = $this->makeSubmenuClasses($item);
        }

        return $item;
    }

    /**
     * Make the HTML classes string attribute for a menu item.
     *
     * @param  array  $item  A menu item
     * @return string
     */
    protected function makeClasses($item)
    {
        $classes = [];

        // Add custom classes (from menu item configuration).

        if (! empty($item['classes'])) {
            $classes[] = $item['classes'];
        }

        // When the item is active, add the "active" class too.

        if (! empty($item['active'])) {
            $classes[] = 'active';
        }

        return implode(' ', $classes);
    }

    /**
     * Make the HTML classes string for the submenu of an item.
     *
     * @param  array  $item  A menu item
     * @return string
     */
    protected function makeSubmenuClasses($item)
    {
        $classes = [];

        // Add the "menu-open" class when a sidebar submenu is active. Note we
        // need to add the class to sidebar submenu items only.

        if (SidebarItemHelper::isValidItem($item) && $item['active']) {
            $classes[] = 'menu-open';
        }

        return implode(' ', $classes);
    }
}

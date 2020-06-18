<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use JeroenNoten\LaravelAdminLte\Helpers\MenuItemHelper;

class ClassesFilter implements FilterInterface
{
    /**
     * Transforms a menu item. Add classes related attributes when suitable.
     *
     * @param array $item A menu item
     * @return array The transformed menu item
     */
    public function transform($item)
    {
        if (! MenuItemHelper::isHeader($item)) {
            $item['class'] = implode(' ', $this->makeClasses($item));
            $item['top_nav_class'] = implode(' ', $this->makeClasses($item, true));
        }

        if (MenuItemHelper::isSubmenu($item)) {
            $item['submenu_class'] = implode(' ', $this->makeSubmenuClasses($item));
        }

        return $item;
    }

    /**
     * Make the classes related to a menu item.
     *
     * @param mixed $item A menu item
     * @param bool $topNav Whether the item is related to the top navbar or not
     * @return array The array of classes
     */
    protected function makeClasses($item, $topNav = false)
    {
        $classes = [];

        // Add the active class when the item is active.

        if ($item['active']) {
            $classes[] = 'active';
        }

        // Add classes related to submenu items.

        if (MenuItemHelper::isSubmenu($item)) {
            $classes[] =  $topNav ? 'dropdown' : 'nav-item';
        }

        return $classes;
    }

    /**
     * Make the classes related to a submenu item.
     *
     * @param mixed $item A menu item
     * @return array The array of classes
     */
    protected function makeSubmenuClasses($item)
    {
        $classes = ['has-treeview'];

        if ($item['active']) {
            $classes[] = 'menu-open';
        }

        return $classes;
    }
}

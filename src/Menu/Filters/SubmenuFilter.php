<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use JeroenNoten\LaravelAdminLte\Helpers\MenuItemHelper;
use JeroenNoten\LaravelAdminLte\Menu\Builder;

class SubmenuFilter implements FilterInterface
{
    /**
     * Transforms a submenu item. Apply all the filters to the submenu items.
     * TODO: This filter should be deleted, and instead put this logic on the
     * menu builder.
     *
     * @param mixed $item A menu item
     * @param Builder $builder A menu builder instance
     * @return mixed The transformed menu item
     */
    public function transform($item, Builder $builder)
    {
        if (MenuItemHelper::isSubmenu($item)) {
            $item['submenu'] = $builder->transformItems($item['submenu']);
        }

        return $item;
    }
}

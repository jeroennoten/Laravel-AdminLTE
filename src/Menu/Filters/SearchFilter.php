<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use JeroenNoten\LaravelAdminLte\Helpers\MenuItemHelper;
use JeroenNoten\LaravelAdminLte\Menu\Builder;

class SearchFilter implements FilterInterface
{
    /**
     * Transforms a menu item. Makes the proper search bar configuration.
     *
     * @param mixed $item A menu item
     * @param Builder $builder A menu builder instance
     * @return mixed The transformed menu item
     */
    public function transform($item, Builder $builder)
    {
        if (! MenuItemHelper::isSearchBar($item)) {
            return $item;
        }

        // Configure search bar method.

        if (! isset($item['method'])) {
            $item['method'] = 'get';
        } elseif (! in_array(strtolower($item['method']), ['post', 'get'])) {
            $item['method'] = 'get';
        }

        // Configure search bar input name.

        if (! isset($item['input_name'])) {
            $item['input_name'] = 'q';
        }

        return $item;
    }
}

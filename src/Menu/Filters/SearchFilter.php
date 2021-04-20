<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use JeroenNoten\LaravelAdminLte\Helpers\MenuItemHelper;

class SearchFilter implements FilterInterface
{
    /**
     * The default name attribute to be used on the search input.
     *
     * @var string
     */
    protected $defInputName = 'adminlteSearch';

    /**
     * Transforms a menu item. Makes the proper search bar configuration.
     *
     * @param array $item A menu item
     * @return array The transformed menu item
     */
    public function transform($item)
    {
        $isSearch = MenuItemHelper::isNavbarSearch($item) ||
                    MenuItemHelper::isSidebarSearch($item);

        if (! $isSearch) {
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
            $item['input_name'] = $this->defInputName;
        }

        return $item;
    }
}

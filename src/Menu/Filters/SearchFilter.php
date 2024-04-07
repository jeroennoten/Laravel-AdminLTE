<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use JeroenNoten\LaravelAdminLte\Helpers\NavbarItemHelper;
use JeroenNoten\LaravelAdminLte\Helpers\SidebarItemHelper;

class SearchFilter implements FilterInterface
{
    /**
     * The default HTML name attribute to be used on the search input.
     *
     * @var string
     */
    protected $defName = 'adminlteSearch';

    /**
     * The default HTML method attribute to be used on the search input.
     *
     * @var string
     */
    protected $defMethod = 'get';

    /**
     * Transforms a menu item. Makes the proper configuration for a search bar
     * item.
     *
     * @param  array  $item  A menu item
     * @return array
     */
    public function transform($item)
    {
        // Menu items that aren't a search bar should be ignored.

        $isSearchBar = NavbarItemHelper::isSearch($item)
            || SidebarItemHelper::isSearch($item);

        if (! $isSearchBar) {
            return $item;
        }

        // Setup the search bar method attribute.

        $isValidMethod = isset($item['method'])
            && in_array(strtolower($item['method']), ['post', 'get']);

        if (! $isValidMethod) {
            $item['method'] = $this->defMethod;
        }

        // Setup the search bar input name attribute.

        if (empty($item['input_name'])) {
            $item['input_name'] = $this->defName;
        }

        return $item;
    }
}

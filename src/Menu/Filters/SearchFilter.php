<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use JeroenNoten\LaravelAdminLte\Helpers\NavbarItemHelper;
use JeroenNoten\LaravelAdminLte\Helpers\SidebarItemHelper;

class SearchFilter implements FilterInterface
{
    /**
     * The default name attribute to be used on the search input.
     *
     * @var string
     */
    protected $defInputName = 'adminlteSearch';

    /**
     * The default method attribute to be used on the search input.
     *
     * @var string
     */
    protected $defMethod = 'get';

    /**
     * Transforms a menu item. Makes the proper search bar configuration.
     *
     * @param  array  $item  A menu item
     * @return array The transformed menu item
     */
    public function transform($item)
    {
        $isSearch = NavbarItemHelper::isSearch($item) ||
                    SidebarItemHelper::isSearch($item);

        if (! $isSearch) {
            return $item;
        }

        // Configure search bar method.

        if (! isset($item['method'])) {
            $item['method'] = $this->defMethod;
        } elseif (! in_array(strtolower($item['method']), ['post', 'get'])) {
            $item['method'] = $this->defMethod;
        }

        // Configure search bar input name.

        if (! isset($item['input_name'])) {
            $item['input_name'] = $this->defInputName;
        }

        return $item;
    }
}

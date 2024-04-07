<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

interface FilterInterface
{
    /**
     * Transforms a menu item somehow, by changing properties or adding new
     * ones, and return the new version of the menu item.
     *
     * @param  array  $item  A menu item
     * @return array
     */
    public function transform($item);
}

<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

interface FilterInterface
{
    /**
     * Transforms a menu item in some way.
     *
     * @param mixed $item A menu item
     * @return mixed The transformed menu item
     */
    public function transform($item);
}

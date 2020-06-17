<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use JeroenNoten\LaravelAdminLte\Menu\Builder;

interface FilterInterface
{
    /**
     * Transforms a menu item in some way.
     *
     * @param mixed $item A menu item
     * @param Builder $builder A menu builder instance
     * @return mixed The transformed menu item
     */
    public function transform($item, Builder $builder);
}

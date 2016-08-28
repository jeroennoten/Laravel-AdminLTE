<?php

namespace JeroenNoten\LaravelAdminLte\Events;

use JeroenNoten\LaravelAdminLte\Menu\Builder;

class BuildingMenu
{
    public $menu;

    public function __construct(Builder $menu)
    {
        $this->menu = $menu;
    }
}

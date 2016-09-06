<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use JeroenNoten\LaravelAdminLte\Menu\Builder;

interface Filter
{
    public function transform($item, Builder $builder);
}
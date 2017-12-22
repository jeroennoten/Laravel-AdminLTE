<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use JeroenNoten\LaravelAdminLte\Menu\Builder;

class SearchFilter implements FilterInterface
{
    public function transform($item, Builder $builder)
    {
        if (! isset($item['search'])) {
            $item['search'] = false;
        } elseif ($item['search'] === true) {
            if (! isset($item['method'])) {
                $item['method'] = 'get';
            } elseif (isset($item['method']) && $item['method'] !== 'post' && $item['method'] !== 'get') {
                $item['method'] = 'get';
            }
            if (! isset($item['input_name'])) {
                $item['input_name'] = 'q';
            }
        }

        return $item;
    }
}

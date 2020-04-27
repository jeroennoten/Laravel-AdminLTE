<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use JeroenNoten\LaravelAdminLte\Menu\Builder;

class DataFilter implements FilterInterface
{
    public function transform($item, Builder $builder)
    {
        if (isset($item['data']) && is_array($item['data'])) {
            $item['data-compiled'] = $this->compileData($item['data']);
        }

        return $item;
    }

    protected function compileData($dataArray)
    {
        $compiled = [];

        foreach ($dataArray as $key => $value) {
            $compiled[] = 'data-'.$key.'="'.$value.'"';
        }

        return implode(' ', $compiled);
    }
}

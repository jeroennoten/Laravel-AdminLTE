<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use JeroenNoten\LaravelAdminLte\Menu\Builder;

class DataFilter implements FilterInterface
{
    /**
     * Transforms a menu item. Adds the compiled data attributes when suitable.
     *
     * @param mixed $item A menu item
     * @param Builder $builder A menu builder instance
     * @return mixed The transformed menu item
     */
    public function transform($item, Builder $builder)
    {
        if (isset($item['data']) && is_array($item['data'])) {
            $item['data-compiled'] = $this->compileData($item['data']);
        }

        return $item;
    }

    /**
     * Compile an array of data attributes into a data string.
     *
     * @param array $dataArray Array of html data attributes
     * @return string The compiled version of data attributes
     */
    protected function compileData($dataArray)
    {
        $compiled = [];

        foreach ($dataArray as $key => $value) {
            $compiled[] = 'data-'.$key.'="'.$value.'"';
        }

        return implode(' ', $compiled);
    }
}

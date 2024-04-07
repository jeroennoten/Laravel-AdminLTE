<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

class DataFilter implements FilterInterface
{
    /**
     * Transforms a menu item. Adds the compiled version of HTML data
     * attributes to an item when suitable.
     *
     * @param  array  $item  A menu item
     * @return array
     */
    public function transform($item)
    {
        if (isset($item['data']) && is_array($item['data'])) {
            $item['data-compiled'] = $this->compileData($item['data']);
        }

        return $item;
    }

    /**
     * Compile an array of HTML data attributes into a data string.
     *
     * @param  array  $dataArray  Array of HTML data attributes
     * @return string
     */
    protected function compileData($dataArray)
    {
        $compiled = [];

        foreach ($dataArray as $key => $value) {
            $compiled[] = "data-{$key}=\"{$value}\"";
        }

        return implode(' ', $compiled);
    }
}

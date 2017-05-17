<?php

namespace JeroenNoten\LaravelAdminLte\Menu;

class Builder
{
    public $menu = [];

    /**
     * @var array
     */
    private $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function add()
    {
        $items = $this->transformItems(func_get_args());

        foreach ($items as $item) {
            array_push($this->menu, $item);
        }
    }

    public function transformItems($items)
    {
        return array_filter(array_map([$this, 'applyFilters'], $items));
    }

    protected function applyFilters($item)
    {
        if (is_string($item)) {
            return $item;
        }

        foreach ($this->filters as $filter) {
            $item = $filter->transform($item, $this);
        }

        if (isset($item['header'])) {
            $item = $item['header'];
        }

        return $item;
    }
}

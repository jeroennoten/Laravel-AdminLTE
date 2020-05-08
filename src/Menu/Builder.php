<?php

namespace JeroenNoten\LaravelAdminLte\Menu;

use Illuminate\Support\Arr;

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

    public function add(...$newItems)
    {
        $items = $this->transformItems($newItems);

        foreach ($items as $item) {
            array_push($this->menu, $item);
        }
    }

    public function addAfter($itemKey, ...$newItems)
    {
        $this->addItem($itemKey, 'after', ...$newItems);
    }

    public function addBefore($itemKey, ...$newItems)
    {
        $this->addItem($itemKey, 'before', ...$newItems);
    }

    public function addIn($itemKey, ...$newItems)
    {
        $this->addItem($itemKey, 'in', ...$newItems);
    }

    public function remove($itemKey)
    {
        $completeArrayPath = $previousArrayPath = '';
        $itemPath = $this->findItem($itemKey, $this->menu);
        if (is_array($itemPath)) {
            foreach ($itemPath as $key => $value) {
                if ($completeArrayPath === '') {
                    $completeArrayPath .= "$value";
                } else {
                    $completeArrayPath .= ".submenu.$value";
                }
            }
            $itemPath = $completeArrayPath;
            $previousArrayPath = preg_replace('/.[^.]*$/', '', $itemPath);
        }
        Arr::forget($this->menu, $itemPath);

        $this->menu = array_values($this->menu);

        if ($previousArrayPath !== '') {
            $oldArray = Arr::get($this->menu, $previousArrayPath);
            $oldArray = array_values($oldArray);
            Arr::set($this->menu, $previousArrayPath, $oldArray);
        }
    }

    public function itemKeyExists($itemKey)
    {
        $position = $this->findItem($itemKey, $this->menu);

        if ((! is_array($position) && $position !== null) || (is_array($position) && end($position) !== null)) {
            return true;
        }

        return false;
    }

    public function transformItems($items)
    {
        return array_filter(array_map([$this, 'applyFilters'], $items));
    }

    protected function addItem($itemKey, $direction, ...$newItems)
    {
        $items = $this->transformItems($newItems);
        $position = $this->findItem($itemKey, $this->menu);

        if ($position !== null) {
            $completeArrayPath = $lastKey = '';

            if (is_array($position)) {
                $completeArrayPath = implode('.submenu.', $position);
                $lastKey = end($position);
            } else {
                $completeArrayPath = $lastKey = $position;
            }

            if ($direction == 'in' || ! is_array($position)) {
                $arrayPath = $completeArrayPath;
            } elseif (is_array($position)) {
                $arrayPath = substr($completeArrayPath, 0, -(strlen(".$lastKey")));
            }

            if ($position == $lastKey && $direction != 'in') {
                array_splice($this->menu, $position + ($direction == 'after' ? 1 : 0), 0, $items);
            } else {
                $menuItems = Arr::get($this->menu, $arrayPath);

                if ($direction == 'in') {
                    if (! isset($menuItems['submenu'])) {
                        $menuItems['submenu'] = [];
                        $menuItems = $this->transformItems([$menuItems]);
                        $menuItems = $menuItems[0];
                        $menuItems['submenu'] = [];
                    }

                    $menuItems['submenu'] = array_merge($menuItems['submenu'], $items);
                } else {
                    array_splice($menuItems, $lastKey + ($direction == 'after' ? 1 : 0), 0, $items);
                }

                Arr::set($this->menu, $arrayPath, $this->applyFilters($menuItems));
            }
        }
    }

    protected function findItem($itemKey, $items, $childPositionOld = null)
    {
        $childPositions = [];
        foreach ($items as $key => $item) {
            if (isset($item['key']) && $item['key'] == $itemKey) {
                return $key;
            } elseif (isset($item['submenu'])) {
                $newKey = $this->findItem($itemKey, $item['submenu']);

                if ($newKey === null) {
                    continue;
                }

                $childPositions[] = $key;
                if (! is_array($newKey)) {
                    $childPositions[] = $newKey;
                } else {
                    $childPositions = array_merge($childPositions, $newKey);
                }

                return $childPositions;
            }
        }
    }

    protected function applyFilters($item)
    {
        if (is_string($item)) {
            return $item;
        }

        foreach ($this->filters as $filter) {
            $item = $filter->transform($item, $this);
        }

        return $item;
    }
}

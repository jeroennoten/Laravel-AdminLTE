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

    public function add()
    {
        $items = $this->transformItems(func_get_args());

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
        $completeArrayPath = '';
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
        }
        Arr::forget($this->menu, $itemPath);
    }

    public function itemKeyExists($itemKey)
    {
        if ($this->findItem($itemKey, $this->menu)) {
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
                        $menuItems['submenu'] = array_merge($menuItems['submenu'], $items);
                    } else {
                        $menuItems['submenu'] = array_merge($menuItems['submenu'], $items);
                    }
                } else {
                    array_splice($menuItems, $lastKey + ($direction == 'after' ? 1 : 0), 0, $items);
                }

                Arr::set($this->menu, $arrayPath, $menuItems);
            }
        }
    }

    protected function findItem($itemKey, $items, $childPositionOld = null)
    {
        if (is_array($childPositionOld)) {
            $childPositions = $childPositionOld;
        } else {
            $childPositions = [];
            if ($childPositionOld) {
                $childPositions[] = $childPositionOld;
            }
        }
        foreach ($items as $key => $item) {
            if (isset($item['key']) && $item['key'] == $itemKey) {
                if ($childPositionOld) {
                    $childPositions[] = $key;

                    return $childPositions;
                }

                return $key;
            } elseif (isset($item['submenu'])) {
                if ($childPositionOld) {
                    $childPositions[] = $key;
                    $childPosition = $this->findItem($itemKey, $item['submenu'], $childPositions);
                    $childPositions[] = $childPosition;

                    if (is_array($childPosition)) {
                        $childPositions = $childPosition;
                    }

                    return $childPositions;
                } else {
                    $newKey = $this->findItem($itemKey, $item['submenu']);

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

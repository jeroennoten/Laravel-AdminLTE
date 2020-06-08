<?php

namespace JeroenNoten\LaravelAdminLte\Menu;

use Illuminate\Support\Arr;

class Builder
{
    /**
     * The set of menu items.
     *
     * @var array
     */
    public $menu = [];

    /**
     * The set of filters applied to menu items.
     *
     * @var array
     */
    private $filters;

    /**
     * Constructor.
     *
     * @param array $filters
     */
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Add new items at the end of the menu.
     *
     * @param mixed $newItems,... Items to be added
     */
    public function add(...$newItems)
    {
        $items = $this->transformItems($newItems);
        array_push($this->menu, ...$items);
    }

    /**
     * Add new items after a specific menu item.
     *
     * @param mixed $itemKey The key that represents the specific menu item
     * @param mixed $newItems,... Items to be added
     */
    public function addAfter($itemKey, ...$newItems)
    {
        // Find the specific menu item. Return if not found.

        if (! ($itemPath = $this->findItem($itemKey, $this->menu))) {
            return;
        }

        // Apply the filters to the new items.

        $items = $this->transformItems($newItems);

        // Add new items after the specific item.

        $itemKeyIdx = array_pop($itemPath);
        $parentDotPath = implode('.', $itemPath) ?: null;
        $parentArr = Arr::get($this->menu, $parentDotPath, $this->menu);
        array_splice($parentArr, $itemKeyIdx + 1, 0, $items);
        Arr::set($this->menu, $parentDotPath, $parentArr);
    }

    /**
     * Add new items before a specific menu item.
     *
     * @param mixed $itemKey The key that represents the specific menu item
     * @param mixed $newItems,... Items to be added
     */
    public function addBefore($itemKey, ...$newItems)
    {
        // Find the specific menu item. Return if not found.

        if (! ($itemPath = $this->findItem($itemKey, $this->menu))) {
            return;
        }

        // Apply the filters to the new items.

        $items = $this->transformItems($newItems);

        // Add new items before the specific item.

        $itemKeyIdx = array_pop($itemPath);
        $parentDotPath = implode('.', $itemPath) ?: null;
        $parentArr = Arr::get($this->menu, $parentDotPath, $this->menu);
        array_splice($parentArr, $itemKeyIdx, 0, $items);
        Arr::set($this->menu, $parentDotPath, $parentArr);
    }

    /**
     * Add new submenu items inside a specific menu item.
     *
     * @param mixed $itemKey The key that represents the specific menu item
     * @param mixed $newItems,... Items to be added
     */
    public function addIn($itemKey, ...$newItems)
    {
        // Find the specific menu item. Return if not found.

        if (! ($itemPath = $this->findItem($itemKey, $this->menu))) {
            return;
        }

        // Apply the filters to the new items.

        $items = $this->transformItems($newItems);

        // Add new items inside the specific item.

        $submenuDotPath = implode('.', array_merge($itemPath, ['submenu']));
        $submenuArr = Arr::get($this->menu, $submenuDotPath, []);
        Arr::set($this->menu, $submenuDotPath, array_merge($submenuArr, $items));
    }

    /**
     * Remove a specific menu item.
     *
     * @param mixed $itemKey The key of the menu item to remove
     */
    public function remove($itemKey)
    {
        // Find the specific menu item. Return if not found.

        if (! ($itemPath = $this->findItem($itemKey, $this->menu))) {
            return;
        }

        // Remove the item.

        Arr::forget($this->menu, implode('.', $itemPath));

        // Normalize the menu (remove holes in the numeric indexes).

        $holedArrPath = implode('.', array_slice($itemPath, 0, -1)) ?: null;
        $holedArr = Arr::get($this->menu, $holedArrPath, $this->menu);
        Arr::set($this->menu, $holedArrPath, array_values($holedArr));
    }

    /**
     * Check if exists a menu item with the specified key.
     *
     * @param mixed $itemKey The key of the menu item to check for
     * @return bool
     */
    public function itemKeyExists($itemKey)
    {
        return (bool)$this->findItem($itemKey, $this->menu);
    }

    /**
     * Transform the items by applying the filters.
     *
     * @param array $items An array with items to be transformed
     * @return array Array with the new transformed items
     */
    public function transformItems($items)
    {
        return array_filter(array_map([$this, 'applyFilters'], $items));
    }

    /**
     * Find a menu item by the item key and return the path to it.
     *
     * @param mixed $itemKey The key of the item to find
     * @param array $items The array to look up for the item
     * @return mixed Array with the path sequence, or empty array if not found
     */
    protected function findItem($itemKey, $items)
    {
        // Look up on all the items.

        foreach ($items as $key => $item) {
            if (isset($item['key']) && $item['key'] === $itemKey) {
                return [$key];
            } elseif (isset($item['submenu']) && is_array($item['submenu'])) {

                // Do the recursive call to search on submenu. If we found the
                // item, merge the path with the current one.

                if ($newPath = $this->findItem($itemKey, $item['submenu'])) {
                    return array_merge([$key, 'submenu'], $newPath);
                }
            }
        }

        // Return empty array when the item is not found.

        return [];
    }

    /**
     * Apply all the available filters to a menu item.
     *
     * @param mixed $item A menu item
     * @return mixed A new item with all the filters applied
     */    
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

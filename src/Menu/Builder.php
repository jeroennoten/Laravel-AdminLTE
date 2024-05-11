<?php

namespace JeroenNoten\LaravelAdminLte\Menu;

use Illuminate\Support\Arr;
use JeroenNoten\LaravelAdminLte\Helpers\MenuItemHelper;

/**
 * Class Builder.
 * Responsible of building and compiling the menu.
 *
 * @property array $menu
 */
class Builder
{
    /**
     * A set of constants that will be used to identify where to insert new
     * items regarding a particular other item (identified by a key).
     */
    protected const ADD_AFTER = 0;
    protected const ADD_BEFORE = 1;
    protected const ADD_INSIDE = 2;

    /**
     * Holds the raw (uncompiled) version of the menu. The menu is a tree-like
     * structure where a submenu item plays the role of a node with children.
     * All dynamic changes on the menu will be applied over this structure.
     *
     * @var array
     */
    protected $rawMenu = [];

    /**
     * Holds the compiled version of the menu, that results of applying all the
     * filters to the raw menu items.
     *
     * @var array
     */
    protected $compiledMenu = [];

    /**
     * Tells whether the compiled version of the menu should be compiled again
     * from the raw version. The idea is to only compile the menu when a client
     * is retrieving it and the raw version differs from the compiled version.
     *
     * @var bool
     */
    protected $shouldCompile;

    /**
     * Holds the set of filters that will be applied to the menu items. These
     * filters will be used in the menu compilation process.
     *
     * @var array
     */
    protected $filters;

    /**
     * Constructor.
     *
     * @param  array  $filters
     */
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
        $this->shouldCompile = false;
    }

    /**
     * A magic method that allows retrieving properties of the objects generated
     * from this class dynamically. We will mainly use this for backward
     * compatibility, note the menu was previously accessed through the 'menu'
     * property.
     *
     * @param  string  $key  The name of the property to retrieve
     * @return mixed
     */
    public function __get($key)
    {
        return $key === 'menu' ? $this->menu() : null;
    }

    /**
     * Gets the compiled version of the menu.
     *
     * @return array
     */
    public function menu()
    {
        // First, check if we need to compile the menu again or we can use the
        // already compiled version.

        if (! $this->shouldCompile) {
            return $this->compiledMenu;
        }

        $this->compiledMenu = $this->compileItems($this->rawMenu);
        $this->shouldCompile = false;

        return $this->compiledMenu;
    }

    /**
     * Adds new items at the end of the menu.
     *
     * @param  mixed  $items  The new items to be added
     */
    public function add(...$items)
    {
        array_push($this->rawMenu, ...$items);
        $this->shouldCompile = true;
    }

    /**
     * Adds new items after the specified target menu item.
     *
     * @param  string  $itemKey  The key that identifies the target menu item
     * @param  mixed  $items  The new items to be added
     */
    public function addAfter($itemKey, ...$items)
    {
        $this->addItems($itemKey, self::ADD_AFTER, ...$items);
    }

    /**
     * Adds new items before the specified target menu item.
     *
     * @param  string  $itemKey  The key that identifies the target menu item
     * @param  mixed  $items  The new items to be added
     */
    public function addBefore($itemKey, ...$items)
    {
        $this->addItems($itemKey, self::ADD_BEFORE, ...$items);
    }

    /**
     * Adds new items inside the specified target menu item. This may be used
     * to create or extend a submenu.
     *
     * @param  string  $itemKey  The key that identifies the target menu item
     * @param  mixed  $items  The new items to be added
     */
    public function addIn($itemKey, ...$items)
    {
        $this->addItems($itemKey, self::ADD_INSIDE, ...$items);
    }

    /**
     * Removes the specified menu item.
     *
     * @param  string  $itemKey  The key that identifies the item to remove
     */
    public function remove($itemKey)
    {
        // Check if a path can be found for the specified menu item.

        if (empty($itemPath = $this->findItemPath($itemKey, $this->rawMenu))) {
            return;
        }

        // Remove the item from the raw menu.

        Arr::forget($this->rawMenu, implode('.', $itemPath));
        $this->shouldCompile = true;
    }

    /**
     * Checks if exists a menu item with the specified key.
     *
     * @param  string  $itemKey  The key of the menu item to check for
     * @return bool
     */
    public function itemKeyExists($itemKey)
    {
        return ! empty($this->findItemPath($itemKey, $this->rawMenu));
    }

    /**
     * Compiles the specified items by applying the filters. Returns an array
     * with the compiled items.
     *
     * @param  array  $items  An array with the items to be compiled
     * @return array
     */
    protected function compileItems($items)
    {
        // Get the set of compiled items.

        $items = array_filter(
            array_map([$this, 'applyFilters'], $items),
            [MenuItemHelper::class, 'isAllowed']
        );

        // Return the set of compiled items without array holes, that's why we
        // use the array_values() method.

        return array_values($items);
    }

    /**
     * Finds the path (an array with a sequence of access keys) to the menu item
     * specified by its key inside the provided array of elements. A null value
     * will be returned if the menu item can't be found.
     *
     * @param  string  $itemKey  The key of the menu item to find
     * @param  array  $items  The array from where to search for the menu item
     * @return ?array
     */
    protected function findItemPath($itemKey, $items)
    {
        // Traverse all the specified items. For each item, we first check if
        // the item has the specified key. Otherwise, if the item is a submenu,
        // we recursively search for the key and path inside that submenu.

        foreach ($items as $key => $item) {
            if (isset($item['key']) && $item['key'] === $itemKey) {
                return [$key];
            } elseif (MenuItemHelper::isSubmenu($item)) {
                $subPath = $this->findItemPath($itemKey, $item['submenu']);

                if (! empty($subPath)) {
                    return array_merge([$key, 'submenu'], $subPath);
                }
            }
        }

        // Return null when the item is not found.

        return null;
    }

    /**
     * Applies all the available filters to a menu item and return the compiled
     * version of the item.
     *
     * @param  mixed  $item  A menu item
     * @return mixed
     */
    protected function applyFilters($item)
    {
        // Filters are only applied to array type menu items.

        if (! is_array($item)) {
            return $item;
        }

        // If the item is a submenu, compile all the child items first (i.e we
        // use a depth-first tree traversal). Note child items needs to be
        // compiled first because some of the filters (like the ActiveFilter)
        // depends on the children properties when applied on a submenu item.

        if (MenuItemHelper::isSubmenu($item)) {
            $item['submenu'] = $this->compileItems($item['submenu']);
        }

        // Now, apply all the filters on the root item. Note there is no need
        // to continue applying the filters if we detect that the item is not
        // allowed to be shown.

        foreach ($this->filters as $filter) {
            if (! MenuItemHelper::isAllowed($item)) {
                return $item;
            }

            $item = $filter->transform($item);
        }

        return $item;
    }

    /**
     * Adds new items to the menu in a particular place, relative to a target
     * menu item identified by its key.
     *
     * @param  string  $itemKey  The key that identifies the target menu item
     * @param  int  $where  Identifier for where to place the new items
     * @param  mixed  $items  The new items to be added
     */
    protected function addItems($itemKey, $where, ...$items)
    {
        // Check if a path can be found for the specified menu item.

        if (empty($itemPath = $this->findItemPath($itemKey, $this->rawMenu))) {
            return;
        }

        // Get the index of the specified menu item relative to its parent.

        $itemKeyIdx = end($itemPath);
        reset($itemPath);

        // Get the target array where the items should be added, and insert the
        // new items there.

        if ($where === self::ADD_INSIDE) {
            $targetPath = implode('.', array_merge($itemPath, ['submenu']));
            $targetArr = Arr::get($this->rawMenu, $targetPath, []);
            array_push($targetArr, ...$items);
        } else {
            $targetPath = implode('.', array_slice($itemPath, 0, -1)) ?: null;
            $targetArr = Arr::get($this->rawMenu, $targetPath, $this->rawMenu);
            $offset = ($where === self::ADD_AFTER) ? 1 : 0;
            array_splice($targetArr, $itemKeyIdx + $offset, 0, $items);
        }

        // Apply changes on the raw menu.

        Arr::set($this->rawMenu, $targetPath, $targetArr);
        $this->shouldCompile = true;
    }
}

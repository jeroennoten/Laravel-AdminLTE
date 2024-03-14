<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use Illuminate\Contracts\Auth\Access\Gate;
use JeroenNoten\LaravelAdminLte\Helpers\MenuItemHelper;

class GateFilter implements FilterInterface
{
    /**
     * The Laravel gate instance, used to check for permissions.
     *
     * @var Gate
     */
    protected $gate;

    /**
     * Constructor.
     *
     * @param  Gate  $gate
     */
    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    /**
     * Transforms a menu item. Add the restricted property to a menu item
     * when situable.
     *
     * @param  array  $item  A menu item
     * @return array The transformed menu item
     */
    public function transform($item)
    {
        // Set a special attribute when item is not allowed. Items with this
        // attribute will be filtered out of the menu.

        $isWholeRestrictedSubmenu = MenuItemHelper::isSubmenu($item)
            && $this->allItemsRestricted($item['submenu']);

        if (! $this->isAllowed($item) || $isWholeRestrictedSubmenu) {
            $item['restricted'] = true;
        }

        return $item;
    }

    /**
     * Check if a menu item is allowed for the current user.
     *
     * @param  array  $item  A menu item
     * @return bool
     */
    protected function isAllowed($item)
    {
        // Check if there are any permission defined for the item.

        if (empty($item['can'])) {
            return true;
        }

        // Read the extra arguments (a db model instance can be used).

        $args = isset($item['model']) ? $item['model'] : [];

        // Check if the current user can perform the configured permissions.

        if (is_string($item['can']) || is_array($item['can'])) {
            return $this->gate->any($item['can'], $args);
        }

        return true;
    }

    /**
     * Check if a set of items are all restricted (or unallowed).
     *
     * @param  array  $items  An array with the menu items to check
     * @return bool
     */
    protected function allItemsRestricted($items)
    {
        // Check if every provided item is restricted.

        foreach ($items as $item) {
            if ($this->isAllowed($item)) {
                return false;
            }
        }

        return true;
    }
}

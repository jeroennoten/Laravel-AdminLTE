<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use Illuminate\Support\Facades\Gate;

class GateFilter implements FilterInterface
{
    /**
     * Transforms a menu item. Add the restricted property to a menu item
     * when the it's not authorized by the Laravel's Gate feature.
     *
     * @param  array  $item  A menu item
     * @return array
     */
    public function transform($item)
    {
        // Set a special property when the item is not authorized by Gate.
        // Items with this property set will be filtered out from the menu.

        if (! $this->isAuthorized($item)) {
            $item['restricted'] = true;
        }

        return $item;
    }

    /**
     * Check if a menu item is authorized to be shown for the current user.
     *
     * @param  array  $item  A menu item
     * @return bool
     */
    protected function isAuthorized($item)
    {
        // Check if there are any permission defined for the item.

        if (empty($item['can'])) {
            return true;
        }

        // Read the extra arguments (a db model instance can be used).

        $args = ! empty($item['model']) ? $item['model'] : [];

        // Check if the current user can perform the configured permissions.

        if (is_string($item['can']) || is_array($item['can'])) {
            return Gate::any($item['can'], $args);
        }

        return true;
    }
}

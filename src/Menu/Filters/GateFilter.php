<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use Illuminate\Contracts\Auth\Access\Gate;

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
     * @param Gate $gate
     */
    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    /**
     * Transforms a menu item. Adds the restricted attribute to an item.
     *
     * @param mixed $item A menu item
     * @return mixed The transformed menu item
     */
    public function transform($item)
    {
        // Set a special attribute when item is not allowed.

        if (! $this->isAllowed($item)) {
            $item['restricted'] = true;
        }

        return $item;
    }

    /**
     * Check if a menu item is allowed for the current user.
     *
     * @param mixed $item A menu item
     * @return bool
     */
    protected function isAllowed($item)
    {
        // Check if there are any permission defined for the item.

        if (! isset($item['can'])) {
            return true;
        }

        // Read the extra arguments (a model instance can be used).

        $args = isset($item['model']) ? $item['model'] : [];

        // Check if the current user can perform the configured permissions.

        if (! is_array($item['can'])) {
            return $this->gate->allows($item['can'], $args);
        }

        return $this->gate->any($item['can'], $args);
    }
}

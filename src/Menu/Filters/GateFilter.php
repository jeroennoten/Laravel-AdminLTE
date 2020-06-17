<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use Illuminate\Contracts\Auth\Access\Gate;
use JeroenNoten\LaravelAdminLte\Menu\Builder;

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
     * Transforms a menu item. Convert item to falsy value if access is not
     * allowed.
     *
     * @param mixed $item A menu item
     * @param Builder $builder A menu builder instance
     * @return mixed The transformed menu item
     */
    public function transform($item, Builder $builder)
    {
        // If the item is not allowed, return false. Falsy items will be
        // filtered out.
        // TODO: This is too tricky, we need to find another alternative in
        // replacement to convert the item into a falsy value.

        return $this->isAllowed($item) ? $item : false;
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

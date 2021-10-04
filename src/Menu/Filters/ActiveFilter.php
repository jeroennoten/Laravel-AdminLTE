<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use JeroenNoten\LaravelAdminLte\Helpers\MenuItemHelper;
use JeroenNoten\LaravelAdminLte\Menu\ActiveChecker;

class ActiveFilter implements FilterInterface
{
    /**
     * The active checker instance.
     *
     * @var ActiveChecker
     */
    protected $activeChecker;

    /**
     * Constructor.
     *
     * @param  ActiveChecker  $activeChecker
     */
    public function __construct(ActiveChecker $activeChecker)
    {
        $this->activeChecker = $activeChecker;
    }

    /**
     * Transforms a menu item. Adds the active attribute when suitable.
     *
     * @param  array  $item  A menu item
     * @return array The transformed menu item
     */
    public function transform($item)
    {
        if (! MenuItemHelper::isHeader($item)) {
            $item['active'] = $this->activeChecker->isActive($item);
        }

        return $item;
    }
}

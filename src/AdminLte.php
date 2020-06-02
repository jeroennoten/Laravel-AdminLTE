<?php

namespace JeroenNoten\LaravelAdminLte;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper;
use JeroenNoten\LaravelAdminLte\Helpers\MenuItemHelper;
use JeroenNoten\LaravelAdminLte\Menu\Builder;

class AdminLte
{
    protected $menu;

    protected $filters;

    protected $events;

    protected $container;

    /**
     * Map between a valid menu filter token and his respective filter method.
     *
     * @var array
     */
    protected $menuFilterMap;

    public function __construct(
        array $filters,
        Dispatcher $events,
        Container $container
    ) {
        $this->filters = $filters;
        $this->events = $events;
        $this->container = $container;

        // Fill the map of filters methods.

        $this->menuFilterMap = [
            'sidebar'      => [$this, 'sidebarFilter'],
            'navbar-left'  => [$this, 'navbarLeftFilter'],
            'navbar-right' => [$this, 'navbarRightFilter'],
            'navbar-user'  => [$this, 'navbarUserMenuFilter'],
        ];
    }

    public function menu($filterToken = null)
    {
        if (! $this->menu) {
            $this->menu = $this->buildMenu();
        }

        // Check for filter token.

        if (isset($this->menuFilterMap[$filterToken])) {
            return array_filter(
                $this->menu,
                $this->menuFilterMap[$filterToken]
            );
        }

        // No filter token provided, return the complete menu.

        return $this->menu;
    }

    protected function buildMenu()
    {
        $builder = new Builder($this->buildFilters());

        $this->events->dispatch(new BuildingMenu($builder));

        return $builder->menu;
    }

    protected function buildFilters()
    {
        return array_map([$this->container, 'make'], $this->filters);
    }

    /**
     * Filter method for sidebar menu items.
     */
    private function sidebarFilter($item)
    {
        return MenuItemHelper::isSidebarItem($item);
    }

    /**
     * Filter method for navbar top left menu items.
     */
    private function navbarLeftFilter($item)
    {
        if (LayoutHelper::isLayoutTopnavEnabled() && MenuItemHelper::isSidebarItem($item)) {
            return MenuItemHelper::isValidNavbarItem($item);
        }

        return MenuItemHelper::isNavbarLeftItem($item);
    }

    /**
     * Filter method for navbar top right menu items.
     */
    private function navbarRightFilter($item)
    {
        return MenuItemHelper::isNavbarRightItem($item);
    }

    /**
     * Filter method for navbar dropdown user menu items.
     */
    private function navbarUserMenuFilter($item)
    {
        return MenuItemHelper::isNavbarUserItem($item);
    }
}

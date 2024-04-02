<?php

namespace JeroenNoten\LaravelAdminLte;

use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper;
use JeroenNoten\LaravelAdminLte\Helpers\NavbarItemHelper;
use JeroenNoten\LaravelAdminLte\Helpers\SidebarItemHelper;
use JeroenNoten\LaravelAdminLte\Menu\Builder as MenuBuilder;

class AdminLte
{
    /**
     * The menu builder instance. This instance will be in charge of generating
     * the compiled version of the menu.
     *
     * @var MenuBuilder
     */
    protected $menuBuilder;

    /**
     * A map between the valid section filter tokens and their respective filter
     * methods. These filters are intended to get a specific set of menu items
     * (sidebar items, navbar items, etc).
     *
     * @var array
     */
    protected $sectionFilterMap;

    /**
     * Constructor.
     *
     * @param  array  $filters  The filters to use on the menu compilation
     */
    public function __construct(array $filters)
    {
        // Setup the map of section filters methods.

        $this->sectionFilterMap = [
            'sidebar' => [$this, 'sidebarFilter'],
            'navbar-left' => [$this, 'navbarLeftFilter'],
            'navbar-right' => [$this, 'navbarRightFilter'],
            'navbar-user' => [$this, 'navbarUserMenuFilter'],
        ];

        // Create the menu builder instance.

        $this->menuBuilder = new MenuBuilder($this->buildFilters($filters));

        // Build the menu.

        $this->buildMenu();
    }

    /**
     * Gets all menu items, or a specific set of them.
     *
     * @param  string  $sectionToken  A token representing a section of items
     * @return array
     */
    public function menu($sectionToken = null)
    {
        // Check for section filter token.

        if (isset($this->sectionFilterMap[$sectionToken])) {
            return array_filter(
                $this->menuBuilder->menu,
                $this->sectionFilterMap[$sectionToken]
            );
        }

        // When no section filter token is provided, return the complete menu.

        return $this->menuBuilder->menu;
    }

    /**
     * Build the compiled version of the menu.
     *
     * @return void
     */
    protected function buildMenu()
    {
        // First, if any, compile the static menu configuration.

        $menu = config('adminlte.menu', []);
        $menu = is_array($menu) ? $menu : [];
        $this->menuBuilder->add(...$menu);

        // Now, dispatch the BuildingMenu event. Listeners of this event may
        // dynamically change the menu or generate it completely when a static
        // menu configuration isn't viable.

        event(new BuildingMenu($this->menuBuilder));
    }

    /**
     * Build and resolve the specified menu filters. Returns the set of filters
     * that will be applied on each menu item.
     *
     * @param  array  $filters  The array of filter classes to be resolved
     * @return array
     */
    protected function buildFilters($filters)
    {
        return array_map([app(), 'make'], $filters);
    }

    /**
     * A filter method to check whether a menu item belongs to the left sidebar.
     *
     * @param  mixed  $item  The menu item to check
     * @return bool
     */
    private function sidebarFilter($item)
    {
        return SidebarItemHelper::isValidItem($item);
    }

    /**
     * A filter method to check whether a menu item belongs to the left section
     * of the top navbar.
     *
     * @param  mixed  $item  The menu item to check
     * @return bool
     */
    private function navbarLeftFilter($item)
    {
        // When layout topnav is enabled, most of the sidebar items will also
        // be placed on the left section of the top navbar.

        if (
            LayoutHelper::isLayoutTopnavEnabled() &&
            SidebarItemHelper::isValidItem($item)
        ) {
            return NavbarItemHelper::isAcceptedItem($item);
        }

        return NavbarItemHelper::isValidLeftItem($item);
    }

    /**
     * A filter method to check whether a menu item belongs to the right section
     * of the top navbar.
     *
     * @param  mixed  $item  The menu item to check
     * @return bool
     */
    private function navbarRightFilter($item)
    {
        return NavbarItemHelper::isValidRightItem($item);
    }

    /**
     * A filter method to check whether a menu item belongs to the user menu
     * section of the top navbar.
     *
     * @param  mixed  $item  The menu item to check
     * @return bool
     */
    private function navbarUserMenuFilter($item)
    {
        return NavbarItemHelper::isValidUserMenuItem($item);
    }
}

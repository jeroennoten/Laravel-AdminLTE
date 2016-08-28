<?php

namespace JeroenNoten\LaravelAdminLte\Menu;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\Auth\Access\Gate;

class Builder
{
    public $menu = [];

    private $urlGenerator;

    private $activeChecker;

    private $gate;

    public function __construct(
        UrlGenerator $urlGenerator,
        ActiveChecker $activeChecker,
        Gate $gate
    ) {
        $this->urlGenerator  = $urlGenerator;
        $this->activeChecker = $activeChecker;
        $this->gate          = $gate;
    }

    public function add()
    {
        $items = $this->transformItems(func_get_args());

        foreach ($items as $item) {
            array_push($this->menu, $item);
        }
    }

    protected function transformItems($items)
    {
        return array_map(
            [$this, 'transformItem'],
            array_filter($items, [$this, 'isVisible'])
        );
    }

    protected function isVisible($item)
    {
        return ! isset( $item['can'] ) || $this->gate->allows($item['can']);
    }

    protected function transformItem($item)
    {
        if (is_string($item)) {
            return $item;
        }

        $item['href']   = $this->makeHref($item);
        $item['active'] = $this->isActive($item);

        if (isset( $item['submenu'] )) {
            $item['submenu']         = $this->transformItems($item['submenu']);
            $item['submenu_open']    = $item['active'];
            $item['submenu_classes'] = $this->makeSubmenuClasses($item);
            $item['submenu_class']   = implode(' ', $item['submenu_classes']);
        }

        $item['classes']         = $this->makeClasses($item);
        $item['class']           = implode(' ', $item['classes']);
        $item['top_nav_classes'] = $this->makeClasses($item, true);
        $item['top_nav_class']   = implode(' ', $item['top_nav_classes']);

        return $item;
    }

    protected function makeHref($item)
    {
        if ( ! isset( $item['url'] )) {
            return '#';
        }

        return $this->urlGenerator->to($item['url']);
    }

    protected function makeClasses($item, $topNav = false)
    {
        $classes = [];

        if ($item['active']) {
            $classes[] = 'active';
        }

        if (isset( $item['submenu'] )) {
            $classes[] = $topNav ? 'dropdown' : 'treeview';
        }

        return $classes;
    }

    protected function isActive($item)
    {
        return $this->activeChecker->isActive($item);
    }

    protected function makeSubmenuClasses($item)
    {
        $classes = ['treeview-menu'];

        return $classes;
    }
}

<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use JeroenNoten\LaravelAdminLte\Helpers\MenuItemHelper;

class HrefFilter implements FilterInterface
{
    /**
     * Transforms a menu item. Compile the href HTML attribute when situable.
     *
     * @param  array  $item  A menu item
     * @return array
     */
    public function transform($item)
    {
        if (! MenuItemHelper::isHeader($item)) {
            $item['href'] = $this->makeHref($item);
        }

        return $item;
    }

    /**
     * Make and return the href HTML attribute for a menu item.
     *
     * @param  array  $item  A menu item
     * @return string
     */
    protected function makeHref($item)
    {
        // If url attribute is available, use it to make the href property.
        // Otherwise, check if route attribute is available.

        if (! empty($item['url'])) {
            return url($item['url']);
        } elseif (! empty($item['route'])) {
            return $this->makeHrefFromRouteAttr($item['route']);
        }

        // When url and route are not available, return a default value.

        return '#';
    }

    /**
     * Make and return the href HTML attribute fom the route attribute of a
     * menu item.
     *
     * @param  mixed  $routeAttr  The route attribute of a menu item
     * @return string
     */
    protected function makeHrefFromRouteAttr($routeAttr)
    {
        $routeName = $routeParams = null;

        // Check type of the route attribute.

        if (is_array($routeAttr)) {
            $routeName = $routeAttr[0] ?? null;
            $routeParams = is_array($routeAttr[1]) ? $routeAttr[1] : null;
        } elseif (is_string($routeAttr)) {
            $routeName = $routeAttr;
        }

        return $routeName ? route($routeName, $routeParams) : '#';
    }
}

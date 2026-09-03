<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use Illuminate\Routing\Exceptions\UrlGenerationException;
use JeroenNoten\LaravelAdminLte\Helpers\MenuItemHelper;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

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
    protected function makeHref($item): string
    {
        // If url attribute is available, use it to make the href property.
        // Otherwise, check if route attribute is available.

        if (! empty($item['url'])) {
            return $this->makeHrefFromUrlAttr($item['url']);
        } elseif (! empty($item['route'])) {
            return $this->makeHrefFromRouteAttr($item['route']);
        }

        // When url and route are not available, return a default value.

        return '#';
    }

    /**
     * Make and return the href HTML attribute from the url attribute of a menu
     * item.
     *
     * @param  mixed  $urlAttr  The url attribute of a menu item
     * @return string
     */
    protected function makeHrefFromUrlAttr($urlAttr): string
    {
        // Only a plain url can be resolved, any other value would abort the
        // compilation of the whole menu.

        if (! is_string($urlAttr) && ! is_numeric($urlAttr)) {
            return '#';
        }

        return url((string) $urlAttr);
    }

    /**
     * Make and return the href HTML attribute fom the route attribute of a
     * menu item.
     *
     * @param  mixed  $routeAttr  The route attribute of a menu item
     * @return string
     */
    protected function makeHrefFromRouteAttr($routeAttr): string
    {
        $routeName = $routeParams = null;

        // Check type of the route attribute.

        if (is_array($routeAttr)) {
            $routeName = $routeAttr[0] ?? null;
            $routeParams = is_array($routeAttr[1] ?? null) ? $routeAttr[1] : null;
        } elseif (is_string($routeAttr)) {
            $routeName = $routeAttr;
        }

        if (! is_string($routeName) || $routeName === '') {
            return '#';
        }

        // Note an unknown route name, or a set of parameters that does not
        // satisfy the route, only makes this particular item unreachable. The
        // rest of the menu (and of the panel) keeps working.

        try {
            return route($routeName, $routeParams);
        } catch (RouteNotFoundException|UrlGenerationException $e) {
            return '#';
        }
    }
}

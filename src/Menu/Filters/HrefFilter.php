<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use Illuminate\Contracts\Routing\UrlGenerator;
use JeroenNoten\LaravelAdminLte\Helpers\MenuItemHelper;

class HrefFilter implements FilterInterface
{
    /**
     * The url generator instance.
     *
     * @var UrlGenerator
     */
    protected $urlGenerator;

    /**
     * Constructor.
     *
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Transforms a menu item. Make the href attribute when situable.
     *
     * @param array $item A menu item
     * @return array The transformed menu item
     */
    public function transform($item)
    {
        if (! MenuItemHelper::isHeader($item)) {
            $item['href'] = $this->makeHref($item);
        }

        return $item;
    }

    /**
     * Make the href attribute for a menu item.
     *
     * @param array $item A menu item
     * @return string The href attribute
     */
    protected function makeHref($item)
    {
        // If url attribute is available, use it to make the href.

        if (isset($item['url'])) {
            return $this->urlGenerator->to($item['url']);
        }

        // When url is not available, check for route attribute.

        if (isset($item['route'])) {
            if (is_array($item['route'])) {
                $route = $item['route'][0];
                $params = is_array($item['route'][1]) ? $item['route'][1] : [];

                return $this->urlGenerator->route($route, $params);
            }

            return $this->urlGenerator->route($item['route']);
        }

        // When no url or route, return a default value.

        return '#';
    }
}

<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use Illuminate\Contracts\Routing\UrlGenerator;
use JeroenNoten\LaravelAdminLte\Menu\Builder;

class HrefFilter implements FilterInterface
{
    protected $urlGenerator;

    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function transform($item, Builder $builder)
    {
        if (! isset($item['header'])) {
            $item['href'] = $this->makeHref($item);
        }

        if (isset($item['submenu'])) {
            $item['submenu'] = array_map(function ($subitem) use ($builder) {
                return $this->transform($subitem, $builder);
            }, $item['submenu']);
        }

        return $item;
    }

    protected function makeHref($item)
    {
        if (isset($item['url'])) {
            return $this->urlGenerator->to($item['url']);
        }

        if (isset($item['route'])) {
            if (is_array($item['route'])) {
                return $this->urlGenerator->route($item['route'][0], $item['route'][1]);
            }

            return $this->urlGenerator->route($item['route']);
        }

        return '#';
    }
}

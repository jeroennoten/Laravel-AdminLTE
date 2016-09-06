<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use Illuminate\Contracts\Routing\UrlGenerator;
use JeroenNoten\LaravelAdminLte\Menu\Builder;

class HrefFilter implements Filter
{
    protected $urlGenerator;

    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function transform($item, Builder $builder)
    {
        $item['href'] = $this->makeHref($item);
        return $item;
    }

    protected function makeHref($item)
    {
        if (! isset($item['url'])) {
            return '#';
        }

        return $this->urlGenerator->to($item['url']);
    }
}
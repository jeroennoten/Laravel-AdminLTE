<?php

namespace JeroenNoten\LaravelAdminLte\Menu;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;

class ActiveChecker
{
    private $request;

    private $url;

    public function __construct(Request $request, UrlGenerator $url)
    {
        $this->request = $request;
        $this->url = $url;
    }

    public function isActive($item)
    {
        if (isset($item['active'])) {
            return $this->isExplicitActive($item['active']);
        }

        if (isset($item['submenu'])) {
            return $this->containsActive($item['submenu']);
        }

        if (isset($item['url'])) {
            return $this->checkExact($item['url']) || $this->checkSub($item['url']);
        }

        return false;
    }

    protected function checkExact($url)
    {
        return $this->checkPattern($url);
    }

    protected function checkSub($url)
    {
        return $this->checkPattern($url.'/*');
    }

    protected function checkPattern($pattern)
    {
        $fullUrlPattern = $this->url->to($pattern);

        return $this->request->fullUrlIs($fullUrlPattern);
    }

    protected function containsActive($items)
    {
        foreach ($items as $item) {
            if ($this->isActive($item)) {
                return true;
            }
        }

        return false;
    }

    private function isExplicitActive($active)
    {
        foreach ($active as $url) {
            if ($this->checkExact($url)) {
                return true;
            }
        }

        return false;
    }
}

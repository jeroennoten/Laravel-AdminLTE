<?php

namespace JeroenNoten\LaravelAdminLte\Menu;

use Illuminate\Http\Request;

class ActiveChecker
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function isActive($item)
    {
        if (isset( $item['active'] )) {
            return $this->isExplicitActive($item['active']);
        }

        if (isset( $item['submenu'] )) {
            return $this->containsActive($item['submenu']);
        }

        if (isset( $item['url'] )) {
            return $this->isActiveUrl($item['url']);
        }

        return false;
    }

    protected function isActiveUrl($url)
    {
        return $this->checkExact($url) || $this->checkSub($url);
    }

    protected function checkExact($url)
    {
        return $this->request->is($url);
    }

    protected function checkSub($url)
    {
        return $this->request->is($url.'/*');
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
            if ($this->isActiveUrl($url)) {
                return true;
            }
        }

        return false;
    }
}

<?php

namespace JeroenNoten\LaravelAdminLte\Menu;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        if (isset($item['submenu']) && $this->containsActive($item['submenu'])) {
            return true;
        }

        if (isset($item['active']) && $this->isExplicitActive($item['active'])) {
            return true;
        }

        if (isset($item['href']) && $this->checkPattern($item['href'])) {
            return true;
        }

        // Support URL for backwards compatibility
        if (isset($item['url']) && $this->checkPattern($item['url'])) {
            return true;
        }

        return false;
    }

    protected function checkPattern($pattern)
    {
        $urlPattern = $this->url->to($pattern);

        $url = $this->request->url();

        if (mb_substr($pattern, 0, 6) === 'regex:') {
            $regex = mb_substr($pattern, 6);

            if (preg_match($regex, $this->request->path()) == 1) {
                return true;
            }

            return false;
        }

        return Str::is($urlPattern, $url);
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
        if (! is_array($active)) {
            return $active;
        }

        foreach ($active as $url) {
            if ($this->checkPattern($url)) {
                return true;
            }
        }

        return false;
    }
}

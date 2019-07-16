<?php

namespace JeroenNoten\LaravelAdminLte\Menu;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\UrlGenerator;

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
        if (isset($item['submenu'])) {
            return $this->containsActive($item['submenu']);
        }

        if (isset($item['active'])) {
            return $this->isExplicitActive($item['active']);
        }

        if (isset($item['href'])) {
            return $this->checkPattern($item['href']);
        }

        // Support URL for backwards compatibility
        if (isset($item['url'])) {
            return $this->checkPattern($item['url']);
        }

        return false;
    }

    protected function checkPattern($pattern)
    {
        $fullUrlPattern = $this->url->to($pattern);

        $fullUrl = $this->request->fullUrl();

        if (mb_substr($pattern, 0, 6) === 'regex:') {
            $regex = mb_substr($pattern, 6);

            if (preg_match($regex, $this->request->path()) == 1) {
                return true;
            }

            return false;
        }

        return Str::is($fullUrlPattern, $fullUrl);
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

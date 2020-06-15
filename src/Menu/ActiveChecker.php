<?php

namespace JeroenNoten\LaravelAdminLte\Menu;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActiveChecker
{
    /**
     * The request instance.
     *
     * @var Request
     */
    private $request;

    /**
     * The url generator instance.
     *
     * @var UrlGenerator
     */
    private $url;

    /**
     * Constructor.
     *
     * @param Request $request
     * @param UrlGenerator $url
     */
    public function __construct(Request $request, UrlGenerator $url)
    {
        $this->request = $request;
        $this->url = $url;
    }

    /**
     * Checks if a menu item is currently active. Active items will be
     * highlighted.
     *
     * @param mixed $item The menu item to check
     * @return bool
     */
    public function isActive($item)
    {
        if (isset($item['submenu'])) {
            return $this->containsActive($item['submenu']);
        } elseif (isset($item['active'])) {
            return $this->isExplicitActive($item['active']);
        } elseif (isset($item['href'])) {
            return $this->checkPattern($item['href']);
        } elseif (isset($item['url'])) {
            // Support URL for backwards compatibility.
            return $this->checkPattern($item['url']);
        }

        return false;
    }

    /**
     * Checks if an array of items contains an active item.
     *
     * @param array $items The items to check
     * @return bool
     */
    protected function containsActive($items)
    {
        foreach ($items as $item) {
            if ($this->isActive($item)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if an item is active by explicit definition of 'active' state.
     *
     * @param bool|array $activeDef
     * @return bool
     */
    protected function isExplicitActive($activeDef)
    {
        // If the active definition is a bool, return it.

        if (is_bool($activeDef)) {
            return $activeDef;
        }

        // Otherwise, check if any of the url patterns that defines the active
        // state matches the requested url.

        foreach ($activeDef as $pattern) {
            if ($this->checkPattern($pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if an url pattern matches the requested url.
     *
     * @param string $pattern
     * @return bool
     */
    protected function checkPattern($pattern)
    {
        // First, check if the pattern is a regular expression.

        if (Str::startsWith($pattern, 'regex:')) {
            $regex = Str::substr($pattern, 6);

            return (bool) preg_match($regex, $this->request->path());
        }

        // If pattern is not a regex, check if the requested url matches the
        // absolute path to the given pattern.

        return Str::is($this->url->to($pattern), $this->request->url());
    }
}

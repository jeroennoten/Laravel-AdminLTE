<?php

namespace JeroenNoten\LaravelAdminLte\Menu;

use Illuminate\Support\Str;

class ActiveChecker
{
    /**
     * A map between menu item properties and their respective test methods.
     *
     * @var array
     */
    protected $tests;

    /**
     * Constructor.
     */
    public function __construct()
    {
        // Fill the map with tests. These tests will check whether a menu item
        // is active. Since the menu is traversed with a depth-first technique,
        // for submenu elements with nested submenus, checking for the
        // dynamically compiled 'active' property first will give us more
        // performance.

        $this->tests = [
            'active' => [$this, 'isExplicitActive'],
            'submenu' => [$this, 'containsActive'],
            'href' => [$this, 'checkPattern'],
            'url' => [$this, 'checkPattern'],
        ];
    }

    /**
     * Checks if a menu item is currently active. An active item will be
     * highlighted in the menu.
     *
     * @param  mixed  $item  The menu item to check
     * @return bool
     */
    public function isActive($item)
    {
        // Return true if any of the verification tests is met. Otherwise,
        // return false.

        foreach ($this->tests as $prop => $testMethod) {
            if (isset($item[$prop]) && $testMethod($item[$prop])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if an array of items contains an active item.
     *
     * @param  array  $items  The items to check
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
     * Checks if an item is active by explicit definition of the 'active'
     * property.
     *
     * @param  bool|array  $activeDef
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
     * Checks if an url pattern matches with the requested url.
     *
     * @param  string  $pattern
     * @return bool
     */
    protected function checkPattern($pattern)
    {
        // First, check if the pattern is a regular expression.

        if (Str::startsWith($pattern, 'regex:')) {
            $regex = Str::substr($pattern, 6);

            return (bool) preg_match($regex, request()->path());
        }

        // If pattern is not a regex, check if the requested url matches with
        // the absolute path to the given pattern. When the pattern uses query
        // parameters, compare with the full requested url.

        $pattern = preg_replace('@^https?://@', '*', url($pattern));

        $request = isset(parse_url($pattern)['query'])
            ? request()->fullUrl()
            : request()->url();

        return Str::is(trim($pattern), trim($request));
    }
}

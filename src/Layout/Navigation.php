<?php

namespace JeroenNoten\LaravelAdminLte\Layout;

use Illuminate\Routing\Exceptions\UrlGenerationException;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class Navigation
{
    /**
     * Checks whether the configured navigation targets (the login url, the
     * dashboard url, ...) are route names instead of plain urls.
     *
     * @return bool
     */
    public static function isRouteMode(): bool
    {
        return (bool) config('adminlte.use_route_url', false);
    }

    /**
     * Makes the url of a configured navigation target. The target may be a
     * plain url or, when the 'use_route_url' option is enabled, the name of a
     * route.
     *
     * A route that can not be resolved falls back to a plain url instead of
     * aborting the request: enabling the option on an application that misses
     * one of the routes would otherwise break every page of the panel.
     *
     * @param  mixed  $target  The configured url or route name
     * @return string
     */
    public static function makeUrl($target): string
    {
        if (! is_string($target) && ! is_numeric($target)) {
            return '';
        }

        $target = (string) $target;

        if ($target === '') {
            return '';
        }

        if (! self::isRouteMode()) {
            return url($target);
        }

        return self::makeRouteUrl($target);
    }

    /**
     * Makes the url of a route name, falling back to a plain url when the
     * route is not available.
     *
     * @param  string  $name  The name of the route
     * @return string
     */
    protected static function makeRouteUrl($name): string
    {
        if (! Route::has($name)) {
            return url($name);
        }

        try {
            return route($name);
        } catch (RouteNotFoundException|UrlGenerationException $e) {
            return url($name);
        }
    }
}

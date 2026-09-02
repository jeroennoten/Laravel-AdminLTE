<?php

namespace JeroenNoten\LaravelAdminLte\Helpers;

use JeroenNoten\LaravelAdminLte\Assets\AdminLteVersion;
use JeroenNoten\LaravelAdminLte\Assets\AssetResolver;

class AssetHelper
{
    /**
     * Gets the configured assets delivery mode.
     *
     * @return string
     */
    public static function mode()
    {
        return AssetResolver::mode();
    }

    /**
     * Gets the version of the installed AdminLTE package, which is used to
     * build the CDN locations.
     *
     * @return string
     */
    public static function adminlteVersion()
    {
        return AdminLteVersion::get();
    }

    /**
     * Replaces the version placeholder of a location by the version of the
     * installed AdminLTE package. Note this is also used by the plugins view,
     * since a plugin may point to an asset of the AdminLTE distribution (for
     * example the Select2 compatibility theme).
     *
     * @param  mixed  $location  The location of an asset
     * @return mixed
     */
    public static function applyVersion($location)
    {
        return AdminLteVersion::apply($location);
    }

    /**
     * Gets the location (url) of the main AdminLTE stylesheet. The RTL variant
     * is returned when the RTL mode is enabled. Returns null when no location
     * can be resolved.
     *
     * @return string|null
     */
    public static function adminlteCss()
    {
        return self::resolve('adminlte_css');
    }

    /**
     * Gets the location (url) of the main AdminLTE script. Returns null when
     * no location can be resolved.
     *
     * @return string|null
     */
    public static function adminlteJs()
    {
        return self::resolve('adminlte_js');
    }

    /**
     * Gets the location (url) of the optional AdminLTE extended colors
     * stylesheet. Returns null when the extended colors are disabled.
     *
     * @return string|null
     */
    public static function colorsCss()
    {
        if (! config('adminlte.assets.extended_colors', false)) {
            return null;
        }

        $key = config('adminlte.assets.extended_colors_v3_aliases', false)
            ? 'colors_v3_css'
            : 'colors_css';

        return self::resolve($key);
    }

    /**
     * Gets the location (url) of the Bootstrap javascript bundle. Returns null
     * when the resource is disabled on the configuration.
     *
     * @return string|null
     */
    public static function bootstrapJs()
    {
        return self::resolveOptional('bootstrap_js', 'assets.bootstrap_js');
    }

    /**
     * Gets the location (url) of the Bootstrap Icons stylesheet. Returns null
     * when the resource is disabled on the configuration.
     *
     * @return string|null
     */
    public static function bootstrapIconsCss()
    {
        return self::resolveOptional('bootstrap_icons_css', 'assets.bootstrap_icons');
    }

    /**
     * Gets the location (url) of the OverlayScrollbars stylesheet. Returns
     * null when the resource is disabled on the configuration.
     *
     * @return string|null
     */
    public static function overlayScrollbarsCss()
    {
        return self::resolveOptional('overlayscrollbars_css', 'assets.overlayscrollbars');
    }

    /**
     * Gets the location (url) of the OverlayScrollbars script. Returns null
     * when the resource is disabled on the configuration.
     *
     * @return string|null
     */
    public static function overlayScrollbarsJs()
    {
        return self::resolveOptional('overlayscrollbars_js', 'assets.overlayscrollbars');
    }

    /**
     * Gets the location (url) of the web font stylesheet. Returns null when
     * the external fonts are not allowed.
     *
     * @return string|null
     */
    public static function fontsCss()
    {
        return self::resolveOptional('fonts_css', 'google_fonts.allowed');
    }

    /**
     * Resolves the location (url) of the specified asset key.
     *
     * @param  string  $key  The asset key (as defined on the config file)
     * @return string|null
     */
    public static function resolve($key)
    {
        return AssetResolver::resolve($key);
    }

    /**
     * Resolves an asset that the configuration can disable.
     *
     * @param  string  $key  The asset key (as defined on the config file)
     * @param  string  $option  The option that enables the asset
     * @return string|null
     */
    protected static function resolveOptional($key, $option): ?string
    {
        if (! config("adminlte.{$option}", true)) {
            return null;
        }

        return self::resolve($key);
    }
}

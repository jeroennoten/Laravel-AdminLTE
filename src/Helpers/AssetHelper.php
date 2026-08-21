<?php

namespace JeroenNoten\LaravelAdminLte\Helpers;

class AssetHelper
{
    /**
     * Holds the set of asset keys that provides a RTL variant. The RTL variant
     * of an asset key is resolved by appending the '_rtl' suffix to the base
     * name of the key.
     *
     * @var array
     */
    protected static $rtlAwareAssets = [
        'adminlte_css', 'colors_css', 'colors_v3_css',
    ];

    /**
     * Gets the configured assets delivery mode.
     *
     * @return string
     */
    public static function mode()
    {
        $mode = config('adminlte.assets.mode', 'local');

        return in_array($mode, ['local', 'cdn']) ? $mode : 'local';
    }

    /**
     * Gets the location (url) of the main AdminLTE stylesheet. The RTL variant
     * is returned when the RTL mode is enabled.
     *
     * @return string
     */
    public static function adminlteCss()
    {
        return self::resolve('adminlte_css');
    }

    /**
     * Gets the location (url) of the main AdminLTE script.
     *
     * @return string
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
        if (! config('adminlte.assets.bootstrap_js', true)) {
            return null;
        }

        return self::resolve('bootstrap_js');
    }

    /**
     * Gets the location (url) of the Bootstrap Icons stylesheet. Returns null
     * when the resource is disabled on the configuration.
     *
     * @return string|null
     */
    public static function bootstrapIconsCss()
    {
        if (! config('adminlte.assets.bootstrap_icons', true)) {
            return null;
        }

        return self::resolve('bootstrap_icons_css');
    }

    /**
     * Gets the location (url) of the OverlayScrollbars stylesheet. Returns
     * null when the resource is disabled on the configuration.
     *
     * @return string|null
     */
    public static function overlayScrollbarsCss()
    {
        if (! config('adminlte.assets.overlayscrollbars', true)) {
            return null;
        }

        return self::resolve('overlayscrollbars_css');
    }

    /**
     * Gets the location (url) of the OverlayScrollbars script. Returns null
     * when the resource is disabled on the configuration.
     *
     * @return string|null
     */
    public static function overlayScrollbarsJs()
    {
        if (! config('adminlte.assets.overlayscrollbars', true)) {
            return null;
        }

        return self::resolve('overlayscrollbars_js');
    }

    /**
     * Gets the location (url) of the web font stylesheet. Returns null when
     * the external fonts are not allowed.
     *
     * @return string|null
     */
    public static function fontsCss()
    {
        if (! config('adminlte.google_fonts.allowed', true)) {
            return null;
        }

        return self::resolve('fonts_css');
    }

    /**
     * Resolves the location (url) of the specified asset key.
     *
     * @param  string  $key  The asset key (as defined on the config file)
     * @return string|null
     */
    public static function resolve($key)
    {
        $key = self::resolveKeyDirection($key);
        $local = config("adminlte.assets.local.{$key}");
        $cdn = config("adminlte.assets.cdn.{$key}");

        // On the CDN mode, always prefer the configured CDN location.

        if (self::mode() === 'cdn' && is_string($cdn) && $cdn !== '') {
            return $cdn;
        }

        // Without a local path, the CDN location is the only option.

        if (! is_string($local) || $local === '') {
            return is_string($cdn) && $cdn !== '' ? $cdn : null;
        }

        // When the local asset is not published yet, fallback to the CDN.

        if (! self::isPublished($local) && config('adminlte.assets.cdn_fallback', true)) {
            return is_string($cdn) && $cdn !== '' ? $cdn : asset($local);
        }

        return asset($local);
    }

    /**
     * Checks whether the specified local asset is published or not.
     *
     * @param  string  $path  The asset path relative to the public folder
     * @return bool
     */
    protected static function isPublished($path)
    {
        return is_file(public_path($path));
    }

    /**
     * Resolves the RTL variant of an asset key when the RTL mode is enabled.
     *
     * @param  string  $key  The asset key (as defined on the config file)
     * @return string
     */
    protected static function resolveKeyDirection($key)
    {
        if (! in_array($key, self::$rtlAwareAssets)) {
            return $key;
        }

        if (! LayoutHelper::isRtlEnabled()) {
            return $key;
        }

        return preg_replace('/_css$/', '_rtl_css', $key);
    }
}

<?php

namespace JeroenNoten\LaravelAdminLte\Assets;

use JeroenNoten\LaravelAdminLte\Layout\Direction;

class AssetResolver
{
    /**
     * The asset keys that provide a RTL variant. The RTL variant of a key is
     * resolved by appending the '_rtl' suffix to its base name.
     *
     * @var array
     */
    public const RTL_AWARE_KEYS = ['adminlte_css', 'colors_css', 'colors_v3_css'];

    /**
     * Gets the configured assets delivery mode.
     *
     * @return string
     */
    public static function mode(): string
    {
        $mode = config('adminlte.assets.mode', 'local');

        return in_array($mode, ['local', 'cdn'], true) ? $mode : 'local';
    }

    /**
     * Resolves the location (url) of the specified asset key.
     *
     * @param  string  $key  The asset key (as defined on the config file)
     * @return string|null
     */
    public static function resolve($key): ?string
    {
        $key = self::resolveKeyDirection($key);
        $cdn = AdminLteVersion::apply(config("adminlte.assets.cdn.{$key}"));
        $local = config("adminlte.assets.local.{$key}");

        if (self::mode() === 'cdn') {
            return self::firstAvailable($cdn, $local);
        }

        if (! self::isUsable($local)) {
            return self::isUsable($cdn) ? $cdn : null;
        }

        if (self::isPublished($local)) {
            return asset($local);
        }

        return self::fallbackFor($local, $cdn);
    }

    /**
     * Gets the location to use when the local asset is not published yet.
     *
     * @param  string  $local  The local path of the asset
     * @param  string|null  $cdn  The CDN location of the asset
     * @return string
     */
    protected static function fallbackFor($local, $cdn): string
    {
        $useCdn = config('adminlte.assets.cdn_fallback', true) && self::isUsable($cdn);

        return $useCdn ? $cdn : asset($local);
    }

    /**
     * Gets the first usable location of the provided ones, giving preference
     * to the CDN one.
     *
     * @param  string|null  $cdn  The CDN location of the asset
     * @param  string|null  $local  The local path of the asset
     * @return string|null
     */
    protected static function firstAvailable($cdn, $local): ?string
    {
        if (self::isUsable($cdn)) {
            return $cdn;
        }

        return self::isUsable($local) ? asset($local) : null;
    }

    /**
     * Checks whether a configured location can be used.
     *
     * @param  mixed  $location  The location to check
     * @return bool
     */
    protected static function isUsable($location): bool
    {
        return is_string($location) && $location !== '';
    }

    /**
     * Checks whether the specified local asset is published or not.
     *
     * @param  string  $path  The asset path relative to the public folder
     * @return bool
     */
    protected static function isPublished($path): bool
    {
        return is_file(public_path($path));
    }

    /**
     * Resolves the RTL variant of an asset key when the RTL mode is enabled.
     *
     * @param  string  $key  The asset key (as defined on the config file)
     * @return string
     */
    protected static function resolveKeyDirection($key): string
    {
        if (! in_array($key, self::RTL_AWARE_KEYS, true) || ! Direction::isRtlEnabled()) {
            return $key;
        }

        return preg_replace('/_css$/', '_rtl_css', $key);
    }
}

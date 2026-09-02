<?php

namespace JeroenNoten\LaravelAdminLte\Assets;

use Composer\InstalledVersions;

class AdminLteVersion
{
    /**
     * The name of the composer package that provides the AdminLTE
     * distribution.
     *
     * @var string
     */
    public const PACKAGE = 'almasaeed2010/adminlte';

    /**
     * The placeholder that the asset locations use for the version.
     *
     * @var string
     */
    public const PLACEHOLDER = '{version}';

    /**
     * The version used when the installed one can not be detected.
     *
     * @var string
     */
    public const FALLBACK = '4.8';

    /**
     * Gets the version of the installed AdminLTE distribution, which is used
     * to build the CDN locations. This way the assets served from the CDN
     * always match the ones provided by the composer dependency.
     *
     * @return string
     */
    public static function get(): string
    {
        $configured = config('adminlte.assets.adminlte_version');

        if (is_string($configured) && $configured !== '') {
            return $configured;
        }

        return self::detect() ?? self::FALLBACK;
    }

    /**
     * Replaces the version placeholder of a location by the version of the
     * installed AdminLTE distribution. A location that is not a string is
     * returned unchanged.
     *
     * @param  mixed  $location  The location of an asset
     * @return mixed
     */
    public static function apply($location): mixed
    {
        if (! is_string($location) || ! str_contains($location, self::PLACEHOLDER)) {
            return $location;
        }

        return str_replace(self::PLACEHOLDER, self::get(), $location);
    }

    /**
     * Detects the version that composer installed. It returns null when the
     * version is not available or is not resolvable on a CDN (for example a
     * development version like 'dev-master').
     *
     * @return string|null
     */
    protected static function detect(): ?string
    {
        if (! class_exists(InstalledVersions::class)) {
            return null;
        }

        try {
            $version = InstalledVersions::getPrettyVersion(self::PACKAGE);
        } catch (\Throwable $e) {
            return null;
        }

        if (! is_string($version)) {
            return null;
        }

        $version = ltrim($version, 'v');

        return preg_match('/^\d+\.\d+(\.\d+)?/', $version) ? $version : null;
    }
}

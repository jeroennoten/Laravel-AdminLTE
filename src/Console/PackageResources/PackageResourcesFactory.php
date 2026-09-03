<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

class PackageResourcesFactory
{
    /**
     * The package resources that the artisan commands can operate on, indexed
     * by the keyword used to refer to them on those commands.
     *
     * @var array
     */
    protected static $resources = [
        'assets' => AdminlteAssetsResource::class,
        'vendor_assets' => VendorAssetsResource::class,
        'config' => ConfigResource::class,
        'translations' => TranslationsResource::class,
        'main_views' => LayoutViewsResource::class,
        'auth_views' => AuthViewsResource::class,
        'auth_routes' => AuthRoutesResource::class,
        'components' => BladeComponentsResource::class,
        'error_views' => ErrorViewsResource::class,
    ];

    /**
     * Makes an instance of every available package resource. The instances are
     * indexed by the keyword of their resource.
     *
     * @return array
     */
    public static function make(): array
    {
        return array_map(
            static function ($resource) {
                return new $resource();
            },
            self::$resources
        );
    }

    /**
     * Gets the keywords of the available package resources.
     *
     * @return array
     */
    public static function keys(): array
    {
        return array_keys(self::$resources);
    }
}

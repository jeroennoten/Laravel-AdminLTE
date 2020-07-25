<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

use JeroenNoten\LaravelAdminLte\Console\PackageResources\PackageResource;
use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;

class TranslationsResource extends PackageResource
{
    /**
     * Create a new resource instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Fill the resource data.

        $this->resource = [
            'description' => 'Default package translations',
            'source'      => CommandHelper::getPackagePath('resources/lang'),
            'target'      => resource_path('lang/vendor/adminlte'),
            'required'    => true,
        ];

        // Fill the installation messages.

        $this->messages = [
            'install'   => 'Install the package translations files?',
            'overwrite' => 'The translation files already exists. Want to replace the files?',
            'success'   => 'Translation files installed successfully.',
        ];
    }

    /**
     * Install/Export the resource.
     *
     * @return void
     */
    public function install()
    {
        // Install the translations files.

        CommandHelper::copyDirectory(
            $this->resource['source'],
            $this->resource['target'],
            true,
            true
        );
    }

    /**
     * Uninstall/Remove the resource.
     *
     * @return void
     */
    public function uninstall()
    {
        $target = $this->resource['target'];

        // Uninstall the translation files.

        if (is_dir($target)) {
            CommandHelper::removeDirectory($target);
        }
    }

    /**
     * Check if the resource already exists on the target destination.
     *
     * @return bool
     */
    public function exists()
    {
        return is_dir($this->resource['target']);
    }

    /**
     * Check if the resource is correctly installed.
     *
     * @return bool
     */
    public function installed()
    {
        return (bool) CommandHelper::compareDirectories(
            $this->resource['source'],
            $this->resource['target'],
            true
        );
    }
}

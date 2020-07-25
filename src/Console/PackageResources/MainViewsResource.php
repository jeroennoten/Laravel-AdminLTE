<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

use JeroenNoten\LaravelAdminLte\Console\PackageResources\PackageResource;
use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;

class MainViewsResource extends PackageResource
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
            'description' => 'The package main views',
            'source'      => CommandHelper::getPackagePath('resources/views'),
            'target'      => CommandHelper::getViewPath('vendor/adminlte'),
            'required'    => false,
        ];

        // Fill the installation messages.

        $this->messages = [
            'install'   => 'Install the AdminLTE main views?',
            'overwrite' => 'The main views already exists. Want to replace the views?',
            'success'   => 'Main views installed successfully.',
        ];
    }

    /**
     * Install/Export the resource.
     *
     * @return void
     */
    public function install()
    {
        // Install the main views.

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

        // Uninstall the package main views.

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

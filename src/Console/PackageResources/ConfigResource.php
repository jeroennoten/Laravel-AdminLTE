<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

use JeroenNoten\LaravelAdminLte\Console\PackageResources\PackageResource;
use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;

class ConfigResource extends PackageResource
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
            'description' => 'Default package configuration',
            'source'      => CommandHelper::getPackagePath('config/adminlte.php'),
            'target'      => config_path('adminlte.php'),
            'required'    => true,
        ];

        // Fill the installation messages.

        $this->messages = [
            'install'   => 'Install the package config file?',
            'overwrite' => 'The config file already exists. Want to replace it?',
            'success'   => 'Configuration file installed successfully.',
        ];
    }

    /**
     * Install/Export the resource.
     *
     * @return void
     */
    public function install()
    {
        // Install the configuration file.

        $target = $this->resource['target'];
        CommandHelper::ensureDirectoryExists(dirname($target));
        copy($this->resource['source'], $target);
    }

    /**
     * Uninstall/Remove the resource.
     *
     * @return void
     */
    public function uninstall()
    {
        $target = $this->resource['target'];

        // Uninstall the configuration file.

        if (is_file($target)) {
            unlink($target);
        }
    }

    /**
     * Check if the resource already exists on the target destination.
     *
     * @return bool
     */
    public function exists()
    {
        return is_file($this->resource['target']);
    }

    /**
     * Check if the resource is correctly installed.
     *
     * @return bool
     */
    public function installed()
    {
        return CommandHelper::compareFiles(
            $this->resource['source'],
            $this->resource['target']
        );
    }
}

<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

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

        $this->description = 'The default package configuration file';
        $this->source = CommandHelper::getPackagePath('config/adminlte.php');
        $this->target = config_path('adminlte.php');
        $this->required = true;

        // Fill the set of installation messages.

        $this->messages = [
            'install' => 'Install the package config file?',
            'overwrite' => 'The config file already exists. Want to replace it?',
            'success' => 'Configuration file installed successfully.',
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

        CommandHelper::ensureDirectoryExists(dirname($this->target));
        copy($this->source, $this->target);
    }

    /**
     * Uninstall/Remove the resource.
     *
     * @return void
     */
    public function uninstall()
    {
        // Uninstall the configuration file.

        if (is_file($this->target)) {
            unlink($this->target);
        }
    }

    /**
     * Check if the resource already exists on the target destination.
     *
     * @return bool
     */
    public function exists()
    {
        return is_file($this->target);
    }

    /**
     * Check if the resource is correctly installed.
     *
     * @return bool
     */
    public function installed()
    {
        return CommandHelper::compareFiles($this->source, $this->target);
    }
}

<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;

class ConfigResource extends PackageResource
{
    /**
     * Create a new package resource instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Fill the resource data.

        $this->description = 'The package configuration file with default options';
        $this->source = CommandHelper::getPackagePath('config/adminlte.php');
        $this->target = config_path('adminlte.php');
        $this->required = true;

        // Fill the set of installation messages.

        $this->messages = [
            'install' => 'Do you want to publish the package config file?',
            'overwrite' => 'Config file was already published. Want to replace it?',
            'success' => 'Configuration file published successfully',
        ];
    }

    /**
     * Installs or publishes the resource.
     *
     * @return void
     */
    public function install()
    {
        // Copy the configuration file to the target file.

        File::ensureDirectoryExists(File::dirname($this->target));
        File::copy($this->source, $this->target);
    }

    /**
     * Uninstalls the resource.
     *
     * @return void
     */
    public function uninstall()
    {
        // Delete the published configuration file. When file does not exists,
        // we consider the config file as uninstalled.

        if (File::isFile($this->target)) {
            File::delete($this->target);
        }
    }

    /**
     * Checks whether the resource already exists in the target location.
     *
     * @return bool
     */
    public function exists()
    {
        return File::isFile($this->target);
    }

    /**
     * Checks whether the resource is correctly installed, i.e. if the source
     * items matches with the items available at the target location.
     *
     * @return bool
     */
    public function installed()
    {
        return CommandHelper::compareFiles($this->source, $this->target);
    }
}

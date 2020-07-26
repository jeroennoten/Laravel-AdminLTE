<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

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

        $this->description = 'The default package translations files';
        $this->source = CommandHelper::getPackagePath('resources/lang');
        $this->target = resource_path('lang/vendor/adminlte');
        $this->required = true;

        // Fill the set of installation messages.

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

        CommandHelper::copyDirectory($this->source, $this->target, true, true);
    }

    /**
     * Uninstall/Remove the resource.
     *
     * @return void
     */
    public function uninstall()
    {
        // Uninstall the translation files.

        if (is_dir($this->target)) {
            CommandHelper::removeDirectory($this->target);
        }
    }

    /**
     * Check if the resource already exists on the target destination.
     *
     * @return bool
     */
    public function exists()
    {
        return is_dir($this->target);
    }

    /**
     * Check if the resource is correctly installed.
     *
     * @return bool
     */
    public function installed()
    {
        return (bool) CommandHelper::compareDirectories(
            $this->source,
            $this->target,
            true
        );
    }
}

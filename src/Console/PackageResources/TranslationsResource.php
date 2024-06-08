<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;

class TranslationsResource extends PackageResource
{
    /**
     * Create a new package resource instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Fill the resource data.

        $this->description = 'The default package translations files';
        $this->source = CommandHelper::getPackagePath('resources/lang');
        $this->target = App::langPath().'/vendor/adminlte';
        $this->required = true;

        // Fill the set of installation messages.

        $this->messages = [
            'install' => 'Do you want to publish the package translations?',
            'overwrite' => 'Translations were already published. Want to replace?',
            'success' => 'Translation files published successfully',
        ];
    }

    /**
     * Installs or publishes the resource.
     *
     * @return bool
     */
    public function install()
    {
        // Copy the translation files to the target folder.

        return (bool) CommandHelper::copyDirectory(
            $this->source,
            $this->target,
            true,
            true
        );
    }

    /**
     * Uninstalls the resource.
     *
     * @return bool
     */
    public function uninstall()
    {
        // Remove the translation files from the target folder.

        if (File::isDirectory($this->target)) {
            return File::deleteDirectory($this->target);
        }

        return false;
    }

    /**
     * Checks whether the resource already exists in the target location.
     *
     * @return bool
     */
    public function exists()
    {
        return File::isDirectory($this->target);
    }

    /**
     * Checks whether the resource is correctly installed, i.e. if the source
     * items matches with the items available at the target location.
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

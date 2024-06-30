<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;

class LayoutViewsResource extends PackageResource
{
    /**
     * Create a new resource instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Fill the resource data.

        $this->description = 'The set of views that defines the AdminLTE layout';
        $this->target = CommandHelper::getViewPath('vendor/adminlte');
        $this->required = false;

        // Note we declare the source as an array of files and folders, we do
        // this way o avoid copying the components views located at the
        // 'resources/views/components' folder.

        $this->source = [
            CommandHelper::getPackagePath('resources/views/auth'),
            CommandHelper::getPackagePath('resources/views/master.blade.php'),
            CommandHelper::getPackagePath('resources/views/page.blade.php'),
            CommandHelper::getPackagePath('resources/views/partials'),
            CommandHelper::getPackagePath('resources/views/plugins.blade.php'),
        ];

        // Fill the set of installation messages.

        $this->messages = [
            'install' => 'Do you want to publish the AdminLTE layout views?',
            'overwrite' => 'The layout views were already published. Want to replace?',
            'success' => 'AdminLTE layout views published successfully',
        ];
    }

    /**
     * Installs or publishes the resource.
     *
     * @return void
     */
    public function install()
    {
        // Publish the package layout views.

        foreach ($this->source as $src) {
            $tgt = $this->target.DIRECTORY_SEPARATOR.File::basename($src);
            $this->publishResource($src, $tgt);
        }
    }

    /**
     * Uninstalls the resource.
     *
     * @return void
     */
    public function uninstall()
    {
        // Uninstall the package layout views.

        foreach ($this->source as $src) {
            $tgt = $this->target.DIRECTORY_SEPARATOR.File::basename($src);
            $this->uninstallResource($tgt);
        }
    }

    /**
     * Checks whether the resource already exists in the target location.
     *
     * @return bool
     */
    public function exists()
    {
        foreach ($this->source as $src) {
            $tgt = $this->target.DIRECTORY_SEPARATOR.File::basename($src);

            if (File::exists($tgt)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks whether the resource is correctly installed, i.e. if the source
     * items matches with the items available at the target location.
     *
     * @return bool
     */
    public function installed()
    {
        foreach ($this->source as $src) {
            $tgt = $this->target.DIRECTORY_SEPARATOR.File::basename($src);

            if (! $this->resourceInstalled($src, $tgt)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Publishes the specified source (usually a file or folder) at the
     * specified target location.
     *
     * @param  string  $source  The source path
     * @param  string  $target  The target path
     * @return void
     */
    protected function publishResource($source, $target)
    {
        // Check whether the resource is a file or a directory.

        if (File::isDirectory($source)) {
            CommandHelper::copyDirectory($source, $target, true, true);
        } else {
            File::copy($source, $target);
        }
    }

    /**
     * Uninstalls the resource at the specified target location.
     *
     * @param  string  $target  The target path
     * @return void
     */
    protected function uninstallResource($target)
    {
        // When the target does not exists, we consider the resource as
        // unistalled.

        if (! File::exists($target)) {
            return;
        }

        // Uninstall the resource at the specified target location.

        if (File::isDirectory($target)) {
            File::deleteDirectory($target);
        } else {
            File::delete($target);
        }
    }

    /**
     * Checks whether a resource is correctly installed at the specified target
     * location.
     *
     * @param  string  $source  The source path
     * @param  string  $target  The target path
     * @return bool
     */
    protected function resourceInstalled($source, $target)
    {
        // Check whether the resource is a file or a directory.

        return File::isDirectory($source)
            ? (bool) CommandHelper::compareDirectories($source, $target, true)
            : CommandHelper::compareFiles($source, $target);
    }
}

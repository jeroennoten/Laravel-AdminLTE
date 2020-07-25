<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

use JeroenNoten\LaravelAdminLte\Console\PackageResources\PackageResource;
use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;

class BasicViewsResource extends PackageResource
{
    /**
     * Array with the replacement content of the basic views.
     *
     * @var array
     */
    protected $basicViewsContent = [
        'home.blade.php' => 'home.stub',
    ];

    /**
     * Create a new resource instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Fill the resource data.

        $this->resource = [
            'description' => 'The package basic views',
            'source'      => $this->basicViewsContent,
            'target'      => CommandHelper::getViewPath(),
            'required'    => false,
        ];

        // Fill the installation messages.

        $this->messages = [
            'install'   => 'Install the AdminLTE basic views?',
            'overwrite' => 'The basic views already exists. Want to replace the views?',
            'success'   => 'Basic views installed successfully.',
        ];
    }

    /**
     * Install/Export the resource.
     *
     * @return void
     */
    public function install()
    {
        // Install the basic views. We going to replace the content of any
        // existing basic view.

        foreach ($this->resource['source'] as $file => $stub) {
            $target = $this->resource['target'].DIRECTORY_SEPARATOR.$file;
            CommandHelper::ensureDirectoryExists(dirname($target));
            copy(CommandHelper::getStubPath($stub), $target);
        }
    }

    /**
     * Uninstall/Remove the resource.
     *
     * @return void
     */
    public function uninstall()
    {
        // Remove the package basic views.

        foreach ($this->resource['source'] as $file => $tub) {
            $target = $this->resource['target'].DIRECTORY_SEPARATOR.$file;

            if (is_file($target)) {
                unlink($target);
            }
        }
    }

    /**
     * Check if the resource already exists on the target destination.
     *
     * @return bool
     */
    public function exists()
    {
        // Check if any of the basic views already exists.

        foreach ($this->resource['source'] as $file => $stub) {
            $target = $this->resource['target'].DIRECTORY_SEPARATOR.$file;

            if (is_file($target)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the resource is correctly installed.
     *
     * @return bool
     */
    public function installed()
    {
        foreach ($this->resource['source'] as $file => $stub) {
            $target = $this->resource['target'].DIRECTORY_SEPARATOR.$file;
            $content = file_get_contents(CommandHelper::getStubPath($stub));

            if (! $this->basicViewInstalled($target, $content)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if a basic view is correctly installed.
     *
     * @param string $path Absolute path of the view
     * @param string $content The expected content of the view
     * @return bool
     */
    protected function basicViewInstalled($path, $content)
    {
        return is_file($path) && (file_get_contents($path) === $content);
    }
}

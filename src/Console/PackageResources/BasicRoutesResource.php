<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

use JeroenNoten\LaravelAdminLte\Console\PackageResources\PackageResource;
use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;

class BasicRoutesResource extends PackageResource
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
            'description' => 'The package routes',
            'source'      => CommandHelper::getStubPath('routes.stub'),
            'target'      => base_path('routes/web.php'),
            'required'    => false,
        ];

        // Fill the installation messages.

        $this->messages = [
            'install'   => 'Install the basic package routes?',
            'overwrite' => 'Basic routes are already installed. Want to install they again?',
            'success'   => 'Basic routes installed successfully.',
        ];
    }

    /**
     * Install/Export the resource.
     *
     * @return void
     */
    public function install()
    {
        // If routes already exists, there is no need to install again.

        if ($this->exists()) {
            return;
        }

        // Get the routes to install.

        $routes = file_get_contents($this->resource['source']);

        // Add the routes.

        $target = $this->resource['target'];
        CommandHelper::ensureDirectoryExists(dirname($target));
        file_put_contents($target, $routes, FILE_APPEND);
    }

    /**
     * Check if the resource already exists on the target destination.
     *
     * @return bool
     */
    public function exists()
    {
        $routes = file_get_contents($this->resource['source']);
        $target = $this->resource['target'];

        // First, check if the target routes file exists.

        if (! is_file($target)) {
            return false;
        }

        // Now, check if the target file already contains the routes.

        $targetContent = file_get_contents($target);

        return (strpos($targetContent, $routes) !== false);
    }

    /**
     * Check if the resource is correctly installed.
     *
     * @return bool
     */
    public function installed()
    {
        return $this->exists();
    }
}

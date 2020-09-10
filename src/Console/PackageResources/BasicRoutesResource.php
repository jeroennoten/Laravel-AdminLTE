<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

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

        $this->description = 'The package routes';
        $this->source = CommandHelper::getStubPath('routes.stub');
        $this->target = base_path('routes/web.php');
        $this->required = false;

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

        $routes = file_get_contents($this->source);

        // Add the routes.

        CommandHelper::ensureDirectoryExists(dirname($this->target));
        file_put_contents($this->target, $routes, FILE_APPEND);
    }

    /**
     * Uninstall/Remove the resource.
     *
     * @return void
     */
    public function uninstall()
    {
        $routes = file_get_contents($this->source);

        // If the target routes file exists, remove the package routes.

        if (is_file($this->target)) {
            $targetContent = file_get_contents($this->target);
            $targetContent = str_replace($routes, '', $targetContent);
            file_put_contents($this->target, $targetContent);
        }
    }

    /**
     * Check if the resource already exists on the target destination.
     *
     * @return bool
     */
    public function exists()
    {
        $routes = file_get_contents($this->source);

        // First, check if the target routes file exists.

        if (! is_file($this->target)) {
            return false;
        }

        // Now, check if the target file already contains the routes.

        $targetContent = file_get_contents($this->target);

        return strpos($targetContent, $routes) !== false;
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

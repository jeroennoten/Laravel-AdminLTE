<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;

class AuthRoutesResource extends PackageResource
{
    /**
     * Create a new resource instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Fill the resource data.

        $this->description = 'The set of routes for the Laravel/UI auth scaffolding';
        $this->source = CommandHelper::getStubPath('routes.stub');
        $this->target = base_path('routes/web.php');
        $this->required = false;

        // Fill the installation messages.

        $this->messages = [
            'install' => 'Do you want to publish the Laravel/UI auth routes?',
            'overwrite' => 'The auth routes were already published. Want to publish again?',
            'success' => 'Auth routes published successfully',
        ];
    }

    /**
     * Checks whether the Laravel/UI package, which provides the 'Auth::routes()'
     * method these routes rely on, is available on the application.
     *
     * @return bool
     */
    public function isScaffoldingAvailable()
    {
        return class_exists(\Laravel\Ui\UiServiceProvider::class);
    }

    /**
     * Installs or publishes the resource.
     *
     * @return void
     */
    public function install()
    {
        // Without the Laravel/UI package the published routes raise on every
        // request and on every artisan command, which also takes down the
        // command that would remove them again. So the resource is skipped
        // instead, and the install message explains how to get them.

        if (! $this->isScaffoldingAvailable()) {
            return;
        }

        // If the routes already exists, we won't publish they again.

        if ($this->exists()) {
            return;
        }

        // Get the set of routes to be published.

        $routes = File::get($this->source);

        // Add the routes to the web routes file.

        File::ensureDirectoryExists(File::dirname($this->target));
        File::append($this->target, $routes);
    }

    /**
     * Gets an installation message. The 'success' message is replaced when the
     * routes can't be published, in order to notify the situation to the final
     * user.
     *
     * @param  string  $key  The message keyword
     * @return string|null
     */
    public function getInstallMessage($key)
    {
        if ($key !== 'success' || $this->isScaffoldingAvailable()) {
            return parent::getInstallMessage($key);
        }

        return $this->makeSkippedRoutesMessage();
    }

    /**
     * Makes the message that notifies about the skipped auth routes.
     *
     * @return string
     */
    protected function makeSkippedRoutesMessage()
    {
        $msg = 'The auth routes were skipped: they use the Auth::routes() ';
        $msg .= 'method, which the Laravel/UI package provides.'.PHP_EOL;
        $msg .= 'Publishing them without it would raise on every request.'.PHP_EOL;
        $msg .= 'To get them, install the package and run the installation again:'.PHP_EOL;
        $msg .= ' composer require laravel/ui'.PHP_EOL;
        $msg .= ' php artisan adminlte:install --only=auth_routes'.PHP_EOL;

        return $msg;
    }

    /**
     * Uninstalls the resource.
     *
     * @return void
     */
    public function uninstall()
    {
        // Get the set of routes to be removed.

        $routes = File::get($this->source);

        // If the target routes file exists, then remove the auth routes.
        // Otherwise, we consider the routes as uninstalled.

        if (File::isFile($this->target)) {
            $targetContent = File::get($this->target);
            $targetContent = str_replace($routes, '', $targetContent);
            File::put($this->target, $targetContent);
        }
    }

    /**
     * Checks whether the resource already exists in the target location.
     *
     * @return bool
     */
    public function exists()
    {
        $routes = File::get($this->source);

        // Check whether the target routes file exists and contains the
        // expected routes.

        return File::isFile($this->target)
            && (strpos(File::get($this->target), $routes) !== false);
    }

    /**
     * Checks whether the resource is correctly installed, i.e. if the source
     * items matches with the items available at the target location.
     *
     * @return bool
     */
    public function installed()
    {
        return $this->exists();
    }
}

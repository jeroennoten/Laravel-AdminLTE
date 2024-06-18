<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;

class AuthViewsResource extends PackageResource
{
    /**
     * Array with the replacement content for the authentication views of the
     * legacy Laravel/UI package.
     *
     * @var array
     */
    protected $authViewsContent = [
        'login.blade.php' => '@extends(\'adminlte::auth.login\')',
        'register.blade.php' => '@extends(\'adminlte::auth.register\')',
        'verify.blade.php' => '@extends(\'adminlte::auth.verify\')',
        'passwords/confirm.blade.php' => '@extends(\'adminlte::auth.passwords.confirm\')',
        'passwords/email.blade.php' => '@extends(\'adminlte::auth.passwords.email\')',
        'passwords/reset.blade.php' => '@extends(\'adminlte::auth.passwords.reset\')',
    ];

    /**
     * Create a new resource instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Fill the resource data.

        $this->description = 'The set of AdminLTE replacement auth views for the Laravel/UI package';
        $this->source = $this->authViewsContent;
        $this->target = CommandHelper::getViewPath('auth');
        $this->required = false;

        // Fill the set of installation messages.

        $this->messages = [
            'install' => 'Do you want to publish the replacement auth views for Laravel/UI?',
            'overwrite' => 'The auth views were already published. Want to replace?',
            'success' => 'Auth views published successfully',
        ];
    }

    /**
     * Installs or publishes the resource.
     *
     * @return void
     */
    public function install()
    {
        // Publish the authentication views. We actually need to replace the
        // content of any existing authentication view that were originally
        // provided by the legacy Laravel/UI package.

        foreach ($this->source as $file => $content) {
            $target = $this->target.DIRECTORY_SEPARATOR.$file;
            File::ensureDirectoryExists(File::dirname($target));
            File::put($target, $content);
        }
    }

    /**
     * Uninstalls the resource.
     *
     * @return void
     */
    public function uninstall()
    {
        // Remove the published authentication views.

        foreach ($this->source as $file => $content) {
            $target = $this->target.DIRECTORY_SEPARATOR.$file;

            if (File::isFile($target)) {
                File::delete($target);
            }
        }
    }

    /**
     * Checks whether the resource already exists in the target location.
     *
     * @return bool
     */
    public function exists()
    {
        // Check if any of the authentication views is published. We need to
        // check that at least one of the target files exists and the
        // replacement content is present.

        foreach ($this->source as $file => $content) {
            $target = $this->target.DIRECTORY_SEPARATOR.$file;

            if ($this->authViewExists($target, $content)) {
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
        foreach ($this->source as $file => $content) {
            $target = $this->target.DIRECTORY_SEPARATOR.$file;

            if (! $this->authViewInstalled($target, $content)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks whether an authentication view exists.
     *
     * @param  string  $path  Absolute path of the authentication view
     * @param  string  $content  The expected content of the view
     * @return bool
     */
    protected function authViewExists($path, $content)
    {
        return File::isFile($path)
            && strpos(File::get($path), $content) !== false;
    }

    /**
     * Checks whether an authentication view is correctly installed.
     *
     * @param  string  $path  Absolute path of the authentication view
     * @param  string  $content  The expected content of the view
     * @return bool
     */
    protected function authViewInstalled($path, $content)
    {
        return File::isFile($path) && File::get($path) === $content;
    }
}

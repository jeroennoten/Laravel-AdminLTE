<?php

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;

class CommandTestCase extends TestCase
{
    /**
     * Get package providers.
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        // Register our service provider into the Laravel's application.

        return ['JeroenNoten\LaravelAdminLte\AdminLteServiceProvider'];
    }

    /**
     * Get the fully qualified path to some package resource.
     *
     * @param string $path Relative path to resource
     * @return string Fully qualified path to resource
     */
    protected function packagePath($path)
    {
        return __DIR__.'/../../'.$path;
    }

    /**
     * Ensure a list of files/folders do not exists by deleting it.
     *
     * @param string $resources The files/folders paths.
     * @return void.
     */
    protected function ensureResourcesNotExists(...$resources)
    {
        foreach ($resources as $res) {
            $this->ensureResourceNotExists($res);
        }
    }

    /**
     * Ensure a file/folder do not exists by deleting it.
     *
     * @param string $path Absolute path to the resource
     * @return void
     */
    protected function ensureResourceNotExists($path)
    {
        if (is_file($path)) {
            File::delete($path);
        } elseif (is_dir($path)) {
            File::deleteDirectory($path);
        }
    }

    /**
     * Ensure a file/folder exists by creating it.
     *
     * @param string $path absolute path to the resource
     * @return void
     */
    protected function ensureResourceExists($path)
    {
        if (pathinfo($path, PATHINFO_EXTENSION)) {
            CommandHelper::ensureDirectoryExists(dirname($path));
            file_put_contents($path, 'stub-content');
        } else {
            CommandHelper::ensureDirectoryExists($path);
        }
    }

    /**
     * Install the required vendor asset "vendor/almasaeed2010" into the
     * laravel testing project.
     *
     * @return void
     */
    protected function installVendorAssets()
    {
        $resource = $this->packagePath('vendor/almasaeed2010');
        $target = base_path('vendor/almasaeed2010');

        // Check if vendor assets are already installed.

        if (is_link($target)) {
            return;
        }

        // Create a symbolic link to the required vendor assets.

        symlink($resource, $target);
    }
}

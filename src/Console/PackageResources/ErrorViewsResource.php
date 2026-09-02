<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;

class ErrorViewsResource extends PackageResource
{
    /**
     * The http status codes for which the package provides an error view.
     * Laravel resolves these views by their name, so they have to live on the
     * 'errors' folder of the application.
     *
     * @var array
     */
    protected $statusCodes = ['401', '403', '404', '419', '429', '500', '503'];

    /**
     * Create a new resource instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Fill the resource data. The published views only extend the ones
        // provided by the package, so an update of the package updates them
        // too, and the application can still replace their content.

        $this->description = 'The set of AdminLTE styled error views (404, 500, ...)';
        $this->source = $this->makeSourceContent();
        $this->target = CommandHelper::getViewPath('errors');
        $this->required = false;

        // Fill the set of installation messages.

        $this->messages = [
            'install' => 'Do you want to publish the error views?',
            'overwrite' => 'The error views were already published. Want to replace?',
            'success' => 'Error views published successfully',
        ];
    }

    /**
     * Makes the content of every error view to publish.
     *
     * @return array
     */
    protected function makeSourceContent()
    {
        $content = [];

        foreach ($this->statusCodes as $code) {
            $content["{$code}.blade.php"] = "@extends('adminlte::errors.{$code}')";
        }

        return $content;
    }

    /**
     * Installs or publishes the resource.
     *
     * @return void
     */
    public function install()
    {
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
        foreach ($this->source as $file => $content) {
            $target = $this->target.DIRECTORY_SEPARATOR.$file;

            if (File::isFile($target)) {
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

            if (! File::isFile($target) || File::get($target) !== $content) {
                return false;
            }
        }

        return true;
    }
}

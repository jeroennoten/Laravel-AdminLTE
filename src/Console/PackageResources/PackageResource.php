<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

abstract class PackageResource
{
    /**
     * The resource data. Generally, a resource will contain the next keys:
     * description: The resource description.
     * source: The source of the resource.
     * target: The target destination for the resource.
     * required: Whether the resource is required for the package to work.
     *
     * @var array
     */
    protected $resource;

    /**
     * The set of installation messages.
     *
     * @var array
     */
    protected $messages;

    /**
     * Path to the package root folder.
     *
     * @var string
     */
    protected $packagePath = __DIR__.'/../../../';

    /**
     * Path to the package stubs folder.
     *
     * @var string
     */
    protected $stubsPath = __DIR__.'/../stubs';

    /**
     * Install or export the resource.
     *
     * @return void
     */
    abstract public function install();

    /**
     * Check if the resource already exists on the target destination.
     *
     * @return bool
     */
    abstract public function exists();

    /**
     * Check if the resource is correctly installed.
     *
     * @return bool
     */
    abstract public function installed();

    /**
     * Get some resource data.
     * TODO: We should do better, it is not fine to expose all resource data.
     *
     * @param string $key The keyword of the data to get from resource
     * @return mixed
     */
    public function get($key)
    {
        if (! isset($this->resource[$key])) {
            return;
        }

        return $this->resource[$key];
    }

    /**
     * Get an installation message.
     *
     * @param string $key The message keyword.
     * @return string
     */
    public function getInstallMessage($key)
    {
        if (! isset($this->messages[$key])) {
            return '';
        }

        return $this->messages[$key];
    }

    /**
     * Get the fully qualified path to some package resource.
     *
     * @param string $path Relative path to the resource
     * @return string Fully qualified path to the resource
     */
    protected function getPackagePath($path = null)
    {
        if (! $path) {
            return $this->packagePath;
        }

        return $this->packagePath.DIRECTORY_SEPARATOR.$path;
    }

    /**
     * Get the fully qualified path to some package stub resource.
     *
     * @param string $path Relative path to the stub resource
     * @return string Fully qualified path to the stub resource
     */
    protected function getStubPath($path = null)
    {
        if (! $path) {
            return $this->stubsPath;
        }

        return $this->stubsPath.DIRECTORY_SEPARATOR.$path;
    }

    /**
     * Get the fully qualified path relative to the configured view path.
     *
     * @param string $path Relative path to some view
     * @return string Fully qualified path to the view
     */
    protected function getViewPath($path = null)
    {
        $basePath = config('view.paths')[0] ?? resource_path('views');

        if (! $path) {
            return $basePath;
        }

        return $basePath.DIRECTORY_SEPARATOR.$path;
    }
}

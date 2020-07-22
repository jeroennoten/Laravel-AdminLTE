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
}

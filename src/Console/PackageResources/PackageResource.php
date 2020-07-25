<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

abstract class PackageResource
{
    /**
     * The package resource description. A litle summary of what this
     * resource contains.
     *
     * @var string
     */
    public $description;

    /**
     * The resource source. Usually the source will be a set of paths to files
     * and/or folders.
     *
     * @var mixed
     */
    protected $source;

    /**
     * The resource target. The destination of the resource, usually a root
     * folder or file.
     *
     * @var mixed
     */
    public $target;

    /**
     * Whether this resource is required for the package in order to work fine.
     *
     * @var bool
     */
    public $required;

    /**
     * The set of installation messages for this resource. Usually, the array
     * should contains keys for 'install', 'overwrite' and 'success' messages.
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
     * Uninstall or remove the resource.
     *
     * @return void
     */
    abstract public function uninstall();

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
     * Get an installation message.
     *
     * @param string $key The message keyword
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

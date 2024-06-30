<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

abstract class PackageResource
{
    /**
     * The package resource description. A little summary of what this resource
     * contains.
     *
     * @var string
     */
    public $description;

    /**
     * The source items of this resource. Usually a set of paths to files
     * and/or folders.
     *
     * @var mixed
     */
    protected $source;

    /**
     * The install location of this resource. Usually a target folder or file.
     *
     * @var mixed
     */
    public $target;

    /**
     * Whether this resource is required for the package to work fine.
     *
     * @var bool
     */
    public $required;

    /**
     * A set of messages that will be used during the resource installation.
     * Usually, the array should contains keys for 'install', 'overwrite' and
     * 'success' messages.
     *
     * @var array
     */
    protected $messages;

    /**
     * Installs or publishes the resource.
     *
     * @return void
     */
    abstract public function install();

    /**
     * Uninstalls the resource.
     *
     * @return void
     */
    abstract public function uninstall();

    /**
     * Checks whether the resource already exists in the target location.
     *
     * @return bool
     */
    abstract public function exists();

    /**
     * Checks whether the resource is correctly installed, i.e. if the source
     * items matches with the items available at the target location.
     *
     * @return bool
     */
    abstract public function installed();

    /**
     * Gets an installation message.
     *
     * @param  string  $key  The message keyword
     * @return string|null
     */
    public function getInstallMessage($key)
    {
        if (! isset($this->messages[$key])) {
            return null;
        }

        return $this->messages[$key];
    }
}

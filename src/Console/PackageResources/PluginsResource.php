<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;

class PluginsResource extends PackageResource
{
    /**
     * The catalogue of the available plugins.
     *
     * @var PluginsCatalog
     */
    protected $catalog;

    /**
     * Create a new resource instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->catalog = new PluginsCatalog();

        // Fill the basic resource data.

        $this->description = 'The set of extra plugins recommended for AdminLTE v4';
        $this->required = false;

        // Define the base source folder of the plugins. Note AdminLTE v4 does
        // not bundle any third party plugin anymore, so the plugins are
        // published from the node modules folder of the application.

        $this->source = base_path('node_modules');

        // Define the base target destination for the plugins.

        $this->target = public_path('vendor');

        // Fill the set of installation messages templates.

        $this->messages = [
            'install' => 'Do you want to plublish the :plugin plugin?',
            'overwrite' => 'The :plugin plugin was already published. Want to replace?',
            'remove' => 'Do you really want to remove the :plugin plugin?',
        ];
    }

    /**
     * Gets the plugins source data.
     *
     * @param  string  $pluginKey  A plugin key
     * @return array
     */
    public function getSourceData($pluginKey = null)
    {
        return $this->catalog->get($pluginKey);
    }

    /**
     * Installs or publishes the specified plugin.
     *
     * @param  string  $pluginKey  A plugin key
     * @return void
     */
    public function install($pluginKey = null)
    {
        if (isset($pluginKey) && ! empty($this->catalog->get($pluginKey))) {
            $plugin = $this->preparePlugin($this->catalog->get($pluginKey));
            $this->installPlugin($plugin);
        }
    }

    /**
     * Checks whether the npm package that provides the specified plugin is
     * available on the application node modules folder.
     *
     * @param  string  $pluginKey  A plugin key
     * @return bool
     */
    public function pluginAvailable($pluginKey)
    {
        $plugin = $this->catalog->get($pluginKey) ?: null;

        if (! isset($plugin)) {
            return false;
        }

        $package = $plugin['package'] ?? null;

        if (! isset($package)) {
            return false;
        }

        $path = $this->source.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $package);

        return File::isDirectory($path);
    }

    /**
     * Gets the npm command required to install the package that provides the
     * specified plugin.
     *
     * @param  string  $pluginKey  A plugin key
     * @return string|null
     */
    public function getInstallPackageCommand($pluginKey)
    {
        $plugin = $this->catalog->get($pluginKey) ?: null;

        if (! isset($plugin['package'])) {
            return null;
        }

        $version = $plugin['version'] ?? null;
        $package = isset($version) ? "{$plugin['package']}@{$version}" : $plugin['package'];

        return "npm i {$package}";
    }

    /**
     * Gets the AdminLTE v4 replacement of a legacy (AdminLTE v3) plugin key.
     * It returns null when the plugin has no replacement, and false when the
     * specified key is not a legacy plugin key.
     *
     * @param  string  $pluginKey  A plugin key
     * @return string|null|false
     */
    public function getLegacyPluginReplacement($pluginKey)
    {
        return $this->catalog->getLegacyReplacement($pluginKey);
    }

    /**
     * Uninstalls the specified plugin.
     *
     * @param  string  $pluginKey  A plugin key
     * @return void
     */
    public function uninstall($pluginKey = null)
    {
        if (isset($pluginKey) && ! empty($this->catalog->get($pluginKey))) {
            $plugin = $this->preparePlugin($this->catalog->get($pluginKey));
            $this->uninstallPlugin($plugin);
        }
    }

    /**
     * Checks whether a plugin already exists in the target location.
     *
     * @param  string  $pluginKey  A plugin key
     * @return bool
     */
    public function exists($pluginKey = null)
    {
        if (isset($pluginKey) && ! empty($this->catalog->get($pluginKey))) {
            $plugin = $this->preparePlugin($this->catalog->get($pluginKey));

            return $this->pluginExists($plugin);
        }

        return false;
    }

    /**
     * Checks whether a plugin is correctly installed, i.e. if the source items
     * matches with the items available at the target location.
     *
     * @param  string  $pluginKey  A plugin key
     * @return bool
     */
    public function installed($pluginKey = null)
    {
        if (isset($pluginKey) && ! empty($this->catalog->get($pluginKey))) {
            $plugin = $this->preparePlugin($this->catalog->get($pluginKey));

            return $this->pluginInstalled($plugin);
        }

        return false;
    }

    /**
     * Prepares a plugin with some sort of normalizations in its data.
     *
     * @param  array  $plugin  An array with the plugin data
     * @return array
     */
    protected function preparePlugin($plugin): array
    {
        // Add source and target when not defined.

        $plugin['source'] = $plugin['source'] ?? '';
        $plugin['target'] = $plugin['target'] ?? $plugin['source'];

        // Add fully qualified paths and default values.

        $plugin['source'] = $this->source.DIRECTORY_SEPARATOR.$plugin['source'];
        $plugin['target'] = $this->target.DIRECTORY_SEPARATOR.$plugin['target'];
        $plugin['ignore'] = $plugin['ignore'] ?? [];
        $plugin['recursive'] = $plugin['recursive'] ?? true;

        // Add fully qualified paths and default values on the resources.

        foreach ($plugin['resources'] ?? [] as $key => $res) {
            $plugin['resources'][$key] = $this->prepareResource($res, $plugin);
        }

        // Return normalized plugin data.

        return $plugin;
    }

    /**
     * Normalizes the data of a plugin resource, relative to its plugin.
     *
     * @param  array  $res  An array with the resource data
     * @param  array  $plugin  An array with the (normalized) plugin data
     * @return array
     */
    protected function prepareResource($res, $plugin): array
    {
        $res['target'] = $res['target'] ?? $res['source'];
        $res['source'] = $plugin['source'].DIRECTORY_SEPARATOR.$res['source'];
        $res['target'] = $plugin['target'].DIRECTORY_SEPARATOR.$res['target'];
        $res['ignore'] = $res['ignore'] ?? $plugin['ignore'];
        $res['recursive'] = $res['recursive'] ?? $plugin['recursive'];

        return $res;
    }

    /**
     * Installs the specified AdminLTE plugin.
     *
     * @param  array  $plugin  An array with the plugin data
     * @return void
     */
    protected function installPlugin($plugin): void
    {
        // First, check and install dependencies plugins, if any.

        foreach (($plugin['dependencies'] ?? []) as $pluginKey) {
            $this->install($pluginKey);
        }

        // Now, check if we need to export the entire plugin.

        if (! isset($plugin['resources'])) {
            $this->publishResource($plugin);

            return;
        }

        // Otherwise, publish only the specified plugin resources.

        foreach ($plugin['resources'] as $res) {
            $this->publishResource($res);
        }
    }

    /**
     * Publishes the specified resource (usually a file or folder).
     *
     * @param  array  $res  An array with the resource data
     * @return void
     */
    protected function publishResource($res): void
    {
        // Check whether the resource is a file or a directory.

        if (File::isDirectory($res['source'])) {
            CommandHelper::copyDirectory(
                $res['source'],
                $res['target'],
                $res['force'] ?? true,
                $res['recursive'] ?? true,
                $res['ignore'] ?? []
            );
        } else {
            File::ensureDirectoryExists(File::dirname($res['target']));
            File::copy($res['source'], $res['target']);
        }
    }

    /**
     * Checks whether the specified plugin already exists in the target
     * location.
     *
     * @param  array  $plugin  An array with the plugin data
     * @return bool
     */
    protected function pluginExists($plugin): bool
    {
        // When the plugin is not a resources list, just check if target exists.

        if (! isset($plugin['resources'])) {
            return File::exists($plugin['target']);
        }

        // Otherwise, check if any of the plugin resources already exists.

        foreach ($plugin['resources'] as $res) {
            if (File::exists($res['target'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks whether the specified plugin is correctly installed.
     *
     * @param  array  $plugin  An array with the plugin data
     * @return bool
     */
    protected function pluginInstalled($plugin): bool
    {
        // Check whether the plugin has resources or not.

        if (! isset($plugin['resources'])) {
            return $this->resourceInstalled($plugin);
        }

        foreach ($plugin['resources'] as $res) {
            if (! $this->resourceInstalled($res)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks whether the specified resource is correctly installed.
     *
     * @param  array  $res  An array with the resource data
     * @return bool
     */
    protected function resourceInstalled($res): bool
    {
        // Check whether the resource is a file or a directory.

        if (File::isDirectory($res['source'])) {
            return (bool) CommandHelper::compareDirectories(
                $res['source'],
                $res['target'],
                $res['recursive'] ?? true,
                $res['ignore'] ?? []
            );
        }

        return CommandHelper::compareFiles($res['source'], $res['target']);
    }

    /**
     * Uninstalls the specified plugin.
     *
     * @param  array  $plugin  An array with the plugin data
     * @return void
     */
    protected function uninstallPlugin($plugin): void
    {
        // If the plugin doensn't have resources, remove the main target
        // location folder.

        if (! isset($plugin['resources'])) {
            $this->uninstallResource($plugin);

            return;
        }

        // Otherwise, remove only the specified plugin resources.

        foreach ($plugin['resources'] as $res) {
            $this->uninstallResource($res);
        }
    }

    /**
     * Removes the specified resource (usually a folder).
     *
     * @param  array  $res  An array with the resource data
     * @return void
     */
    protected function uninstallResource($res): void
    {
        $target = $res['target'];

        // Uninstall the specified resource. When the target location does not
        // exists, we consider the resource as uninstalled. Note a resource may
        // be published as a single file too.

        if (File::isDirectory($target)) {
            File::deleteDirectory($target);
        } elseif (File::exists($target)) {
            File::delete($target);
        }
    }
}

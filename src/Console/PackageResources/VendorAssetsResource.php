<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;

class VendorAssetsResource extends PackageResource
{
    /**
     * The set of third party assets that are required at runtime by the
     * AdminLTE v4 template, but that are not distributed with it. These
     * assets are published from the node modules folder of the application.
     *
     * Every asset may contain the next data keys:
     * - name: The descriptive name of the asset.
     * - package: The name of the npm package that provides the asset.
     * - version: The npm version constraint suggested for the package.
     * - source: The fully qualified path of the npm package folder.
     * - target: The fully qualified path of the publishing destination.
     * - resources: An array with the resources to publish from the package.
     *
     * @var array
     */
    protected $assets;

    /**
     * Create a new resource instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Fill the resource data. Note this resource is optional, since the
        // package provides a CDN fallback for all these assets (see the
        // 'assets' section of the package configuration file).

        $this->description = 'The third party assets required by AdminLTE v4 (published from node_modules)';
        $this->target = public_path('vendor');
        $this->required = false;

        // Define the set of third party assets. The publishing targets are
        // aligned with the 'adminlte.assets.local' configuration options.

        $nodePath = base_path('node_modules');

        $this->assets = [
            'bootstrap' => [
                'name' => 'Bootstrap 5 (Javascript bundle)',
                'package' => 'bootstrap',
                'version' => '^5.3',
                'source' => $nodePath.DIRECTORY_SEPARATOR.'bootstrap',
                'target' => public_path('vendor/bootstrap'),
                'resources' => [
                    [
                        'source' => 'dist/js',
                        'target' => 'js',
                        'recursive' => false,
                        'ignore' => ['*.map'],
                    ],
                ],
            ],
            'bootstrap-icons' => [
                'name' => 'Bootstrap Icons',
                'package' => 'bootstrap-icons',
                'version' => '^1.13',
                'source' => $nodePath.DIRECTORY_SEPARATOR.'bootstrap-icons',
                'target' => public_path('vendor/bootstrap-icons'),
                'resources' => [
                    [
                        'source' => 'font',
                        'target' => 'font',
                        'recursive' => true,
                        'ignore' => ['*.map', '*.json', '*.scss'],
                    ],
                ],
            ],
            'overlayscrollbars' => [
                'name' => 'OverlayScrollbars',
                'package' => 'overlayscrollbars',
                'version' => '^2.11',
                'source' => $nodePath.DIRECTORY_SEPARATOR.'overlayscrollbars',
                'target' => public_path('vendor/overlayscrollbars'),
                'resources' => [
                    [
                        'source' => 'styles',
                        'target' => 'styles',
                        'recursive' => false,
                        'ignore' => ['*.map'],
                    ],
                    [
                        'source' => 'browser',
                        'target' => 'browser',
                        'recursive' => false,
                        'ignore' => ['*.map'],
                    ],
                ],
            ],
        ];

        // The source of this resource is the node modules folder.

        $this->source = $nodePath;

        // Fill the set of installation messages.

        $this->messages = [
            'install' => 'Do you want to publish the third party asset files (Bootstrap, Bootstrap Icons, OverlayScrollbars)?',
            'overwrite' => 'The third party asset files were already published. Want to replace?',
            'success' => 'Third party asset files published successfully',
        ];
    }

    /**
     * Gets an installation message. The 'success' message is adapted when some
     * of the third party assets can't be published, in order to notify the
     * situation to the final user.
     *
     * @param  string  $key  The message keyword
     * @return string|null
     */
    public function getInstallMessage($key)
    {
        if ($key !== 'success') {
            return parent::getInstallMessage($key);
        }

        // When all the third party assets are available, just return the
        // default success message.

        $missing = $this->getMissingAssets();

        if (empty($missing)) {
            return parent::getInstallMessage($key);
        }

        // Otherwise, notify which assets were skipped and how to get them.

        return $this->makeSkippedAssetsMessage($missing);
    }

    /**
     * Gets the third party assets data.
     *
     * @param  string  $assetKey  An asset key
     * @return array
     */
    public function getSourceData($assetKey = null)
    {
        if (! empty($assetKey)) {
            return $this->assets[$assetKey] ?? [];
        }

        return $this->assets;
    }

    /**
     * Gets the keys of the third party assets that can't be published, since
     * their npm packages are not available on the application.
     *
     * @return array
     */
    public function getMissingAssets()
    {
        $missing = [];

        foreach ($this->assets as $key => $asset) {
            if (! $this->assetAvailable($asset)) {
                $missing[] = $key;
            }
        }

        return $missing;
    }

    /**
     * Gets the npm command required to install the missing third party assets.
     *
     * @return string|null
     */
    public function getInstallPackagesCommand()
    {
        $packages = [];

        foreach ($this->getMissingAssets() as $key) {
            $asset = $this->assets[$key];
            $packages[] = "{$asset['package']}@{$asset['version']}";
        }

        if (empty($packages)) {
            return null;
        }

        return 'npm i '.implode(' ', $packages);
    }

    /**
     * Installs or publishes the resource. Note the third party assets that are
     * not available on the node modules folder are just skipped, they are not
     * required since the package provides a CDN fallback for all of them.
     *
     * @return void
     */
    public function install()
    {
        foreach ($this->assets as $asset) {
            if ($this->assetAvailable($asset)) {
                $this->installAsset($asset);
            }
        }
    }

    /**
     * Uninstalls the resource.
     *
     * @return void
     */
    public function uninstall()
    {
        foreach ($this->assets as $asset) {
            $this->uninstallAsset($asset);
        }
    }

    /**
     * Checks whether the resource already exists in the target location.
     *
     * @return bool
     */
    public function exists()
    {
        foreach ($this->assets as $asset) {
            if (File::exists($asset['target'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks whether the resource is correctly installed, i.e. if the source
     * items matches with the items available at the target location. Note the
     * assets without an available source can't be published, so they are not
     * considered on the verification.
     *
     * @return bool
     */
    public function installed()
    {
        $verified = 0;

        foreach ($this->assets as $asset) {
            if (! $this->assetAvailable($asset)) {
                continue;
            }

            if (! $this->assetInstalled($asset)) {
                return false;
            }

            $verified++;
        }

        // When none of the third party assets is available, there is nothing
        // published, so we consider the resource as not installed.

        return $verified > 0;
    }

    /**
     * Checks whether the npm package of the specified asset is available.
     *
     * @param  array  $asset  An array with the asset data
     * @return bool
     */
    protected function assetAvailable($asset)
    {
        return File::isDirectory($asset['source']);
    }

    /**
     * Makes the message that notifies about the skipped third party assets.
     *
     * @param  array  $missing  An array with the missing assets keys
     * @return string
     */
    protected function makeSkippedAssetsMessage($missing)
    {
        $names = array_map(
            function ($key) {
                return $this->assets[$key]['name'];
            },
            $missing
        );

        $msg = 'Some third party assets were skipped: '.implode(', ', $names);
        $msg .= PHP_EOL;
        $msg .= 'They are not available at: '.$this->source;
        $msg .= PHP_EOL;
        $msg .= 'The CDN fallback will be used for the skipped assets.';
        $msg .= PHP_EOL;
        $msg .= 'To publish them locally, install the npm packages and run the installation again:';
        $msg .= PHP_EOL;
        $msg .= '  '.$this->getInstallPackagesCommand();
        $msg .= PHP_EOL;
        $msg .= '  php artisan adminlte:install --only=vendor_assets';

        return $msg;
    }

    /**
     * Installs the specified third party asset.
     *
     * @param  array  $asset  An array with the asset data
     * @return void
     */
    protected function installAsset($asset)
    {
        foreach ($this->prepareAssetResources($asset) as $res) {
            $this->publishResource($res);
        }
    }

    /**
     * Prepares the resources of an asset by adding the fully qualified paths
     * and the default values.
     *
     * @param  array  $asset  An array with the asset data
     * @return array
     */
    protected function prepareAssetResources($asset)
    {
        $resources = [];

        foreach ($asset['resources'] as $res) {
            $res['target'] = $res['target'] ?? $res['source'];
            $res['source'] = $asset['source'].DIRECTORY_SEPARATOR.$res['source'];
            $res['target'] = $asset['target'].DIRECTORY_SEPARATOR.$res['target'];
            $resources[] = $res;
        }

        return $resources;
    }

    /**
     * Publishes the specified resource (usually a file or folder).
     *
     * @param  array  $res  An array with the resource data
     * @return void
     */
    protected function publishResource($res)
    {
        // When the source does not exists, there is nothing to publish.

        if (! File::exists($res['source'])) {
            return;
        }

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
     * Checks whether the specified asset is correctly installed.
     *
     * @param  array  $asset  An array with the asset data
     * @return bool
     */
    protected function assetInstalled($asset)
    {
        foreach ($this->prepareAssetResources($asset) as $res) {
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
    protected function resourceInstalled($res)
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
     * Uninstalls the specified asset.
     *
     * @param  array  $asset  An array with the asset data
     * @return void
     */
    protected function uninstallAsset($asset)
    {
        $target = $asset['target'];

        // Uninstall the specified asset. Note the asset target location is
        // always a folder. When the target folder does not exists, we consider
        // the asset as uninstalled.

        if (File::isDirectory($target)) {
            File::deleteDirectory($target);
        }
    }
}

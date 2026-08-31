<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;

class AdminlteAssetsResource extends PackageResource
{
    /**
     * Create a new resource instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Fill the resource data.

        $this->description = 'The set of files required to use the AdminLTE template';
        $this->target = public_path('vendor');
        $this->required = true;

        // Define the array of required assets (source).

        $adminltePath = base_path('vendor/almasaeed2010/adminlte/');

        $this->source = [
            'adminlte' => [
                'name' => 'AdminLTE v4',
                'source' => $adminltePath,
                'target' => public_path('vendor/adminlte/'),
                'resources' => [
                    // The compiled stylesheets. Note we publish the entire
                    // folder in order to provide the RTL variants and the
                    // optional stylesheets (extended colors, select2 theme).
                    // The source maps are not published, they are only
                    // meaningful with the (not published) Sass sources. The
                    // 'adminlte-docs' stylesheet is only used by the AdminLTE
                    // documentation site, so it's not published too.

                    [
                        'source' => 'dist/css',
                        'recursive' => false,
                        'ignore' => [
                            '*.map',
                            'adminlte-docs.*',
                        ],
                    ],

                    // The compiled scripts. The ESM builds are intended to be
                    // consumed by a javascript bundler (they can't be loaded
                    // with a plain script tag), so they are not published.

                    [
                        'source' => 'dist/js',
                        'recursive' => false,
                        'ignore' => [
                            '*.map',
                            '*.esm.js',
                            '*.esm.min.js',
                        ],
                    ],

                    // The default logo image. Note the AdminLTE v4 images are
                    // located at the 'dist/assets/img' folder.

                    [
                        'source' => 'dist/assets/img/AdminLTELogo.png',
                    ],
                ],
            ],
        ];

        // Fill the set of installation messages.

        $this->messages = [
            'install' => 'Do you want to publish the AdminLTE asset files?',
            'overwrite' => 'AdminLTE asset files were already published. Want to replace?',
            'success' => 'AdminLTE assets files published successfully',
        ];
    }

    /**
     * Installs or publishes the resource.
     *
     * @return void
     */
    public function install()
    {
        // Install the AdminLTE asset files.

        foreach ($this->source as $asset) {
            $this->installAsset($asset);
        }
    }

    /**
     * Uninstalls the resource.
     *
     * @return void
     */
    public function uninstall()
    {
        // Uninstall the AdminLTE asset files.

        foreach ($this->source as $asset) {
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
        foreach ($this->source as $asset) {
            if ($this->assetExists($asset)) {
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
        foreach ($this->source as $asset) {
            if (! $this->assetInstalled($asset)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Installs the specified AdminLTE asset.
     *
     * @param  array  $asset  An array with the asset data
     * @return void
     */
    protected function installAsset($asset)
    {
        // Check if we just need to publish the entire asset.

        if (! isset($asset['resources'])) {
            $this->publishResource($asset);

            return;
        }

        // Otherwise, publish only the specified asset resources.

        foreach ($asset['resources'] as $res) {
            $res['target'] = $res['target'] ?? $res['source'];
            $res['target'] = $asset['target'].$res['target'];
            $res['source'] = $asset['source'].$res['source'];
            $this->publishResource($res);
        }
    }

    /**
     * Publishes the specified resource (usually a file or folder).
     *
     * @param  array  $res  An array with the resource data
     * @return void
     */
    protected function publishResource($res)
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
     * Checks whether the specified asset already exists in the target location.
     *
     * @param  array  $asset  An array with the asset data
     * @return bool
     */
    protected function assetExists($asset)
    {
        return File::exists($asset['target']);
    }

    /**
     * Checks whether the specified asset is correctly installed.
     *
     * @param  array  $asset  An array with the asset data
     * @return bool
     */
    protected function assetInstalled($asset)
    {
        // Check whether the asset has resources or not.

        if (! isset($asset['resources'])) {
            return $this->resourceInstalled($asset);
        }

        foreach ($asset['resources'] as $res) {
            $res['target'] = $res['target'] ?? $res['source'];
            $res['target'] = $asset['target'].$res['target'];
            $res['source'] = $asset['source'].$res['source'];

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

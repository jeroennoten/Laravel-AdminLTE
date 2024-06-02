<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;

class AssetsResource extends PackageResource
{
    /**
     * Create a new resource instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Fill the resource data.

        $this->description = 'The required files for use the AdminLTE template';
        $this->target = public_path('vendor');
        $this->required = true;

        // Define the array of required assets (source).

        $adminltePath = base_path('vendor/almasaeed2010/adminlte/');

        $this->source = [
            'adminlte' => [
                'name' => 'AdminLTE v3',
                'source' => $adminltePath,
                'target' => public_path('vendor/adminlte/'),
                'resources' => [
                    [
                        'source' => 'dist/css',
                        'recursive' => false,
                    ],
                    [
                        'source' => 'dist/js',
                        'recursive' => false,
                        'ignore' => [
                            'demo.js',
                        ],
                    ],
                    [
                        'source' => 'dist/img/AdminLTELogo.png',
                    ],
                ],
            ],
            'fontawesome' => [
                'name' => 'FontAwesome 5 Free',
                'source' => $adminltePath.'/plugins/fontawesome-free',
                'target' => public_path('vendor/fontawesome-free'),
            ],
            'bootstrap' => [
                'name' => 'Bootstrap 4 (only JS files)',
                'source' => $adminltePath.'/plugins/bootstrap',
                'target' => public_path('vendor/bootstrap'),
            ],
            'popper' => [
                'name' => 'Popper.js (Bootstrap 4 requirement)',
                'source' => $adminltePath.'/plugins/popper',
                'target' => public_path('vendor/popper'),
            ],
            'jquery' => [
                'name' => 'jQuery (Bootstrap 4 requirement)',
                'source' => $adminltePath.'/plugins/jquery',
                'target' => public_path('vendor/jquery'),
                'ignore' => [
                    'core.js',
                    'jquery.slim.js',
                    'jquery.slim.min.js',
                    'jquery.slim.min.map',
                ],
            ],
            'overlay' => [
                'name' => 'Overlay Scrollbars',
                'source' => $adminltePath.'/plugins/overlayScrollbars',
                'target' => public_path('vendor/overlayScrollbars'),
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
     * @return bool
     */
    public function install()
    {
        // Install the AdminLTE asset files.

        foreach ($this->source as $asset) {
            if (! $this->installAsset($asset)) {
                return false;
            };
        }

        return true;
    }

    /**
     * Uninstalls the resource.
     *
     * @return bool
     */
    public function uninstall()
    {
        // Uninstall the AdminLTE asset files.

        foreach ($this->source as $asset) {
            if (! $this->uninstallAsset($asset)) {
                return false;
            }
        }

        return true;
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
     * Install the specified AdminLTE asset.
     *
     * @param  array  $asset  An array with the asset data
     * @return bool
     */
    protected function installAsset($asset)
    {
        // Check if we just need to publish the entire asset.

        if (! isset($asset['resources'])) {
            return $this->publishResource($asset);
        }

        // Otherwise, publish only the specified asset resources.

        foreach ($asset['resources'] as $res) {
            $res['target'] = $res['target'] ?? $res['source'];
            $res['target'] = $asset['target'].$res['target'];
            $res['source'] = $asset['source'].$res['source'];

            if (! $this->publishResource($res)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Publishes the specified resource (usually a file or folder).
     *
     * @param  array  $res  An array with the resource data
     * @return bool
     */
    protected function publishResource($res)
    {
        // Check whether the resource is a file or a directory.

        if (File::isDirectory($res['source'])) {
            return CommandHelper::copyDirectory(
                $res['source'],
                $res['target'],
                $res['force'] ?? true,
                $res['recursive'] ?? true,
                $res['ignore'] ?? []
            );
        }

        File::ensureDirectoryExists(File::dirname($res['target']));

        return File::copy($res['source'], $res['target']);
    }

    /**
     * Checks whether the specified asset already exists in the target location.
     *
     * @param  array  $asset  An array with the asset data
     * @return bool
     */
    protected function assetExists($asset)
    {
        return File::isDirectory($asset['target']);
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

        // Uninstall the asset (actually, the target is always a folder).

        if (File::isDirectory($target)) {
            return File::deleteDirectory($target);
        }

        return false;
    }
}

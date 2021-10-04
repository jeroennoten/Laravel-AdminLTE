<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

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

        $this->description = 'The AdminLTE required assets';
        $this->target = public_path('vendor');
        $this->required = true;

        // Define the array of required assets (source).

        $this->source = [
            'adminlte' => [
                'name' => 'AdminLTE v3',
                'source' => base_path('vendor/almasaeed2010/adminlte/'),
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
                'source' => base_path('vendor/almasaeed2010/adminlte/plugins/fontawesome-free'),
                'target' => public_path('vendor/fontawesome-free'),
            ],
            'bootstrap' => [
                'name' => 'Bootstrap 4 (js files only)',
                'source' => base_path('vendor/almasaeed2010/adminlte/plugins/bootstrap'),
                'target' => public_path('vendor/bootstrap'),
            ],
            'popper' => [
                'name' => 'Popper.js (Bootstrap 4 requirement)',
                'source' => base_path('vendor/almasaeed2010/adminlte/plugins/popper'),
                'target' => public_path('vendor/popper'),
            ],
            'jquery' => [
                'name' => 'jQuery (Bootstrap 4 requirement)',
                'source' => base_path('vendor/almasaeed2010/adminlte/plugins/jquery'),
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
                'source' => base_path('vendor/almasaeed2010/adminlte/plugins/overlayScrollbars'),
                'target' => public_path('vendor/overlayScrollbars'),
            ],
        ];

        // Fill the set of installation messages.

        $this->messages = [
            'install'   => 'Install the basic package assets?',
            'overwrite' => 'The basic assets already exists. Want to replace the assets?',
            'success'   => 'Basic assets installed successfully.',
        ];
    }

    /**
     * Install/Export the resource.
     *
     * @return void
     */
    public function install()
    {
        // Install the AdminLTE basic assets.

        foreach ($this->source as $asset) {
            $this->installAsset($asset);
        }
    }

    /**
     * Uninstall/Remove the resource.
     *
     * @return void
     */
    public function uninstall()
    {
        // Uninstall the AdminLTE basic assets.

        foreach ($this->source as $asset) {
            $this->uninstallAsset($asset);
        }
    }

    /**
     * Check if the resource already exists on the target destination.
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
     * Check if the resource is correctly installed.
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
     * @return void
     */
    protected function installAsset($asset)
    {
        // Check if we just need to export the entire asset.

        if (! isset($asset['resources'])) {
            $this->exportResource($asset);

            return;
        }

        // Otherwise, export only the specified asset resources.

        foreach ($asset['resources'] as $res) {
            $res['target'] = $res['target'] ?? $res['source'];
            $res['target'] = $asset['target'].$res['target'];
            $res['source'] = $asset['source'].$res['source'];
            $this->exportResource($res);
        }
    }

    /**
     * Exports the specified resource (usually a file or folder).
     *
     * @param  array  $res  An array with the resource data
     * @return void
     */
    protected function exportResource($res)
    {
        // Check the resource type in order to copy it.

        if (is_dir($res['source'])) {
            CommandHelper::copyDirectory(
                $res['source'],
                $res['target'],
                $res['force'] ?? true,
                $res['recursive'] ?? true,
                $res['ignore'] ?? []
            );
        } elseif (is_file($res['source'])) {
            CommandHelper::ensureDirectoryExists(dirname($res['target']));
            copy($res['source'], $res['target']);
        }
    }

    /**
     * Check if the specified asset already exists on the target destination.
     *
     * @param  array  $asset  An array with the asset data
     * @return bool
     */
    protected function assetExists($asset)
    {
        return is_dir($asset['target']);
    }

    /**
     * Check if the specified asset is correctly installed.
     *
     * @param  array  $asset  An array with the asset data
     * @return bool
     */
    protected function assetInstalled($asset)
    {
        // Check if the asset have resources.

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
     * Check if the specified resource is correctly installed.
     *
     * @param  array  $res  An array with the resource data
     * @return bool
     */
    protected function resourceInstalled($res)
    {
        $installed = false;

        if (is_dir($res['source'])) {
            $installed = (bool) CommandHelper::compareDirectories(
                $res['source'],
                $res['target'],
                $res['recursive'] ?? true,
                $res['ignore'] ?? []
            );
        } elseif (is_file($res['source'])) {
            $installed = CommandHelper::compareFiles(
                $res['source'],
                $res['target']
            );
        }

        return $installed;
    }

    /**
     * Uninstall or remove the specified asset.
     *
     * @param  array  $asset  An array with the asset data
     * @return void
     */
    protected function uninstallAsset($asset)
    {
        $target = $asset['target'];

        // Uninstall the asset (actually, the target should be a folder).

        if (is_dir($target)) {
            CommandHelper::removeDirectory($target);
        }
    }
}

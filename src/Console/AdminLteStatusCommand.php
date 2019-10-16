<?php

namespace JeroenNoten\LaravelAdminLte\Console;

use Illuminate\Console\Command;
use JeroenNoten\LaravelAdminLte\Http\Helpers\CommandHelper;
use JeroenNoten\LaravelAdminLte\Console\AdminLteInstallCommand;

class AdminLteStatusCommand extends Command
{
    protected $signature = 'adminlte:status' .
        '{--include-images : Includes AdminLTE asset images to the checkup}'.
        '{--include-plugins : Includes AdminLTE plugin assets to the checkup}';

    protected $description = 'Checks the install status for AdminLTE assets, routes & views.';

    protected $extra_steps = [
        'config', 'translations', 'main_views', 'auth_views', 'basic_views',
        'basic_routes',
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $headers = ['Group', 'Assets Name', 'Status', 'Required'];
        $step_count = 0;
        $table_content = [];
        $install_command = new AdminLteInstallCommand();

        $assets = $install_command->getProtected('assets');
        $step_count += count($assets);
        $step_count += 1;

        $this->line('Checking Installation ...');
        $bar = $this->output->createProgressBar(count($assets));
        $bar->start();

        foreach ($assets as $asset_key => $asset) {
            $table_content[] = ['assets', $asset['name'], $this->checkAsset($asset_key, $this->option('include-images')), 'true'];
            $bar->advance();
        }



        $bar->finish();

        $this->line('');
        $this->line('Installation Checked');

        $this->table($headers, $table_content);
    }

    /**
     * Check Plugin.
     *
     * @return void
     */
    protected function checkAsset($asset_key, $include_images)
    {
        $install_command = new AdminLteInstallCommand();
        $asset = $install_command->getProtected('assets')[$asset_key];
        $assets_path = $install_command->getProtected('assets_path');
        $package_path = $install_command->getProtected('package_path');

        $asset_exist = true;
        $asset_missmatch = false;
        $asset_child_exist = true;
        $asset_child_missmatch = false;
        $asset_public_path = public_path($assets_path);
        $asset_base_path = base_path($package_path);
        $asset_package_path = $asset['package_path'];
        $asset_assets_path = $asset['assets_path'];
        $asset_ignore = $asset['ignore'] ?? [];
        $asset_ignore_ending = $asset['ignore_ending'] ?? [];
        $asset_recursive = $asset['recursive'] ?? true;

        if (is_array($asset_assets_path)) {
            foreach ($asset_assets_path as $key => $assets_path) {
                if (! file_exists($asset_public_path.$assets_path)) {
                    $asset_exist = false;
                    $asset_child_exist = false;
                } else {
                    $compare = CommandHelper::compareDirectories($asset_base_path.$asset_package_path[$key], $asset_public_path.$assets_path, '', $asset_ignore, $asset_ignore_ending, $asset_recursive);

                    if (! $asset_child_missmatch && $compare) {
                        $asset_child_missmatch = false;
                    } else {
                        $asset_child_missmatch = true;
                    }
                }
            }
        } else {
            if (! file_exists($asset_public_path.$asset_assets_path)) {
                $asset_exist = false;
            } else {
                if (! $compare = CommandHelper::compareDirectories($asset_base_path.$asset_package_path, $asset_public_path.$asset_assets_path, '', $asset_ignore, $asset_ignore_ending, $asset_recursive)) {
                    $asset_missmatch = true;
                }
            }
        }

        if ($include_images && isset($asset['images_path']) && isset($asset['images'])) {
            $asset_images_path = $asset['images_path'];

            foreach ($asset['images'] as $image_package_path => $image_asset_path) {
                if (! file_exists($asset_public_path.$asset_images_path.$image_asset_path)) {
                    $asset_child_exist = false;
                } else {
                    $compare = sha1_file($asset_base_path.$image_package_path) === sha1_file($asset_public_path.$asset_images_path.$image_asset_path);
                    if (! $asset_child_missmatch && $compare) {
                        $asset_child_missmatch = false;
                    } else {
                        $asset_child_missmatch = true;
                    }
                }
            }
        }

        if ($asset_exist && $asset_child_exist && (! $asset_missmatch && ! $asset_child_missmatch)) {
            return 'Installed';
        } elseif ($asset_exist && (($asset_missmatch || $asset_child_missmatch) || ! $asset_child_exist)) {
            return 'Update Available';
        } elseif (! $asset_exist) {
            return 'Not Installed';
        }
    }

    public function checkStep($step) {

    }

    public function checkFile($source_file, $destination_file)
    {
        $file_exist = true;
        $file_missmatch = false;
        $file_public_path = public_path($assets_path);
        $file_base_path = base_path($package_path);
        $file_package_path = $asset['package_path'];
        $file_assets_path = $asset['assets_path'];
        $file_ignore = $asset['ignore'] ?? [];
        $file_ignore_ending = $asset['ignore_ending'] ?? [];
        $file_recursive = $asset['recursive'] ?? true;

        if (! file_exists($file_public_path.$file_assets_path)) {
            $file_exist = false;
        } else {
            $compare = sha1_file($asset_base_path.$image_package_path) === sha1_file($asset_public_path.$asset_images_path.$image_asset_path);
            if (! $compare) {
                $file_missmatch = true;
            }
        }

        if ($file_exist  && (! $file_missmatch)) {
            return 'Installed';
        } elseif ($file_exist && $file_missmatch) {
            return 'Update Available';
        } elseif (! $file_exist) {
            return 'Not Installed';
        }
    }
}

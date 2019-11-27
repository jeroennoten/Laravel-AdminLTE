<?php

namespace JeroenNoten\LaravelAdminLte\Console;

use Illuminate\Console\Command;
use JeroenNoten\LaravelAdminLte\Http\Helpers\CommandHelper;

class AdminLteStatusCommand extends Command
{
    protected $signature = 'adminlte:status'.
        '{--include-images : Includes AdminLTE asset images to the checkup}';

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
        $step_count = 5;
        $table_content = [];
        $install_command = new AdminLteInstallCommand();

        $assets = $install_command->getProtected('assets');
        $package_path = $install_command->getProtected('package_path');

        $this->line('Checking Installation ...');
        $bar = $this->output->createProgressBar($step_count);
        $bar->start();

        // Checking Assets
        foreach ($assets as $asset_key => $asset) {
            $table_content[] = ['assets', $asset['name'], $this->resolveCompare($this->checkAsset($asset_key, $this->option('include-images'))), 'true'];
        }
        $bar->advance();

        // Checking Config
        $table_content[] = ['config', 'Default Config', $this->resolveCompare($this->compareFile($package_path.'config/adminlte.php', base_path('config/adminlte.php'))), 'true'];
        $bar->advance();

        // Checking Translations
        $table_content[] = ['translations', 'Default Translations', $this->resolveCompare($this->compareFolder('resources/lang', 'resources/lang', $package_path, base_path(), true, ['menu.php'])), 'true'];
        $bar->advance();

        // Checking Main Views
        $table_content[] = ['auth_views', 'Auth Views', $this->resolveCompare($this->compareAuthViews()), 'false'];
        $bar->advance();

        // Checking Main Views
        $table_content[] = ['main_views', 'Main Views', $this->resolveCompare($this->compareFolder('resources/views', 'resources/views/vendor/adminlte/', $package_path, base_path(), true)), 'false'];
        $bar->advance();

        $bar->finish();

        $this->line('');
        $this->line('Installation Checked');

        $this->table($headers, $table_content);
    }

    /**
     * Resolve Compare.
     *
     * @param  $compare
     * @return string
     */
    protected function resolveCompare($compare)
    {
        if ($compare === 1) {
            return 'Installed';
        } elseif ($compare === 2) {
            return 'Update Available / Modified';
        } elseif ($compare === 0) {
            return 'Not Installed';
        }
    }

    /**
     * Check Plugin.
     *
     * @param  $asset_key
     * @param  $include_images
     * @return string
     */
    protected function checkAsset($asset_key, $include_images)
    {
        $install_command = new AdminLteInstallCommand();
        $asset = $install_command->getProtected('assets')[$asset_key];
        $assets_path = $install_command->getProtected('assets_path');
        $package_path = $install_command->getProtected('assets_package_path');
        $compare = $compare_multiple = null;

        if (is_array($asset['package_path'])) {
            foreach ($asset['package_path'] as $key => $value) {
                $compare_multiple += $this->compareFolder($asset['package_path'][$key], $asset['assets_path'][$key], base_path($package_path), public_path($assets_path), $asset['recursive'] ?? true, $asset['ignore'] ?? [], $asset['ignore_ending'] ?? [], $asset['images'] ?? null, $asset['images_path'] ?? null);
            }

            $compare_multiple /= count($asset['package_path']);
            if ($compare_multiple == 1) {
                $compare = 1;
            } elseif ($compare_multiple >= 1) {
                $compare = 2;
            } elseif ($compare_multiple <= 1) {
                $compare = 0;
            }
        } else {
            $compare = $this->compareFolder($asset['package_path'], $asset['assets_path'], base_path($package_path), public_path($assets_path), $asset['recursive'] ?? true, $asset['ignore'] ?? [], $asset['ignore_ending'] ?? [], $asset['images'] ?? null, $asset['images_path'] ?? null);
        }

        return $compare;
    }

    /**
     * Compare Folder.
     *
     * @param  $source_path
     * @param  $destination_path
     * @param  $source_base_path
     * @param  $destination_base_path
     * @param  $recursive
     * @param  $ignore
     * @param  $ignore_ending
     * @param  $images
     * @param  $images_path
     * @return int
     */
    public function compareFolder($source_path, $destination_path, $source_base_path = null, $destination_base_path = null, $recursive = true, $ignore = [], $ignore_ending = [], $images = null, $images_path = null, $ignore_base_folder = null)
    {
        $dest_exist = true;
        $dest_missing = false;
        $dest_missmatch = false;
        $dest_child_exist = true;
        $dest_child_missmatch = false;

        if (! $source_base_path) {
            $source_base_path = base_path();
        }
        if (substr($source_base_path, -1) !== '/') {
            $source_base_path .= '/';
        }

        if (! $destination_base_path) {
            $destination_base_path = public_path();
        }
        if (substr($destination_base_path, -1) !== '/') {
            $destination_base_path .= '/';
        }

        if (is_array($source_path)) {
            foreach ($source_path as $key => $destination_child_path) {
                if (! file_exists($destination_base_path.$destination_child_path)) {
                    $dest_exist = false;
                    $dest_child_exist = false;
                } else {
                    $compare = CommandHelper::compareDirectories($source_base_path.$source_path[$key], $destination_base_path.$destination_child_path, '', $ignore, $ignore_ending, $recursive);

                    if (! $dest_child_missmatch && $compare) {
                        $dest_child_missmatch = false;
                    } else {
                        $dest_child_missmatch = true;
                    }
                }
            }
        } else {
            if (! file_exists($destination_base_path.$destination_path)) {
                $dest_exist = false;
            } else {
                $compare = CommandHelper::compareDirectories($source_base_path.$source_path, $destination_base_path.$destination_path, '', $ignore, $ignore_ending, $recursive, null, true);
                if ($compare === false) {
                    $dest_missmatch = true;
                } elseif ($compare === null) {
                    $dest_missing = true;
                }
            }
        }

        if ($images_path && $images) {
            $asset_images_path = $images_path;

            foreach ($images as $image_destination_path => $image_asset_path) {
                $compareFile = $this->compareFile($source_base_path.$image_destination_path, $destination_base_path.$images_path.$image_asset_path);
                if ($compareFile === 0) {
                    $dest_child_exist = false;
                } elseif ($compareFile == 1) {
                    $dest_child_missmatch = false;
                } elseif ($compareFile == 2) {
                    $dest_child_missmatch = true;
                }
            }
        }

        if ($dest_exist && $dest_child_exist && ! $ignore_base_folder && (! $dest_missmatch && ! $dest_child_missmatch) && ! $dest_missing) {
            return 1;
        } elseif ($dest_exist && (($dest_missmatch || $dest_child_missmatch) || ! $dest_child_exist)) {
            return 2;
        } elseif (! $dest_exist || $dest_missing) {
            return 0;
        }
    }

    /**
     * Compare File.
     *
     * @param  $source_file
     * @param  $destination_file
     * @return int
     */
    public function compareFile($source_file, $destination_file)
    {
        $file_exist = true;
        $file_missmatch = false;

        if (! file_exists($destination_file)) {
            $file_exist = false;
        } else {
            $compare = sha1_file($source_file) === sha1_file($destination_file);
            if (! $compare) {
                $file_missmatch = true;
            }
        }

        if ($file_exist && (! $file_missmatch)) {
            return 1;
        } elseif ($file_exist && $file_missmatch) {
            return 2;
        } elseif (! $file_exist) {
            return 0;
        }
    }

    /**
     * Compare Auth Views.
     *
     * @return int
     */
    public function compareAuthViews()
    {
        $install_command = new AdminLteInstallCommand();
        $auth_views = $install_command->getProtected('authViews');
        $view_exists = true;
        $view_found = 0;
        $view_excepted = count($auth_views);

        foreach ($auth_views as $file_name => $file_content) {
            $file = $install_command->getViewPath($file_name);
            if (file_exists($file)) {
                $dest_file_content = file_get_contents($file);
                if (strpos($dest_file_content, $file_content) !== false) {
                    $view_found++;
                }
            }
        }

        if ($view_found === 0) {
            return 0;
        } elseif ($view_found === $view_excepted) {
            return 1;
        } elseif ($view_found !== $view_excepted) {
            return 2;
        }
    }
}

<?php

namespace JeroenNoten\LaravelAdminLte\Console;

use Illuminate\Console\Command;

class AdminLteInstallCommand extends Command
{
    protected $signature = 'adminlte:install '.
        '{--basic : Only publishes the assets and a basic page example}'.
        '{--force : Overwrite existing views by default}'.
        '{--interactive : The installation will guide you through the process}';

    protected $description = 'Install all the required files for AdminLTE and the authentication views and routes';

    protected $authViews = [
        'auth/login.blade.php'           => '@extends(\'adminlte::login\')',
        'auth/register.blade.php'        => '@extends(\'adminlte::register\')',
        'auth/passwords/email.blade.php' => '@extends(\'adminlte::passwords.email\')',
        'auth/passwords/reset.blade.php' => '@extends(\'adminlte::passwords.reset\')',
    ];

    protected $basicViews = [
        'home.stub' => 'home.blade.php',
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->exportAssets();

        $this->exportBasicViews();

        $this->exportAuthViews();

        $this->exportRoutes();

        $this->exportConfig();

        $this->info(($this->option('basic') ? 'Basic' : 'Full').' AdminLTE Installation complete.');
    }

    /**
     * Export the authentication views.
     *
     * @return void
     */
    protected function exportAuthViews()
    {
        if (! $this->option('basic')) {
            if ($this->option('interactive')) {
                if (! $this->confirm('Install AdminLTE authentication views?')) {
                    return;
                }
            }
            $this->ensureDirectoriesExist($this->getViewPath('auth/passwords'));
            foreach ($this->authViews as $file => $content) {
                file_put_contents($this->getViewPath($file), $content);
            }
            $this->comment('Authentication views installed successfully.');
        }
    }

    /**
     * Export the basic views.
     *
     * @return void
     */
    protected function exportBasicViews()
    {
        if ($this->option('interactive')) {
            if (! $this->confirm('Install AdminLTE basic views?')) {
                return;
            }
        }
        foreach ($this->basicViews as $key => $value) {
            if (file_exists($view = $this->getViewPath($value)) && ! $this->option('force')) {
                if (! $this->confirm("The [{$value}] view already exists. Do you want to replace it?")) {
                    continue;
                }
            }
            copy(
                __DIR__.'/stubs/'.$key,
                $view
            );
        }
        $this->comment('Basic views installed successfully.');
    }

    /**
     * Export the authentication routes.
     *
     * @return void
     */
    protected function exportRoutes()
    {
        if (! $this->option('basic')) {
            if ($this->option('interactive')) {
                if (! $this->confirm('Install AdminLTE authentication routes?')) {
                    return;
                }
            }
            file_put_contents(
                base_path('routes/web.php'),
                file_get_contents(__DIR__.'/stubs/routes.stub'),
                FILE_APPEND
            );
            $this->comment('Authentication routes installed successfully.');
        }
    }

    /**
     * Copy all the content of the Assets Folder to Public Directory.
     */
    protected function exportAssets()
    {
        if ($this->option('interactive')) {
            if (! $this->confirm('Install the package assets?')) {
                return;
            }
        }

        $assetsPath = public_path() . '/vendor/';
        $packagePath = base_path().'/vendor/almasaeed2010/adminlte/';

        // Copy AdminlTE dist
        $this->directoryCopy($packagePath . 'dist/css/', $assetsPath . 'adminlte/dist/css', false);
        $this->directoryCopy($packagePath . 'dist/js/', $assetsPath . 'adminlte/dist/js', false, ['demo.js']);

        // Copy Bootstrap
        $this->directoryCopy($packagePath . 'plugins/bootstrap', $assetsPath . 'bootstrap', true);

        // Copy jQuery
        $this->directoryCopy($packagePath . 'plugins/jquery', $assetsPath . 'jquery', true, ['core.js', 'jquery.slim.js', 'jquery.slim.min.js', 'jquery.slim.min.map']);

        // Copy overlayScrollbars
        $this->directoryCopy($packagePath . 'plugins/overlayScrollbars', $assetsPath . 'overlayScrollbars', true);

        $this->comment('Basic Assets Installation complete.');

        $plugins = [
            [
                'name' => 'Bootstrap Colorpicker',
                'package_path' => 'bootstrap-colorpicker',
                'assets_path' => 'bootstrap-colorpicker',
            ],
            [
                'name' => 'Bootstrap Slider',
                'package_path' => 'bootstrap-slider',
                'assets_path' => 'bootstrap-slider',
            ],
            [
                'name' => 'Bootstrap4 Duallistbox',
                'package_path' => 'bootstrap4-duallistbox',
                'assets_path' => 'bootstrap4-duallistbox',
            ],
            [
                'name' => 'Chart.js',
                'package_path' => 'chart.js',
                'assets_path' => 'chart.js',
            ],
            [
                'name' => 'Datatables',
                'package_path' => [
                    'datatables',
                    'datatables-bs4',
                ],
                'assets_path' => [
                    'datatables/js',
                    'datatables',
                ],
            ],
            [
                'name' => 'Datatables Plugins',
                'package_path' => [
                    'datatables-autofill',
                ],
                'assets_path' => [
                    'datatables-plugins/autofill',
                ],
            ],
        ];

        foreach ($plugins as $plugin) {
            if ($this->option('interactive')) {
                if (! $this->confirm('Install the ' . $plugin['name'] . ' assets?')) {
                    continue;
                }
            }

            print_r($plugin['package_path']);

            // $this->directoryCopy($packagePath . 'dist/css/', $assetsPath . 'adminlte/dist/css', false);
            // $this->directoryCopy($packagePath . 'dist/js/', $assetsPath . 'adminlte/dist/js', false, ['demo.js']);
        }



        $this->comment('Plugin Assets Installation complete.');
    }

    /**
     * Install the config file.
     */
    protected function exportConfig()
    {
        if ($this->option('interactive')) {
            if (! $this->confirm('Install the package config file?')) {
                return;
            }
        }
        if (file_exists(config_path('adminlte.php')) && ! $this->option('force')) {
            if (! $this->confirm('The AdminLTE configuration file already exists. Do you want to replace it?')) {
                return;
            }
        }
        copy(
            __DIR__.'/../../config/adminlte.php',
            config_path('adminlte.php')
        );

        $this->comment('Configuration Files Installation complete.');
    }

    /**
     * Check if the directories for the files exists.
     *
     * @param $directory
     * @return void
     */
    protected function ensureDirectoriesExist($directory)
    {
        // CHECK if directory exists, if not create it
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
    }

    /**
     * Get full view path relative to the application's configured view path.
     *
     * @param  string  $path
     * @return string
     */
    protected function getViewPath($path)
    {
        return implode(DIRECTORY_SEPARATOR, [
            config('view.paths')[0] ?? resource_path('views'), $path,
        ]);
    }

    /**
     * Recursive function that copies an entire directory to a destination.
     *
     * @param $source_directory
     * @param $destination_directory
     */
    protected function directoryCopy($source_directory, $destination_directory, $recursive = false, $ignore = [])
    {
        //Checks destination folder existance
        $this->ensureDirectoriesExist($destination_directory);
        //Open source directory
        $directory = opendir($source_directory);

        while (false !== ($file = readdir($directory))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($source_directory.'/'.$file) && $recursive) {
                    $this->directoryCopy($source_directory.'/'.$file, $destination_directory.'/'.$file, true);
                } elseif (! is_dir($source_directory.'/'.$file)) {
                    if (! in_array($file, $ignore)) {
                        if (file_exists($destination_directory.'/'.$file) && ! $this->option('force')) {
                            if (! $this->confirm("The [{$file}] file already exists. Do you want to replace it?")) {
                                continue;
                            }
                        }
                        copy($source_directory.'/'.$file, $destination_directory.'/'.$file);
                    }
                }
            }
        }

        closedir($directory);
    }
}

<?php

namespace JeroenNoten\LaravelAdminLte\Console;

use Illuminate\Console\Command;

class AdminLteInstallCommand extends Command
{
    protected $signature = 'adminlte:install '.
        '{--basic : Only publishes the assets and a basic page example}'.
        '{--force : Overwrite existing views by default}'.
        '{--type= : Installation type, Available type: none, enhanced & full.}'.
        '{--only= : Install only specific part, Available parts: assets, config, translations, auth_views, basic_views & basic_routes. This option can not used with the with option.}'.
        '{--with=* : Install basic assets with specific parts, Available parts: auth_views, basic_views & basic_routes}'.
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
        if ($this->option('only')) {
            switch ($this->option('only')) {
            case 'assets':
                $this->exportAssets();

                break;

            case 'config':
                $this->exportConfig();

                break;

            case 'translations':
                $this->exportTranslations();

                break;

            case 'auth_views':
                $this->exportAuthViews();

                break;

            case 'basic_views':
                $this->exportBasicViews();

                break;

            case 'basic_routes':
                $this->exportRoutes();

                break;

            default:
                $this->error('Invalid option!');
                break;
            }

            return;
        }

        if ($this->option('type') == 'basic' || $this->option('type') != 'none' || ! $this->option('type')) {
            $this->exportAssets();
            $this->exportConfig();
            $this->exportTranslations();
        }

        if ($this->option('with')) {
            if (in_array('auth_views', $this->option('with'))) {
                $this->exportAuthViews();
            }
            if (in_array('basic_views', $this->option('with'))) {
                $this->exportBasicViews();
            }
            if (in_array('basic_routes', $this->option('with'))) {
                $this->exportRoutes();
            }
        } elseif ($this->option('type') != 'none') {
            if ($this->option('type') == 'enhanced' || $this->option('type') == 'full') {
                $this->exportAuthViews();
            }
            if ($this->option('type') == 'full') {
                $this->exportBasicViews();
                $this->exportRoutes();
            }
        }

        $this->info('AdminLTE Installation complete.');
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
                if (! $this->confirm('Install AdminLTE basic routes?')) {
                    return;
                }
            }
            file_put_contents(
                base_path('routes/web.php'),
                file_get_contents(__DIR__.'/stubs/routes.stub'),
                FILE_APPEND
            );
            $this->comment('Basic routes installed successfully.');
        }
    }

    /**
     * Export the translation files.
     *
     * @return void
     */
    protected function exportTranslations()
    {
        if (! $this->option('basic')) {
            if ($this->option('interactive')) {
                if (! $this->confirm('Install AdminLTE authentication translations?')) {
                    return;
                }
            }

            $this->directoryCopy($this->packagePath('resources/lang'), base_path('resources/lang'), true);

            $this->comment('Translation files installed successfully.');
        }
    }

    /**
     * Copy all the content of the Assets Folder to Public Directory.
     */
    protected function exportAssets()
    {
        if ($this->option('interactive')) {
            if (! $this->confirm('Install the basic package assets?')) {
                return;
            }
        }

        $assetsPath = public_path().'/vendor/';
        $packagePath = base_path().'/vendor/almasaeed2010/adminlte/';

        // Copy AdminlTE dist
        $this->directoryCopy($packagePath.'dist/css/', $assetsPath.'adminlte/dist/css', false);
        $this->directoryCopy($packagePath.'dist/js/', $assetsPath.'adminlte/dist/js', false, ['demo.js']);

        if (! is_dir($assetsPath.'adminlte/dist/img/')) {
            mkdir($assetsPath.'adminlte/dist/img/');
        }

        copy($packagePath.'dist/img/AdminLTELogo.png', $assetsPath.'adminlte/dist/img/AdminLTELogo.png');

        // Copy Font Awesome Free
        $this->directoryCopy($packagePath.'plugins/fontawesome-free', $assetsPath.'fontawesome-free', true);

        // Copy Bootstrap
        $this->directoryCopy($packagePath.'plugins/bootstrap', $assetsPath.'bootstrap', true);

        // Copy Popper
        $this->directoryCopy($packagePath.'plugins/popper', $assetsPath.'popper', true);

        // Copy jQuery
        $this->directoryCopy($packagePath.'plugins/jquery', $assetsPath.'jquery', true, ['core.js', 'jquery.slim.js', 'jquery.slim.min.js', 'jquery.slim.min.map']);

        // Copy overlayScrollbars
        $this->directoryCopy($packagePath.'plugins/overlayScrollbars', $assetsPath.'overlayScrollbars', true);

        $this->comment('Basic Assets Installation complete.');
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

        $this->comment('Configuration Files installed successfully.');
    }

    /**
     * Get Package Path
     */
    protected function packagePath($path)
    {
        return __DIR__."/../../$path";
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
    protected function directoryCopy($source_directory, $destination_directory, $recursive = false, $ignore = [], $ignore_ending = null)
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
                    $checkup = true;

                    if ($ignore_ending) {
                        if (! is_array($ignore_ending)) {
                            $ignore_ending = str_replace('*', '', $ignore_ending);

                            $checkup = (substr($file, -strlen($ignore_ending)) !== $ignore_ending);
                        } else {
                            foreach ($ignore_ending as $key => $ignore_ending_sub) {
                                if ($checkup) {
                                    $ignore_ending_sub = str_replace('*', '', $ignore_ending_sub);

                                    $checkup = (substr($file, -strlen($ignore_ending_sub)) !== $ignore_ending_sub);
                                }
                            }
                        }
                    }

                    if ($checkup && (! in_array($file, $ignore))) {
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

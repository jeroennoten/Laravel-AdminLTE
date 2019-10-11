<?php

namespace JeroenNoten\LaravelAdminLte\Console;

use Illuminate\Console\Command;
use JeroenNoten\LaravelAdminLte\Http\Helpers\CommandHelper;

class AdminLteInstallCommand extends Command
{
    protected $signature = 'adminlte:install '.
        '{--force : Overwrite existing views by default}'.
        '{--type= : Installation type, Available type: none, enhanced & full.}'.
        '{--only= : Install only specific part, Available parts: assets, config, translations, auth_views, basic_views, basic_routes & main_views. This option can not used with the with option.}'.
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

            case 'main_views':
                $this->exportMainViews();

                break;

            case 'auth_views':
                $this->exportAuthViews();

                break;

            case 'basic_views':
                $this->exportBasicViews();

                break;

            case 'basic_routes':
                $this->exportBasicRoutes();

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
            if (in_array('main_views', $this->option('with'))) {
                $this->exportMainViews();
            }
            if (in_array('auth_views', $this->option('with'))) {
                $this->exportAuthViews();
            }
            if (in_array('basic_views', $this->option('with'))) {
                $this->exportBasicViews();
            }
            if (in_array('basic_routes', $this->option('with'))) {
                $this->exportBasicRoutes();
            }
        }
        if ($this->option('type') != 'none') {
            if ($this->option('type') == 'enhanced' || $this->option('type') == 'full') {
                $this->exportAuthViews();
            }
            if ($this->option('type') == 'full') {
                $this->exportBasicViews();
                $this->exportBasicRoutes();
            }
        }

        $this->info('AdminLTE Installation complete.');
    }

    /**
     * Export the main views.
     *
     * @return void
     */
    protected function exportMainViews()
    {
        if ($this->option('interactive')) {
            if (! $this->confirm('Install AdminLTE main views?')) {
                return;
            }
        }

        CommandHelper::directoryCopy($this->packagePath('resources/views'), base_path('resources/views'), $this->option('force'));

        $this->comment('Main views installed successfully.');
    }

    /**
     * Export the authentication views.
     *
     * @return void
     */
    protected function exportAuthViews()
    {
        if ($this->option('interactive')) {
            if (! $this->confirm('Install AdminLTE authentication views?')) {
                return;
            }
        }
        CommandHelper::ensureDirectoriesExist($this->getViewPath('auth/passwords'));
        foreach ($this->authViews as $file => $content) {
            file_put_contents($this->getViewPath($file), $content);
        }
        $this->comment('Authentication views installed successfully.');
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
    protected function exportBasicRoutes()
    {
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

    /**
     * Export the translation files.
     *
     * @return void
     */
    protected function exportTranslations()
    {
        if ($this->option('interactive')) {
            if (! $this->confirm('Install AdminLTE authentication translations?')) {
                return;
            }
        }

        CommandHelper::directoryCopy($this->packagePath('resources/lang'), base_path('resources/lang'), $this->option('force'), true);

        $this->comment('Translation files installed successfully.');
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
        CommandHelper::directoryCopy($packagePath.'dist/css/', $assetsPath.'adminlte/dist/css', $this->option('force'));
        CommandHelper::directoryCopy($packagePath.'dist/js/', $assetsPath.'adminlte/dist/js', $this->option('force'), ['demo.js']);

        if (! is_dir($assetsPath.'adminlte/dist/img/')) {
            mkdir($assetsPath.'adminlte/dist/img/');
        }

        copy($packagePath.'dist/img/AdminLTELogo.png', $assetsPath.'adminlte/dist/img/AdminLTELogo.png');

        // Copy Font Awesome Free
        CommandHelper::directoryCopy($packagePath.'plugins/fontawesome-free', $assetsPath.'fontawesome-free', $this->option('force'));

        // Copy Bootstrap
        CommandHelper::directoryCopy($packagePath.'plugins/bootstrap', $assetsPath.'bootstrap', $this->option('force'));

        // Copy Popper
        CommandHelper::directoryCopy($packagePath.'plugins/popper', $assetsPath.'popper', $this->option('force'));

        // Copy jQuery
        CommandHelper::directoryCopy($packagePath.'plugins/jquery', $assetsPath.'jquery', $this->option('force'), ['core.js', 'jquery.slim.js', 'jquery.slim.min.js', 'jquery.slim.min.map']);

        // Copy overlayScrollbars
        CommandHelper::directoryCopy($packagePath.'plugins/overlayScrollbars', $assetsPath.'overlayScrollbars', $this->option('force'));

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
     * Get Package Path.
     */
    protected function packagePath($path)
    {
        return __DIR__."/../../$path";
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
}

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
        '{--with=* : Install basic assets with specific parts, Available parts: auth_views, basic_views, basic_routes & main_views}'.
        '{--interactive : The installation will guide you through the process}';

    protected $description = 'Install all the required files for AdminLTE and the authentication views and routes';

    protected $authViews = [
        'auth/login.blade.php'             => '@extends(\'adminlte::login\')',
        'auth/register.blade.php'          => '@extends(\'adminlte::register\')',
        'auth/verify.blade.php'            => '@extends(\'adminlte::verify\')',
        'auth/passwords/confirm.blade.php' => '@extends(\'adminlte::passwords.confirm\')',
        'auth/passwords/email.blade.php'   => '@extends(\'adminlte::passwords.email\')',
        'auth/passwords/reset.blade.php'   => '@extends(\'adminlte::passwords.reset\')',
    ];

    protected $basicViews = [
        'home.stub' => 'home.blade.php',
    ];

    protected $package_path = __DIR__.'/../../';

    protected $assets_path = 'vendor/';

    protected $assets_package_path = 'vendor/almasaeed2010/adminlte/';

    protected $assets = [
        'adminlte' => [
            'name' => 'AdminLTE v3',
            'package_path' => [
                'dist/css/',
                'dist/js/',
            ],
            'assets_path' => [
                'adminlte/dist/css',
                'adminlte/dist/js',
            ],
            'images_path' => 'adminlte/dist/img/',
            'images' => [
                'dist/img/AdminLTELogo.png' => 'AdminLTELogo.png',
            ],
            'recursive' => false,
            'ignore' => [
                'demo.js',
            ],
        ],
        'fontawesomeFree' => [
            'name' => 'FontAwesome 5 Free',
            'package_path' => 'plugins/fontawesome-free',
            'assets_path' => 'fontawesome-free',
        ],
        'bootstrap' => [
            'name' => 'Bootstrap 4 (js files only)',
            'package_path' => 'plugins/bootstrap',
            'assets_path' => 'bootstrap',
        ],
        'popper' => [
            'name' => 'Popper.js (Bootstrap 4 requirement)',
            'package_path' => 'plugins/popper',
            'assets_path' => 'popper',
        ],
        'jquery' => [
            'name' => 'jQuery (Bootstrap 4 requirement)',
            'package_path' => 'plugins/jquery',
            'assets_path' => 'jquery',
            'ignore' => [
                'core.js', 'jquery.slim.js', 'jquery.slim.min.js', 'jquery.slim.min.map',
            ],
        ],
        'overlayScrollbars' => [
            'name' => 'Overlay Scrollbars',
            'package_path' => 'plugins/overlayScrollbars',
            'assets_path' => 'overlayScrollbars',
        ],
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

        CommandHelper::directoryCopy($this->packagePath('resources/views'), base_path('resources/views/vendor/adminlte'), $this->option('force'), true);

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

        $file = file_get_contents(base_path('routes/web.php'));
        $newRoutes = file_get_contents(__DIR__.'/stubs/routes.stub');

        if (! strpos($file, $newRoutes)) {
            file_put_contents(
                base_path('routes/web.php'),
                file_get_contents(__DIR__.'/stubs/routes.stub'),
                FILE_APPEND
            );
            $this->comment('Basic routes installed successfully.');

            return;
        }
        $this->comment('Basic routes already installed.');
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

        CommandHelper::directoryCopy($this->packagePath('resources/lang'), resource_path('lang/vendor/adminlte'), $this->option('force'), true);

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

        foreach ($this->assets as $asset_key => $asset) {
            $this->copyAssets($asset_key, $this->option('force'));
        }

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
            $this->packagePath('config/adminlte.php'),
            config_path('adminlte.php')
        );

        $this->comment('Configuration Files installed successfully.');
    }

    /**
     * Get Package Path.
     */
    protected function packagePath($path)
    {
        return $this->package_path.$path;
    }

    /**
     * Get full view path relative to the application's configured view path.
     *
     * @param  string  $path
     * @return string
     */
    public function getViewPath($path)
    {
        return implode(DIRECTORY_SEPARATOR, [
            config('view.paths')[0] ?? resource_path('views'), $path,
        ]);
    }

    /**
     * Copy Assets Data.
     *
     * @param  string  $asset_name
     * @param  bool $force
     * @return void
     */
    protected function copyAssets($asset_name, $force = false)
    {
        if (! isset($this->assets[$asset_name])) {
            return;
        }

        $asset = $this->assets[$asset_name];

        if (is_array($asset['package_path'])) {
            foreach ($asset['package_path'] as $key => $asset_package_path) {
                $asset_assets_path = $asset['assets_path'][$key];
                CommandHelper::directoryCopy(base_path($this->assets_package_path).$asset_package_path, public_path($this->assets_path).$asset_assets_path, $force, ($asset['recursive'] ?? true), ($asset['ignore'] ?? []), ($asset['ignore_ending'] ?? null));
            }
        } else {
            CommandHelper::directoryCopy(base_path($this->assets_package_path).$asset['package_path'], public_path($this->assets_path).$asset['assets_path'], $force, ($asset['recursive'] ?? true), ($asset['ignore'] ?? []), ($asset['ignore_ending'] ?? null));
        }

        if (isset($asset['images_path']) && isset($asset['images'])) {
            CommandHelper::ensureDirectoriesExist(public_path($this->assets_path).$asset['images_path']);
            foreach ($asset['images'] as $image_package_path => $image_assets_path) {
                if (file_exists(public_path($this->assets_path).$asset['images_path'].$image_assets_path) && ! $force) {
                    continue;
                }
                copy(base_path($this->assets_package_path).$image_package_path, public_path($this->assets_path).$asset['images_path'].$image_assets_path);
            }
        }
    }

    /**
     * Get Protected.
     *
     * @return array
     */
    public function getProtected($var)
    {
        return $this->{$var};
    }
}

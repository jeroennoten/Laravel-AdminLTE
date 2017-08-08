<?php

namespace JeroenNoten\LaravelAdminLte;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Container\Container;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use JeroenNoten\LaravelAdminLte\Console\AdminLteMakeCommand;
use JeroenNoten\LaravelAdminLte\Console\MakeAdminLteCommand;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use JeroenNoten\LaravelAdminLte\Http\ViewComposers\AdminLteComposer;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->singleton(AdminLte::class, function (Container $app) {
            return new AdminLte(
                $app['config']['adminlte.filters'],
                $app['events'],
                $app
            );
        });
    }

    public function boot(
        Factory $view,
        Dispatcher $events,
        Repository $config
    ) {
        $this->loadViews();

        $this->loadTranslations();

        $this->publishConfig();

        $this->publishAssets();

        $this->registerCommands();

        $this->registerViewComposers($view);

        static::registerMenu($events, $config);
    }

    private function loadViews()
    {
        $viewsPath = $this->packagePath('resources/views');

        $this->loadViewsFrom($viewsPath, 'adminlte');

        $this->publishes([
            $viewsPath => base_path('resources/views/vendor/adminlte'),
        ], 'views');
    }

    private function loadTranslations()
    {
        $translationsPath = $this->packagePath('resources/lang');

        $this->loadTranslationsFrom($translationsPath, 'adminlte');

        $this->publishes([
            $translationsPath => base_path('resources/lang/vendor/adminlte'),
        ], 'translations');
    }

    private function publishConfig()
    {
        $configPath = $this->packagePath('config/adminlte.php');

        $this->publishes([
            $configPath => config_path('adminlte.php'),
        ], 'config');

        $this->mergeConfigFrom($configPath, 'adminlte');
    }

    private function publishAssets()
    {
        $this->publishes([
            $this->packagePath('resources/assets') => public_path('vendor/adminlte'),
        ], 'assets');
    }

    private function packagePath($path)
    {
        return __DIR__."/../$path";
    }

    private function registerCommands()
    {
        // Laravel >=5.2 only
        if (class_exists('Illuminate\\Auth\\Console\\MakeAuthCommand')) {
            $this->commands(MakeAdminLteCommand::class);
        } elseif (class_exists('Illuminate\\Auth\\Console\\AuthMakeCommand')) {
            $this->commands(AdminLteMakeCommand::class);
        }
    }

    private function registerViewComposers(Factory $view)
    {
        $view->composer('adminlte::page', AdminLteComposer::class);
    }

    public static function registerMenu(Dispatcher $events, Repository $config)
    {
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) use ($config) {
            $menu = $config->get('adminlte.menu');
            call_user_func_array([$event->menu, 'add'], $menu);
        });
    }
}

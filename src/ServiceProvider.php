<?php

namespace JeroenNoten\LaravelAdminLte;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use JeroenNoten\LaravelAdminLte\Console\MakeAdminLteCommand;

class ServiceProvider extends BaseServiceProvider
{

    public function register()
    {
        //
    }

    public function boot()
    {
        $this->loadViews();

        $this->loadTranslations();

        $this->publishConfig();

        $this->publishAssets();

        $this->registerCommands();
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
        return __DIR__ . "/../$path";

    }

    private function registerCommands()
    {
        $this->commands(MakeAdminLteCommand::class);
    }

}
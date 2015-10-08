<?php

namespace JeroenNoten\LaravelAdminLte;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{

    public function register()
    {
        //
    }

    public function boot()
    {
        $this->loadViews();

        $this->publishConfig();

        $this->publishAssets();
    }

    private function loadViews()
    {
        $this->loadViewsFrom($this->packagePath('resources/views'), 'adminlte');
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

}
<?php

namespace JeroenNoten\LaravelAdminLte;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use JeroenNoten\LaravelAdminLte\Console\AdminLteInstallCommand;
use JeroenNoten\LaravelAdminLte\Console\AdminLtePluginCommand;
use JeroenNoten\LaravelAdminLte\Console\AdminLteStatusCommand;
use JeroenNoten\LaravelAdminLte\Console\AdminLteUpdateCommand;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use JeroenNoten\LaravelAdminLte\Http\ViewComposers\AdminLteComposer;

class AdminLteServiceProvider extends BaseServiceProvider
{
    /**
     * Register the package services.
     *
     * @return void
     */
    public function register()
    {
        // Bind a singleton instance of the AdminLte class into the service
        // container.

        $this->app->singleton(AdminLte::class, function (Container $app) {
            return new AdminLte(
                $app['config']['adminlte.filters'],
                $app['events'],
                $app
            );
        });
    }

    /**
     * Bootstrap the package's services.
     *
     * @return void
     */
    public function boot(Factory $view, Dispatcher $events, Repository $config)
    {
        $this->loadViews();
        $this->loadTranslations();
        $this->loadConfig();
        $this->registerCommands();
        $this->registerViewComposers($view);
        $this->registerMenu($events, $config);
        $this->loadComponents();
    }

    /**
     * Load the package views.
     *
     * @return void
     */
    private function loadViews()
    {
        $viewsPath = $this->packagePath('resources/views');
        $this->loadViewsFrom($viewsPath, 'adminlte');
    }

    /**
     * Load the package translations.
     *
     * @return void
     */
    private function loadTranslations()
    {
        $translationsPath = $this->packagePath('resources/lang');
        $this->loadTranslationsFrom($translationsPath, 'adminlte');
    }

    /**
     * Load the package config.
     *
     * @return void
     */
    private function loadConfig()
    {
        $configPath = $this->packagePath('config/adminlte.php');
        $this->mergeConfigFrom($configPath, 'adminlte');
    }

    /**
     * Get the absolute path to some package resource.
     *
     * @param string $path The relative path to the resource
     * @return string
     */
    private function packagePath($path)
    {
        return __DIR__."/../$path";
    }

    /**
     * Register the package's artisan commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->commands([
            AdminLteInstallCommand::class,
            AdminLteStatusCommand::class,
            AdminLteUpdateCommand::class,
            AdminLtePluginCommand::class,
        ]);
    }

    /**
     * Register the package's view composers.
     *
     * @return void
     */
    private function registerViewComposers(Factory $view)
    {
        $view->composer('adminlte::page', AdminLteComposer::class);
    }

    /**
     * Register the menu events handlers.
     *
     * @return void
     */
    private static function registerMenu(Dispatcher $events, Repository $config)
    {
        // Register a handler for the BuildingMenu event, this handler will add
        // the menu defined on the config file to the menu builder instance.

        $events->listen(
            BuildingMenu::class,
            function (BuildingMenu $event) use ($config) {
                $menu = $config->get('adminlte.menu', []);
                $menu = is_array($menu) ? $menu : [];
                $event->menu->add(...$menu);
            }
        );
    }

    /**
     * Load the Components.
     *
     * @return void
     */
    private function loadComponents()
    {
        /**
         * FORM COMPONENTS.
         */
        Blade::component('adminlte-input', Components\Input::class);
        Blade::component('adminlte-input-file', Components\InputFile::class);
        Blade::component('adminlte-input-color', Components\InputColor::class);
        Blade::component('adminlte-input-date', Components\InputDate::class);
        Blade::component('adminlte-textarea', Components\Textarea::class);
        Blade::component('adminlte-select', Components\Select::class);
        Blade::component('adminlte-select2', Components\Select2::class);
        Blade::component('adminlte-select-icon', Components\SelectIcon::class);
        Blade::component('adminlte-option', Components\Option::class);
        Blade::component('adminlte-input-switch', Components\InputSwitch::class);
        Blade::component('adminlte-input-tag', Components\InputTag::class);
        Blade::component('adminlte-submit', Components\Submit::class);
        Blade::component('adminlte-text-editor', Components\TextEditor::class);
        Blade::component('adminlte-date-range', Components\DateRange::class);
        Blade::component('adminlte-input-slider', Components\InputSlider::class);

        /**
         * WIDGETS.
         */
        Blade::component('adminlte-card', Components\Card::class);
        Blade::component('adminlte-info-box', Components\InfoBox::class);
        Blade::component('adminlte-small-box', Components\SmallBox::class);
        Blade::component('adminlte-profile-flat', Components\ProfileFlat::class);
        Blade::component('adminlte-profile-flat-item', Components\ProfileFlatItem::class);
        Blade::component('adminlte-profile-widget', Components\ProfileWidget::class);
        Blade::component('adminlte-profile-widget-item', Components\ProfileWidgetItem::class);
        Blade::component('adminlte-alert', Components\Alert::class);
        Blade::component('adminlte-callout', Components\Callout::class);
        Blade::component('adminlte-progress', Components\Progress::class);
        Blade::component('adminlte-modal', Components\Modal::class);
        Blade::component('adminlte-datatable', Components\Datatable::class);
    }
}

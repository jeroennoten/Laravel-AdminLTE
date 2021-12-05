<?php

namespace JeroenNoten\LaravelAdminLte;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use JeroenNoten\LaravelAdminLte\Console\AdminLteInstallCommand;
use JeroenNoten\LaravelAdminLte\Console\AdminLtePluginCommand;
use JeroenNoten\LaravelAdminLte\Console\AdminLteStatusCommand;
use JeroenNoten\LaravelAdminLte\Console\AdminLteUpdateCommand;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use JeroenNoten\LaravelAdminLte\Http\ViewComposers\AdminLteComposer;
use JeroenNoten\LaravelAdminLte\View\Components\Form;
use JeroenNoten\LaravelAdminLte\View\Components\Layout;
use JeroenNoten\LaravelAdminLte\View\Components\Tool;
use JeroenNoten\LaravelAdminLte\View\Components\Widget;

class AdminLteServiceProvider extends BaseServiceProvider
{
    /**
     * The prefix to use for register/load the package resources.
     *
     * @var string
     */
    protected $pkgPrefix = 'adminlte';

    /**
     * Array with the available layout components.
     *
     * @var array
     */
    protected $layoutComponents = [
        'navbar-darkmode-widget' => Layout\NavbarDarkmodeWidget::class,
        'navbar-notification' => Layout\NavbarNotification::class,
    ];

    /**
     * Array with the available form components.
     *
     * @var array
     */
    protected $formComponents = [
        'button' => Form\Button::class,
        'date-range' => Form\DateRange::class,
        'input' => Form\Input::class,
        'input-color' => Form\InputColor::class,
        'input-date' => Form\InputDate::class,
        'input-file' => Form\InputFile::class,
        'input-slider' => Form\InputSlider::class,
        'input-switch' => Form\InputSwitch::class,
        'options' => Form\Options::class,
        'select' => Form\Select::class,
        'select2' => Form\Select2::class,
        'select-bs' => Form\SelectBs::class,
        'textarea' => Form\Textarea::class,
        'text-editor' => Form\TextEditor::class,
    ];

    /**
     * Array with the available tool components.
     *
     * @var array
     */
    protected $toolComponents = [
        'datatable' => Tool\Datatable::class,
        'modal' => Tool\Modal::class,
    ];

    /**
     * Array with the available widget components.
     *
     * @var array
     */
    protected $widgetComponents = [
        'alert' => Widget\Alert::class,
        'callout' => Widget\Callout::class,
        'card' => Widget\Card::class,
        'info-box' => Widget\InfoBox::class,
        'profile-col-item' => Widget\ProfileColItem::class,
        'profile-row-item' => Widget\ProfileRowItem::class,
        'profile-widget' => Widget\ProfileWidget::class,
        'progress' => Widget\Progress::class,
        'small-box' => Widget\SmallBox::class,
    ];

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
        $this->loadRoutes();
    }

    /**
     * Load the package views.
     *
     * @return void
     */
    private function loadViews()
    {
        $viewsPath = $this->packagePath('resources/views');
        $this->loadViewsFrom($viewsPath, $this->pkgPrefix);
    }

    /**
     * Load the package translations.
     *
     * @return void
     */
    private function loadTranslations()
    {
        $translationsPath = $this->packagePath('resources/lang');
        $this->loadTranslationsFrom($translationsPath, $this->pkgPrefix);
    }

    /**
     * Load the package config.
     *
     * @return void
     */
    private function loadConfig()
    {
        $configPath = $this->packagePath('config/adminlte.php');
        $this->mergeConfigFrom($configPath, $this->pkgPrefix);
    }

    /**
     * Get the absolute path to some package resource.
     *
     * @param  string  $path  The relative path to the resource
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
     * Load the blade view components.
     *
     * @return void
     */
    private function loadComponents()
    {
        // Support of x-components is only available for Laravel >= 7.x
        // versions. So, we check if we can load components.

        $canLoadComponents = method_exists(
            'Illuminate\Support\ServiceProvider',
            'loadViewComponentsAs'
        );

        if (! $canLoadComponents) {
            return;
        }

        // Load all the blade-x components.

        $components = array_merge(
            $this->layoutComponents,
            $this->formComponents,
            $this->toolComponents,
            $this->widgetComponents
        );

        $this->loadViewComponentsAs($this->pkgPrefix, $components);
    }

    /**
     * Load the package web routes.
     *
     * @return void
     */
    private function loadRoutes()
    {
        $routesCfg = [
            'as' => "{$this->pkgPrefix}.",
            'prefix' => $this->pkgPrefix,
            'middleware' => ['web'],
        ];

        Route::group($routesCfg, function () {
            $routesPath = $this->packagePath('routes/web.php');
            $this->loadRoutesFrom($routesPath);
        });
    }
}

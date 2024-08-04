<?php

namespace JeroenNoten\LaravelAdminLte;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use JeroenNoten\LaravelAdminLte\Console\AdminLteInstallCommand;
use JeroenNoten\LaravelAdminLte\Console\AdminLtePluginCommand;
use JeroenNoten\LaravelAdminLte\Console\AdminLteRemoveCommand;
use JeroenNoten\LaravelAdminLte\Console\AdminLteStatusCommand;
use JeroenNoten\LaravelAdminLte\Console\AdminLteUpdateCommand;
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
     * Array with the available layout blade components.
     *
     * @var array
     */
    protected $layoutComponents = [
        'navbar-darkmode-widget' => Layout\NavbarDarkmodeWidget::class,
        'navbar-notification' => Layout\NavbarNotification::class,
    ];

    /**
     * Array with the available form blade components.
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
        'input-file-krajee' => Form\InputFileKrajee::class,
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
     * Array with the available tool blade components.
     *
     * @var array
     */
    protected $toolComponents = [
        'datatable' => Tool\Datatable::class,
        'modal' => Tool\Modal::class,
    ];

    /**
     * Array with the available widget blade components.
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

        $this->app->singleton(AdminLte::class, function () {
            return new AdminLte(config('adminlte.filters', []));
        });
    }

    /**
     * Bootstrap the package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViews();
        $this->loadTranslations();
        $this->loadConfig();
        $this->registerCommands();
        $this->registerViewComposers();
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
        $transPath = $this->packagePath('resources/lang');
        $this->loadTranslationsFrom($transPath, $this->pkgPrefix);
    }

    /**
     * Load the package configuration.
     *
     * @return void
     */
    private function loadConfig()
    {
        $configPath = $this->packagePath('config/adminlte.php');
        $this->mergeConfigFrom($configPath, $this->pkgPrefix);
    }

    /**
     * Register the artisan commands of the package.
     *
     * @return void
     */
    private function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AdminLteInstallCommand::class,
                AdminLtePluginCommand::class,
                AdminLteRemoveCommand::class,
                AdminLteStatusCommand::class,
                AdminLteUpdateCommand::class,
            ]);
        }
    }

    /**
     * Register the view composers of the package. View composers are callbacks
     * or class methods that are called when a view is rendered. If you have
     * data that you want to be bound to a view each time that view is rendered,
     * a view composer can help you organize that logic into a single location.
     *
     * @return void
     */
    private function registerViewComposers()
    {
        // Bind the AdminLte singleton instance into each adminlte page view.

        View::composer('adminlte::page', function (\Illuminate\View\View $v) {
            $v->with('adminlte', $this->app->make(AdminLte::class));
        });
    }

    /**
     * Load the blade view components of the package.
     *
     * @return void
     */
    private function loadComponents()
    {
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
     * Load the routes of the package.
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
}

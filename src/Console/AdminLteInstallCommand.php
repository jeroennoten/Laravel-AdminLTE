<?php

namespace JeroenNoten\LaravelAdminLte\Console;

use Illuminate\Console\Command;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\AdminlteAssetsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\AuthRoutesResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\AuthViewsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\BladeComponentsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\ConfigResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\LayoutViewsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\TranslationsResource;

class AdminLteInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adminlte:install
        {--type=basic : The installation type: basic, basic_with_auth, basic_with_views or full}
        {--only=* : To install only specific resources: assets, config, translations, auth_views, auth_routes, main_views or components. Can\'t be mixed with option --with}
        {--with=* : To install with additional resources: auth_views, auth_routes, main_views or components}
        {--force : To force the overwrite of existing files during the installation process}
        {--interactive : To allow the installation process guide you through it}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes the required files, and other resources, for use the AdminLTE template';

    /**
     * Array with all the available package resources.
     *
     * @var array
     */
    protected $pkgResources;

    /**
     * Array with all the installed packages on the current execution.
     *
     * @var array
     */
    protected $installedResources;

    /**
     * Array with the resources available for the --only options.
     *
     * @var array
     */
    protected $optOnlyResources;

    /**
     * Array with the resources available for the --with options.
     *
     * @var array
     */
    protected $optWithResources;

    /**
     * Array with the resources available for the --type options.
     *
     * @var array
     */
    protected $optTypeResources;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // Fill the array with the available package resources.

        $this->pkgResources = [
            'assets' => new AdminlteAssetsResource(),
            'config' => new ConfigResource(),
            'translations' => new TranslationsResource(),
            'main_views' => new LayoutViewsResource(),
            'auth_views' => new AuthViewsResource(),
            'auth_routes' => new AuthRoutesResource(),
            'components' => new BladeComponentsResource(),
        ];

        // Add the resources related to each available --type option.

        $basic = ['assets', 'config', 'translations'];
        $basicWithAuth = array_merge($basic, ['auth_views', 'auth_routes']);
        $basicWithViews = array_merge($basic, ['main_views']);
        $full = array_merge($basicWithAuth, ['main_views', 'components']);

        $this->optTypeResources = [
            'basic' => $basic,
            'basic_with_auth' => $basicWithAuth,
            'basic_with_views' => $basicWithViews,
            'full' => $full,
        ];

        // Add the resources related to each available --only option.

        $this->optOnlyResources = [
            'assets' => ['assets'],
            'config' => ['config'],
            'translations' => ['translations'],
            'main_views' => ['main_views'],
            'auth_views' => ['auth_views'],
            'auth_routes' => ['auth_routes'],
            'components' => ['components'],
        ];

        // Add the resources related to each available --with option.

        $this->optWithResources = [
            'main_views' => ['main_views'],
            'auth_views' => ['auth_views'],
            'auth_routes' => ['auth_routes'],
            'components' => ['components'],
        ];
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Reset the variable that keep track of the installed packages.

        $this->installedResources = [];

        // Check if option --only is used. In this case, install only the
        // specified resources and finish.

        if ($optValues = $this->option('only')) {
            $this->handleOptions($optValues, $this->optOnlyResources, 'only');

            return;
        }

        // Handle the --type option. Default value for this option is "basic"
        // installation type.

        $optValue = $this->option('type');
        $this->handleOption($optValue, $this->optTypeResources, 'type');

        // Check if option --with is used. In this case, we also need to install
        // the specified additional resources.

        if ($optValues = $this->option('with')) {
            $this->handleOptions($optValues, $this->optWithResources, 'with');
        }

        $this->line('The installation is complete.');
    }

    /**
     * Handles multiple option values.
     *
     * @param  array  $values  An array with the option values
     * @param  array  $resources  An array with the resources of each option
     * @param  string  $opt  Descriptive name of the handled option
     * @return void
     */
    protected function handleOptions($values, $resources, $opt)
    {
        foreach ($values as $value) {
            $this->handleOption($value, $resources, $opt);
        }
    }

    /**
     * Handles an option value.
     *
     * @param  string  $value  A string with the option value
     * @param  array  $resources  An array with the resources of each option
     * @param  string  $opt  Descriptive name of the handled option
     * @return void
     */
    protected function handleOption($value, $resources, $opt)
    {
        if (! isset($resources[$value])) {
            $this->comment("The option --{$opt}={$value} is invalid!");

            return;
        }

        // Install all the resources related to the option value.

        $this->exportPackageResources(...$resources[$value]);
    }

    /**
     * Installs multiple packages resources.
     *
     * @param  string  $resources  The resources to install
     * @return void
     */
    protected function exportPackageResources(...$resources)
    {
        foreach ($resources as $resource) {
            // Check if the resource was already installed on the current
            // command execution. This can happen, for example, when using:
            // php artisan --type=full --with=auth_views

            if (isset($this->installedResources[$resource])) {
                continue;
            }

            $this->exportPackageResource($resource);
            $this->installedResources[$resource] = true;
        }
    }

    /**
     * Installs a package resource.
     *
     * @param  string  $resource  The keyword of the resource to install
     * @return void
     */
    protected function exportPackageResource($resource)
    {
        $resource = $this->pkgResources[$resource];
        $installMsg = $resource->getInstallMessage('install');
        $overwriteMsg = $resource->getInstallMessage('overwrite');
        $successMsg = $resource->getInstallMessage('success');

        // Check if the --interactive option is enabled.

        if ($this->option('interactive') && ! $this->confirm($installMsg)) {
            return;
        }

        // Check for overwrite warning.

        $shouldWarnOverwrite = ! $this->option('force') && $resource->exists();

        if ($shouldWarnOverwrite && ! $this->confirm($overwriteMsg)) {
            return;
        }

        // Install the resource.

        $resource->install();
        $this->info($successMsg);
    }
}

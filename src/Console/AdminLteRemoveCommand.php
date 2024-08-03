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

class AdminLteRemoveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adminlte:remove
        {resource* : The resource to uninstall: assets, config, translations, auth_views, auth_routes, main_views or components}
        {--force : To force the uninstall procedure without warnings alerts}
        {--interactive : To allow the uninstall process guide you through it}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uninstalls one or more specified resources';

    /**
     * Array with all the available package resources.
     *
     * @var array
     */
    protected $pkgResources;

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
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Read and verify the resources to be uninstalled.

        foreach ($this->argument('resource') as $res) {
            // Check if resource is valid.

            if (! isset($this->pkgResources[$res])) {
                $this->comment("The provided resource: {$res} is invalid!");

                continue;
            }

            // Uninstall the resource.

            $this->uninstallPackageResource($res);
        }
    }

    /**
     * Uninstalls a package resource.
     *
     * @param  string  $resource  The keyword of the resource to uninstall
     * @return void
     */
    protected function uninstallPackageResource($resource)
    {
        $removeMsg = 'Do you really want to uninstall the resource: :res?';
        $removeMsg = str_replace(':res', $resource, $removeMsg);
        $resource = $this->pkgResources[$resource];

        // Check if the --interactive option is enabled.

        if ($this->option('interactive') && ! $this->confirm($removeMsg)) {
            return;
        }

        // Check whether to warn for uninstalling a required resource.

        $requiredWarnMsg = 'This resource is required by the package, ';
        $requiredWarnMsg .= 'do you really want to uninstall it?';

        $shouldWarn = ! $this->option('force') && $resource->required;

        if ($shouldWarn && ! $this->confirm($requiredWarnMsg)) {
            return;
        }

        // Uninstall the resource.

        $resource->uninstall();
        $this->info('The resource was successfully removed');
    }
}

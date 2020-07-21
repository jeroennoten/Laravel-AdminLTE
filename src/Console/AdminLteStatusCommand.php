<?php

namespace JeroenNoten\LaravelAdminLte\Console;

use Illuminate\Console\Command;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\AssetsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\ConfigResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\TranslationsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\MainViewsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\AuthViewsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\BasicViewsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\BasicRoutesResource;

class AdminLteStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adminlte:status ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks the installation status of the AdminLTE resources';

    /**
     * Array with all the available package resources.
     *
     * @var array
     */
    protected $pkgResources;

    /**
     * Array with the output table headers (columns).
     *
     * @var array
     */
    protected $tblHeaders = [
        'Package Resource',
        'Description',
        'Status',
        'Required'
    ];

    /**
     * Array with the available resource status.
     *
     * @var array
     */
    protected $status = [
        'installed' => [
            'description' => 'Installed',
            'legend' => 'The package resource is correctly installed',
        ],
        'mismatch' => [
            'description' => 'Mismatch',
            'legend' => 'The installed resource mismatch the package resource (Update available or resource modified)',
        ],
        'uninstalled' => [
            'description' => 'Not Installed',
            'legend' => 'The package resource is not installed',
        ],
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // Fill the array with the package resources.

        $this->pkgResources = [
            'assets'       => new AssetsResource(),
            'config'       => new ConfigResource(),
            'translations' => new TranslationsResource(),
            'main_views'   => new MainViewsResource(),
            'auth_views'   => new AuthViewsResource(),
            'basic_views'  => new BasicViewsResource(),
            'basic_routes' => new BasicRoutesResource(),
        ];
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Define the array that will hold the output table content.

        $tblContent = [];

        // Create a progress bar.

        $steps = count($this->pkgResources);
        $bar = $this->output->createProgressBar($steps);

        // Initialize the status check procedure.

        $this->line('Checking the resources installation ...');
        $bar->start();

        foreach ($this->pkgResources as $name => $resource) {

            // Fill the status row of the current resource.

            $tblContent[] = [
                $name,
                $resource->get('description'),
                $this->getResourceStatus($resource),
                var_export($resource->get('required'), true),
            ];

            // Advance the progress bar.

            $bar->advance();
        }

        // Finish the progress bar.

        $bar->finish();
        $this->line('All resources checked succesfully!');

        // Display the resource status table.

        $this->table($this->tblHeaders, $tblContent);

        // Display the legends.

        $this->displayStatusLegends();
    }

    /**
     * Get the installation status of a package resource.
     *
     * @param PackageResource $resource The package resource to check
     * @return string The resource status
     */
    protected function getResourceStatus($resource)
    {
        $status = $this->status['uninstalled'];

        if ($resource->installed()) {
            $status = $this->status['installed'];
        } elseif ($resource->exists()) {
            $status = $this->status['mismatch'];
        }

        return $status['description'];
    }

    /**
     * Display the legends of the resource status values.
     *
     * @return void
     */
    protected function displayStatusLegends()
    {
        foreach ($this->status as $status) {
            $legend = implode(': ', array_values($status));
            $this->comment($legend);
        }
    }
}

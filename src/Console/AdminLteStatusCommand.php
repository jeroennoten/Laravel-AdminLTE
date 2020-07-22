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
     * Array with the available resource status.
     *
     * @var array
     */
    protected $status = [
        'installed' => [
            'description' => 'Installed',
            'legend' => 'The package resource is correctly installed',
            'color' => 'green',
        ],
        'mismatch' => [
            'description' => 'Mismatch',
            'legend' => 'The installed resource mismatch the package resource (update available or resource modified)',
            'color' => 'yellow',
        ],
        'uninstalled' => [
            'description' => 'Not Installed',
            'legend' => 'The package resource is not installed',
            'color' => 'red',
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
        // Display the resources status table.

        $this->showResourcesStatus();

        // Display the legends table.

        $this->line('');
        $this->showStatusLegends();
    }

    /**
     * Display the resources status table.
     *
     * @return void
     */
    protected function showResourcesStatus()
    {
        // Define the table headers.

        $tblHeader = [
            $this->styleOutput('Package Resource', 'blue'),
            $this->styleOutput('Description', 'blue'),
            $this->styleOutput('Status', 'blue'),
            $this->styleOutput('Required', 'blue'),
        ];

        // Display the table.

        $this->table($tblHeader, $this->getStatusTableRows());
    }

    /**
     * Get the rows for the resources status table.
     *
     * @return array
     */
    protected function getStatusTableRows()
    {
        // Define the array that will hold the table rows.

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
        $this->line('');
        $this->line('All resources checked succesfully!');
        $this->line('');

        return $tblContent;
    }

    /**
     * Get the status of a package resource.
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

        return $this->styleOutput($status['description'], $status['color']);
    }

    /**
     * Display the legends of the status values.
     *
     * @return void
     */
    protected function showStatusLegends()
    {
        $this->line('Legends:');

        // Create a table for the legends.

        $tblHeader = [
            $this->styleOutput('Status', 'blue'),
            $this->styleOutput('Description', 'blue'),
        ];

        $tblContent = [];

        foreach ($this->status as $status) {
            $tblContent[] = [
                $this->styleOutput($status['description'], $status['color']),
                $status['legend'],
            ];
        }

        // Display the legends table.

        $this->table($tblHeader, $tblContent);
    }

    /**
     * Give an output style to text.
     *
     * @param string $text The text to style
     * @param string $color The output color for the text
     * @return string The styled text
     */
    protected function styleOutput($text, $color)
    {
        return "<fg={$color}>{$text}</>";
    }
}

<?php

namespace JeroenNoten\LaravelAdminLte\Console;

use Illuminate\Console\Command;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\AssetsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\AuthViewsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\BasicRoutesResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\BasicViewsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\ConfigResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\MainViewsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\TranslationsResource;

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
     * Array with the possible resources statuses.
     *
     * @var array
     */
    protected $status = [
        'installed' => [
            'label' => 'Installed',
            'legend' => 'The resource is installed and matches with the default package resource',
            'color' => 'green',
        ],
        'mismatch' => [
            'label' => 'Mismatch',
            'legend' => 'The installed resource mismatch the package resource (update available or resource modified)',
            'color' => 'yellow',
        ],
        'uninstalled' => [
            'label' => 'Not Installed',
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
        // Display the resources installation status.

        $this->showResourcesStatus();

        // Display the legends table.

        $this->line('');
        $this->showStatusLegends();
    }

    /**
     * Display the resources status.
     *
     * @return void
     */
    protected function showResourcesStatus()
    {
        // Define the table headers.

        $tblHeader = [
            $this->styleOutput('Package Resource', 'cyan'),
            $this->styleOutput('Description', 'cyan'),
            $this->styleOutput('Status', 'cyan'),
            $this->styleOutput('Required', 'cyan'),
        ];

        // Get the table rows.

        $tblContent = $this->getResourcesStatusRows();

        // Display the table.

        $this->line('');
        $this->table($tblHeader, $tblContent);
    }

    /**
     * Get the rows for the resources status table.
     *
     * @return array
     */
    protected function getResourcesStatusRows()
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
                $resource->description,
                $this->getResourceStatus($resource),
                var_export($resource->required, true),
            ];

            // Advance the progress bar one step.

            $bar->advance();
        }

        // Finish the progress bar.

        $bar->finish();
        $this->line('');
        $this->line('All resources checked succesfully!');

        // Return the rows.

        return $tblContent;
    }

    /**
     * Get the installation status of a package resource.
     *
     * @param  PackageResource  $resource  The package resource to check
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

        return $this->styleOutput($status['label'], $status['color']);
    }

    /**
     * Display the legends of the possible status values.
     *
     * @return void
     */
    protected function showStatusLegends()
    {
        $this->line('Status legends:');

        // Create the table headers for the legends.

        $tblHeader = [
            $this->styleOutput('Status', 'cyan'),
            $this->styleOutput('Description', 'cyan'),
        ];

        // Create the table rows for the legends.

        $tblContent = [];

        foreach ($this->status as $status) {
            $tblContent[] = [
                $this->styleOutput($status['label'], $status['color']),
                $status['legend'],
            ];
        }

        // Display the legends table.

        $this->table($tblHeader, $tblContent);
    }

    /**
     * Give output style to some text.
     *
     * @param  string  $text  The text to be styled
     * @param  string  $color  The output color for the text
     * @return string The styled text
     */
    protected function styleOutput($text, $color)
    {
        return "<fg={$color}>{$text}</>";
    }
}

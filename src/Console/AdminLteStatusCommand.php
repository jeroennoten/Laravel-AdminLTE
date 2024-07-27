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
    protected $description = 'Checks the installation status of the package resources';

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
            'legend' => 'The resource is published and matches with the package original resource',
            'color' => 'green',
        ],
        'mismatch' => [
            'label' => 'Mismatch',
            'legend' => 'The resource is published but mismatches with the package original resource (update available or resource modified)',
            'color' => 'yellow',
        ],
        'uninstalled' => [
            'label' => 'Not Installed',
            'legend' => 'The resource is not published',
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
        // Get the installation status of each resource.

        $this->line('Verifying the installation of the resources...');
        $resStatus = $this->getResourcesStatus();
        $this->line('');
        $this->line('All resources verified successfully!');

        // Display the resources installation status.

        $this->line('');
        $this->line('Resources Status:');
        $this->showResourcesStatus($resStatus);

        // Display the status legends table.

        $this->line('');
        $this->line('Status Legends:');
        $this->showStatusLegends();
    }

    /**
     * Gets the resources installation status array.
     *
     * @return array
     */
    protected function getResourcesStatus()
    {
        // Define the array that will hold the resources status.

        $resStatus = [];

        // Create and initialize a progress bar.

        $bar = $this->output->createProgressBar(count($this->pkgResources));
        $bar->start();

        // Get the installation status of each resource.

        foreach ($this->pkgResources as $name => $resource) {
            $resStatus[$name] = $this->getResourceStatus($resource);
            $bar->advance();
        }

        $bar->finish();

        // Return the resources status.

        return $resStatus;
    }

    /**
     * Displays the status of the resources.
     *
     * @param  array  $resStatus  Array with the status of each resource
     * @return void
     */
    protected function showResourcesStatus($resStatus)
    {
        // Define the table headers.

        $tblHeader = [
            $this->styleOutput('Package Resource', 'cyan'),
            $this->styleOutput('Description', 'cyan'),
            $this->styleOutput('Publishing Target', 'cyan'),
            $this->styleOutput('Required', 'cyan'),
            $this->styleOutput('Status', 'cyan'),
        ];

        // Create the table rows.

        $tblContent = [];

        foreach ($this->pkgResources as $name => $resource) {
            $requiredLabel = $resource->required
                ? $this->styleOutput('yes', 'green')
                : 'no';

            $publishingTarget = is_array($resource->target)
                ? implode(PHP_EOL, $resource->target)
                : $resource->target;

            $tblContent[] = [
                $name,
                $resource->description,
                str_replace(base_path().'/', '', $publishingTarget),
                $requiredLabel,
                $resStatus[$name] ?? 'Unknown',
            ];
        }

        // Display the table.

        $this->table($tblHeader, $tblContent);
    }

    /**
     * Gets the installation status of the specified package resource.
     *
     * @param  PackageResource  $resource  The package resource to check
     * @return string
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
     * Displays the legends for the possible status values.
     *
     * @return void
     */
    protected function showStatusLegends()
    {
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
     * Gives output style to the provided text.
     *
     * @param  string  $text  The text to be styled
     * @param  string  $color  The output color for the text
     * @return string
     */
    protected function styleOutput($text, $color)
    {
        return "<fg={$color}>{$text}</>";
    }
}

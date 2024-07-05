<?php

namespace JeroenNoten\LaravelAdminLte\Console;

use Illuminate\Console\Command;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\PluginsResource;

class AdminLtePluginCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adminlte:plugins
        {operation=list : The type of operation: list, install or remove}
        {--plugin=* : To apply the operation only over the specified plugins, the value should be a plugin key}
        {--force : To force the overwrite of existing files during an installation process}
        {--interactive : To allow the operation process guide you through it}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manages the installation and removal of additional AdminLTE plugins';

    /**
     * Array with the operations handlers.
     *
     * @var array
     */
    protected $opHandlers;

    /**
     * The plugins package resource instance.
     *
     * @var PluginsResource
     */
    protected $plugins;

    /**
     * Array with the possible statuses of the plugins.
     *
     * @var array
     */
    protected $status = [
        'installed' => [
            'label' => 'Installed',
            'legend' => 'The plugin is published and matches with the package original plugin',
            'color' => 'green',
        ],
        'mismatch' => [
            'label' => 'Mismatch',
            'legend' => 'The plugin is published but mismatches with the package original plugin (update available or plugin modified)',
            'color' => 'yellow',
        ],
        'uninstalled' => [
            'label' => 'Not Installed',
            'legend' => 'The plugin is not published',
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

        // Fill the available operations handlers.

        $this->opHandlers = [
            'list' => [$this, 'showPlugins'],
            'install' => [$this, 'installPlugins'],
            'remove' => [$this, 'removePlugins'],
        ];

        // Create the plugins resource instance.

        $this->plugins = new PluginsResource();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Get the type of operation to perform.

        $op = $this->argument('operation');

        // Check if the operation is valid.

        if (! isset($this->opHandlers[$op])) {
            $this->error("The specified operation: {$op} is not valid!");

            return;
        }

        // Call the handler of the operation.

        $handler = $this->opHandlers[$op];
        $handler();
    }

    /**
     * Displays a list with the installation status of the plugins.
     *
     * @return void
     */
    protected function showPlugins()
    {
        // Get the list of affected plugins on the current operation.

        $pluginsKeys = $this->getAffectedPlugins();

        // Get the installation status of the affected plugins.

        $this->line('Verifying the installation of the plugins...');
        $pluginsStatus = $this->getPluginsStatus($pluginsKeys);
        $this->line('');
        $this->line('All plugins verified successfully!');

        // Display the plugins installation status.

        $this->line('');
        $this->line('Plugins Status:');
        $this->showPluginsStatus($pluginsStatus);

        // Display the legends table.

        $this->line('');
        $this->line('Status legends:');
        $this->showStatusLegends();
    }

    /**
     * Gets the list of plugins keys that should be affected by an operation.
     *
     * @return array
     */
    protected function getAffectedPlugins()
    {
        // First, check if the user has specified the plugins keys.

        if (! empty($this->option('plugin'))) {
            return $this->option('plugin');
        }

        // Otherwise, return a list with all the available plugins keys.

        return array_keys($this->plugins->getSourceData());
    }

    /**
     * Gets the installation status of the specicied plugins keys.
     *
     * @param  array  $pluginsKeys  Array with the plugins keys to evaluate
     * @return array
     */
    protected function getPluginsStatus($pluginsKeys)
    {
        // Define the array that will hold the resources status.

        $status = [];

        // Create and initialize a progress bar.

        $bar = $this->output->createProgressBar(count($pluginsKeys));
        $bar->start();

        // Get the installation status of each plugin.

        foreach ($pluginsKeys as $key) {
            $pluginData = $this->plugins->getSourceData($key);

            if (empty($pluginData)) {
                $this->line('');
                $this->error("The plugin key: {$key} is not valid!");
                $bar->advance();
                continue;
            }

            $status[$key] = $this->getPluginStatus($key);
            $bar->advance();
        }

        $bar->finish();

        // Return the plugins status.

        return $status;
    }

    /**
     * Displays the status of the specified plugins.
     *
     * @param  array  $pluginsStatus  Array with the status of plugins
     * @return void
     */
    protected function showPluginsStatus($pluginsStatus)
    {
        // Define the table headers.

        $tblHeader = [
            $this->styleOutput('Plugin Name', 'cyan'),
            $this->styleOutput('Plugin Key', 'cyan'),
            $this->styleOutput('Status', 'cyan'),
        ];

        // Create the table rows.

        $tblContent = [];

        foreach ($pluginsStatus as $key => $status) {
            $pluginData = $this->plugins->getSourceData($key);
            $tblContent[] = [$pluginData['name'], $key, $status];
        }

        // Display the plugins installation status.

        $this->table($tblHeader, $tblContent);
    }

    /**
     * Gets the installation status of a plugin.
     *
     * @param  string  $pluginKey  The plugin key
     * @return string
     */
    protected function getPluginStatus($pluginKey)
    {
        $status = $this->status['uninstalled'];

        if ($this->plugins->installed($pluginKey)) {
            $status = $this->status['installed'];
        } elseif ($this->plugins->exists($pluginKey)) {
            $status = $this->status['mismatch'];
        }

        return $this->styleOutput($status['label'], $status['color']);
    }

    /**
     * Displays the legends of the possible status values.
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
     * Gives output style to the specified text.
     *
     * @param  string  $text  The text to be styled
     * @param  string  $color  The output color for the text
     * @return string
     */
    protected function styleOutput($text, $color)
    {
        return "<fg={$color}>{$text}</>";
    }

    /**
     * Installs the specified list of plugins (all if none specified).
     *
     * @return void
     */
    protected function installPlugins()
    {
        $summary = [];

        // Get the list of plugins to be installed.

        $pluginsKeys = $this->getAffectedPlugins();

        // Create a progress bar.

        $bar = $this->output->createProgressBar(count($pluginsKeys));
        $bar->start();

        // Install the plugins.

        foreach ($pluginsKeys as $pluginKey) {
            // Install the plugin, if it's a valid plugin key.

            if (empty($this->plugins->getSourceData($pluginKey))) {
                $this->line('');
                $this->error("The plugin key: {$pluginKey} is not valid!");
                $status = $this->styleOutput('Invalid', 'red');
            } elseif ($this->installPlugin($pluginKey)) {
                $status = $this->styleOutput('Installed', 'green');
            } else {
                $status = $this->styleOutput('Not Installed', 'red');
            }

            $summary[] = [$pluginKey, $status];
            $bar->advance();
        }

        // Finish the progress bar.

        $bar->finish();
        $this->line('');
        $this->line('The installation of plugins is complete. Summary:');
        $this->line('');

        // Show summary of installed plugins.

        $this->showSummaryTable($summary);
    }

    /**
     * Installs the specified plugin. Returns whether the plugin was installed.
     *
     * @param  string  $pluginKey  The plugin string key
     * @return bool
     */
    protected function installPlugin($pluginKey)
    {
        // Customize the output messages.

        $confirmMsg = $this->plugins->getInstallMessage('install') ?? '';
        $overwriteMsg = $this->plugins->getInstallMessage('overwrite') ?? '';

        $confirmMsg = strtr($confirmMsg, [':plugin' => $pluginKey]);
        $overwriteMsg = strtr($overwriteMsg, [':plugin' => $pluginKey]);

        // Check if the --interactive option is enabled.

        if ($this->option('interactive') && ! $this->confirm($confirmMsg)) {
            return false;
        }

        // Check for overwrite warning

        $shouldWarnOverwrite = ! $this->option('force')
            && $this->plugins->exists($pluginKey);

        if ($shouldWarnOverwrite && ! $this->confirm($overwriteMsg)) {
            return false;
        }

        // Install the plugin.

        $this->plugins->install($pluginKey);

        return true;
    }

    /**
     * Removes the specified list of plugins (all if none specified).
     *
     * @return void
     */
    protected function removePlugins()
    {
        $summary = [];

        // Get the list of plugins to remove.

        $pluginsKeys = $this->getAffectedPlugins();

        // Create a progress bar.

        $bar = $this->output->createProgressBar(count($pluginsKeys));
        $bar->start();

        // Remove the plugins.

        foreach ($pluginsKeys as $pluginKey) {
            // Remove the plugin, if it's a valid plugin key.

            if (empty($this->plugins->getSourceData($pluginKey))) {
                $this->line('');
                $this->error("The plugin key: {$pluginKey} is not valid!");
                $status = $this->styleOutput('Invalid', 'red');
            } elseif ($this->removePlugin($pluginKey)) {
                $status = $this->styleOutput('Removed', 'green');
            } else {
                $status = $this->styleOutput('Not Removed', 'red');
            }

            $summary[] = [$pluginKey, $status];
            $bar->advance();
        }

        // Finish the progress bar.

        $bar->finish();
        $this->line('');
        $this->line('The removal of plugins is complete. Summary:');
        $this->line('');

        // Show summary of removed plugins.

        $this->showSummaryTable($summary);
    }

    /**
     * Removes/Uninstalls the specified plugin. Returns whether the plugin was
     * removed.
     *
     * @param  string  $pluginKey  The plugin string key
     * @return bool
     */
    protected function removePlugin($pluginKey)
    {
        // Customize the output messages.

        $confirmMsg = $this->plugins->getInstallMessage('remove') ?? '';
        $confirmMsg = strtr($confirmMsg, [':plugin' => $pluginKey]);

        // Check if the --interactive option is enabled.

        if ($this->option('interactive') && ! $this->confirm($confirmMsg)) {
            return false;
        }

        // Remove the plugin.

        $this->plugins->uninstall($pluginKey);

        return true;
    }

    /**
     * Show the summary table for some operation.
     *
     * @param  array  $rows  The table rows.
     * @return void
     */
    protected function showSummaryTable($rows)
    {
        $header = [
            $this->styleOutput('Plugin Key', 'cyan'),
            $this->styleOutput('Status', 'cyan'),
        ];

        $this->table($header, $rows);
    }
}

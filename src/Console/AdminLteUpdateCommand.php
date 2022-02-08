<?php

namespace JeroenNoten\LaravelAdminLte\Console;

use Illuminate\Console\Command;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\MainViewsResource;

class AdminLteUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adminlte:update ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all the required assets for AdminLTE';

    /**
     * A warning notification to be used when main views were previously
     * installed/published.
     *
     * @var string
     */
    protected $mainViewsWarn = '<fg=yellow>Outdated main views at %s</>
    <fg=cyan>
    We detected that the package main views were previously published and they
    differs from the ones currently available. Note this package may not work
    correctly if you do not update those views manually in order to include the
    latest changes. In the particular case you have recently changed those views
    to include own customizations, then you can ignore this warning. Please,
    refer to next link for more instructions on how to update the views:
    https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Updating</>';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $options = ['--force' => true, '--only' => ['assets']];

        $this->call('adminlte:install', $options);

        // When the main views were previously installed and they differs from
        // the original ones, alarm the user that those views may require a
        // manual update.

        $mainViewsRes = new MainViewsResource();

        if ($mainViewsRes->exists() && ! $mainViewsRes->installed()) {
            $this->info(sprintf($this->mainViewsWarn, $mainViewsRes->target));
        }
    }
}

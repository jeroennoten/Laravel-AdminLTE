<?php

namespace JeroenNoten\LaravelAdminLte\Console;

use Illuminate\Console\Command;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\LayoutViewsResource;

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
    protected $description = 'Updates the AdminLTE distribution files and its dependencies';

    /**
     * A warning notification to be used when main views were previously
     * installed/published.
     *
     * @var string
     */
    protected $layoutViewsWarn = '<fg=yellow>Outdated layout views at %s</>
    <fg=cyan>
    We detected that the package layout views were previously published and they
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

        // When the layout views were previously published and they differs
        // from the package default ones, alarm the user to notify that those
        // views may require a manual update.

        $layoutViewsRes = new LayoutViewsResource();

        if ($layoutViewsRes->exists() && ! $layoutViewsRes->installed()) {
            $msg = sprintf($this->layoutViewsWarn, $layoutViewsRes->target);
            $this->info($msg);
        }
    }
}

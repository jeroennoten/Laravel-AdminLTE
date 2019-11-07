<?php

namespace JeroenNoten\LaravelAdminLte\Console;

use Illuminate\Console\Command;

class AdminLteUpdateCommand extends Command
{
    protected $signature = 'adminlte:update ';

    protected $description = 'Updated the all the required assets for AdminLTE';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('adminlte:install', [
            '--force' => true, '--only' => 'assets',
        ]);
    }
}

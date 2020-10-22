<?php

namespace JeroenNoten\LaravelAdminLte\Console;

use Illuminate\Console\Command;

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
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $options = ['--force' => true, '--only' => ['assets']];

        $this->call('adminlte:install', $options);
    }
}

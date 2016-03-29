<?php


namespace JeroenNoten\LaravelAdminLte\Console;


use Illuminate\Auth\Console\MakeAuthCommand;

class MakeAdminLteCommand extends MakeAuthCommand
{
    protected $signature = 'make:adminlte {--views : Only scaffold the authentication views}';

    protected $description = 'Scaffold basic AdminLTE login and registration views and routes';

    protected $views = [
        'auth/emails/password.stub' => 'auth/emails/password.blade.php',
        'layouts/app.stub' => 'layouts/app.blade.php',
        'welcome.stub' => 'welcome.blade.php',
    ];

    protected $adminLteViews = [
        'auth/login.stub' => 'auth/login.blade.php',
        'auth/register.stub' => 'auth/register.blade.php',
        'auth/passwords/email.stub' => 'auth/passwords/email.blade.php',
        'auth/passwords/reset.stub' => 'auth/passwords/reset.blade.php',
        'home.stub' => 'home.blade.php',
    ];

    protected function exportViews()
    {
        parent::exportViews();

        foreach ($this->adminLteViews as $key => $value) {
            $path = base_path('resources/views/'.$value);

            $this->line('<info>Created View:</info> '.$path);

            copy(__DIR__.'/stubs/make/views/'.$key, $path);
        }
    }
}
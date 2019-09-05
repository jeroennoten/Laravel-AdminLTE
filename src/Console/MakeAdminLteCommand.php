<?php

namespace JeroenNoten\LaravelAdminLte\Console;

use Illuminate\Console\Command;

class MakeAdminLteCommand extends Command
{
    protected $signature = 'make:adminlte {--views : Only scaffold the authentication views}{--force : Overwrite existing views by default}';

    protected $description = 'Scaffold basic AdminLTE login and registration views and routes';

    protected $adminLteViews = [
        'auth/login.stub'           => 'auth/login.blade.php',
        'auth/register.stub'        => 'auth/register.blade.php',
        'auth/passwords/email.stub' => 'auth/passwords/email.blade.php',
        'auth/passwords/reset.stub' => 'auth/passwords/reset.blade.php',
        'home.stub'                 => 'home.blade.php',
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->ensureDirectoriesExist();

        $this->exportViews();

        $this->exportRoutes();

        $this->info('Authentication scaffolding complete.');
    }

    /**
     * Check if the directories for the files exists.
     *
     * @return void
     */
    protected function ensureDirectoriesExist()
    {
        // CHECK if resources/views/auth/passwords directory exists, if not create it
        // Since recursive is on, auth dir will be created first, then passwords
        if (! is_dir($directory = $this->getViewPath('auth/passwords'))) {
            mkdir($directory, 0755, true);
        }
    }

    /**
     * Export the authentication views.
     *
     * @return void
     */
    protected function exportViews()
    {
        foreach ($this->adminLteViews as $key => $value) {
            if (file_exists($view = $this->getViewPath($value)) && ! $this->option('force')) {
                if (! $this->confirm("The [{$value}] view already exists. Do you want to replace it?")) {
                    continue;
                }
            }
            copy(
                __DIR__.'/stubs/make/views/'.$key,
                $view
            );
        }
        $this->info('Authentication views generated successfully.');
    }

    /**
     * Export the authentication routes.
     *
     * @return void
     */
    protected function exportRoutes()
    {
        if (! $this->option('views')) {
            file_put_contents(
                base_path('routes/web.php'),
                file_get_contents(__DIR__.'/stubs/make/routes.stub'),
                FILE_APPEND
            );
        }

        $this->info('Authentication routes generated successfully.');
    }

    /**
     * Get full view path relative to the application's configured view path.
     *
     * @param  string  $path
     * @return string
     */
    protected function getViewPath($path)
    {
        return implode(DIRECTORY_SEPARATOR, [
            config('view.paths')[0] ?? resource_path('views'), $path,
        ]);
    }
}

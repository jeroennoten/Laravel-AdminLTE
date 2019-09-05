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
        $this->createDirectories();

        $this->exportViews();

        if (! $this->option('views')) {
            file_put_contents(
                base_path('routes/web.php'),
                file_get_contents(__DIR__.'/stubs/make/routes.stub'),
                FILE_APPEND
            );
        }

        $this->info('Authentication scaffolding generated successfully.');
    }

    /**
     * Create the directories for the files.
     *
     * @return void
     */
    protected function createDirectories()
    {
        if (! is_dir($directory = $this->getViewPath('auth/passwords'))) {
            mkdir($directory, 0755, true);
        }
    }

    protected function exportViews()
    {
        foreach ($this->adminLteViews as $key => $value) {
            copy(__DIR__.'/stubs/make/views/'.$key,
                base_path('resources/views/'.$value));
        }
    }

    /**
     * Get full view path relative to the app's configured view path.
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

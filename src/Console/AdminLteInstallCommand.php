<?php

namespace JeroenNoten\LaravelAdminLte\Console;

use Illuminate\Console\Command;
use JeroenNoten\LaravelAdminLte\AdminLteServiceProvider;

class AdminLteInstallCommand extends Command
{
    protected $signature = 'adminlte:install {--basic : Only publishes basic assets}{--force : Overwrite existing views by default}';

    protected $description = 'Install all the required files for AdminLTE and the authentication views and routes';

    protected $authViews = [
        'auth/login.blade.php'           => '@extends(\'adminlte::login\')',
        'auth/register.blade.php'        => '@extends(\'adminlte::register\')',
        'auth/passwords/email.blade.php' => '@extends(\'adminlte::passwords.email\')',
        'auth/passwords/reset.blade.php' => '@extends(\'adminlte::passwords.reset\')',
    ];

    protected $basicViews = [
        'home.stub' => 'home.blade.php',
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->exportAssets();

        $this->exportViews();

        $this->exportRoutes();

        $this->exportConfig();

        $this->info(($this->option('basic') ? 'Basic' : 'Full').' AdminLTE Installation complete.');
    }

    /**
     * Check if the directories for the files exists.
     *
     * @param $directory
     * @return void
     */
    protected function ensureDirectoriesExist($directory)
    {
        // CHECK if directory exists, if not create it
        if (! is_dir($directory)) {
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
        foreach ($this->basicViews as $key => $value) {
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
        $this->comment('Basic views generated successfully.');

        if (! $this->option('basic')) {
            $this->ensureDirectoriesExist($this->getViewPath('auth/passwords'));
            foreach($this->authViews as $file => $content) {
                file_put_contents($this->getViewPath($file), $content);
            }
            $this->comment('Authentication views generated successfully.');
        }

    }

    /**
     * Export the authentication routes.
     *
     * @return void
     */
    protected function exportRoutes()
    {
        if (! $this->option('basic')) {
            file_put_contents(
                base_path('routes/web.php'),
                file_get_contents(__DIR__.'/stubs/make/routes.stub'),
                FILE_APPEND
            );
            $this->comment('Authentication routes generated successfully.');
        }

    }

    /**
     * Copy all the content of the Assets Folder to Public Directory
     */
    protected function exportAssets()
    {
        $this->directoryCopy(__DIR__.'/../../resources/assets/', public_path('adminlte'), true);
        $this->comment('Assets Installation complete.');
    }

    /**
     * Install the config files (Copy it to config folder using the Service Provider
     */
    protected function exportConfig()
    {
        $this->call('vendor:publish', [
            "--provider" => AdminLteServiceProvider::class,
            "--tag" => 'config'
        ]);
        $this->comment('Configuration Files Installation complete.');
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

    /**
     * Recursive function that copies an entire directory to a destination
     *
     * @param $source_directory
     * @param $destination_directory
     */
    protected function directoryCopy($source_directory, $destination_directory, $recursive)
    {
        //Checks destination folder existance
        $this->ensureDirectoriesExist($destination_directory);
        //Open source directory
        $directory = opendir($source_directory);
        while(false !== ( $file = readdir($directory)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($source_directory . '/' . $file) && $recursive) {
                    $this->directoryCopy($source_directory . '/' . $file, $destination_directory . '/' . $file, true);
                }
                else {
                    if (file_exists($destination_directory . '/' . $file) && ! $this->option('force')) {
                        if (!$this->confirm("The [{$file}] file already exists. Do you want to replace it?")) {
                            continue;
                        }
                    }
                    copy($source_directory . '/' . $file,$destination_directory . '/' . $file);
                }
            }
        }
        closedir($directory);
    }

}

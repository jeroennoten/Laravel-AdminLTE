<?php

namespace JeroenNoten\LaravelAdminLte\Console;

use Illuminate\Console\Command;

class AdminLteInstallCommand extends Command
{
    protected $signature = 'adminlte:install '.
        '{--basic : Only publishes the assets and a basic page example}'.
        '{--force : Overwrite existing views by default}'.
        '{--interactive : The installation will guide you through the process}';

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

        $this->exportPlugins();

        $this->exportBasicViews();

        $this->exportAuthViews();

        $this->exportRoutes();

        $this->exportConfig();

        $this->info(($this->option('basic') ? 'Basic' : 'Full').' AdminLTE Installation complete.');
    }

    /**
     * Export the authentication views.
     *
     * @return void
     */
    protected function exportAuthViews()
    {
        if (! $this->option('basic')) {
            if ($this->option('interactive')) {
                if (! $this->confirm('Install AdminLTE authentication views?')) {
                    return;
                }
            }
            $this->ensureDirectoriesExist($this->getViewPath('auth/passwords'));
            foreach ($this->authViews as $file => $content) {
                file_put_contents($this->getViewPath($file), $content);
            }
            $this->comment('Authentication views installed successfully.');
        }
    }

    /**
     * Export the basic views.
     *
     * @return void
     */
    protected function exportBasicViews()
    {
        if ($this->option('interactive')) {
            if (! $this->confirm('Install AdminLTE basic views?')) {
                return;
            }
        }
        foreach ($this->basicViews as $key => $value) {
            if (file_exists($view = $this->getViewPath($value)) && ! $this->option('force')) {
                if (! $this->confirm("The [{$value}] view already exists. Do you want to replace it?")) {
                    continue;
                }
            }
            copy(
                __DIR__.'/stubs/'.$key,
                $view
            );
        }
        $this->comment('Basic views installed successfully.');
    }

    /**
     * Export the authentication routes.
     *
     * @return void
     */
    protected function exportRoutes()
    {
        if (! $this->option('basic')) {
            if ($this->option('interactive')) {
                if (! $this->confirm('Install AdminLTE authentication routes?')) {
                    return;
                }
            }
            file_put_contents(
                base_path('routes/web.php'),
                file_get_contents(__DIR__.'/stubs/routes.stub'),
                FILE_APPEND
            );
            $this->comment('Authentication routes installed successfully.');
        }
    }

    /**
     * Copy all the content of the Assets Folder to Public Directory.
     */
    protected function exportAssets()
    {
        if ($this->option('interactive')) {
            if (! $this->confirm('Install the basic package assets?')) {
                return;
            }
        }

        $assetsPath = public_path().'/vendor/';
        $packagePath = base_path().'/vendor/almasaeed2010/adminlte/';

        // Copy AdminlTE dist
        $this->directoryCopy($packagePath.'dist/css/', $assetsPath.'adminlte/dist/css', false);
        $this->directoryCopy($packagePath.'dist/js/', $assetsPath.'adminlte/dist/js', false, ['demo.js']);

        if (! is_dir($assetsPath.'adminlte/dist/img/')) {
            mkdir($assetsPath.'adminlte/dist/img/');
        }

        copy($packagePath.'dist/img/AdminLTELogo.png', $assetsPath.'adminlte/dist/img/AdminLTELogo.png');

        // Copy Font Awesome Free
        $this->directoryCopy($packagePath.'plugins/fontawesome-free', $assetsPath.'fontawesome-free', true);

        // Copy Bootstrap
        $this->directoryCopy($packagePath.'plugins/bootstrap', $assetsPath.'bootstrap', true);

        // Copy Popper
        $this->directoryCopy($packagePath.'plugins/popper', $assetsPath.'popper', true);

        // Copy jQuery
        $this->directoryCopy($packagePath.'plugins/jquery', $assetsPath.'jquery', true, ['core.js', 'jquery.slim.js', 'jquery.slim.min.js', 'jquery.slim.min.map']);

        // Copy overlayScrollbars
        $this->directoryCopy($packagePath.'plugins/overlayScrollbars', $assetsPath.'overlayScrollbars', true);

        $this->comment('Basic Assets Installation complete.');
    }

    /**
     * Copy all Plugin Assets to Public Directory.
     */
    protected function exportPlugins()
    {
        if ($this->option('interactive')) {
            if (! $this->confirm('Install the plugin package assets?')) {
                return;
            }
        }

        $assetsPath = public_path().'/vendor/';
        $packagePath = base_path().'/vendor/almasaeed2010/adminlte/';

        $plugins = [
            'bootstrapColorpicker' => [
                'name' => 'Bootstrap Colorpicker',
                'package_path' => 'bootstrap-colorpicker',
                'assets_path' => 'bootstrap-colorpicker',
            ],
            'bootstrapSlider' => [
                'name' => 'Bootstrap Slider',
                'package_path' => 'bootstrap-slider',
                'assets_path' => 'bootstrap-slider',
            ],
            'bootstrap4Duallistbox' => [
                'name' => 'Bootstrap4 Duallistbox',
                'package_path' => 'bootstrap4-duallistbox',
                'assets_path' => 'bootstrap4-duallistbox',
            ],
            'chartJs' => [
                'name' => 'Chart.js',
                'package_path' => 'chart.js',
                'assets_path' => 'chart.js',
            ],
            'datatables' => [
                'name' => 'Datatables',
                'package_path' => [
                    'datatables',
                    'datatables-bs4',
                ],
                'assets_path' => [
                    'datatables/js',
                    'datatables',
                ],
            ],
            'datatablesPlugins' => [
                'name' => 'Datatables Plugins',
                'package_path' => [
                    'datatables-autofill',
                    'datatables-buttons',
                    'datatables-colreorder',
                    'datatables-fixedcolumns',
                    'datatables-fixedheader',
                    'datatables-keytable',
                    'datatables-responsive',
                    'datatables-rowgroup',
                    'datatables-rowreorder',
                    'datatables-scroller',
                    'datatables-select',
                    'pdfmake',
                    'jszip',
                ],
                'assets_path' => [
                    'datatables-plugins/autofill',
                    'datatables-plugins/buttons',
                    'datatables-plugins/colreorder',
                    'datatables-plugins/fixedcolumns',
                    'datatables-plugins/fixedheader',
                    'datatables-plugins/keytable',
                    'datatables-plugins/responsive',
                    'datatables-plugins/rowgroup',
                    'datatables-plugins/rowreorder',
                    'datatables-plugins/scroller',
                    'datatables-plugins/select',
                    'datatables-plugins/pdfmake',
                    'datatables-plugins/jszip',
                ],
            ],
            'daterangepicker' => [
                'name' => 'DateRangePicker',
                'package_path' => [
                    'daterangepicker',
                    'moment',
                ],
                'assets_path' => [
                    'daterangepicker',
                    'moment',
                ],
            ],
            'ekkoLightbox' => [
                'name' => 'Ekko Lightbox',
                'package_path' => 'ekko-lightbox',
                'assets_path' => 'ekko-lightbox',
            ],
            'fastclick' => [
                'name' => 'Fastclick',
                'package_path' => 'fastclick',
                'assets_path' => 'fastclick',
            ],
            'filterizr' => [
                'name' => 'Filterizr',
                'package_path' => 'filterizr',
                'assets_path' => 'filterizr',
                'ignore_ending' => '*.d.ts',
                'recursive' => false,
            ],
            'flagIconCss' => [
                'name' => 'Flag Icon Css',
                'package_path' => 'flag-icon-css',
                'assets_path' => 'flag-icon-css',
            ],
            'flot' => [
                'name' => 'Flot',
                'package_path' => 'flot',
                'assets_path' => 'flot',
            ],
            'fullcalendar' => [
                'name' => 'Fullcalendar',
                'package_path' => 'fullcalendar',
                'assets_path' => 'fullcalendar',
                'ignore_ending' => [
                    '*.d.ts', '*.json', '*.md',
                ],
            ],
            'fullcalendarPlugins' => [
                'name' => 'Fullcalendar Plugins',
                'package_path' => [
                    'fullcalendar-bootstrap',
                    'fullcalendar-daygrid',
                    'fullcalendar-interaction',
                    'fullcalendar-timegrid',
                ],
                'assets_path' => [
                    'fullcalendar-plugins/bootstrap',
                    'fullcalendar-plugins/daygrid',
                    'fullcalendar-plugins/interaction',
                    'fullcalendar-plugins/timegrid',
                ],
                'ignore_ending' => [
                    '*.d.ts', '*.json', '*.md',
                ],
            ],
            'icheckBootstrap' => [
                'name' => 'iCheck Bootstrap',
                'package_path' => 'icheck-bootstrap',
                'assets_path' => 'icheck-bootstrap',
                'ignore_ending' => [
                    '*.json', '*.md',
                ],
            ],
            'inputmask' => [
                'name' => 'InputMask',
                'package_path' => 'inputmask',
                'assets_path' => 'inputmask',
            ],
            'ionRangslider' => [
                'name' => 'ion RangeSlider',
                'package_path' => 'ion-rangeslider',
                'assets_path' => 'ion-rangeslider',
                'ignore_ending' => [
                    '*.json', '*.md', '.editorconfig',
                ],
            ],
            'jqueryKnob' => [
                'name' => 'jQuery Knob',
                'package_path' => 'jquery-knob',
                'assets_path' => 'jquery-knob',
            ],
            'jqueryMapael' => [
                'name' => 'jQuery Mapael',
                'package_path' => [
                    'jquery-mapael',
                    'raphael',
                    'jquery-mousewheel',
                ],
                'assets_path' => [
                    'jquery-mapael',
                    'raphael',
                    'jquery-mousewheel',
                ],
                'ignore_ending' => [
                    '*.json', '*.md', '.editorconfig',
                ],
            ],
            'jqueryUi' => [
                'name' => 'jQuery UI',
                'package_path' => [
                    'jquery-ui',
                    'jquery-ui/images',
                ],
                'assets_path' => [
                    'jquery-ui',
                    'jquery-ui/images',
                ],
                'recursive' => false,
                'ignore_ending' => [
                    '*.json', '*.md', '*.html', '.editorconfig',
                ],
            ],
            'jqvmap' => [
                'name' => 'jQVMap',
                'package_path' => 'jqvmap',
                'assets_path' => 'jqvmap',
            ],
            'jsgrid' => [
                'name' => 'jsGrid',
                'package_path' => [
                    'jsgrid',
                    'jsgrid/i18n',
                ],
                'assets_path' => [
                    'jsgrid',
                    'jsgrid/i18n',
                ],
                'recursive' => false,
            ],
            'paceProgress' => [
                'name' => 'Pace Progress',
                'package_path' => 'pace-progress',
                'assets_path' => 'pace-progress',
            ],
            'select2' => [
                'name' => 'Select 2 with Bootstrap 4 Theme',
                'package_path' => [
                    'select2',
                    'select2-bootstrap4-theme',
                ],
                'assets_path' => [
                    'select2',
                    'select2-bootstrap4-theme',
                ],
                'ignore_ending' => [
                    '*.json', '*.md',
                ],
            ],
            'sparklines' => [
                'name' => 'Sparklines',
                'package_path' => 'sparklines',
                'assets_path' => 'sparklines',
            ],
            'summernote' => [
                'name' => 'Summernote',
                'package_path' => 'summernote',
                'assets_path' => 'summernote',
            ],
            'sweetalert2' => [
                'name' => 'Sweetalert 2 with Bootstrap 4 Theme',
                'package_path' => [
                    'sweetalert2',
                    'sweetalert2-theme-bootstrap-4',
                ],
                'assets_path' => [
                    'sweetalert2',
                    'sweetalert2-theme-bootstrap-4',
                ],
            ],
            'tempusdominusBootstrap4' => [
                'name' => 'Tempusdominus Bootstrap 4',
                'package_path' => 'tempusdominus-bootstrap-4',
                'assets_path' => 'tempusdominus-bootstrap-4',
            ],
            'toastr' => [
                'name' => 'Toastr',
                'package_path' => 'toastr',
                'assets_path' => 'toastr',
            ],
        ];

        foreach ($plugins as $plugin) {
            if ($this->option('interactive')) {
                if (! $this->confirm('Install the '.$plugin['name'].' assets?')) {
                    continue;
                }
            }

            if (is_array($plugin['package_path'])) {
                foreach ($plugin['package_path'] as $key => $pluginPackagePath) {
                    $pluginAssetsPath = $plugin['assets_path'][$key];
                    $this->directoryCopy($packagePath.'plugins/'.$pluginPackagePath, $assetsPath.$pluginAssetsPath, ($plugin['recursive'] ?? true), ($plugin['ignore'] ?? []), ($plugin['ignore_ending'] ?? null));
                }
            } else {
                $this->directoryCopy($packagePath.'plugins/'.$plugin['package_path'], $assetsPath.$plugin['assets_path'], ($plugin['recursive'] ?? true), ($plugin['ignore'] ?? []), ($plugin['ignore_ending'] ?? null));
            }
        }

        $this->comment('Plugin Assets Installation complete.');
    }

    /**
     * Install the config file.
     */
    protected function exportConfig()
    {
        if ($this->option('interactive')) {
            if (! $this->confirm('Install the package config file?')) {
                return;
            }
        }
        if (file_exists(config_path('adminlte.php')) && ! $this->option('force')) {
            if (! $this->confirm('The AdminLTE configuration file already exists. Do you want to replace it?')) {
                return;
            }
        }
        copy(
            __DIR__.'/../../config/adminlte.php',
            config_path('adminlte.php')
        );

        $this->comment('Configuration Files Installation complete.');
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
     * Recursive function that copies an entire directory to a destination.
     *
     * @param $source_directory
     * @param $destination_directory
     */
    protected function directoryCopy($source_directory, $destination_directory, $recursive = false, $ignore = [], $ignore_ending = null)
    {
        //Checks destination folder existance
        $this->ensureDirectoriesExist($destination_directory);
        //Open source directory
        $directory = opendir($source_directory);

        while (false !== ($file = readdir($directory))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($source_directory.'/'.$file) && $recursive) {
                    $this->directoryCopy($source_directory.'/'.$file, $destination_directory.'/'.$file, true);
                } elseif (! is_dir($source_directory.'/'.$file)) {
                    $checkup = true;

                    if ($ignore_ending) {
                        if (! is_array($ignore_ending)) {
                            $ignore_ending = str_replace('*', '', $ignore_ending);

                            $checkup = (substr($file, -strlen($ignore_ending)) !== $ignore_ending);
                        } else {
                            foreach ($ignore_ending as $key => $ignore_ending_sub) {
                                if ($checkup) {
                                    $ignore_ending_sub = str_replace('*', '', $ignore_ending_sub);

                                    $checkup = (substr($file, -strlen($ignore_ending_sub)) !== $ignore_ending_sub);
                                }
                            }
                        }
                    }

                    if ($checkup && (! in_array($file, $ignore))) {
                        if (file_exists($destination_directory.'/'.$file) && ! $this->option('force')) {
                            if (! $this->confirm("The [{$file}] file already exists. Do you want to replace it?")) {
                                continue;
                            }
                        }
                        copy($source_directory.'/'.$file, $destination_directory.'/'.$file);
                    }
                }
            }
        }

        closedir($directory);
    }
}

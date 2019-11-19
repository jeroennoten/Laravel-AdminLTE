<?php

namespace JeroenNoten\LaravelAdminLte\Console;

use Illuminate\Console\Command;
use JeroenNoten\LaravelAdminLte\Http\Helpers\CommandHelper;

class AdminLtePluginCommand extends Command
{
    protected $signature = 'adminlte:plugins '.
        '{operation? : Operation command, Available commands; list, install, update & remove}'.
        '{--plugin=* : Plugin Key}'.
        '{--interactive : The installation will guide you through the process}';

    protected $description = 'Manages additional plugin files for AdminLTE';

    protected $plugins = [
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
        'bootstrapSwitch' => [
            'name' => 'Bootstrap Switch',
            'package_path' => 'bootstrap-switch',
            'assets_path' => 'bootstrap-switch',
        ],
        'bootstrap4Duallistbox' => [
            'name' => 'Bootstrap4 Duallistbox',
            'package_path' => 'bootstrap4-duallistbox',
            'assets_path' => 'bootstrap4-duallistbox',
        ],
        'bsCustomFileInput' => [
            'name' => 'bs-custom-file-input',
            'package_path' => 'bs-custom-file-input',
            'assets_path' => 'bs-custom-file-input',
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
        'jqueryValidation' => [
            'name' => 'jQuery Validation',
            'package_path' => 'jquery-validation',
            'assets_path' => 'jquery-validation',
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

    protected $assets_path = 'vendor/';

    protected $package_path = 'vendor/almasaeed2010/adminlte/';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $operation = $this->arguments()['operation'];

        if (! $operation) {
            $operation = 'list';
        }

        switch ($operation) {
        case 'install':
            $this->copyPlugins();
            $this->info('AdminLTE Plugin Install complete.');

            break;

        case 'update':
            $this->copyPlugins(true);
            $this->info('AdminLTE Plugin Update complete.');

            break;

        case 'remove':
            $this->removePlugins();
            $this->info('AdminLTE Plugin Remove complete.');

            break;

        default:
            if ($operation != 'list' && $operation != '') {
                $this->error('Invalid operation!');
            } else {
                $this->listPlugins();
            }

            break;
        }
    }

    /**
     * List Plugins.
     *
     * @return void
     */
    protected function listPlugins()
    {
        $headers = ['Plugin Name', 'Plugin Key', 'Status'];
        $plugins = [];

        $bar = $this->output->createProgressBar(count($this->plugins));

        $this->line('Checking Plugins');

        $bar->start();

        foreach ($this->plugins as $plugin_key => $plugin) {
            $plugins[] = [$plugin['name'], $plugin_key, $this->checkPlugin($plugin_key)];
            $bar->advance();
        }

        $bar->finish();

        $this->line('');
        $this->line('Plugins Checked');

        $this->table($headers, $plugins);
    }

    /**
     * Check Plugin.
     *
     * @return void
     */
    protected function checkPlugin($plugin_key)
    {
        $plugin_exist = true;
        $plugin_missmatch = false;
        $plugin_child_exist = true;
        $plugin_child_missmatch = false;
        $plugin_public_path = public_path($this->assets_path);
        $plugin_base_path = base_path($this->package_path.'plugins/');
        $plugin_package_path = $this->plugins[$plugin_key]['package_path'];
        $plugin_assets_path = $this->plugins[$plugin_key]['assets_path'];
        $plugin_ignore = $this->plugins[$plugin_key]['ignore'] ?? [];
        $plugin_ignore_ending = $this->plugins[$plugin_key]['ignore_ending'] ?? [];
        $plugin_recursive = $this->plugins[$plugin_key]['recursive'] ?? true;

        if (is_array($plugin_assets_path)) {
            foreach ($plugin_assets_path as $key => $assets_path) {
                if (! file_exists($plugin_public_path.$assets_path)) {
                    $plugin_exist = false;
                    $plugin_child_exist = false;
                } else {
                    $compare = CommandHelper::compareDirectories($plugin_base_path.$plugin_package_path[$key], $plugin_public_path.$assets_path, '', $plugin_ignore, $plugin_ignore_ending, $plugin_recursive);

                    if (! $plugin_child_missmatch && $compare) {
                        $plugin_child_missmatch = false;
                    } else {
                        $plugin_child_missmatch = true;
                    }
                }
            }
        } else {
            if (! file_exists($plugin_public_path.$plugin_assets_path)) {
                $plugin_exist = false;
            } else {
                if (! $compare = CommandHelper::compareDirectories($plugin_base_path.$plugin_package_path, $plugin_public_path.$plugin_assets_path, '', $plugin_ignore, $plugin_ignore_ending, $plugin_recursive)) {
                    $plugin_missmatch = true;
                }
            }
        }

        if ($plugin_exist && $plugin_child_exist && (! $plugin_missmatch && ! $plugin_child_missmatch)) {
            return 'Installed';
        } elseif ($plugin_exist && (($plugin_missmatch || $plugin_child_missmatch) || ! $plugin_child_exist)) {
            return 'Update Available';
        } elseif (! $plugin_exist) {
            return 'Not Installed';
        }
    }

    /**
     * Copy all Plugin Assets to Public Directory.
     */
    protected function copyPlugins($force = null)
    {
        if (! $plugins = $this->option('plugin')) {
            $plugins = $this->plugins;
        }

        $bar = $this->output->createProgressBar(count($plugins));

        $bar->start();

        if ($this->option('interactive')) {
            if (! $this->confirm('Install the plugin package assets?')) {
                return;
            }
        }

        foreach ($plugins as $plugin_key => $plugin) {
            if (is_string($plugin)) {
                $plugin_key = $plugin;
            }

            if (! isset($this->plugins[$plugin_key])) {
                $this->error('Plugin Key not found: '.$plugin_key.'.');
                continue;
            }

            $plugin_keys[] = $plugin_key;
            $plugin = $this->plugins[$plugin_key];

            if ($this->option('interactive')) {
                if (! $this->confirm('Install the '.$plugin['name'].' assets?')) {
                    continue;
                }
            }
            if (is_array($plugin['package_path'])) {
                foreach ($plugin['package_path'] as $key => $plugin_package_path) {
                    $plugin_assets_path = $plugin['assets_path'][$key];
                    CommandHelper::directoryCopy(base_path($this->package_path).'plugins/'.$plugin_package_path, public_path($this->assets_path).$plugin_assets_path, $force, ($plugin['recursive'] ?? true), ($plugin['ignore'] ?? []), ($plugin['ignore_ending'] ?? null));
                }
            } else {
                CommandHelper::directoryCopy(base_path($this->package_path).'plugins/'.$plugin['package_path'], public_path($this->assets_path).$plugin['assets_path'], $force, ($plugin['recursive'] ?? true), ($plugin['ignore'] ?? []), ($plugin['ignore_ending'] ?? null));
            }

            $bar->advance();
        }

        $bar->finish();
        $this->line('');
    }

    /**
     * Removes all Plugin Assets to Public Directory.
     */
    protected function removePlugins()
    {
        if (! $this->confirm('Do you really want to remove the plugin package assets?')) {
            return;
        }

        if (! $plugins = $this->option('plugin')) {
            $plugins = $this->plugins;
        }

        $plugin_keys = [];

        foreach ($plugins as $plugin_key => $plugin) {
            if (is_string($plugin)) {
                $plugin_key = $plugin;
            }

            if (! isset($this->plugins[$plugin_key])) {
                $this->error('Plugin Key not found: '.$plugin_key.'.');
                continue;
            }

            $plugin_keys[] = $plugin_key;
            $plugin = $this->plugins[$plugin_key];

            if ($this->option('interactive')) {
                if (! $this->confirm('Remove the '.$plugin['name'].' assets?')) {
                    continue;
                }
            }
            if (is_array($plugin['package_path'])) {
                foreach ($plugin['package_path'] as $key => $plugin_package_path) {
                    $plugin_assets_path = $plugin['assets_path'][$key];
                    $plugin_path = public_path($this->assets_path).$plugin_assets_path;
                    CommandHelper::removeDirectory($plugin_path);
                }
            } else {
                $plugin_path = public_path($this->assets_path).$plugin['assets_path'];
                CommandHelper::removeDirectory($plugin_path);
            }
        }

        $this->info('Plugins removed: '.implode(', ', $plugin_keys).'.');
    }
}

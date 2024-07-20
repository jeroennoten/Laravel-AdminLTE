<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;

class PluginsResource extends PackageResource
{
    /**
     * The available plugins data. A plugin can contain next data keys:
     * - name: The name of the plugin.
     * - source: The source of the plugin (relative to base source).
     * - target: The target of the plugin (relative to base target).
     * - resources: An array with resources data items.
     * - ignore: A list of file patterns to ignore.
     * - recursive: Whether to copy files recursively (default is true).
     * - dependencies: A list of plugin keys that are required as dependencies.
     *
     * When the target is not specified, the source will be used as the
     * relative path to the base target destination. A resource can contain the
     * same keys that are availables for a plugin.
     *
     * @var array
     */
    protected $plugins = [
        'bootstrap4DualListbox' => [
            'name' => 'Bootstrap4 Dual Listbox',
            'source' => 'bootstrap4-duallistbox',
        ],
        'bootstrapColorpicker' => [
            'name' => 'Bootstrap Colorpicker',
            'source' => 'bootstrap-colorpicker',
        ],
        'bootstrapSlider' => [
            'name' => 'Bootstrap Slider',
            'source' => 'bootstrap-slider',
        ],
        'bootstrapSwitch' => [
            'name' => 'Bootstrap Switch',
            'source' => 'bootstrap-switch',
        ],
        'bsCustomFileInput' => [
            'name' => 'bs-custom-file-input',
            'source' => 'bs-custom-file-input',
        ],
        'chartJs' => [
            'name' => 'Chart.js',
            'source' => 'chart.js',
        ],
        'datatables' => [
            'name' => 'Datatables',
            'resources' => [
                ['source' => 'datatables', 'target' => 'datatables/js'],
                ['source' => 'datatables-bs4', 'target' => 'datatables'],
            ],
        ],
        'datatablesPlugins' => [
            'name' => 'Datatables Plugins',
            'resources' => [
                ['source' => 'datatables-autofill', 'target' => 'datatables-plugins/autofill'],
                ['source' => 'datatables-buttons', 'target' => 'datatables-plugins/buttons'],
                ['source' => 'datatables-colreorder', 'target' => 'datatables-plugins/colreorder'],
                ['source' => 'datatables-fixedcolumns', 'target' => 'datatables-plugins/fixedcolumns'],
                ['source' => 'datatables-fixedheader', 'target' => 'datatables-plugins/fixedheader'],
                ['source' => 'datatables-keytable', 'target' => 'datatables-plugins/keytable'],
                ['source' => 'datatables-responsive', 'target' => 'datatables-plugins/responsive'],
                ['source' => 'datatables-rowgroup', 'target' => 'datatables-plugins/rowgroup'],
                ['source' => 'datatables-rowreorder', 'target' => 'datatables-plugins/rowreorder'],
                ['source' => 'datatables-scroller', 'target' => 'datatables-plugins/scroller'],
                ['source' => 'datatables-select', 'target' => 'datatables-plugins/select'],
                ['source' => 'pdfmake', 'target' => 'datatables-plugins/pdfmake'],
                ['source' => 'jszip', 'target' => 'datatables-plugins/jszip'],
            ],
        ],
        'daterangepicker' => [
            'name' => 'Date Range Picker',
            'source' => 'daterangepicker',
            'dependencies' => ['moment'],
        ],
        'ekkoLightbox' => [
            'name' => 'Ekko Lightbox',
            'source' => 'ekko-lightbox',
        ],
        'fastclick' => [
            'name' => 'FastClick',
            'source' => 'fastclick',
        ],
        'filterizr' => [
            'name' => 'Filterizr',
            'source' => 'filterizr',
            'ignore' => ['*.d.ts'],
            'recursive' => false,
        ],
        'flagIconCss' => [
            'name' => 'Flag Icon Css',
            'source' => 'flag-icon-css',
        ],
        'flot' => [
            'name' => 'Flot',
            'source' => 'flot',
        ],
        'fullcalendar' => [
            'name' => 'Fullcalendar',
            'source' => 'fullcalendar',
            'ignore' => ['*.d.ts', '*.json', '*.md'],
        ],
        'icheckBootstrap' => [
            'name' => 'iCheck Bootstrap',
            'source' => 'icheck-bootstrap',
            'ignore' => ['*.json', '*.md'],
        ],
        'inputmask' => [
            'name' => 'InputMask',
            'source' => 'inputmask',
        ],
        'ionRangslider' => [
            'name' => 'Ion.RangeSlider',
            'source' => 'ion-rangeslider',
            'ignore' => ['*.json', '*.md', '.editorconfig'],
        ],
        'jqueryKnob' => [
            'name' => 'jQuery Knob',
            'source' => 'jquery-knob',
        ],
        'jqueryMapael' => [
            'name' => 'jQuery Mapael',
            'resources' => [
                ['source' => 'jquery-mapael'],
                ['source' => 'raphael'],
                ['source' => 'jquery-mousewheel'],
            ],
            'ignore' => ['*.json', '*.md', '.editorconfig'],
        ],
        'jqueryUi' => [
            'name' => 'jQuery UI',
            'resources' => [
                ['source' => 'jquery-ui'],
                ['source' => 'jquery-ui/images'],
            ],
            'recursive' => false,
        ],
        'jqueryValidation' => [
            'name' => 'jQuery Validation',
            'source' => 'jquery-validation',
        ],
        'jqvmap' => [
            'name' => 'jQVMap',
            'source' => 'jqvmap',
        ],
        'jsgrid' => [
            'name' => 'jsGrid',
            'resources' => [
                ['source' => 'jsgrid'],
                ['source' => 'jsgrid/i18n'],
            ],
            'recursive' => false,
        ],
        'moment' => [
            'name' => 'Moment.js',
            'source' => 'moment',
        ],
        'paceProgress' => [
            'name' => 'Pace Progress',
            'source' => 'pace-progress',
        ],
        'select2' => [
            'name' => 'Select 2 with Bootstrap 4 Theme',
            'resources' => [
                ['source' => 'select2'],
                ['source' => 'select2-bootstrap4-theme'],
            ],
            'ignore' => ['*.json', '*.md'],
        ],
        'sparklines' => [
            'name' => 'Sparklines',
            'source' => 'sparklines',
        ],
        'summernote' => [
            'name' => 'Summernote',
            'source' => 'summernote',
        ],
        'sweetalert2' => [
            'name' => 'Sweetalert 2 with Bootstrap 4 Theme',
            'resources' => [
                ['source' => 'sweetalert2'],
                ['source' => 'sweetalert2-theme-bootstrap-4'],
            ],
        ],
        'tempusdominusBootstrap4' => [
            'name' => 'Tempus Dominus for Bootstrap 4',
            'source' => 'tempusdominus-bootstrap-4',
            'dependencies' => ['moment'],
        ],
        'toastr' => [
            'name' => 'Toastr',
            'source' => 'toastr',
        ],
    ];

    /**
     * Create a new resource instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Fill the basic resource data.

        $this->description = 'The set of extra plugins available with AdminLTE';
        $this->required = false;

        // Define the base source folder of the plugins.

        $this->source = base_path('vendor/almasaeed2010/adminlte/plugins');

        // Define the base target destination for the plugins.

        $this->target = public_path('vendor');

        // Fill the set of installation messages templates.

        $this->messages = [
            'install' => 'Do you want to plublish the :plugin plugin?',
            'overwrite' => 'The :plugin plugin was already published. Want to replace?',
            'remove' => 'Do you really want to remove the :plugin plugin?',
        ];
    }

    /**
     * Gets the plugins source data.
     *
     * @param  string  $pluginKey  A plugin key
     * @return array
     */
    public function getSourceData($pluginKey = null)
    {
        // Check if we need to get data of a specific AdminLTE plugin.

        if (! empty($pluginKey)) {
            return $this->plugins[$pluginKey] ?? [];
        }

        // Otherwise, return all the AdminLTE plugins data.

        return $this->plugins;
    }

    /**
     * Installs or publishes the specified plugin.
     *
     * @param  string  $pluginKey  A plugin key
     * @return void
     */
    public function install($pluginKey = null)
    {
        if (isset($pluginKey) && isset($this->plugins[$pluginKey])) {
            $plugin = $this->preparePlugin($this->plugins[$pluginKey]);
            $this->installPlugin($plugin);
        }
    }

    /**
     * Uninstalls the specified plugin.
     *
     * @param  string  $pluginKey  A plugin key
     * @return void
     */
    public function uninstall($pluginKey = null)
    {
        if (isset($pluginKey) && isset($this->plugins[$pluginKey])) {
            $plugin = $this->preparePlugin($this->plugins[$pluginKey]);
            $this->uninstallPlugin($plugin);
        }
    }

    /**
     * Checks whether a plugin already exists in the target location.
     *
     * @param  string  $pluginKey  A plugin key
     * @return bool
     */
    public function exists($pluginKey = null)
    {
        if (isset($pluginKey) && isset($this->plugins[$pluginKey])) {
            $plugin = $this->preparePlugin($this->plugins[$pluginKey]);

            return $this->pluginExists($plugin);
        }

        return false;
    }

    /**
     * Checks whether a plugin is correctly installed, i.e. if the source items
     * matches with the items available at the target location.
     *
     * @param  string  $pluginKey  A plugin key
     * @return bool
     */
    public function installed($pluginKey = null)
    {
        if (isset($pluginKey) && isset($this->plugins[$pluginKey])) {
            $plugin = $this->preparePlugin($this->plugins[$pluginKey]);

            return $this->pluginInstalled($plugin);
        }

        return false;
    }

    /**
     * Prepares a plugin with some sort of normalizations in its data.
     *
     * @param  array  $plugin  An array with the plugin data
     * @return array
     */
    protected function preparePlugin($plugin)
    {
        // Add source and target when not defined.

        $plugin['source'] = $plugin['source'] ?? '';
        $plugin['target'] = $plugin['target'] ?? $plugin['source'];

        // Add fully qualified paths and default values.

        $DS = DIRECTORY_SEPARATOR;
        $plugin['source'] = $this->source.$DS.$plugin['source'];
        $plugin['target'] = $this->target.$DS.$plugin['target'];
        $plugin['ignore'] = $plugin['ignore'] ?? [];
        $plugin['recursive'] = $plugin['recursive'] ?? true;

        // Add fully qualified paths and default values on the resources.

        if (isset($plugin['resources'])) {
            foreach ($plugin['resources'] as $key => $res) {
                $res['target'] = $res['target'] ?? $res['source'];
                $res['source'] = $plugin['source'].$DS.$res['source'];
                $res['target'] = $plugin['target'].$DS.$res['target'];
                $res['ignore'] = $res['ignore'] ?? $plugin['ignore'];
                $res['recursive'] = $res['recursive'] ?? $plugin['recursive'];
                $plugin['resources'][$key] = $res;
            }
        }

        // Return normalized plugin data.

        return $plugin;
    }

    /**
     * Installs the specified AdminLTE plugin.
     *
     * @param  array  $plugin  An array with the plugin data
     * @return void
     */
    protected function installPlugin($plugin)
    {
        // First, check and install dependencies plugins, if any.

        foreach (($plugin['dependencies'] ?? []) as $pluginKey) {
            $this->install($pluginKey);
        }

        // Now, check if we need to export the entire plugin.

        if (! isset($plugin['resources'])) {
            $this->publishResource($plugin);

            return;
        }

        // Otherwise, publish only the specified plugin resources.

        foreach ($plugin['resources'] as $res) {
            $this->publishResource($res);
        }
    }

    /**
     * Publishes the specified resource (usually a file or folder).
     *
     * @param  array  $res  An array with the resource data
     * @return void
     */
    protected function publishResource($res)
    {
        // Check whether the resource is a file or a directory.

        if (File::isDirectory($res['source'])) {
            CommandHelper::copyDirectory(
                $res['source'],
                $res['target'],
                $res['force'] ?? true,
                $res['recursive'] ?? true,
                $res['ignore'] ?? []
            );
        } else {
            File::ensureDirectoryExists(File::dirname($res['target']));
            File::copy($res['source'], $res['target']);
        }
    }

    /**
     * Checks whether the specified plugin already exists in the target
     * location.
     *
     * @param  array  $plugin  An array with the plugin data
     * @return bool
     */
    protected function pluginExists($plugin)
    {
        // When the plugin is not a resources list, just check if target exists.

        if (! isset($plugin['resources'])) {
            return File::exists($plugin['target']);
        }

        // Otherwise, check if any of the plugin resources already exists.

        foreach ($plugin['resources'] as $res) {
            if (File::exists($res['target'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks whether the specified plugin is correctly installed.
     *
     * @param  array  $plugin  An array with the plugin data
     * @return bool
     */
    protected function pluginInstalled($plugin)
    {
        // Check whether the plugin has resources or not.

        if (! isset($plugin['resources'])) {
            return $this->resourceInstalled($plugin);
        }

        foreach ($plugin['resources'] as $res) {
            if (! $this->resourceInstalled($res)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks whether the specified resource is correctly installed.
     *
     * @param  array  $res  An array with the resource data
     * @return bool
     */
    protected function resourceInstalled($res)
    {
        // Check whether the resource is a file or a directory.

        if (File::isDirectory($res['source'])) {
            return (bool) CommandHelper::compareDirectories(
                $res['source'],
                $res['target'],
                $res['recursive'] ?? true,
                $res['ignore'] ?? []
            );
        }

        return CommandHelper::compareFiles($res['source'], $res['target']);
    }

    /**
     * Uninstalls the specified plugin.
     *
     * @param  array  $plugin  An array with the plugin data
     * @return void
     */
    protected function uninstallPlugin($plugin)
    {
        // If the plugin doensn't have resources, remove the main target
        // location folder.

        if (! isset($plugin['resources'])) {
            $this->uninstallResource($plugin);

            return;
        }

        // Otherwise, remove only the specified plugin resources.

        foreach ($plugin['resources'] as $res) {
            $this->uninstallResource($res);
        }
    }

    /**
     * Removes the specified resource (usually a folder).
     *
     * @param  array  $res  An array with the resource data
     * @return void
     */
    protected function uninstallResource($res)
    {
        $target = $res['target'];

        // Uninstall the specified resource. Note the target location is always
        // a folder. When the target folder does not exists, we consider the
        // resource as uninstalled.

        if (File::isDirectory($target)) {
            File::deleteDirectory($target);
        }
    }
}

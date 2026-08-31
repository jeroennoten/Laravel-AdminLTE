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
        'apexcharts' => [
            'name' => 'ApexCharts',
            'package' => 'apexcharts',
            'version' => '^4.3',
            'source' => 'apexcharts/dist',
            'target' => 'apexcharts',
            'recursive' => false,
            'ignore' => ['*.map', '*.esm.js'],
        ],
        'chartJs' => [
            'name' => 'Chart.js',
            'package' => 'chart.js',
            'version' => '^4.4',
            'source' => 'chart.js/dist',
            'target' => 'chart.js',
            'recursive' => false,
            'ignore' => ['*.map', '*.d.ts'],
        ],
        'datatables' => [
            'name' => 'Datatables (requires jQuery)',
            'package' => 'datatables.net',
            'version' => '^2.1',
            'resources' => [
                ['source' => 'datatables.net/js', 'target' => 'datatables/js'],
                ['source' => 'datatables.net-bs5/js', 'target' => 'datatables/js'],
                ['source' => 'datatables.net-bs5/css', 'target' => 'datatables/css'],
            ],
            'recursive' => false,
            'ignore' => ['*.map', '*.d.ts', '*.mjs'],
        ],
        'dropzone' => [
            'name' => 'Dropzone',
            'package' => 'dropzone',
            'version' => '^6.0',
            'source' => 'dropzone/dist',
            'target' => 'dropzone',
            'recursive' => false,
            'ignore' => ['*.map'],
        ],
        'easymde' => [
            'name' => 'EasyMDE (Markdown editor)',
            'package' => 'easymde',
            'version' => '^2.18',
            'source' => 'easymde/dist',
            'target' => 'easymde',
            'recursive' => false,
            'ignore' => ['*.map'],
        ],
        'filepond' => [
            'name' => 'FilePond',
            'package' => 'filepond',
            'version' => '^4.32',
            'source' => 'filepond/dist',
            'target' => 'filepond',
            'recursive' => false,
            'ignore' => ['*.map', '*.esm.js'],
        ],
        'flatpickr' => [
            'name' => 'Flatpickr (date, time and range picker)',
            'package' => 'flatpickr',
            'version' => '^4.6',
            'source' => 'flatpickr/dist',
            'target' => 'flatpickr',
            'recursive' => false,
            'ignore' => ['*.map'],
        ],
        'fullcalendar' => [
            'name' => 'FullCalendar',
            'package' => 'fullcalendar',
            'version' => '^6.1',
            'source' => 'fullcalendar/index.global.min.js',
            'target' => 'fullcalendar/index.global.min.js',
        ],
        'glightbox' => [
            'name' => 'GLightbox',
            'package' => 'glightbox',
            'version' => '^3.3',
            'source' => 'glightbox/dist',
            'target' => 'glightbox',
            'recursive' => true,
            'ignore' => ['*.map'],
        ],
        'imask' => [
            'name' => 'IMask (input masks)',
            'package' => 'imask',
            'version' => '^7.6',
            'source' => 'imask/dist',
            'target' => 'imask',
            'recursive' => false,
            'ignore' => ['*.map', '*.d.ts'],
        ],
        'jsvectormap' => [
            'name' => 'jsVectorMap',
            'package' => 'jsvectormap',
            'version' => '^1.6',
            'source' => 'jsvectormap/dist',
            'target' => 'jsvectormap',
            'recursive' => true,
            'ignore' => ['*.map'],
        ],
        'noUiSlider' => [
            'name' => 'noUiSlider',
            'package' => 'nouislider',
            'version' => '^15.8',
            'source' => 'nouislider/dist',
            'target' => 'nouislider',
            'recursive' => false,
            'ignore' => ['*.map', '*.mjs'],
        ],
        'pickr' => [
            'name' => 'Pickr (color picker)',
            'package' => '@simonwep/pickr',
            'version' => '^1.9',
            'source' => '@simonwep/pickr/dist',
            'target' => 'pickr',
            'recursive' => true,
            'ignore' => ['*.map', '*.es5.min.js.map'],
        ],
        'quill' => [
            'name' => 'Quill (rich text editor)',
            'package' => 'quill',
            'version' => '^2.0',
            'source' => 'quill/dist',
            'target' => 'quill',
            'recursive' => false,
            'ignore' => ['*.map'],
        ],
        'select2' => [
            'name' => 'Select2 (requires jQuery)',
            'package' => 'select2',
            'version' => '^4.1',
            'source' => 'select2/dist',
            'target' => 'select2',
            'recursive' => true,
            'ignore' => ['*.map'],
        ],
        'sortablejs' => [
            'name' => 'SortableJS',
            'package' => 'sortablejs',
            'version' => '^1.15',
            'resources' => [
                ['source' => 'sortablejs/Sortable.min.js', 'target' => 'Sortable.min.js'],
            ],
            'target' => 'sortablejs',
        ],
        'sweetalert2' => [
            'name' => 'SweetAlert2',
            'package' => 'sweetalert2',
            'version' => '^11.14',
            'source' => 'sweetalert2/dist',
            'target' => 'sweetalert2',
            'recursive' => false,
            'ignore' => ['*.map'],
        ],
        'tabulator' => [
            'name' => 'Tabulator (data tables)',
            'package' => 'tabulator-tables',
            'version' => '^6.3',
            'resources' => [
                ['source' => 'tabulator-tables/dist/js', 'target' => 'js'],
                ['source' => 'tabulator-tables/dist/css', 'target' => 'css'],
            ],
            'target' => 'tabulator',
            'recursive' => false,
            'ignore' => ['*.map'],
        ],
        'tomSelect' => [
            'name' => 'Tom Select',
            'package' => 'tom-select',
            'version' => '^2.3',
            'source' => 'tom-select/dist',
            'target' => 'tom-select',
            'recursive' => true,
            'ignore' => ['*.map', '*.scss', '*.ts'],
        ],
    ];

    /**
     * The set of AdminLTE v3 plugin keys that are not available anymore, with
     * the AdminLTE v4 replacement suggested for each one. A null replacement
     * means the plugin has no direct replacement (usually because Bootstrap
     * 5.3 or AdminLTE v4 covers the feature natively).
     *
     * @var array
     */
    protected $legacyPlugins = [
        'bootstrap4DualListbox' => 'tomSelect',
        'bootstrapColorpicker' => 'pickr',
        'bootstrapSlider' => 'noUiSlider',
        'bootstrapSwitch' => null,
        'bsCustomFileInput' => null,
        'datatablesPlugins' => 'datatables',
        'daterangepicker' => 'flatpickr',
        'ekkoLightbox' => 'glightbox',
        'fastclick' => null,
        'filterizr' => null,
        'flagIconCss' => null,
        'flot' => 'apexcharts',
        'icheckBootstrap' => null,
        'inputmask' => 'imask',
        'ionRangslider' => 'noUiSlider',
        'jqueryKnob' => 'apexcharts',
        'jqueryMapael' => 'jsvectormap',
        'jqueryMousewheel' => null,
        'jqueryUiTouchPunch' => null,
        'jqvmap' => 'jsvectormap',
        'jquery' => null,
        'jqueryUi' => null,
        'jqueryValidation' => null,
        'jsgrid' => 'tabulator',
        'moment' => null,
        'overlayScrollbars' => null,
        'paceProgress' => null,
        'raphael' => null,
        'simplemde' => 'easymde',
        'sparklines' => 'apexcharts',
        'summernote' => 'quill',
        'tempusdominusBootstrap4' => 'flatpickr',
        'toastr' => 'sweetalert2',
        'uplot' => 'apexcharts',
    ];

    /**
     * Create a new resource instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Fill the basic resource data.

        $this->description = 'The set of extra plugins recommended for AdminLTE v4';
        $this->required = false;

        // Define the base source folder of the plugins. Note AdminLTE v4 does
        // not bundle any third party plugin anymore, so the plugins are
        // published from the node modules folder of the application.

        $this->source = base_path('node_modules');

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
     * Checks whether the npm package that provides the specified plugin is
     * available on the application node modules folder.
     *
     * @param  string  $pluginKey  A plugin key
     * @return bool
     */
    public function pluginAvailable($pluginKey)
    {
        $plugin = $this->plugins[$pluginKey] ?? null;

        if (! isset($plugin)) {
            return false;
        }

        $package = $plugin['package'] ?? null;

        if (! isset($package)) {
            return false;
        }

        $path = $this->source.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $package);

        return File::isDirectory($path);
    }

    /**
     * Gets the npm command required to install the package that provides the
     * specified plugin.
     *
     * @param  string  $pluginKey  A plugin key
     * @return string|null
     */
    public function getInstallPackageCommand($pluginKey)
    {
        $plugin = $this->plugins[$pluginKey] ?? null;

        if (! isset($plugin['package'])) {
            return null;
        }

        $version = $plugin['version'] ?? null;
        $package = isset($version) ? "{$plugin['package']}@{$version}" : $plugin['package'];

        return "npm i {$package}";
    }

    /**
     * Gets the AdminLTE v4 replacement of a legacy (AdminLTE v3) plugin key.
     * It returns null when the plugin has no replacement, and false when the
     * specified key is not a legacy plugin key.
     *
     * @param  string  $pluginKey  A plugin key
     * @return string|null|false
     */
    public function getLegacyPluginReplacement($pluginKey)
    {
        if (! array_key_exists($pluginKey, $this->legacyPlugins)) {
            return false;
        }

        return $this->legacyPlugins[$pluginKey];
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

        // Uninstall the specified resource. When the target location does not
        // exists, we consider the resource as uninstalled. Note a resource may
        // be published as a single file too.

        if (File::isDirectory($target)) {
            File::deleteDirectory($target);
        } elseif (File::exists($target)) {
            File::delete($target);
        }
    }
}

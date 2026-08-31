<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

class PluginsCatalog
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
     * Gets the data of the available plugins, or of a single one.
     *
     * @param  string  $pluginKey  A plugin key
     * @return array
     */
    public function get($pluginKey = null)
    {
        if (! empty($pluginKey)) {
            return $this->plugins[$pluginKey] ?? [];
        }

        return $this->plugins;
    }

    /**
     * Gets the AdminLTE v4 replacement of a legacy (AdminLTE v3) plugin key.
     * It returns null when the plugin has no replacement, and false when the
     * specified key is not a legacy plugin key.
     *
     * @param  string  $pluginKey  A plugin key
     * @return string|null|false
     */
    public function getLegacyReplacement($pluginKey)
    {
        if (! array_key_exists($pluginKey, $this->legacyPlugins)) {
            return false;
        }

        return $this->legacyPlugins[$pluginKey];
    }
}

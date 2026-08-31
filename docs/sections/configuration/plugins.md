## Plugins

> [!Important]
> AdminLTE v4 does not bundle any third party plugin and dropped jQuery. The plugins shipped on the default configuration are the AdminLTE v4 recommended replacements of the old jQuery widgets (for example **Tom Select** instead of `bootstrap-select`, **Tabulator** instead of the jQuery datatables, **Flatpickr** instead of `daterangepicker`, **Quill** instead of `Summernote` and **noUiSlider** instead of `bootstrap-slider`). **Select2** and the **Krajee file input** are still available, but they keep requiring jQuery.

The `plugins` configuration allows you to setup which additional plugins should be included into your blade views. Every plugin is represented with an array that can have the next attributes:

- `active`: Boolean to enable/disable the plugin injection on every blade file. When configured to `false` you will need to manually include the plugin on your blade files by using the directive `@section('plugins.PluginName', true)`.

> [!Tip]
> When a plugin is `active` by default, you can disable the injection of the plugin on a particular blade file using the directive `@section('plugins.PluginName', false)`.

- `files`: An array specifying the plugin files to be included. Each file should be described with another array that can have the next properties:

  - `type`: The type of the file, values can be `'css'` or `'js'` strings.
  - `location`: String with the path or url of the file.
  - `asset`: Boolean to indicate if the location should be internally created by using the Laravel's [asset()](https://laravel.com/docs/helpers#method-asset) function.
  - `defer`: Boolean to indicate if [defer attribute](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/script#attr-defer) should be added to the `script` file. Note this only works for `type='js'` files.
  - `rtl`: String with an alternative path or url used instead of the `location` when the [RTL mode](./layout_and_styling.md#rtl-mode) is active.

The plugin **active** status and the **files** array (even empty) are always required attributes for a plugin. The **files**, when added, need to have a **type** attribute (`'js'` or `'css'` string), an **asset** attribute (`true` or `false`) and also a **location** (`string`) specifying the path or url of the file. When the **asset** attribute is set to `true`, the **location** will be output using the Laravel's `asset()` helper function.

> [!Note]
> The **asset** attribute is optional. There is no need to define it when you expect to setup it to the `false` value.

> [!Warning]
> A `{version}` placeholder inside a plugin **location** (or inside its `rtl` counterpart) is substituted by the installed **AdminLTE** version, exactly as on the [assets](./other.md#the-adminlte-version-of-the-cdn-locations) configuration. This is useful for the plugins that point to an asset of the AdminLTE distribution, like the shipped `Select2` compatibility theme.

By default the [DataTables](https://datatables.net/), [Select2](https://select2.org/), [Tom Select](https://tom-select.js.org/), [Tabulator](https://tabulator.info/), [Flatpickr](https://flatpickr.js.org/), [Quill](https://quilljs.com/), [noUiSlider](https://refreshless.com/nouislider/), [ChartJS](https://www.chartjs.org/), [Pace](https://codebyzach.github.io/pace/) and [SweetAlert2](https://sweetalert2.github.io/) plugins are configured out-of-the-box with `CDN` files but they are not active. You can activate them by changing the `active` attribute to load it on every page, or instead by adding a `@section(...)` directive in some specific blade file to automatically inject their files. For example, to inject the **Datatables** plugin you can use the following code at the begin of your blade template:

```blade
@section('plugins.Datatables', true)
```

As an example, the current **Datatables** configuration for the package is:

```php
'plugins' => [
    ...
    'Datatables' => [
        'active' => false,
        'files' => [
            [
                'type' => 'js',
                'asset' => false,
                'location' => '//cdn.datatables.net/2.1.8/js/dataTables.min.js',
            ],
            [
                'type' => 'js',
                'asset' => false,
                'location' => '//cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js',
            ],
            [
                'type' => 'css',
                'asset' => false,
                'location' => '//cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css',
            ],
        ],
    ],
    ...
],
```

You can add new plugins by extending the `plugins` array configuration option, using the below structure as reference:

```php
'plugins' => [
    ...
    'Plugin Name' => [
        'active' => true,
        'files' => [
            [
                'type' => 'js',
                'asset' => false,
                'location' => '//cdn.plugin.net/plugin.min.js',
            ],
            [
                'type' => 'css',
                'asset' => true,
                'location' => 'css/plugin.min.css',
            ],
        ],
    ],
]
```

In the previous example, the plugin will be injected on every blade file. The new plugin consists of a Javascript file available via CDN and a stylesheet that will be located using the `asset()` function. Usually, if you haven't changed the Laravel `ASSET_URL` configuration, then the `asset()` function will point to the `public` folder of your Laravel project.

### Pace Plugin Configuration

You can change the Pace plugin theme by modifying the `css` file location when using the `CDN` injection.

```php
'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.2.4/themes/{{color}}/pace-theme-{{theme}}.min.css',
```

- __Available colors are__: black, blue (default), green, orange, pink, purple, red, silver, white & yellow
- __Available themes are__: barber-shop, big-counter, bounce, center-atom, center-circle, center-radar (default), center-simple, corner-indicator, fill-left, flash, flat-top, loading-bar, mac-osx, minimal

### Install a Plugin with the Artisan Command

There is a set of predefined plugins that are part of the underlying **AdminLTE** template and that you can install using the [artisan command](/sections/overview/artisan_console_commands#the-adminlte-plugins-command) provided by this package. You can view the list of available plugins using the next command:

```sh
php artisan adminlte:plugins
```

The result will be something like next, depending on the installation status of each plugin:

```sh
Checking the plugins installation ...
 19/19 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100%
All plugins checked succesfully!

+-------------------------------------+-------------------------+---------------+
| Plugin Name                         | Plugin Key              | Status        |
+-------------------------------------+-------------------------+---------------+
| ApexCharts                          | apexcharts              | Not Installed |
| Chart.js                            | chartJs                 | Not Installed |
| Datatables (requires jQuery)        | datatables              | Not Installed |
| Dropzone                            | dropzone                | Not Installed |
| EasyMDE (Markdown editor)           | easymde                 | Not Installed |
| FilePond                            | filepond                | Not Installed |
| Flatpickr (date, time and range)    | flatpickr               | Installed     |
| FullCalendar                        | fullcalendar            | Not Installed |
| GLightbox                           | glightbox               | Not Installed |
| IMask (input masks)                 | imask                   | Not Installed |
| jsVectorMap                         | jsvectormap             | Not Installed |
| noUiSlider                          | noUiSlider              | Installed     |
| Pickr (color picker)                | pickr                   | Not Installed |
| Quill (rich text editor)            | quill                   | Installed     |
| Select2 (requires jQuery)           | select2                 | Not Installed |
| SortableJS                          | sortablejs              | Not Installed |
| SweetAlert2                         | sweetalert2             | Not Installed |
| Tabulator (data tables)             | tabulator               | Not Installed |
| Tom Select                          | tomSelect               | Not Installed |
+-------------------------------------+-------------------------+---------------+

Status legends:
+---------------+----------------------------------------------------------------------------------------+
| Status        | Description                                                                            |
+---------------+----------------------------------------------------------------------------------------+
| Installed     | The plugin is installed and matches with the default package plugin                    |
| Mismatch      | The installed plugin mismatch the package plugin (update available or plugin modified) |
| Not Installed | The plugin is not installed                                                            |
+---------------+----------------------------------------------------------------------------------------+
```

To install one of these plugins locally, you need to use the **plugin key** listed in the above table. Note the plugin files come from the `node_modules` folder of your project, so install the related npm package first, for example, to install **Flatpickr**:

```sh
npm i flatpickr
php artisan adminlte:plugins install --plugin=flatpickr
```

All the plugins will be installed in the `public/vendor` folder of your Laravel project. Once they are installed, you need to setup their configuration as explained before in order to use the plugins on the blade files.

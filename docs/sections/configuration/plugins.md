## Plugins

The `plugins` configuration allows you to setup which additional plugins should be included into your blade views. Every plugin is represented with an array that can have the next attributes:

- `active`: Boolean to enable/disable the plugin injection on every blade file. When configured to `false` you will need to manually include the plugin on your blade files by using the directive `@section('plugins.PluginName', true)`.

> [!Tip]
> From package version <Badge type="tip">v3.8.5</Badge> and over, when a plugin is `active` by default, you can disable the injection of the plugin on a particular blade file using the directive `@section('plugins.PluginName', false)`.

- `files`: An array specifying the plugin files to be included. Each file should be described with another array that can have the next properties:

  - `type`: The type of the file, values can be `'css'` or `'js'` strings.
  - `location`: String with the path or url of the file.
  - `asset`: Boolean to indicate if the location should be internally created by using the Laravel's [asset()](https://laravel.com/docs/helpers#method-asset) function.
  - `defer`: Boolean to indicate if [defer attribute](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/script#attr-defer) should be added to the `script` file. Note this only works for `type='js'` files.

The plugin **active** status and the **files** array (even empty) are always required attributes for a plugin. The **files**, when added, need to have a **type** attribute (`'js'` or `'css'` string), an **asset** attribute (`true` or `false`) and also a **location** (`string`) specifying the path or url of the file. When the **asset** attribute is set to `true`, the **location** will be output using the Laravel's `asset()` helper function.

> [!Note]
> For package versions greater than <Badge type="tip">v3.5.0</Badge>, the **asset** attribute is optional. There is no need to define it when you expect to setup it to the `false` value.

By default the [DataTables](https://datatables.net/), [Select2](https://select2.github.io/), [ChartJS](https://www.chartjs.org/), [Pace](http://github.hubspot.com/pace/docs/welcome/) and [SweetAlert2](https://sweetalert2.github.io/) plugins are configured out-of-the-box with `CDN` files but they are not active. You can activate them by changing the `active` attribute to load it on every page, or instead by adding a `@section(...)` directive in some specific blade file to automatically inject their files. For example, to inject the **Datatables** plugin you can use the following code at the begin of your blade template:

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
                'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
            ],
            [
                'type' => 'js',
                'asset' => false,
                'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
            ],
            [
                'type' => 'css',
                'asset' => false,
                'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
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
'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/{{color}}/pace-theme-{{theme}}.min.css',
```

- __Available colors are__: black, blue (default), green, orange, pink, purple, red, silver, white & yellow
- __Available themes are__: barber-shop, big-counter, bounce, center-atom, center-circle, center-radar (default), center-simple, corner-indicator, fill-left, flash, flat-top, loading-bar, mac-osx, minimal

### Install a Plugin with the Artisan Command

There is a set of predefined plugins that are part of the underlying **AdminLTE** template and that you can install using the [artisan command](/sections/overview/artisan_console_commands#the-adminlteplugins-command) provided by this package. You can view the list of available plugins using the next command:

```sh
php artisan adminlte:plugins
```

The result will be something like next, depending on the installation status of each plugin:

```sh
Checking the plugins installation ...
 31/31 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100%
All plugins checked succesfully!

+-------------------------------------+-------------------------+---------------+
| Plugin Name                         | Plugin Key              | Status        |
+-------------------------------------+-------------------------+---------------+
| Bootstrap4 Dual Listbox             | bootstrap4DualListbox   | Not Installed |
| Bootstrap Colorpicker               | bootstrapColorpicker    | Installed     |
| Bootstrap Slider                    | bootstrapSlider         | Installed     |
| Bootstrap Switch                    | bootstrapSwitch         | Installed     |
| bs-custom-file-input                | bsCustomFileInput       | Installed     |
| Chart.js                            | chartJs                 | Not Installed |
| Datatables                          | datatables              | Installed     |
| Datatables Plugins                  | datatablesPlugins       | Installed     |
| Date Range Picker                   | daterangepicker         | Installed     |
| Ekko Lightbox                       | ekkoLightbox            | Not Installed |
| FastClick                           | fastclick               | Not Installed |
| Filterizr                           | filterizr               | Not Installed |
| Flag Icon Css                       | flagIconCss             | Not Installed |
| Flot                                | flot                    | Not Installed |
| Fullcalendar                        | fullcalendar            | Installed     |
| iCheck Bootstrap                    | icheckBootstrap         | Installed     |
| InputMask                           | inputmask               | Not Installed |
| Ion.RangeSlider                     | ionRangslider           | Not Installed |
| jQuery Knob                         | jqueryKnob              | Not Installed |
| jQuery Mapael                       | jqueryMapael            | Not Installed |
| jQuery UI                           | jqueryUi                | Not Installed |
| jQuery Validation                   | jqueryValidation        | Not Installed |
| jQVMap                              | jqvmap                  | Not Installed |
| jsGrid                              | jsgrid                  | Not Installed |
| Pace Progress                       | paceProgress            | Not Installed |
| Select 2 with Bootstrap 4 Theme     | select2                 | Installed     |
| Sparklines                          | sparklines              | Not Installed |
| Summernote                          | summernote              | Installed     |
| Sweetalert 2 with Bootstrap 4 Theme | sweetalert2             | Installed     |
| Tempus Dominus for Bootstrap 4      | tempusdominusBootstrap4 | Installed     |
| Toastr                              | toastr                  | Not Installed |
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

To install one of these plugins locally, you need to use the **plugin key** listed in the above table, for example, to install **Tempus Dominus** you may execute the next command:

```sh
php artisan adminlte:plugins install --plugin=tempusdominusBootstrap4
```

All the plugins will be installed in the `public` folder of your Laravel project. Once they are installed, you need to setup their configuration as explained before in order to use the plugins on the blade files.

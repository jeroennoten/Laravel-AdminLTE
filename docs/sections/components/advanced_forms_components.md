These components are expected to be used within a `form` element. They can be used to generate forms with advanced input fields. At next you can see the list of available components:

|Components
|-----------
| [DateRange](#daterange), [InputColor](#inputcolor), [InputDate](#inputdate), [InputFileKrajee](#inputfilekrajee), [InputSlider](#inputslider), [InputSwitch](#inputswitch), [SelectBs](#selectbs), [TextEditor](#texteditor)

# DateRange

> [!Important]
> This component requires the [Date Range Picker](https://www.daterangepicker.com/) and [Moment](https://momentjs.com/) plugins, so be sure to first install and setup these plugins on the package configuration file. Read more on the [plugins configuration section](/sections/configuration/plugins). Both plugins can be installed locally using the `php artisan adminlte:plugins install --plugin=daterangepicker` command, after that you will need to include the plugins files on the configuration file.

This component represents a **date-range** selector and extends from the base [Input Group Component](/sections/components/basic_forms_components#input-group-component), so all the attributes from it will be inherited. The component also defines the next additional attributes:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
config | Array with the plugin configuration parameters | array | `[]` | no
enable-default-ranges | Enables a default set of ranges option. The string value, if any, will be used as the initial date range | string | null | no
enable-old-support | Enable auto retrievement and filling with the submitted value in case of validation errors | any | `null` | no

> [!Important]
> Please, note the `enable-old-support` property is only available for package version <Badge type="tip">> v3.7.2</Badge> and offers a similar behavior as using the Laravel `old()` helper explicitly by your own.

By using the `enable-default-ranges` property you can configure the initial date range by assigning a string to the option. The current set of accepted values are: `'Today'`, `'Yesterday'`, `'Last 7 Days'`, `'Last 30 Days'`, `'This Month'` or `'Last Month'`.

The available configuration (for the `config` option) are those explained on the [plugin documentation](https://www.daterangepicker.com/#options). You can also assign a `javascript` expression to a particular configuration option by prepending the `js:` string token. All other attributes you define will be inserted directly on the underlying `input` element.

> [!Note]
> You may also configure the plugin from `Javascript/jQuery` using the `id` or `name` property of the component as the selector for the `id` attribute, instead of using the `config` property of the component.

### Examples

```blade
{{-- Minimal --}}
<x-adminlte-date-range name="drBasic"/>

{{-- Disabled with predefined config --}}
@php
$config = [
    "timePicker" => true,
    "startDate" => "js:moment().subtract(6, 'days')",
    "endDate" => "js:moment()",
    "locale" => ["format" => "YYYY-MM-DD HH:mm"],
];
@endphp
<x-adminlte-date-range name="drDisabled" :config="$config" disabled/>

{{-- Prepend slot and custom ranges enables --}}
<x-adminlte-date-range name="drCustomRanges" enable-default-ranges="Last 30 Days">
    <x-slot name="prependSlot">
        <div class="input-group-text bg-gradient-info">
            <i class="fas fa-calendar-alt"></i>
        </div>
    </x-slot>
</x-adminlte-date-range>

{{-- Label and placeholder --}}
<x-adminlte-date-range name="drPlaceholder" placeholder="Select a date range..."
    label="Date Range">
    <x-slot name="prependSlot">
        <div class="input-group-text bg-gradient-danger">
            <i class="far fa-lg fa-calendar-alt"></i>
        </div>
    </x-slot>
</x-adminlte-date-range>
@push('js')<script>$(() => $("#drPlaceholder").val(''))</script>@endpush

{{-- SM size with single date/time config --}}
@php
$config = [
    "singleDatePicker" => true,
    "showDropdowns" => true,
    "startDate" => "js:moment()",
    "minYear" => 2000,
    "maxYear" => "js:parseInt(moment().format('YYYY'),10)",
    "timePicker" => true,
    "timePicker24Hour" => true,
    "timePickerSeconds" => true,
    "cancelButtonClasses" => "btn-danger",
    "locale" => ["format" => "YYYY-MM-DD HH:mm:ss"],
];
@endphp
<x-adminlte-date-range name="drSizeSm" label="Date/Time" igroup-size="sm" :config="$config">
    <x-slot name="appendSlot">
        <div class="input-group-text bg-dark">
            <i class="fas fa-calendar-day"></i>
        </div>
    </x-slot>
</x-adminlte-date-range>

{{-- LG size with some config and add-ons --}}
@php
$config = [
    "showDropdowns" => true,
    "startDate" => "js:moment()",
    "endDate" => "js:moment().subtract(1, 'days')",
    "minYear" => 2000,
    "maxYear" => "js:parseInt(moment().format('YYYY'),10)",
    "timePicker" => true,
    "timePicker24Hour" => true,
    "timePickerIncrement" => 30,
    "locale" => ["format" => "YYYY-MM-DD HH:mm"],
    "opens" => "center",
];
@endphp
<x-adminlte-date-range name="drSizeLg" label="Date/Time Range" label-class="text-primary"
    igroup-size="lg" :config="$config">
    <x-slot name="prependSlot">
        <div class="input-group-text text-primary">
            <i class="fas fa-lg fa-calendar-alt"></i>
        </div>
    </x-slot>
    <x-slot name="appendSlot">
        <x-adminlte-button theme="outline-primary" label="Review" icon="fas fa-lg fa-clipboard-check"/>
    </x-slot>
</x-adminlte-date-range>
```

Use the next image as reference to check how every example is rendered. Please, note in the image the elements were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them.

![Date Range Component](/imgs/components/advanced_forms_components/date-range-component.png)

### Required Plugin Configuration

To use this component you need to install and enable the required [Date Range Picker](https://www.daterangepicker.com/) and [Moment](https://momentjs.com/) plugins. You can install both plugins locally using the next command:

```sh
php artisan adminlte:plugins install --plugin=daterangepicker
```

After installed, you can use the next plugin configuration as a reference:

```php
'plugins' => [
    ...
    'DateRangePicker' => [
        'active' => false,
        'files' => [
            [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/moment/moment.min.js',
            ],
            [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/daterangepicker/daterangepicker.js',
            ],
            [
                'type' => 'css',
                'asset' => true,
                'location' => 'vendor/daterangepicker/daterangepicker.css',
            ],
        ],
    ],
    ...
],
```

Finally, you need to use the `@section('plugins.DateRangePicker', true)` sentence on the blade file where you expect to use the component. Alternatively, you can choose to use the plugin files from a `CDN` instead of installing it locally.

# InputColor

> [!Important]
> This component requires the [Bootstrap Colorpicker](https://itsjavi.com/bootstrap-colorpicker/index.html) plugin, so be sure to first setup the plugin on the package configuration file. Read more on the [plugins configuration section](/sections/configuration/plugins). The plugin can be installed locally using `php artisan adminlte:plugins install --plugin=bootstrapColorpicker` command, after that you will need to include the plugins files on the configuration file.

This component represents a **color picker** input and extends from the base [Input Group Component](/sections/components/basic_forms_components#input-group-component), so all the attributes from it will be inherited. When you enable an `addon` icon, this icon will automatically be set to show the picked color. The component also defines next additional attributes:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
config | Array with the plugin configuration parameters | array | `[]` | no
enable-old-support | Enable auto retrievement and filling with the submitted value in case of validation errors | any | `null` | no

> [!Important]
> Please, note the `enable-old-support` property is only available for package version <Badge type="tip">> v3.7.2</Badge> and offers a similar behavior as using the Laravel `old()` helper explicitly by your own.

The available plugin configuration are those explained on the [plugin documentation](https://itsjavi.com/bootstrap-colorpicker/module-options.html). All other attributes you define will be inserted directly on the underlying `input` element, so you can also use the `data-*` attributes to configure the plugin.

> [!Note]
> Alternatively, you can make all the plugin configuration from `Javascript/jQuery` using the `id` or `name` property of the component as the selector for the `id` attribute, instead of using the `config` property of the component.

### Examples

```blade
{{-- Minimal --}}
<x-adminlte-input-color name="icBasic"/>

{{-- Disabled with predefined value --}}
<x-adminlte-input-color name="icDisabled" value="rgb(50, 100, 50)" disabled/>

{{-- Append slot and data-* config --}}
<x-adminlte-input-color name="icAddon" data-color="rgb(50, 100, 150)" data-format='hex'
    data-horizontal=true>
    <x-slot name="appendSlot">
        <div class="input-group-text">
            <i class="fas fa-lg fa-square"></i>
        </div>
    </x-slot>
</x-adminlte-input-color>

{{-- Label and placeholder --}}
<x-adminlte-input-color name="icPlaceholder" placeholder="Select a color..." label="Color">
    <x-slot name="prependSlot">
        <div class="input-group-text bg-gradient-light">
            <i class="fas fa-lg fa-tint"></i>
        </div>
    </x-slot>
</x-adminlte-input-color>

{{-- SM size with custom config --}}
@php
$config = [
    "color" => "#000000",
    "horizontal" => true,
    "format" => null,
];
@endphp
<x-adminlte-input-color name="icSizeSm" label="Fill Color" igroup-size="sm" :config="$config">
    <x-slot name="appendSlot">
        <div class="input-group-text bg-white">
            <i class="fas fa-lg fa-fill"></i>
        </div>
    </x-slot>
</x-adminlte-input-color>

{{-- LG size with predefined color extension --}}
@php
$config = [
    "extensions" => [
        [
            "name" => 'swatches',
            "options" => [
                "colors" => [
                    'black'   => '#000000',
                    'gray'    => '#888888',
                    'white'   => '#ffffff',
                    'red'     => '#ff0000',
                    'default' => '#777777',
                    'primary' => '#337ab7',
                    'success' => '#5cb85c',
                    'info'    => '#5bc0de',
                    'warning' => '#f0ad4e',
                    'danger'  => '#d9534f'
                ],
                "namesAsValues" => true,
            ]
        ]
    ]
];
@endphp
<x-adminlte-input-color name="icSizeLg" placeholder="Choice a color..."
    label="Brush Color" label-class="text-primary" igroup-size="lg" :config="$config">
    <x-slot name="appendSlot">
        <div class="input-group-text">
            <i class="fas fa-lg fa-brush"></i>
        </div>
    </x-slot>
</x-adminlte-input-color>
```

Use the next image as reference to check how every example is rendered. Please, note in the image the elements were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them.

![Input Color Component](/imgs/components/advanced_forms_components/input-color-component.png)

### Required Plugin Configuration

To use this component you need to install and enable the required [Bootstrap Colorpicker](https://itsjavi.com/bootstrap-colorpicker/index.html) plugin. You can install the plugin locally using the next command:

```sh
php artisan adminlte:plugins install --plugin=bootstrapColorpicker
```

After installed, you can use the next plugin configuration as a reference:

```php
'plugins' => [
    ...
    'BootstrapColorpicker' => [
        'active' => false,
        'files' => [
            [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js',
            ],
            [
                'type' => 'css',
                'asset' => true,
                'location' => 'vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css',
            ],
        ],
    ],
    ...
],
```

Finally, you need to use the `@section('plugins.BootstrapColorpicker', true)` sentence on the blade file where you expect to use the component. Alternatively, you can choose to use the plugins files from a `CDN` instead of installing it locally.

# InputDate

> [!Important]
> This component requires the [Tempus Dominus](https://tempusdominus.github.io/bootstrap-4/) and [Moment](https://momentjs.com/) plugins, so be sure to first install and setup these plugins on the package configuration file. Read more on the [plugins configuration section](/sections/configuration/plugins). Both plugins can be installed locally using `php artisan adminlte:plugins install --plugin=tempusdominusBootstrap4` command, after that you will need to include the plugins files on the configuration file.

This component represents a **date and time** selector and extends from the base [Input Group Component](/sections/components/basic_forms_components#input-group-component), so all the attributes from it will be inherited. The component also defines next additional attributes:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
config | Array with the plugin configuration parameters | array | `[]` | no
enable-old-support | Enable auto retrievement and filling with the submitted value in case of validation errors | any | `null` | no

> [!Important]
> Please, note the `enable-old-support` property is only available for package version <Badge type="tip">> v3.7.2</Badge> and offers a similar behavior as using the Laravel `old()` helper explicitly by your own.

The available plugin configuration (for the `config` option) are those explained on the [plugin documentation](https://tempusdominus.github.io/bootstrap-4/Options/). You can assign a `javascript` expression to a particular configuration prepending the `js:` string token. All other attributes you define will be inserted directly on the underlying `input` element.

> [!Note]
> Alternatively, You can make all the configuration from `Javascript/jQuery` using the `id` or `name` property of the component as the selector for the `id` attribute, instead of using the `config` property of the component. However, you may need to invoke the [destroy](https://getdatepicker.com/5-4/Functions/) method first.

### Examples

```blade
{{-- Minimal --}}
<x-adminlte-input-date name="idBasic"/>

{{-- Disabled with predefined value --}}
@php
$config = ['format' => 'YYYY-MM-DD'];
@endphp
<x-adminlte-input-date name="idDisabled" value="2020-10-04" :config="$config" disabled/>

{{-- Placeholder, time only and prepend icon --}}
@php
$config = ['format' => 'LT'];
@endphp
<x-adminlte-input-date name="idTimeOnly" :config="$config" placeholder="Choose a time...">
    <x-slot name="prependSlot">
        <div class="input-group-text bg-gradient-info">
            <i class="fas fa-clock"></i>
        </div>
    </x-slot>
</x-adminlte-input-date>

{{-- Placeholder, date only and append icon --}}
@php
$config = ['format' => 'L'];
@endphp
<x-adminlte-input-date name="idDateOnly" :config="$config" placeholder="Choose a date...">
    <x-slot name="appendSlot">
        <div class="input-group-text bg-gradient-danger">
            <i class="fas fa-calendar-alt"></i>
        </div>
    </x-slot>
</x-adminlte-input-date>

{{-- With Label --}}
@php
$config = ['format' => 'DD/MM/YYYY HH:mm'];
@endphp
<x-adminlte-input-date name="idLabel" :config="$config" placeholder="Choose a date..."
    label="Datetime" label-class="text-primary">
    <x-slot name="appendSlot">
        <x-adminlte-button theme="outline-primary" icon="fas fa-lg fa-birthday-cake"
            title="Set to Birthday"/>
    </x-slot>
</x-adminlte-input-date>

{{-- SM size, restricted to current month and week days --}}
@php
$config = [
    'format' => 'YYYY-MM-DD HH.mm',
    'dayViewHeaderFormat' => 'MMM YYYY',
    'minDate' => "js:moment().startOf('month')",
    'maxDate' => "js:moment().endOf('month')",
    'daysOfWeekDisabled' => [0, 6],
];
@endphp
<x-adminlte-input-date name="idSizeSm" label="Working Datetime" igroup-size="sm"
    :config="$config" placeholder="Choose a working day...">
    <x-slot name="appendSlot">
        <div class="input-group-text bg-dark">
            <i class="fas fa-calendar-day"></i>
        </div>
    </x-slot>
</x-adminlte-input-date>

{{-- LG size with multiple datetimes --}}
@php
$config = [
    'allowMultidate' => true,
    'multidateSeparator' => ',',
    'format' => 'DD MMM YYYY',
];
@endphp
<x-adminlte-input-date name="idSizeLg" label="Multiple Datetimes" label-class="text-danger"
    igroup-size="lg" placeholder="Multidate..." :config="$config">
    <x-slot name="prependSlot">
        <div class="input-group-text bg-white">
            <i class="far fa-lg fa-calendar-alt text-danger"></i>
        </div>
    </x-slot>
</x-adminlte-input-date>
```

Use the next image as reference to check how every example is rendered. Please, note in the image the elements were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them.

![Input Date Component](/imgs/components/advanced_forms_components/input-date-component.png)

### Required Plugin Configuration

To use this component you need to install and enable the required [Tempus Dominus](https://tempusdominus.github.io/bootstrap-4/) and [Moment](https://momentjs.com/) plugins. You can install both plugins locally using the next command:

```sh
php artisan adminlte:plugins install --plugin=tempusdominusBootstrap4
```

After installed, you can use the next plugin configuration as a reference:

```php
'plugins' => [
    ...
    'TempusDominusBs4' => [
        'active' => false,
        'files' => [
            [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/moment/moment.min.js',
            ],
            [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js',
            ],
            [
                'type' => 'css',
                'asset' => true,
                'location' => 'vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',
            ],
        ],
    ],
    ...
],
```

Finally, you need to use the `@section('plugins.TempusDominusBs4', true)` sentence on the blade file where you expect to use the component. Alternatively, you can choose to use the plugin files from a `CDN` instead of installing it locally.

# InputFileKrajee

> [!Important]
> This component is only available from <Badge type="tip">v3.9.0</Badge> and requires the [krajee-bootstrap-file-input](https://plugins.krajee.com/file-input) plugin, so be sure to first setup the plugin on the package configuration file, read more on the [plugins configuration section](/sections/configuration/plugins). The plugin can be installed manually inside the `public/vendor` folder or you can use its `CDN` files (read the instructions on the plugin site).

This component represents an advanced **file-input** component with file preview and other features. The component extends from the base [Input Group Component](/sections/components/basic_forms_components#input-group-component), so all the attributes from it will be inherited. The component also defines the next additional attributes:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
config | Array with the plugin configuration parameters | array | `[]` | no
preset-mode | Used to make specific plugin configuration for some particular scenarios. The current supported set of values are: `'avatar'` and `'minimalist'` | string | `null` | no

> [!Note]
> The `enable-old-support` attribute is not supported here, due to **security** reasons related to file inputs.

The available plugin configuration are those explained on the [plugin documentation](https://plugins.krajee.com/file-input#options). All other attributes you define will be inserted directly on the underlying `input` element, so you can also use `data-* attributes` to configure the plugin.

> [!Note]
> Alternatively, you can make all the configuration from `Javascript/jQuery` using the `id` or `name` property of the component as the selector for the `id` attribute, instead of using the `config` property of the component. However, you may need to invoke the [destroy](https://plugins.krajee.com/file-input/plugin-methods#destroy) method first.

### Examples

```blade
{{-- Basic --}}
<x-adminlte-input-file-krajee name="kifBasic"/>

{{-- With placeholder, SM size multiple and data-* options --}}
<x-adminlte-input-file-krajee id="kifPholder" name="kifPholder[]"
    igroup-size="sm" data-msg-placeholder="Choose multiple files..."
    data-show-cancel="false" data-show-close="false" multiple/>

{{-- With a label, some plugin config, and error feedback disabled --}}
@php
$config = [
    'allowedFileTypes' => ['text', 'office', 'pdf'],
    'browseOnZoneClick' => true,
    'theme' => 'explorer-fa5',
];
@endphp
<x-adminlte-input-file-krajee name="kifLabel" label="Upload document file"
    data-msg-placeholder="Choose a text, office or pdf file..."
    label-class="text-primary" :config="$config" disable-feedback/>

{{-- With the avatar preset-mode --}}
<x-adminlte-input-file-krajee name="kifAvatar" label="Set Profile Picture"
    preset-mode="avatar"/>

{{-- With the minimalist preset-mode --}}
<x-adminlte-input-file-krajee name="kifMinimalist" label="Choose a file"
    preset-mode="minimalist"/>
```

Use the next images as reference to check how every example is rendered. Please, note in the images the elements were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them.

![InputFileKrajee Component 1](/imgs/components/advanced_forms_components/inputfilekrajee-component-1.png)
![InputFileKrajee Component 2](/imgs/components/advanced_forms_components/inputfilekrajee-component-2.png)

### Required Plugin Configuration

To use this component you need to install and enable the required [krajee-file-input](https://plugins.krajee.com/file-input) plugin. You can manually download and install the plugin locally on the `public/vendor/krajee-fileinput/` folder. Please, note there is no artisan command to install this plugin.

After installed on `public/vendor/krajee-fileinput/` folder, you can use the next plugin configuration as a reference. However, note the set of included plugin files may change depending on your needs:

```php
'plugins' => [
    ...
    'KrajeeFileinput' => [
        'active' => false,
        'files' => [
            [
                'type' => 'css',
                'asset' => true,
                'location' => 'vendor/krajee-fileinput/css/fileinput.min.css',
            ],
            [
                'type' => 'css',
                'asset' => true,
                'location' => 'vendor/krajee-fileinput/themes/explorer-fa5/theme.min.css',
            ],
            [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/krajee-fileinput/js/fileinput.min.js',
            ],
            [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/krajee-fileinput/themes/fa5/theme.min.js',
            ],
            [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/krajee-fileinput/themes/explorer-fa5/theme.min.js',
            ],
            [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/krajee-fileinput/js/locales/es.js',
            ],
        ],
    ],
    ...
],
```

Finally, you need to use the `@section('plugins.KrajeeFileinput', true)` sentence on the blade file where you expect to use the component. Alternatively, you can choose to use the plugin files from a `CDN` instead of installing the plugin locally.

# InputSlider

> [!Important]
> This component requires the [bootstrap-slider](https://github.com/seiyria/bootstrap-slider) plugin, so be sure to first setup the plugin on the package configuration file. Read more on the [plugins configuration section](/sections/configuration/plugins). The plugin can be installed locally using `php artisan adminlte:plugins install --plugin=bootstrapSlider` command, after that you will need to include the plugins files on the configuration file.

This component represents a **slider** input and extends from the base [Input Group Component](/sections/components/basic_forms_components#input-group-component), so all the attributes from it will be inherited. The component also defines next additional attributes:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
color | The slider color. One of the available `html` colors | string | `null` | no
config | Array with the plugin configuration parameters | array | `[]` | no
enable-old-support | Enable auto retrievement and filling with the submitted value in case of validation errors | any | `null` | no

> [!Important]
> Please, note the `enable-old-support` property is only available for package version <Badge type="tip">> v3.7.2</Badge> and offers a similar behavior as using the Laravel `old()` helper explicitly by your own.

The available plugin configuration (for `config` attribute) are those explained on the [plugin documentation](https://github.com/seiyria/bootstrap-slider#options). All other attributes you define will be inserted directly on the underlying `input` element, so you can also use the `data-slider-*` attributes to configure the plugin. Also, the standard `value`, `min`, `max`, `step` and `disabled` attributes are mapped as shortcuts of the respective plugin options: `data-slider-value`, `data-slider-min`, `data-slider-max`, `data-slider-step` and `data-slider-enabled`.

> [!Note]
> Alternatively, you can make all the plugin configuration from `Javascript/jQuery` using the `id` or `name` property of the component as the selector for the `id` attribute, instead of using the `config` property of the component. However, you may need to invoke the [destroy](https://github.com/seiyria/bootstrap-slider#functions) method first.

### Examples

```blade
{{-- Minimal --}}
<x-adminlte-input-slider name="isMin"/>

{{-- Disabled --}}
<x-adminlte-input-slider name="isDisabled" disabled/>

{{-- With min, max, step and value --}}
<x-adminlte-input-slider name="isMinMax" min=5 max=15 step=0.5 value=11.5 color="purple"/>

{{-- Label, prepend icon and sm size --}}
<x-adminlte-input-slider name="isSizeSm" label="Slider" igroup-size="sm"
    color="#3c8dbc" data-slider-handle="square">
    <x-slot name="prependSlot">
        <div class="input-group-text bg-lightblue">
            <i class="fas fa-sliders-h"></i>
        </div>
    </x-slot>
</x-adminlte-input-slider>

{{-- With slots, range mode and lg size --}}
@php
$config = [
    'handle' => 'square',
    'range' => true,
    'value' => [3, 8],
];
@endphp
<x-adminlte-input-slider name="isSizeLg" label="Range" size="lg"
    color="orange" label-class="text-orange" :config="$config">
    <x-slot name="prependSlot">
        <x-adminlte-button theme="warning" icon="fas fa-minus" title="Decrement"/>
    </x-slot>
    <x-slot name="appendSlot">
        <x-adminlte-button theme="warning" icon="fas fa-plus" title="Increment"/>
    </x-slot>
</x-adminlte-input-slider>

{{-- Vertical slider with ticks --}}
@php
$config = [
    'value' => 150,
    'orientation' => 'vertical',
    'ticks' => [0, 100, 200, 300],
    'ticks_labels' => ['$0', '$100', '$200', '$300'],
];
@endphp
<x-adminlte-input-slider name="isVertical" label="Vertical" color="#77dd77"
    label-class="text-olive" :config="$config"/>
```

Use the next images as reference to check how every example is rendered. Please, note in the images the elements were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them.

![Input Slider Component](/imgs/components/advanced_forms_components/input-slider-component.png)

### Required Plugin Configuration

To use this component you need to install and enable the required [bootstrap-slider](https://github.com/seiyria/bootstrap-slider) plugin. You can install the plugin locally using the next command:

```sh
php artisan adminlte:plugins install --plugin=bootstrapSlider
```

After installed, you can use the next plugin configuration as a reference:

```php
'plugins' => [
    ...
    'BootstrapSlider' => [
        'active' => false,
        'files' => [
            [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/bootstrap-slider/bootstrap-slider.min.js',
            ],
            [
                'type' => 'css',
                'asset' => true,
                'location' => 'vendor/bootstrap-slider/css/bootstrap-slider.min.css',
            ],
        ],
    ],
    ...
],
```

Finally, you need to use the `@section('plugins.BootstrapSlider', true)` sentence on the blade file where you expect to use the component. Alternatively, you can choose to use the plugin files from a `CDN` instead of installing it locally.

# InputSwitch

> [!Important]
> This component requires the [Bootstrap Switch](https://bttstrp.github.io/bootstrap-switch/) plugin, so be sure to first setup the plugin on the package configuration file. Read more on the [plugins configuration section](/sections/configuration/plugins). The plugin can be installed locally using `php artisan adminlte:plugins install --plugin=bootstrapSwitch` command, after that you will need to include the plugins files on the configuration file (only `bootstrap-switch.min.js` file is required).

This component represents a **switch** input and extends from the base [Input Group Component](/sections/components/basic_forms_components#input-group-component), so all the attributes from it will be inherited. The component also defines next additional attributes:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
config | Array with the plugin configuration parameters | array | `[]` | no
enable-old-support | Enable auto retrievement and filling with the submitted value in case of validation errors | any | `null` | no
is-checked | To specify whether the switch should be active or not (from <Badge type="tip">v3.14.2</Badge>) | bool | `null` | no

> [!Important]
> Plase, note the `enable-old-support` property is only available for package version <Badge type="tip">> 3.7.2</Badge> and offers a similar behavior as using the Laravel `old()` helper explicitly by your own. Also, the `is-checked` property is only available from package version <Badge type="tip">v3.14.2</Badge> and may be used as an alternative to the **HTML checked attribute**.

The available plugin configuration are those explained on the [plugin documentation](https://bttstrp.github.io/bootstrap-switch/options.html). All other attributes you define will be inserted directly on the underlying `input` element, so you can also use the `data-*` attributes to configure the plugin.

> [!Note]
> Optionally, you can make all the plugin configuration from `Javascript/jQuery` using the `id` or `name` property of the component as the selector for the `id` attribute, instead of using the `config` property of the component.

### Examples

```blade
{{-- Minimal --}}
<x-adminlte-input-switch name="iswMin"/>

{{-- Disabled --}}
<x-adminlte-input-switch name="iswDisabled" disabled/>

{{-- With colors using data-* config --}}
<x-adminlte-input-switch name="iswColor" data-on-color="success" data-off-color="danger" checked/>

{{-- With custom text using data-* config --}}
<x-adminlte-input-switch name="iswText" data-on-text="YES" data-off-text="NO"
    data-on-color="teal" checked/>

{{-- Label, and prepend icon --}}
<x-adminlte-input-switch name="iswPrepend" label="Switch">
    <x-slot name="prependSlot">
        <div class="input-group-text bg-lightblue">
            <i class="fas fa-toggle-on"></i>
        </div>
    </x-slot>
</x-adminlte-input-switch>

{{-- Label, slots and lg size --}}
@php
$config = [
    'onColor' => 'orange',
    'offColor' => 'dark',
    'inverse' => true,
    'animate' => false,
    'state' => true,
    'labelText' => '<i class="fas fa-2x fa-fw fa-lightbulb text-orange"></i>',
];
@endphp
<x-adminlte-input-switch name="iswSizeLG" label="Switch LG" igroup-size="lg"
    :config="$config">
    <x-slot name="appendSlot">
        <x-adminlte-button icon="fas fa-caret-right" title="On"/>
    </x-slot>
    <x-slot name="prependSlot">
        <x-adminlte-button icon="fas fa-caret-left" title="Off"/>
    </x-slot>
</x-adminlte-input-switch>

{{-- Indeterminate with icon and SM size --}}
@php
$config = [
    'onColor' => 'indigo',
    'offColor' => 'gray',
    'onText' => '1',
    'offText' => '0',
    'indeterminate' => true,
    'labelText' => '<i class="fas fa-power-off text-muted"></i>',
];
@endphp
<x-adminlte-input-switch name="iswSizeSM" label="Switch SM (indeterminate)"
    igroup-size="sm" :config="$config"/>
```

Use the next images as reference to check how every example is rendered. Please, note in the images the elements were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them.

![Input Switch Component](/imgs/components/advanced_forms_components/input-switch-component.png)

### Required Plugin Configuration

To use this component you need to install and enable the required [Bootstrap Switch](https://bttstrp.github.io/bootstrap-switch/) plugin. You can install the plugin locally using the next command:

```sh
php artisan adminlte:plugins install --plugin=bootstrapSwitch
```

After installed, you can use the next plugin configuration as a reference:

```php
'plugins' => [
    ...
    'BootstrapSwitch' => [
        'active' => false,
        'files' => [
            [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/bootstrap-switch/js/bootstrap-switch.min.js',
            ],
        ],
    ],
    ...
],
```

Finally, you need to use the `@section('plugins.BootstrapSwitch', true)` sentence on the blade file where you expect to use the component. Alternatively, you can choose to use the plugin files from a `CDN` instead of installing it locally.

# SelectBs

> [!Important]
> This component requires the [bootstrap-select](https://developer.snapappointments.com/bootstrap-select/) plugin, so be sure to first setup this plugin on the package configuration file. Read more on the [plugins configuration section](/sections/configuration/plugins). The plugin can be installed manually on the `public/vendor` folder or you can use the `CDN` (read the instructions on the plugin site). Finally, you will need to include the plugins files on the configuration file.

This component represents a **bootstrap-select** option selector. The plugin includes features like search, placeholder, and customized options, and extends from the base [Input Group Component](/sections/components/basic_forms_components#input-group-component), so all the attributes from it will be inherited. The component also defines next additional attributes:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
config | Array with the plugin configuration parameters | array | `[]` | no
enable-old-support | Enable auto retrievement and filling with the submitted value in case of validation errors | any | `null` | no

> [!Important]
> Please, note the `enable-old-support` property is only available for package version <Badge type="tip">> v3.7.2</Badge> and offers a similar behavior as using the Laravel `old()` helper explicitly by your own.

The available plugin configuration are those explained on the [plugin documentation](https://developer.snapappointments.com/bootstrap-select/options/#core-options). All other attributes you define will be inserted directly on the underlying `select` element, so you can also use the `data-* attributes` to configure the plugin (as explained on the site).

> [!Note]
> Optionally, you can make all the configuration from `Javascript/jQuery` using the `id` or `name` property of the component as the selector for the `id` attribute, instead of using the `config` property of the component. However, you may need to invoke the [destroy](https://developer.snapappointments.com/bootstrap-select/methods/#selectpickerdestroy) method first.

### Examples

```blade
{{-- Minimal --}}
<x-adminlte-select-bs name="selBsBasic">
    <option>Option 1</option>
    <option disabled>Option 2</option>
    <option selected>Option 3</option>
</x-adminlte-select-bs>

{{-- Disabled --}}
<x-adminlte-select-bs name="selBsDisabled" disabled>
    <option>Option 1</option>
    <option>Option 2</option>
</x-adminlte-select-bs>

{{-- With prepend slot, label and data-* config --}}
<x-adminlte-select-bs name="selBsVehicle" label="Vehicle" label-class="text-lightblue"
    igroup-size="lg" data-title="Select an option..." data-live-search
    data-live-search-placeholder="Search..." data-show-tick>
    <x-slot name="prependSlot">
        <div class="input-group-text bg-gradient-info">
            <i class="fas fa-car-side"></i>
        </div>
    </x-slot>
    <option data-icon="fa fa-fw fa-car">Car</option>
    <option data-icon="fa fa-fw fa-motorcycle">Motorcycle</option>
</x-adminlte-select-bs>

{{-- With multiple slots, plugin config parameter and custom options --}}
@php
    $config = [
        "title" => "Select multiple options...",
        "liveSearch" => true,
        "liveSearchPlaceholder" => "Search...",
        "showTick" => true,
        "actionsBox" => true,
    ];
@endphp
<x-adminlte-select-bs id="selBsCategory" name="selBsCategory[]" label="Categories"
    label-class="text-danger" igroup-size="sm" :config="$config" multiple>
    <x-slot name="prependSlot">
        <div class="input-group-text bg-gradient-red">
            <i class="fas fa-tag"></i>
        </div>
    </x-slot>
    <x-slot name="appendSlot">
        <x-adminlte-button theme="outline-dark" label="Clear" icon="fas fa-lg fa-ban text-danger"/>
    </x-slot>
    <option data-icon="fa fa-fw fa-running text-info" data-subtext="Running">Sports</option>
    <option data-icon="fa fa-fw fa-futbol text-info" data-subtext="Futbol">Sports</option>
    <option data-icon="fa fa-fw fa-newspaper text-danger">News</option>
    <option data-icon="fa fa-fw fa-gamepad text-warning">Games</option>
    <option data-icon="fa fa-fw fa-flask text-primary">Science</option>
    <option data-icon="fa fa-fw fa-calculator text-dark">Maths</option>
</x-adminlte-select-bs>
```

Use the next images as reference to check how every example is rendered. Please, note in the images the elements were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them.

![SelectBs Component](/imgs/components/advanced_forms_components/selectbs-component.png)

### Required Plugin Configuration

To use this component you need to install and enable the required [bootstrap-select](https://developer.snapappointments.com/bootstrap-select/) plugin. You can manually download and install the plugin locally on the `public/vendor/bootstrap-select/` folder. Please, note there is no artisan command to install this plugin.

After installed on `public/vendor/bootstrap-select/` folder, you can use the next plugin configuration as a reference:

```php
'plugins' => [
    ...
    'BootstrapSelect' => [
        'active' => false,
        'files' => [
            [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/bootstrap-select/dist/js/bootstrap-select.min.js',
            ],
            [
                'type' => 'css',
                'asset' => true,
                'location' => 'vendor/bootstrap-select/dist/css/bootstrap-select.min.css',
            ],
        ],
    ],
    ...
],
```

Finally, you need to use the `@section('plugins.BootstrapSelect', true)` sentence on the blade file where you expect to use the component. Alternatively, you can choose to use the plugin files from a `CDN` instead of installing it locally.

# TextEditor

> [!Important]
> This component requires the [Summernote](https://summernote.org/) plugin, so be sure to first setup the plugin on the package configuration file. Read more on the [plugins configuration section](/sections/configuration/plugins). The plugin can be installed locally using `php artisan adminlte:plugins install --plugin=summernote` command, after that you will need to include the plugins files (for **Bootstrap 4**) on the configuration file (`summernote-bs4.min.js` and `summernote-bs4.min.css`).

This component represents a **WYSIWYG editor** and extends from the base [Input Group Component](/sections/components/basic_forms_components#input-group-component), so all the attributes from it will be inherited. However, the append and prepend slots used for addons are not supported here due to conflicts with the underlying plugin. The component also defines next additional attributes:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
config | Array with the plugin configuration parameters | array | `[]` | no
enable-old-support | Enable auto retrievement and filling with the submitted value in case of validation errors | any | `null` | no

> [!Important]
> Please, note the `enable-old-support` property is only available for package version <Badge type="tip">> v3.7.2</Badge> and offers a similar behavior as using the Laravel `old()` helper explicitly by your own.

The available plugin configuration are those explained on the [plugin documentation](https://summernote.org/deep-dive/). All other attributes you define will be inserted directly on the underlying `textarea` element (`disabled` and `placeholder` attributes are supported).

> [!Note]
> Optionally, you can make all the configuration from `Javascript/jQuery` using the `id` or `name` property of the component as the selector for the `id` attribute, instead of using the `config` property of the component. However, you may need to invoke the [destroy](https://summernote.org/getting-started/#destroy) method first.

### Examples

```blade
{{-- Minimal --}}
<x-adminlte-text-editor name="teBasic"/>

{{-- Disabled with content --}}
<x-adminlte-text-editor name="teDisabled" disabled>
    <b>Lorem ipsum dolor sit amet</b>, consectetur adipiscing elit.
    <br>
    <i>Aliquam quis nibh massa.</i>
</x-adminlte-text-editor>

{{-- With placeholder, sm size, label and some configuration --}}
@php
$config = [
    "height" => "100",
    "toolbar" => [
        // [groupName, [list of button]]
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough', 'superscript', 'subscript']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['height', ['height']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']],
    ],
]
@endphp
<x-adminlte-text-editor name="teConfig" label="WYSIWYG Editor" label-class="text-danger"
    igroup-size="sm" placeholder="Write some text..." :config="$config"/>
```

Use the next images as reference to check how every example is rendered. Please, note in the images the elements were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them.

![Text Editor Component](/imgs/components/advanced_forms_components/text-editor-component.png)

### Required Plugin Configuration

To use this component you need to install and enable the required [Summernote](https://summernote.org/) plugin. You can install the plugin locally using the next command:

```sh
php artisan adminlte:plugins install --plugin=summernote
```

After installed, you can use the next plugin configuration as a reference:

```php
'plugins' => [
    ...
    'Summernote' => [
        'active' => false,
        'files' => [
            [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/summernote/summernote-bs4.min.js',
            ],
            [
                'type' => 'css',
                'asset' => true,
                'location' => 'vendor/summernote/summernote-bs4.min.css',
            ],
        ],
    ],
    ...
],
```

Finally, you need to use the `@section('plugins.Summernote', true)` sentence on the blade file where you expect to use the component. Alternatively, you can choose to use the plugin files from a `CDN` instead of installing it locally.

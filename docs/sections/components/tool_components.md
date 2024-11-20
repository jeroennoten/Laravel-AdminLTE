These components are classified under the **Tool** category. At next you can see the list of available components:

|Components
|-----------
| [Datatables](#datatables), [Modal](#modal)

# Datatables

> [!Important]
> This component requires the [Datatables](https://datatables.net/) plugin, so be sure to first setup the plugin on the package configuration file. Read more on the [plugins configuration section](/sections/configuration/other#plugins). The plugin can be installed locally using `php artisan adminlte:plugins install --plugin=datatables --plugin=datatablesPlugins` command. The `datatablesPlugins` provides the files for the tool buttons that are used for the data export feature, so you can avoid its installation if you won't use them. After installing the plugins, you will need to include the related plugins files on the configuration file, example of this configuration will be provided later.

This component represents a wrapper around the well known **Datatables** plugin. The component defines the next set of attributes:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
beautify | When enabled, the table cells will be vertically and horizontally centered. | any | `null` | no
bordered | When enabled, borders will be displayed around the table | any | `null` | no
compressed | When enabled, the table will be compressed using less white space between cells and rows | any | `null` | no
config | Array with the plugin configuration parameters | array | `[]` | no
footer-theme | The table footer theme (light or dark) | string | `null` | no
heads | An array with the headers (titles) for the table columns. Each header can be a `string` or an `array` with next properties: **label**, **width**, **no-export** and **classes** | array | - | **yes**
head-theme | The table head theme (light or dark) | string | `null` | no
hoverable | When enabled, a hover effect will be available for the table rows | any | `null` | no
id | The table identification (`id`) attribute | string | - | **yes**
striped | When enabled, a striped effect will be available for the table rows | any | `null` | no
theme | The table theme (light, dark, primary, secondary, info, warning or danger) | string | `null` | no
with-buttons | When enabled, a set of tool buttons for exporting the data of the table will be available | any | `null` | no
with-footer | Enables a footer with header cells. The footer can be fully customized using the [footerCallback](https://datatables.net/reference/option/footerCallback) option | any | `null` | no

The available options for the `config` attribute are those explained on the [plugin documentation](https://datatables.net/reference/option/). You can define each `header` of the `heads` attribute with an inner array, the next properties are available:

- `label`: for the column title.
- `width`: to define the column width percentage.
- `no-export`: to disable data export for the column (useful for columns with buttons or actions).
- `classes`: to add extra classes for the column title (only for versions <Badge type="tip">>= v3.8.6</Badge>).

All other extra attributes you define will be inserted directly on the underlying `table` element.

> [!Note]
> You can always do all the plugin configuration from `Javascript/jQuery` using the `id` property of the component as the selector for the `id` attribute, instead of using the `config` property of the component. However, you may need to invoke the [destroy](https://datatables.net/reference/api/destroy()) method first.

### Examples

```blade
{{-- Setup data for datatables --}}
@php
$heads = [
    'ID',
    'Name',
    ['label' => 'Phone', 'width' => 40],
    ['label' => 'Actions', 'no-export' => true, 'width' => 5],
];

$btnEdit = '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                <i class="fa fa-lg fa-fw fa-pen"></i>
            </button>';
$btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                  <i class="fa fa-lg fa-fw fa-trash"></i>
              </button>';
$btnDetails = '<button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">
                   <i class="fa fa-lg fa-fw fa-eye"></i>
               </button>';

$config = [
    'data' => [
        [22, 'John Bender', '+02 (123) 123456789', '<nobr>'.$btnEdit.$btnDelete.$btnDetails.'</nobr>'],
        [19, 'Sophia Clemens', '+99 (987) 987654321', '<nobr>'.$btnEdit.$btnDelete.$btnDetails.'</nobr>'],
        [3, 'Peter Sousa', '+69 (555) 12367345243', '<nobr>'.$btnEdit.$btnDelete.$btnDetails.'</nobr>'],
    ],
    'order' => [[1, 'asc']],
    'columns' => [null, null, null, ['orderable' => false]],
];
@endphp

{{-- Minimal example / fill data using the component slot --}}
<x-adminlte-datatable id="table1" :heads="$heads">
    @foreach($config['data'] as $row)
        <tr>
            @foreach($row as $cell)
                <td>{!! $cell !!}</td>
            @endforeach
        </tr>
    @endforeach
</x-adminlte-datatable>

{{-- Compressed with style options / fill data using the plugin config --}}
<x-adminlte-datatable id="table2" :heads="$heads" head-theme="dark" :config="$config"
    striped hoverable bordered compressed/>
```

> [!Important]
> Please, note the differences between the previous two examples, on the first one the rows and cells were manually constructed using loops over they available dataset. On the second example, the dataset is passed directly to the underlying plugin using the `$config['data']` property. You can't mix both strategies, use one or another.

Use the next image as reference to check how every example is rendered. Please, note in the image the tables were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them. This same consideration is valid for all the other examples below.

![Datatables Component Example 1](/imgs/components/tool_components/datatables-component-example-1.png)

```blade
{{-- Themes --}}
<x-adminlte-datatable id="table3" :heads="$heads" :config="$config" theme="info" striped hoverable/>

<x-adminlte-datatable id="table4" :heads="$heads" theme="danger" :config="$config"
    striped hoverable/>

<x-adminlte-datatable id="table5" :heads="$heads" :config="$config" theme="light" striped hoverable/>

<x-adminlte-datatable id="table6" :heads="$heads" head-theme="light" theme="dark" :config="$config"
    striped hoverable with-footer footer-theme="light" beautify/>
```

![Datatables Component Example 2](/imgs/components/tool_components/datatables-component-example-2.png)

```blade
{{-- With buttons --}}
<x-adminlte-datatable id="table7" :heads="$heads" head-theme="light" theme="warning" :config="$config"
    striped hoverable with-buttons/>

{{-- With buttons + customization --}}
@php        
$config['dom'] = '<"row" <"col-sm-7" B> <"col-sm-5 d-flex justify-content-end" i> >
                  <"row" <"col-12" tr> >
                  <"row" <"col-sm-12 d-flex justify-content-start" f> >';
$config['paging'] = false;
$config["lengthMenu"] = [ 10, 50, 100, 500];
@endphp

<x-adminlte-datatable id="table8" :heads="$heads" head-theme="dark" class="bg-teal" :config="$config"
    striped hoverable with-buttons/>
```

![Datatables Component Example 3](/imgs/components/tool_components/datatables-component-example-3.png)

### Required Plugin Configuration

To use this component you need to install and enable the required **Datatables** plugins. You can install the plugins locally using the next command:

```sh
php artisan adminlte:plugins install --plugin=datatables --plugin=datatablesPlugins
```

Then, the plugin configuration to use `Datatables` without the set of `export` buttons may look like this:

```php
'Datatables' => [
    'active' => false,
    'files' => [
        [
            'type' => 'js',
            'asset' => true,
            'location' => 'vendor/datatables/js/jquery.dataTables.min.js',
        ],
        [
            'type' => 'js',
            'asset' => true,
            'location' => 'vendor/datatables/js/dataTables.bootstrap4.min.js',
        ],
        [
            'type' => 'css',
            'asset' => true,
            'location' => 'vendor/datatables/css/dataTables.bootstrap4.min.css',
        ],
    ],
],
```

To use the export buttons (refer to `with-buttons` attribute), you will need also next configuration:

```php
'DatatablesPlugins' => [
    'active' => false,
    'files' => [
        [
            'type' => 'js',
            'asset' => true,
            'location' => 'vendor/datatables-plugins/buttons/js/dataTables.buttons.min.js',
        ],
        [
            'type' => 'js',
            'asset' => true,
            'location' => 'vendor/datatables-plugins/buttons/js/buttons.bootstrap4.min.js',
        ],
        [
            'type' => 'js',
            'asset' => true,
            'location' => 'vendor/datatables-plugins/buttons/js/buttons.html5.min.js',
        ],
        [
            'type' => 'js',
            'asset' => true,
            'location' => 'vendor/datatables-plugins/buttons/js/buttons.print.min.js',
        ],
        [
            'type' => 'js',
            'asset' => true,
            'location' => 'vendor/datatables-plugins/jszip/jszip.min.js',
        ],
        [
            'type' => 'js',
            'asset' => true,
            'location' => 'vendor/datatables-plugins/pdfmake/pdfmake.min.js',
        ],
        [
            'type' => 'js',
            'asset' => true,
            'location' => 'vendor/datatables-plugins/pdfmake/vfs_fonts.js',
        ],
        [
            'type' => 'css',
            'asset' => true,
            'location' => 'vendor/datatables-plugins/buttons/css/buttons.bootstrap4.min.css',
        ],
    ],
],
```

Finally, you need to use the `@section('plugins.Datatables', true)` and/or `@section('plugins.DatatablesPlugin', true)` sentences on the blade file where you expect to use the component. Alternatively, you can choose to use the plugin files from a `CDN` instead of installing it locally.

# Modal

This component represents an `AdminLTE` modal notification. The following attributes are available:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
disable-animations | Disables the show/hide modal fade animations | any | `null` | no
icon | A `fontawesome` icon for the modal header | string | `null` | no
id | The modal `id` attribute, used to target the modal and show it | string | - | **yes**
scrollable | Enables a scrollable modal. Use this when the modal content is large | any | `null` | no
size | The modal size (`sm`, `lg` or `xl`). | string | `null` | no
static-backdrop | Enables a static backdrop. The modal will not close when clicking outside it | any | `null` | no
theme | The modal theme: light, dark, primary, secondary, info, success, warning, danger or any other `AdminLTE` color like lightblue or teal | string | `null` | no
title | The title for the modal header | string | `null` | no
v-centered | Enables a vertically centered modal | any | `null` | no

Any other attribute you define will be directly inserted into the underlying `div.modal` element. For example, you may define a `class`, `onclick`, or any other attribute you may need.

The modal also defines the next extra **slot** (the main slot is used for the modal body content):
- **footerSlot**: Use this slot to customize the modal footer.

### Examples

```blade
{{-- Minimal --}}
<x-adminlte-modal id="modalMin" title="Minimal"/>
{{-- Example button to open modal --}}
<x-adminlte-button label="Open Modal" data-toggle="modal" data-target="#modalMin"/>
```
![Minimal Modal Component](/imgs/components/tool_components/minimal-modal-component.png)

```blade
{{-- Themed --}}
<x-adminlte-modal id="modalPurple" title="Theme Purple" theme="purple"
    icon="fas fa-bolt" size='lg' disable-animations>
    This is a purple theme modal without animations.
</x-adminlte-modal>
{{-- Example button to open modal --}}
<x-adminlte-button label="Open Modal" data-toggle="modal" data-target="#modalPurple" class="bg-purple"/>
```
![Themed Modal Component](/imgs/components/tool_components/themed-modal-component.png)

```blade
{{-- Custom --}}
<x-adminlte-modal id="modalCustom" title="Account Policy" size="lg" theme="teal"
    icon="fas fa-bell" v-centered static-backdrop scrollable>
    <div style="height:800px;">Read the account policies...</div>
    <x-slot name="footerSlot">
        <x-adminlte-button class="mr-auto" theme="success" label="Accept"/>
        <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal"/>
    </x-slot>
</x-adminlte-modal>
{{-- Example button to open modal --}}
<x-adminlte-button label="Open Modal" data-toggle="modal" data-target="#modalCustom" class="bg-teal"/>
```
![Custom Modal Component](/imgs/components/tool_components/custom-modal-component.png)

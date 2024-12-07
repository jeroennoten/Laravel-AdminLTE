These components are expected to be used within a `form` element. They can be used to generate forms with styled input fields. At next you can see the list of available components:

|Components
|-----------
| [InputGroup](#input-group-component), [Button](#button), [Input](#input), [InputFile](#inputfile), [Options](#options), [Select](#select), [Select2](#select2), [Textarea](#textarea)

# Input Group Component

> [!Important]
> This component is **not intended to be used** directly, but it provides a base layout and some properties that other components may extend.

This component represents an empty input group to easily generate form controls by adding text, icons, buttons on either side of textual inputs, custom selects, or custom file inputs. The component yields an `input_group_item` section that other components (like an `input`, `select` or `textarea`) may use to extend the layout. At next, you can see the list of supported attributes:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
disable-feedback | Disables the invalid feedback notification for the input group | any | `null` | no
error-key | The lookup key to use when searching for validation errors | string | `null` | no
fgroup-class | Additional classes for the `form-group` container (to customize the main container) | string | `null` | no
id | The `id` attribute for the underlying input group item | string | `null` | no
igroup-class | Additional classes for the `input-group` container | string | `null` | no
igroup-size | The input group size (you can specify `sm` or `lg` values only) | string | `null` | no
label | The label for the input group | string | `null` | no
label-class | Classes for the label container (to customize the label) | string | `null` | no
name | The `name` and default `id` attribute for the underlying input group item | string | - | **yes**

The `name` attribute is the only required property and will be used as the default `id` property when no other is provided. Also, the lookup key for validation errors will be automatically generated from the `name` property if no `error-key` is specified. For example, when you want to submit multiple files from a file input field, you can setup `name` property as `files[]` in order to submit the file names inside an array called `files`, in this case the auto-generated lookup key for validation errors will be `files`.

You should note that all the others components that extends from this one will have the previous set of attributes available on their interface. This component also defines some **slots** that can be used to push `add-ons` into the input group:

### Slots

- **prependSlot**: Use this slot to prepend an add-on in the input group item.
- **appendSlot**: Use this slot to append an add-on in the input group item.
- **bottomSlot**: Use this slot to add extra information or markup below the input group item (only available for versions greater than <Badge type="tip">v3.7.2</Badge>).

# Button

This component represents an `AdminLTE` styled button. The following attributes are available:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
icon | A `fontawesome` icon for the button | string | `null` | no
label | The visible label (text) for the button | string | `null` | no
theme | The `AdminLTE` button style theme: primary, info, success, warning, danger, etc. | string | `'default'` | no
type | The button type (`button`, `submit` or `reset`) | string | `'button'` | no

Any other attribute you define will be directly inserted into the underlying `button` tag. You can, for example, define a `class`, `onclick`, `title` or any other attribute you may need.

### Examples

```blade
{{-- Button with minimal setup --}}
<x-adminlte-button label="Button"/>

{{-- A disabled button --}}
<x-adminlte-button label="Disabled" theme="dark" disabled/>

{{-- Button with themes and icons --}}
<x-adminlte-button label="Primary" theme="primary" icon="fas fa-key"/>
<x-adminlte-button label="Secondary" theme="secondary" icon="fas fa-hashtag"/>
<x-adminlte-button label="Info" theme="info" icon="fas fa-info-circle"/>
<x-adminlte-button label="Warning" theme="warning" icon="fas fa-exclamation-triangle"/>
<x-adminlte-button label="Danger" theme="danger" icon="fas fa-ban"/>
<x-adminlte-button label="Success" theme="success" icon="fas fa-thumbs-up"/>
<x-adminlte-button label="Dark" theme="dark" icon="fas fa-adjust"/>

{{-- Button with types --}}
<x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
<x-adminlte-button class="btn-lg" type="reset" label="Reset" theme="outline-danger" icon="fas fa-lg fa-trash"/>
<x-adminlte-button class="btn-sm bg-gradient-info" type="button" label="Help" icon="fas fa-lg fa-question"/>

{{-- Icons only buttons --}}
<x-adminlte-button theme="primary" icon="fab fa-fw fa-lg fa-facebook-f"/>
<x-adminlte-button theme="info" icon="fab fa-fw fa-lg fa-twitter"/>
```

At next you can check how the previous set of defined buttons are rendered:

![Button Component](/imgs/components/basic_forms_components/button-component.png)

# Input

This component represents an input element, and extends from the base [Input Group Component](#input-group-component), so all the attributes from it will be inherited. Even more, you are able to setup any other attribute you generally use on an `input` html element without any problem. The component also defines the next additional attributes:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
enable-old-support | Enable the auto retrievement and filling with the submitted value in case of validation errors | any | `null` | no

> [!Important]
> Please, note the `enable-old-support` property is only available for package version <Badge type="tip">>= v3.7.2</Badge> and offers a similar behavior as using the Laravel `old()` helper explicitly by your own.

### Examples

```blade
{{-- Minimal --}}
<x-adminlte-input name="iBasic"/>

{{-- Email type --}}
<x-adminlte-input name="iMail" type="email" placeholder="mail@example.com"/>

{{-- With label, invalid feedback disabled, and form group class --}}
<div class="row">
    <x-adminlte-input name="iLabel" label="Label" placeholder="placeholder"
        fgroup-class="col-md-6" disable-feedback/>
</div>

{{-- With prepend slot --}}
<x-adminlte-input name="iUser" label="User" placeholder="username" label-class="text-lightblue">
    <x-slot name="prependSlot">
        <div class="input-group-text">
            <i class="fas fa-user text-lightblue"></i>
        </div>
    </x-slot>
</x-adminlte-input>

{{-- With append slot, number type, and sm size --}}
<x-adminlte-input name="iNum" label="Number" placeholder="number" type="number"
    igroup-size="sm" min=1 max=10>
    <x-slot name="appendSlot">
        <div class="input-group-text bg-dark">
            <i class="fas fa-hashtag"></i>
        </div>
    </x-slot>
</x-adminlte-input>

{{-- With a link on the bottom slot, and old support enabled --}}
<x-adminlte-input name="iPostalCode" label="Postal Code" placeholder="postal code"
    enable-old-support>
    <x-slot name="prependSlot">
        <div class="input-group-text text-olive">
            <i class="fas fa-map-marked-alt"></i>
        </div>
    </x-slot>
    <x-slot name="bottomSlot">
        <a href="#">Search your postal code here</a>
    </x-slot>
</x-adminlte-input>

{{-- With extra information on the bottom slot --}}
<x-adminlte-input name="iExtraAddress" label="Other Address Data">
    <x-slot name="prependSlot">
        <div class="input-group-text text-purple">
            <i class="fas fa-address-card"></i>
        </div>
    </x-slot>
    <x-slot name="bottomSlot">
        <span class="text-sm text-gray">
            [Add other address information you may consider important]
        </span>
    </x-slot>
</x-adminlte-input>

{{-- With multiple slots, and lg size --}}
<x-adminlte-input name="iSearch" label="Search" placeholder="search" igroup-size="lg">
    <x-slot name="appendSlot">
        <x-adminlte-button theme="outline-danger" label="Go!"/>
    </x-slot>
    <x-slot name="prependSlot">
        <div class="input-group-text text-danger">
            <i class="fas fa-search"></i>
        </div>
    </x-slot>
</x-adminlte-input>
```

Use the next image as reference to check how every input example is rendered. Please, note in the image the inputs were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them.

![Input Component](/imgs/components/basic_forms_components/input-component.png)

# InputFile

> [!Important]
> This component requires the [bs-custom-file-input](https://github.com/Johann-S/bs-custom-file-input) plugin, so be sure to first setup it on the package configuration file. Read more on the [plugins configuration section](/sections/configuration/plugins). The plugin can be installed locally using `php artisan adminlte:plugins install --plugin=bsCustomFileInput` command.

This component represents a file input element. This component extends from the base [Input Group Component](#input-group-component), so all the attributes from it will be inherited. The component also defines the next additional attributes:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
legend | A legend to replace the default `Browse` text | string | `null` | no
placeholder | The placeholder for the input file box | string | `''` | no

> [!Note]
> Please, note the `enable-old-support` attribute is not supported here, due to security reasons related to the file inputs fields.

All other attributes you define on the component will be inserted directly on the underlying `input[type='file']` element, so you can use the standard attributes too (like `accept` or `multiple`).

### Examples

```blade
{{-- Minimal --}}
<x-adminlte-input-file name="ifMin"/>

{{-- Placeholder, sm size, and prepend icon --}}
<x-adminlte-input-file name="ifPholder" igroup-size="sm" placeholder="Choose a file...">
    <x-slot name="prependSlot">
        <div class="input-group-text bg-lightblue">
            <i class="fas fa-upload"></i>
        </div>
    </x-slot>
</x-adminlte-input-file>

{{-- With label and feedback disabled --}}
<x-adminlte-input-file name="ifLabel" label="Upload file" placeholder="Choose a file..."
    disable-feedback/>

{{-- With multiple slots and multiple files --}}
<x-adminlte-input-file id="ifMultiple" name="ifMultiple[]" label="Upload files"
    placeholder="Choose multiple files..." igroup-size="lg" legend="Choose" multiple>
    <x-slot name="appendSlot">
        <x-adminlte-button theme="primary" label="Upload"/>
    </x-slot>
    <x-slot name="prependSlot">
        <div class="input-group-text text-primary">
            <i class="fas fa-file-upload"></i>
        </div>
    </x-slot>
</x-adminlte-input-file>
```

Use the next image as reference to check how every input example is rendered. Please, note in the image the inputs were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them.

![Input File Component](/imgs/components/basic_forms_components/input-file-component.png)

### Required Plugin Configuration

To use this component you need to install and enable the required [bs-custom-file-input](https://github.com/Johann-S/bs-custom-file-input) plugin. You can install the plugin locally using the next command:

```sh
php artisan adminlte:plugins install --plugin=bsCustomFileInput
```

After installed, you can use the next plugin configuration inside the `config/adminlte.php` file as a reference:

```php
'plugins' => [
    ...
    'BsCustomFileInput' => [
        'active' => false,
        'files' => [
            [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/bs-custom-file-input/bs-custom-file-input.min.js',
            ],
        ],
    ],
    ...
],
```

Finally, you need to use the `@section('plugins.BsCustomFileInput', true)` sentence on the blade file where you expect to use the component. Alternatively, you can choose to use the plugin files from a `CDN` instead of installing it locally.

# Options

> [!Important]
> This component is only available from package version <Badge type="tip">>= v3.7.0</Badge>.

This component represents a set of option tags. It can be used with [Select](#select), [Select2](#select2) or [SelectBs](/sections/components/advanced_forms_components#selectbs) components. The following attributes are available:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
disabled | A list of disabled option `keys` | array | `null` | no
empty-option | Whether to add a selectable empty option to the list. If the value is a `string`, it will be used as the option label, otherwise no label will be available | bool/string | `false` | no
options | The list of options as `key => value` pairs | array | - | **yes**
placeholder | Whether to add a placeholder (non selectable hidden option) to the list. If the value is a `string`, it will be used as the placeholder label, otherwise no label will be available | bool/string | `false` | no
selected | A list of selected option `keys` | array | `null` | no
strict | Whether to use strict comparison between option's key and the keys of selected/disabled options | bool | `false` | no

The intention of the `empty-option` attribute is to represent a selectable option that will submit a `null` value for a selection component. On the other hand, the `placeholder` adds a non selectable (hidden option) to the list of options that will acts as a placeholder for the selection component. As an example, note the next components definition:

```blade
{{-- Options with empty option --}}
<x-adminlte-options :options="['Option 1', 'Option 2', 'Option 3']"
        disabled="1" empty-option="Select an option..."/>

{{-- Options with placeholder --}}
<x-adminlte-options :options="['Option 1', 'Option 2', 'Option 3']"
        disabled="1" placeholder="Select an option..."/>
```

They will be rendered as:

```blade
{{-- Options with empty option --}}
<option value="">Select an option...</option>
<option value="0">Option 1</option>
<option value="1" disabled="">Option 2</option>
<option value="2">Option 3</option>

{{-- Options with placeholder --}}
<option class="d-none" value="">Select an option...</option>
<option value="0">Option 1</option>
<option value="1" disabled="">Option 2</option>
<option value="2">Option 3</option>
```

### Other examples

```blade
{{-- Example with empty option (for Select) --}}
<x-adminlte-select name="optionsTest1">
    <x-adminlte-options :options="['Option 1', 'Option 2', 'Option 3']" disabled="1"
        empty-option="Select an option..."/>
</x-adminlte-select>

{{-- Example with placeholder (for Select) --}}
<x-adminlte-select name="optionsTest2">
    <x-adminlte-options :options="['Option 1', 'Option 2', 'Option 3']" disabled="1"
        placeholder="Select an option..."/>
</x-adminlte-select>

{{-- Example with empty option (for Select2) --}}
<x-adminlte-select2 name="optionsVehicles" igroup-size="lg" label-class="text-lightblue"
    data-placeholder="Select an option...">
    <x-slot name="prependSlot">
        <div class="input-group-text bg-gradient-info">
            <i class="fas fa-car-side"></i>
        </div>
    </x-slot>
    <x-adminlte-options :options="['Car', 'Truck', 'Motorcycle']" empty-option/>
</x-adminlte-select2>

{{-- Example with multiple selections (for Select) --}}
@php
    $options = ['s' => 'Spanish', 'e' => 'English', 'p' => 'Portuguese'];
    $selected = ['s','e'];
@endphp
<x-adminlte-select id="optionsLangs" name="optionsLangs[]" label="Languages"
    label-class="text-danger" multiple>
    <x-slot name="prependSlot">
        <div class="input-group-text bg-gradient-red">
            <i class="fas fa-lg fa-language"></i>
        </div>
    </x-slot>
    <x-adminlte-options :options="$options" :selected="$selected"/>
</x-adminlte-select>

{{-- Example with multiple selections (for SelectBs) --}}
@php
    $config = [
        "title" => "Select multiple options...",
        "liveSearch" => true,
        "liveSearchPlaceholder" => "Search...",
        "showTick" => true,
        "actionsBox" => true,
    ];
@endphp
<x-adminlte-select-bs id="optionsCategory" name="optionsCategory[]" label="Categories"
    label-class="text-danger" :config="$config" multiple>
    <x-slot name="prependSlot">
        <div class="input-group-text bg-gradient-red">
            <i class="fas fa-tag"></i>
        </div>
    </x-slot>
    <x-adminlte-options :options="['News', 'Sports', 'Science', 'Games']"/>
</x-adminlte-select-bs>
```

Use the next image as reference to check how every example is rendered. Please, note in the image the selection fields were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them.

![Options Component](/imgs/components/basic_forms_components/options-component.png)

# Select

This component represents an option selection element, and extends from the base [Input Group Component](#input-group-component), so all the attributes from it will be inherited. Even more, you are able to set any attribute you usually will use on a `select` html element without any problem. The component also defines next additional attributes:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
enable-old-support | Enable the auto retrievement and selection of the submitted value in case of validation errors | any | `null` | no

> [!Important]
> Please, note the `enable-old-support` property is only available for package version <Badge type="tip">>= v3.7.2</Badge> and offers a similar behavior as using the Laravel `old()` helper explicitly by your own.

### Examples

```blade
{{-- Minimal --}}
<x-adminlte-select name="selBasic">
    <option>Option 1</option>
    <option disabled>Option 2</option>
    <option selected>Option 3</option>
</x-adminlte-select>

{{-- Disabled --}}
<x-adminlte-select name="selDisabled" disabled>
    <option>Option 1</option>
    <option>Option 2</option>
</x-adminlte-select>

{{-- With prepend slot, lg size, and label --}}
<x-adminlte-select name="selVehicle" label="Vehicle" label-class="text-lightblue"
    igroup-size="lg">
    <x-slot name="prependSlot">
        <div class="input-group-text bg-gradient-info">
            <i class="fas fa-car-side"></i>
        </div>
    </x-slot>
    <option>Vehicle 1</option>
    <option>Vehicle 2</option>
</x-adminlte-select>

{{-- With multiple slots and multiple options --}}
<x-adminlte-select id="selUser" name="selUser[]" label="User" label-class="text-danger" multiple>
    <x-slot name="prependSlot">
        <div class="input-group-text bg-gradient-red">
            <i class="fas fa-lg fa-user"></i>
        </div>
    </x-slot>
    <x-slot name="appendSlot">
        <x-adminlte-button theme="outline-dark" label="Clear" icon="fas fa-lg fa-ban text-danger"/>
    </x-slot>
    <option>Admin</option>
    <option>Guest</option>
</x-adminlte-select>
```

Use the next image as reference to check how every example is rendered. Please, note in the image the selection fields were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them.

![Select Component](/imgs/components/basic_forms_components/select-component.png)

# Select2

> [!Important]
> This component requires the [select2](https://select2.org/) and [select2-bootstrap4-theme](https://github.com/ttskch/select2-bootstrap4-theme) plugins, so be sure to first setup these plugins on the package configuration file. Read more on the [plugins configuration section](/sections/configuration/plugins). Both plugins can be installed locally using `php artisan adminlte:plugins install --plugin=select2` command, after that you will need to include the plugins files on the configuration file.

This component represents a **select2** option selector and includes features like option search and placeholder. The component extends from the base [Input Group Component](#input-group-component), so all the attributes from it will be inherited. The component also defines next additional attributes:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
config | Array with the `select2` plugin configuration parameters | array | `[]` | no
enable-old-support | Enable the auto retrievement and selection of the submitted value in case of validation errors | any | `null` | no

> [!Important]
> Please, note the `enable-old-support` property is only available for package version <Badge type="tip">>= v3.7.2</Badge> and offers a similar behavior as using the Laravel `old()` helper explicitly by your own.

The available plugin configuration options are those explained on the [plugin documentation](https://select2.org/sections/configuration/options-api). All other attributes you define will be inserted directly on the underlying `select` element, so you can also use the [data-* attributes](https://select2.org/sections/configuration/data-attributes) to configure the plugin.

> [!Note]
> You may also configure the plugin from `Javascript/jQuery` using the `id` or `name` property of the component as the selector for the `id` attribute, instead of using the `config` property of the component. However, you may need to invoke the [destroy](https://select2.org/programmatic-control/methods#destroying-the-select2-control) method first.

### Examples

```blade
{{-- Minimal --}}
<x-adminlte-select2 name="sel2Basic">
    <option>Option 1</option>
    <option disabled>Option 2</option>
    <option selected>Option 3</option>
</x-adminlte-select2>

{{-- Disabled --}}
<x-adminlte-select2 name="sel2Disabled" disabled>
    <option>Option 1</option>
    <option>Option 2</option>
</x-adminlte-select2>

{{-- With prepend slot, label, and data-placeholder config --}}
<x-adminlte-select2 name="sel2Vehicle" label="Vehicle" label-class="text-lightblue"
    igroup-size="lg" data-placeholder="Select an option...">
    <x-slot name="prependSlot">
        <div class="input-group-text bg-gradient-info">
            <i class="fas fa-car-side"></i>
        </div>
    </x-slot>
    <option/>
    <option>Vehicle 1</option>
    <option>Vehicle 2</option>
</x-adminlte-select2>

{{-- With multiple slots, and plugin config parameters --}}
@php
    $config = [
        "placeholder" => "Select multiple options...",
        "allowClear" => true,
    ];
@endphp
<x-adminlte-select2 id="sel2Category" name="sel2Category[]" label="Categories"
    label-class="text-danger" igroup-size="sm" :config="$config" multiple>
    <x-slot name="prependSlot">
        <div class="input-group-text bg-gradient-red">
            <i class="fas fa-tag"></i>
        </div>
    </x-slot>
    <x-slot name="appendSlot">
        <x-adminlte-button theme="outline-dark" label="Clear" icon="fas fa-lg fa-ban text-danger"/>
    </x-slot>
    <option>Sports</option>
    <option>News</option>
    <option>Games</option>
    <option>Science</option>
    <option>Maths</option>
</x-adminlte-select2>
```

Use the next image as reference to check how every example is rendered. Please, note in the image the selection fields were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them.

![Select2 Component](/imgs/components/basic_forms_components/select2-component.png)

### Required Plugin Configuration

To use this component you need to install and enable the required [select2](https://select2.org/) and [select2-bootstrap4-theme](https://github.com/ttskch/select2-bootstrap4-theme) plugins. You can install both plugins locally using the next command:

```sh
php artisan adminlte:plugins install --plugin=select2
```

After installed, you can use the next plugin configuration as a reference:

```php
'plugins' => [
    ...
    'Select2' => [
        'active' => false,
        'files' => [
            [
                'type' => 'js',
                'asset' => true,
                'location' => 'vendor/select2/js/select2.full.min.js',
            ],
            [
                'type' => 'css',
                'asset' => true,
                'location' => 'vendor/select2/css/select2.min.css',
            ],
            [
                'type' => 'css',
                'asset' => true,
                'location' => 'vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css',
            ],
        ],
    ],
    ...
],
```

Finally, you need to use the `@section('plugins.Select2', true)` sentence on the blade file where you expect to use the component. Alternatively, you can choose to use the plugin files from a `CDN` instead of installing it locally.

# Textarea

This component represents a `textarea` element and extends from the base [Input Group Component](#input-group-component), so all the attributes from it will be inherited. Even more, you are able to set any attribute you usually will use on a `textarea` html element without any problem. The component also defines next additional attributes:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
enable-old-support | Enable the auto retrievement and filling with the submitted value in case of validation errors | any | `null` | no

> [!Important]
> Please, note the `enable-old-support` property is only available for package version <Badge type="tip">>= v3.7.2</Badge> and offers a similar behavior as using the Laravel `old()` helper explicitly by your own.

### Examples

```blade
{{-- Minimal with placeholder --}}
<x-adminlte-textarea name="taBasic" placeholder="Insert description..."/>

{{-- Disabled --}}
<x-adminlte-textarea name="taDisabled" disabled>
    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam quis nibh massa.
</x-adminlte-textarea>

{{-- With prepend slot, sm size, and label --}}
<x-adminlte-textarea name="taDesc" label="Description" rows=5 label-class="text-warning"
    igroup-size="sm" placeholder="Insert description...">
    <x-slot name="prependSlot">
        <div class="input-group-text bg-dark">
            <i class="fas fa-lg fa-file-alt text-warning"></i>
        </div>
    </x-slot>
</x-adminlte-textarea>

{{-- With slots, sm size, and feedback disabled --}}
<x-adminlte-textarea name="taMsg" label="Message" rows=5 igroup-size="sm"
    label-class="text-primary" placeholder="Write your message..." disable-feedback>
    <x-slot name="prependSlot">
        <div class="input-group-text">
            <i class="fas fa-lg fa-comment-dots text-primary"></i>
        </div>
    </x-slot>
    <x-slot name="appendSlot">
        <x-adminlte-button theme="primary" icon="fas fa-paper-plane" label="Send"/>
    </x-slot>
</x-adminlte-textarea>
```

Use the next image as reference to check how every example is rendered. Please, note in the image the textarea fields were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them.

![Textarea Component](/imgs/components/basic_forms_components/textarea-component.png)

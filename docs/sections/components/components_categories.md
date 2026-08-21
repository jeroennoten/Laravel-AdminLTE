This package provides some useful [blade-x components](https://laravel.com/docs/blade#components) with `AdminLTE` style that you can use to improve the speed of the **front end** development. The components are classified into the following categories:

Category | Components
---------|-----------
[Forms Basic](/sections/components/basic_forms_components) | [InputGroup](/sections/components/basic_forms_components#input-group-component), [Button](/sections/components/basic_forms_components#button), [Input](/sections/components/basic_forms_components#input), [InputFile](/sections/components/basic_forms_components#inputfile), [Options](/sections/components/basic_forms_components#options), [Select](/sections/components/basic_forms_components#select), [Select2](/sections/components/basic_forms_components#select2), [Textarea](/sections/components/basic_forms_components#textarea)
[Forms Advanced](/sections/components/advanced_forms_components) | [DateRange](/sections/components/advanced_forms_components#daterange), [InputColor](/sections/components/advanced_forms_components#inputcolor), [InputDate](/sections/components/advanced_forms_components#inputdate), [InputFileKrajee](/sections/components/advanced_forms_components#inputfilekrajee), [InputSlider](/sections/components/advanced_forms_components#inputslider), [InputSwitch](/sections/components/advanced_forms_components#inputswitch), [SelectBs](/sections/components/advanced_forms_components#selectbs), [TextEditor](/sections/components/advanced_forms_components#texteditor)
[Tools](/sections/components/tool_components) | [Datatables](/sections/components/tool_components#datatables), [Modal](/sections/components/tool_components#modal)
[Widgets](/sections/components/widget_components) | [Alert](/sections/components/widget_components#alert), [Callout](/sections/components/widget_components#callout), [Card](/sections/components/widget_components#card), [Info Box](/sections/components/widget_components#info-box), [ProfileColItem](/sections/components/widget_components#profile-col-item-profile-row-item), [ProfileRowItem](/sections/components/widget_components#profile-col-item-profile-row-item), [ProfileWidget](/sections/components/widget_components#profile-widget), [Progress](/sections/components/widget_components#progress), [Small Box](/sections/components/widget_components#small-box)

Each link will redirect you to the corresponding component documentation.

## About the Icons Used on the Examples

**AdminLTE v4** ships [Bootstrap Icons](https://icons.getbootstrap.com/) as its icon set, and every `icon` attribute of the components (and every icon default of this package) uses a Bootstrap Icons class name like `bi bi-person` or `bi bi-bell-fill`. The examples on the following pages are written with those names.

The `icon` attributes are plain **class strings** that are copied verbatim into an `<i>` element, so nothing prevents you from using another icon font. If you prefer, for example, **Font Awesome**, just load its stylesheet on your layout (for example, with a `@section('adminlte_css_pre')` block or through your asset bundling setup) and pass its class names instead:

```blade
{{-- Bootstrap Icons (the AdminLTE v4 default) --}}
<x-adminlte-button label="Save" theme="success" icon="bi bi-save"/>

{{-- Any other icon font you loaded on your own --}}
<x-adminlte-button label="Save" theme="success" icon="bi bi-save"/>
```

> [!Note]
> The **Bootstrap Icons** stylesheet is loaded by the package (from the local assets or a CDN). You can disable it with the `assets.bootstrap_icons` option of the [assets configuration](/sections/configuration/other#assets) when you provide the icon font on your own.

## About the Colors Used on the Examples

The components accept the eight **Bootstrap 5.3** theme colors out of the box: `primary`, `secondary`, `success`, `danger`, `warning`, `info`, `light` and `dark`.

**AdminLTE v4** also provides an **extended color palette** on a separate stylesheet (`adminlte-colors.css`), with the next colors: `navy`, `midnight`, `slate`, `steel`, `graphite`, `sky`, `teal`, `olive`, `amber`, `orange`, `pink`, `fuchsia`, `violet` and `indigo`. To use any of those colors, enable the `assets.extended_colors` option of the [assets configuration](/sections/configuration/other#assets):

```php
'assets' => [
    ...
    'extended_colors' => true,
    ...
],
```

> [!Important]
> On the **AdminLTE v4** palette the `lightblue` color was renamed to `sky` and the `maroon` color was renamed to `pink`, while `purple` and `lime` were dropped in favour of `violet` and `olive`. The widget components still accept the **AdminLTE v3** color names (`lightblue`, `maroon`, `purple`, `lime`, `blue`, `red`, `green`, `yellow`, `cyan`, `gray` and `gray-dark`) and map them to their v4 equivalent on the fly. If you'd rather keep the old names as real CSS classes, enable the `assets.extended_colors_v3_aliases` option instead, so that the `adminlte-colors-v3.css` stylesheet is loaded and no mapping is applied.

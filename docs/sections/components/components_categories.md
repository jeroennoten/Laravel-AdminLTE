# Blade Components

This package provides some useful [blade-x components](https://laravel.com/docs/blade#components) with `AdminLTE` style that you can use to improve the speed of the **front end** development. The components are classified into the following categories:

Category | Components
---------|-----------
[Forms Basic](/sections/components/basic_forms_components) | [InputGroup](/sections/components/basic_forms_components#input-group-component), [Button](/sections/components/basic_forms_components#button), [Input](/sections/components/basic_forms_components#input), [InputFile](/sections/components/basic_forms_components#inputfile), [Options](/sections/components/basic_forms_components#options), [Select](/sections/components/basic_forms_components#select), [Select2](/sections/components/basic_forms_components#select2), [Textarea](/sections/components/basic_forms_components#textarea)
[Forms Advanced](/sections/components/advanced_forms_components) | [DateRange](/sections/components/advanced_forms_components#daterange), [InputColor](/sections/components/advanced_forms_components#inputcolor), [InputDate](/sections/components/advanced_forms_components#inputdate), [InputFileKrajee](/sections/components/advanced_forms_components#inputfilekrajee), [InputSlider](/sections/components/advanced_forms_components#inputslider), [InputSwitch](/sections/components/advanced_forms_components#inputswitch), [SelectBs](/sections/components/advanced_forms_components#selectbs), [TextEditor](/sections/components/advanced_forms_components#texteditor)
[Layout](/sections/components/layout_components) | [Content Header](/sections/components/layout_components#content-header), [Navbar Dropdown](/sections/components/layout_components#navbar-dropdown), [Navbar Dropdown Item](/sections/components/layout_components#navbar-dropdown-item), [Navbar Custom Menu](/sections/components/layout_components#navbar-custom-menu), [Navbar Notification](/sections/components/layout_components#navbar-notification), [Navbar Darkmode Widget](/sections/configuration/special_menu_items#navbar-darkmode-widget)
[Tools](/sections/components/tool_components) | [Datatables](/sections/components/tool_components#datatables), [Modal](/sections/components/tool_components#modal)
[Widgets](/sections/components/widget_components) | [Alert](/sections/components/widget_components#alert), [Callout](/sections/components/widget_components#callout), [Card](/sections/components/widget_components#card), [Direct Chat](/sections/components/widget_components#direct-chat) (with [DirectChatContact and DirectChatMsg](/sections/components/widget_components#direct-chat)), [Info Box](/sections/components/widget_components#info-box), [Post](/sections/components/widget_components#post), [ProfileColItem](/sections/components/widget_components#profile-col-item-profile-row-item), [ProfileRowItem](/sections/components/widget_components#profile-col-item-profile-row-item), [ProfileWidget](/sections/components/widget_components#profile-widget), [Progress](/sections/components/widget_components#progress), [Progress Group](/sections/components/widget_components#progress-group), [Ribbon](/sections/components/widget_components#ribbon), [Small Box](/sections/components/widget_components#small-box), [Timeline](/sections/components/widget_components#timeline) (with [TimelineItem and TimelineLabel](/sections/components/widget_components#timeline)), [Toast](/sections/components/widget_components#toast), [User Block](/sections/components/widget_components#user-block)

Each link will redirect you to the corresponding component documentation.

## How to Use a Component

A **blade component** is a custom tag you write on your own blade views, and that Laravel replaces with a piece of markup when the view is rendered. All the components of this package are named `<x-adminlte-{name}>` and are available as soon as the package is installed, there is nothing to register and nothing to publish.

They are placed inside the sections of a blade file that extends the layout of this package, most commonly the `content` section described on the [usage](/sections/overview/usage) page:

```blade
{{-- resources/views/dashboard.blade.php --}}

@extends('adminlte::page')

@section('content')
    <x-adminlte-card title="My first card" theme="primary" icon="bi bi-star-fill">
        The content of the card goes here.
    </x-adminlte-card>
@stop
```

Two terms are used on every component page and are worth introducing here:

- **Attribute**: a value you pass to the component, written like an HTML attribute (`title="My first card"`). An attribute whose value is a PHP expression instead of a literal string is prefixed with a colon, as in `:update-cfg="['period' => 30]"`. A boolean attribute may be written without any value at all (`enable-dropdown-mode` is the same as `:enable-dropdown-mode="true"`). The attribute names are always the **kebab-case** form of the property (`badge-label`, `url-target`, …), and every attribute the component does not know about is copied through to the main element it renders.

- **Slot**: a block of markup you place **inside** the component tag. Everything between the opening and the closing tag fills the **default slot**. Some components also accept extra, named slots, which are written with the Laravel `<x-slot>` tag:

  ```blade
  <x-adminlte-card title="With a footer">
      This text fills the default slot, the body of the card.

      <x-slot name="footerSlot">
          And this one fills the footer.
      </x-slot>
  </x-adminlte-card>
  ```

> [!Note]
> The **text attributes** of the components (a `title`, a `text`, a `description`, a `label`, …) are passed through an HTML entity decoder before being rendered, so an entity such as `&middot;` or `&nbsp;` written on one of them reaches the page as the character it stands for. The decoded value is still **escaped** when it is printed, so it can never inject markup: use the matching slot when you need real markup instead.

Some components need an extra Javascript library to work, which this package calls a **plugin**. Those components say so at the top of their section and list the **plugin key** you have to enable, as explained on the [plugins configuration](/sections/configuration/plugins) page.

> [!Note]
> The **Navbar Notification** and **Navbar Darkmode Widget** components are also available as [special menu items](/sections/configuration/special_menu_items), which is how you normally place them: adding an entry to the `menu` configuration renders them in the topbar without writing any blade.

## About the Icons Used on the Examples

**AdminLTE v4** ships [Bootstrap Icons](https://icons.getbootstrap.com/) as its icon set, and every `icon` attribute of the components (and every icon default of this package) uses a Bootstrap Icons class name like `bi bi-person` or `bi bi-bell-fill`. The examples on the following pages are written with those names.

The `icon` attributes are plain **class strings** that are copied verbatim into an `<i>` element, so nothing prevents you from using another icon font. If you prefer, for example, **Font Awesome**, just load its stylesheet on your layout (for example, with a `@section('adminlte_css_pre')` block or through your asset bundling setup) and pass its class names instead:

```blade
{{-- Bootstrap Icons (the AdminLTE v4 default) --}}
<x-adminlte-button label="Save" theme="success" icon="bi bi-save"/>

{{-- Any other icon font you loaded on your own (Font Awesome here) --}}
<x-adminlte-button label="Save" theme="success" icon="fas fa-save"/>
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
> On the **AdminLTE v4** palette the `lightblue` color was renamed to `sky` and the `maroon` color was renamed to `pink`, while `purple` and `lime` were dropped in favour of `violet` and `olive`. The widget components still accept the **AdminLTE v3** color names (`lightblue`, `maroon`, `purple`, `lime`, `blue`, `red`, `green`, `yellow`, `cyan`, `gray` and `gray-dark`) and map them to their v4 equivalent on the fly. If you'd rather keep the old names as real CSS classes, enable the `assets.extended_colors_v3_aliases` option **in addition to** `assets.extended_colors`, so that the `adminlte-colors-v3.css` stylesheet is loaded instead of the v4 one and no mapping is applied. Note the alias option alone does nothing: no palette stylesheet is emitted at all while `assets.extended_colors` stays disabled.

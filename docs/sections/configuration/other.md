| Other Configuration
| -------------------
| [Assets](#assets)
| [Laravel Mix](#laravel-mix)
| [Laravel Vite](#laravel-vite)
| [Livewire](#livewire)

## Assets

AdminLTE v4 bundles Bootstrap 5.3 into its own stylesheet, but it still needs a few external resources at runtime: the Bootstrap JavaScript bundle, the Bootstrap Icons font, the OverlayScrollbars plugin (used by the main sidebar) and the web font. None of them is distributed inside the `almasaeed2010/adminlte` composer package, so this package can either serve them from a CDN or from your public folder.

The following configuration options are available:

- __`assets.mode`__

  How the base assets are served. Use `'local'` (the default) to serve the files published into your public folder, or `'cdn'` to always use the configured CDN locations.

- __`assets.cdn_fallback`__

  When enabled (the default) and a local asset is not published yet, its CDN location is used instead. This keeps a fresh installation working before running `php artisan adminlte:install`.

- __`assets.adminlte_version`__

  The **AdminLTE** version substituted into the `{version}` placeholder of the CDN locations. See [The AdminLTE version of the CDN locations](#the-adminlte-version-of-the-cdn-locations) below.

- __`assets.extended_colors`__

  Loads the optional AdminLTE palette stylesheet and generates the missing component families on top of it. See [The extended color palette](#the-extended-color-palette) below.

- __`assets.extended_colors_v3_aliases`__

  Loads `adminlte-colors-v3.css` instead of `adminlte-colors.css`, which keeps the AdminLTE v3 color names (for example `lightblue` and `maroon`, renamed to `sky` and `pink` in v4). Only has an effect when `assets.extended_colors` is enabled.

- __`assets.bootstrap_js`__, __`assets.bootstrap_icons`__, __`assets.overlayscrollbars`__

  Set any of them to `false` when you provide that resource on your own (for example through your asset bundling setup).

- __`assets.local`__ and __`assets.cdn`__

  The location of every asset. The `local` paths are relative to the public folder, the `cdn` ones are absolute URLs. The RTL variant of the AdminLTE stylesheets is selected automatically when the [RTL mode](./layout_and_styling.md#rtl-mode) is active.

To serve the third party assets locally, install them with `npm` and publish them:

```sh
npm i bootstrap@^5.3 bootstrap-icons@^1.13 overlayscrollbars@^2.11
php artisan adminlte:install --only=vendor_assets
```

### The AdminLTE version of the CDN locations

Every AdminLTE CDN location in the `assets.cdn` array carries a **`{version}` placeholder** instead of a hard coded version number:

```php
'cdn' => [
    'adminlte_css' => 'https://cdn.jsdelivr.net/npm/admin-lte@{version}/dist/css/adminlte.min.css',
    ...
],
```

The placeholder is replaced at render time with the **version of `almasaeed2010/adminlte` that composer actually installed** in your project, read from the composer runtime metadata. So the CDN fallback follows your composer dependency automatically: when you bump the AdminLTE constraint in your `composer.json`, the CDN URLs move to the new version on their own and can never drift out of sync with the files published in `public/vendor/adminlte`.

The `assets.adminlte_version` option lets you override that:

- __`null`__ (the default): detect the composer installed version. This is what you want in almost every case.
- __a version string__ (for example `'4.9.0'`): always use that version on the CDN locations, whatever composer installed. Useful when you deliberately want to pin the CDN to a version, or when you removed the composer dependency and only rely on the CDN.

> [!Note]
> When the installed version cannot be detected, or when it is a development version such as `dev-master` (which is not resolvable on a CDN), the package falls back to a built-in version.

> [!Warning]
> The `{version}` placeholder is substituted inside the **`assets.cdn`** array and inside the file locations of the [plugins](./plugins.md) configuration, so a plugin pointing to an asset of the AdminLTE distribution stays in sync too.

### The extended color palette

The AdminLTE v4 core stylesheet only knows the **Bootstrap theme colors** (`primary`, `secondary`, `success`, `info`, `warning`, `danger`, `light`, `dark`). Enabling `assets.extended_colors` loads the optional AdminLTE palette stylesheet and unlocks the **extended AdminLTE colors**:

```php
'assets' => [
    ...
    'extended_colors' => true,
],
```

The available colors depend on which palette you load:

| `extended_colors_v3_aliases` | Stylesheet | Colors |
| ---------------------------- | ---------- | ------ |
| `false` (the default) | `adminlte-colors.css` | `amber`, `fuchsia`, `graphite`, `indigo`, `midnight`, `navy`, `olive`, `orange`, `pink`, `sky`, `slate`, `steel`, `teal`, `violet` |
| `true` | `adminlte-colors-v3.css` | `blue`, `cyan`, `fuchsia`, `gray`, `gray-dark`, `green`, `indigo`, `lightblue`, `lime`, `maroon`, `navy`, `olive`, `orange`, `pink`, `purple`, `red`, `teal`, `yellow` |

The palette stylesheet itself provides the `bg-*`, `text-bg-*`, `text-*`, `border-*`, `link-*`, `bg-gradient-*`, `card-*`, `callout-*` and `direct-chat-*` families. It does **not** ship the `alert-*`, `btn-*` and `btn-outline-*` families, which is a problem for the blade components of this package, since a `theme` such as `teal` on an [Alert](/sections/components/widget_components#alert) or a [Button](/sections/components/basic_forms_components#button) would render unstyled.

So, when the extended colors are enabled, this package **generates those three missing families itself**, for every color of the active palette, from the custom properties the palette stylesheet already defines. The generated rules are emitted as a small inline `<style>` block in the page head.

The practical consequence:

```blade
{{-- These only work with 'assets.extended_colors' enabled --}}
<x-adminlte-alert theme="teal" title="Note">A teal alert.</x-adminlte-alert>
<x-adminlte-button theme="navy" label="Save"/>
<x-adminlte-button theme="outline-olive" label="Cancel"/>
```

> [!Warning]
> With `assets.extended_colors` left at its default `false`, an extended color name on the `theme` attribute of any component silently produces an **unstyled element** (there is no `btn-teal` or `alert-teal` class to match). If a themed component looks wrong, this option is the first thing to check.

> [!Tip]
> The generated `btn-*` rules assume a **white foreground** on the solid variant, which is the right choice for the saturated colors of both palettes. If you enable the v3 aliases and use a light color such as `yellow` or `lime` on a solid button, add your own `text-dark` class to keep the label readable.

## Laravel Mix

> [!Important]
> Please, be sure you're familiar with [Laravel Mix](https://laravel-mix.com/) before changing or using this configuration.

If you want to use **Laravel Mix** to compile the assets into single files instead of publishing them in the `/public/vendor` folder, start by installing the required `NPM` packages:

```sh
npm i admin-lte@^4.8 bootstrap@^5.3 bootstrap-icons@^1.13 overlayscrollbars@^2.11
```

Now, add the following to your `resources/js/app.js` file:

```javascript
import 'bootstrap';
import 'overlayscrollbars';
import 'admin-lte';
```

Also, add the following to your `resources/css/app.css` (or `app.scss`) file:

```scss
// OverlayScrollbars
@import 'overlayscrollbars/overlayscrollbars.css';
// Bootstrap Icons
@import 'bootstrap-icons/font/bootstrap-icons.css';
// AdminLTE (Bootstrap 5.3 is already bundled inside it)
@import 'admin-lte/dist/css/adminlte.css';
```

> [!Tip]
> On the [RTL mode](./layout_and_styling.md#rtl-mode), import `admin-lte/dist/css/adminlte.rtl.css` instead.

Finally, set the `laravel_asset_bundling` configuration option to `'mix'` to enable the load of the `css/app.css` & `js/app.js` files that are located in the public folder.

> [!Warning]
> The legacy `enabled_laravel_mix`, `laravel_mix_css_path` and `laravel_mix_js_path` options were removed. Use `laravel_asset_bundling => 'mix'` together with `laravel_css_path` and `laravel_js_path` instead.

Also, you can change the paths used to lookup for the compiled `JS` and `CSS` files using the next configuration options.

- __`laravel_css_path`__

  Path (including file name) to the compiled `CSS` file. This path should be relative to the public folder. Default value is `css/app.css`

- __`laravel_js_path`__

  Path (including file name) to the compiled `JS` file. This path should be relative to the public folder. Default value is `js/app.js`

## Laravel Vite

> [!Important]
> Please, be sure you're familiar with [Laravel Vite](https://laravel.com/docs/vite) before changing or using this configuration.

To use the **Laravel Vite** assets bundling tool with this package, set the `laravel_asset_bundling` configuration option to `'vite'` or `'vite_js_only'` (if you expect to import your `CSS` via `JavaScript`) to enable the load of your bundled assets in the master layout. The `NPM` packages and the imports are the same ones listed on the [Laravel Mix](#laravel-mix) section.

Also, you can change the paths used to lookup for the bundled `JS` and `CSS` files using the next configuration options.

- __`laravel_css_path`__

  Path (including file name) to the bundled `CSS` file. This path should be relative to the root folder. Default value is `resources/css/app.css` if the configuration option does not exists.

- __`laravel_js_path`__

  Path (including file name) to the bundled `JS` file. This path should be relative to the root folder. Default value is `resources/js/app.js` if the configuration option does not exists.

> [!Note]
> When you bundle the assets yourself, the package stops emitting the AdminLTE core stylesheet and script, but it still emits the third party resources that are enabled on the [assets](#assets) configuration. Disable `assets.bootstrap_js`, `assets.bootstrap_icons` and `assets.overlayscrollbars` when your bundle already includes them.

## Livewire

> [!Important]
> Please, be sure you're familiar with [Livewire](https://livewire.laravel.com/) before changing or using this configuration.

This option provides support to the [Livewire](https://livewire.laravel.com/) package. Before enabling livewire support, you must install the livewire package using composer:

```sh
composer require livewire/livewire
```

After that, just enable livewire support in the configuration file:

```php
/*
|--------------------------------------------------------------------------
| Livewire configuration
|--------------------------------------------------------------------------
|
| Here we can modify the livewire configuration.
|
*/

'livewire' => true,
```

This will setup the `@livewireStyles` and the `@livewireScripts` directives correctly on the `master.blade.php` blade file of this package, as explained on the [Livewire Documentation](https://livewire.laravel.com/docs/installation#manually-including-livewires-frontend-assets).

# Assets and Integrations

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

- __`assets.palette.primary`__

  Remaps the **primary color** of the whole template to any other color of the active palette. See [Tuning the palette](#tuning-the-palette) below.

- __`assets.palette.contrast`__

  Applies the WCAG AA contrast correction of the palette. See [Tuning the palette](#tuning-the-palette) below.

- __`assets.bootstrap_js`__, __`assets.bootstrap_icons`__, __`assets.overlayscrollbars`__

  Set any of them to `false` when you provide that resource on your own (for example through your asset bundling setup).

- __`assets.local`__ and __`assets.cdn`__

  The location of every asset. The `local` paths are relative to the public folder, the `cdn` ones are absolute URLs. Both arrays hold the very same set of keys, so an asset can always be switched between the two delivery modes. The RTL variant of the AdminLTE stylesheets is selected automatically when the [RTL mode](./layout_and_styling.md#rtl-mode) is active.

The keys of those two arrays are the next ones:

Key | What it points to | Published by
----|-------------------|--------------
`adminlte_css` | The AdminLTE v4 core stylesheet | `--only=assets`
`adminlte_rtl_css` | The same stylesheet, right-to-left variant | `--only=assets`
`adminlte_js` | The AdminLTE v4 script (the sidebar, the card tools, the color mode, …) | `--only=assets`
`colors_css` / `colors_rtl_css` | The [extended color palette](#the-extended-color-palette) stylesheet | `--only=assets`
`colors_v3_css` / `colors_v3_rtl_css` | The same palette with the **AdminLTE v3** color names | `--only=assets`
`bootstrap_js` | The Bootstrap 5.3 Javascript bundle | `--only=vendor_assets`
`bootstrap_icons_css` | The Bootstrap Icons font | `--only=vendor_assets`
`overlayscrollbars_css` / `overlayscrollbars_js` | OverlayScrollbars, used by the sidebar | `--only=vendor_assets`
`fonts_css` | The `Source Sans 3` web font, controlled by [`google_fonts.allowed`](/sections/configuration/basic_configuration#google-fonts) | nothing, see the note below

> [!Note]
> **No package resource publishes the web font.** The `assets.local.fonts_css` path is only served when you put the font there yourself, and until then the `cdn_fallback` option keeps it coming from the CDN. Set `google_fonts.allowed` to `false` when your application must not reach an external font provider at all.

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

### Tuning the palette

The palette stylesheets provide two attributes on the `<html>` element that this package sets for you. Both **require `assets.extended_colors` to be enabled**, since the attributes only exist inside those stylesheets.

#### Remapping the primary color

```php
'assets' => [
    'extended_colors' => true,

    'palette' => [
        'primary' => 'teal',
    ],
],
```

This emits `data-lte-primary="teal"` on the `<html>` element, which repoints `--bs-primary` and everything derived from it. It is the cheapest way to brand the whole panel: every `btn-primary`, `text-bg-primary`, link and focus ring follows, with no stylesheet of your own.

The accepted values are the colors of the **active palette** plus the Bootstrap theme colors, except `primary` itself. An unknown value is ignored, and the attribute is not emitted at all.

#### The contrast correction

Eight of the eighteen colors of the **v3 palette** miss the WCAG AA contrast ratio of 4.5:1. The stylesheet ships a correction for exactly those, enabled by the `data-lte-contrast="aa"` attribute.

Since this package steers you into that palette through `extended_colors_v3_aliases`, the correction is **applied automatically** whenever the v3 palette is active:

```php
'palette' => [
    'contrast' => null,   // Automatic: applied on the v3 palette (the default)
    // 'contrast' => 'aa',   // Always apply it
    // 'contrast' => false,  // Never apply it
],
```

The correction also feeds the contrast decisions of the components: with it active, the footer link of a [Small Box](/sections/components/widget_components#small-box) and the close button of a themed [Modal](/sections/components/tool_components#modal) switch to their dark variants on the affected colors.

## CSS Variables

The AdminLTE v4 theming is driven by the **Bootstrap 5.3 and AdminLTE custom properties**, so overriding a handful of them is enough for most brandings and needs no stylesheet of your own. The `css_variables` option emits them as an inline block in the document head, after the AdminLTE stylesheets and before your own:

```php
'css_variables' => [
    '--bs-primary' => '#6f42c1',
    '--bs-primary-rgb' => '111, 66, 193',
    '--bs-body-bg' => '#fbfbfe',
    '--bs-border-radius' => '.5rem',
    '--bs-font-sans-serif' => '"Inter", system-ui, sans-serif',
],

'css_variables_scope' => ':root',

// The sidebar properties need their own block, see below.
'css_variables_sidebar' => [
    '--lte-sidebar-color' => 'rgba(255, 255, 255, .8)',
    '--lte-sidebar-menu-active-color' => '#fff',
],
```

- __`css_variables`__

  A map of custom property names to values. Only names matching `--[a-zA-Z0-9_-]+` are accepted, and a value containing `;`, `{`, `}`, `<`, `>`, a backslash, a comment, an `@import` or an `expression(...)` is dropped, so a configuration value cannot inject arbitrary CSS. Leave the array empty (the default) and no `<style>` block is emitted at all.

- __`css_variables_scope`__

  Where to declare them: `':root'` (the default) or `'body'`. Any other value falls back to `':root'`. Use `'body'` when a variable has to lose against something declared on `:root` by a stylesheet of your own.

- __`css_variables_sidebar`__

  The same, but declared on the **sidebar element**. This is a separate option because AdminLTE redeclares every `--lte-sidebar-*` property on `.app-sidebar` under a color mode selector:

  ```css
  [data-bs-theme=dark] .app-sidebar { --lte-sidebar-color: #c2c7d0; ... }
  ```

  That rule is more specific than `:root`, and the sidebar carries `data-bs-theme` by default (see the `sidebar_theme` option), so a `--lte-sidebar-color` placed in `css_variables` would be **silently ignored**. The values of this option are emitted as `[data-bs-theme] .app-sidebar, [data-bs-theme].app-sidebar, .app-sidebar`, which matches that specificity in both of the shapes AdminLTE uses, and comes later on the document, so it wins in both color modes.

> [!Warning]
> **These options are not color-mode aware.** A value you set here applies to the **light and the dark mode alike**, because the block is emitted after the AdminLTE dark tokens and wins over them. Setting `'--bs-body-bg' => '#fbfbfe'` therefore gives you a white body in dark mode too. When a property has to differ per mode, declare it in a stylesheet of your own under `[data-bs-theme="dark"]` instead, and keep only the mode-independent values here (the border radius, the font stack, the spacing).

> [!Note]
> Not every `--lte-*` property in the stylesheet is actually consumed. `--lte-sidebar-active-color`, for example, is declared but never read — the active sidebar link is painted by `--lte-sidebar-menu-active-color`. And the two search widgets (`.navbar-search`, `.sidebar-search`) declare their `--lte-search-field-*` properties **on the element itself**, which no ancestor scope can override; those need a stylesheet of your own.

> [!Tip]
> To repoint the **primary color** of the whole template, prefer [`assets.palette.primary`](#tuning-the-palette): it uses the AdminLTE `data-lte-primary` attribute and recomputes every derived shade, while overriding `--bs-primary` by hand only changes the base color. Use `css_variables` for the properties the palette does not cover, such as the border radius, the font stack or the sidebar colors.

> [!Note]
> The block is emitted **before** the `adminlte_css` section, so a stylesheet you add there still wins. It is also emitted before the [color mode](/sections/configuration/layout_and_styling#color-mode) does its work, so to theme the dark mode separately, declare the variable inside your own stylesheet under `[data-bs-theme="dark"]` instead.

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

  Path (including file name) to the compiled `CSS` file. This path should be relative to the **public** folder, typically `css/app.css`.

- __`laravel_js_path`__

  Path (including file name) to the compiled `JS` file. This path should be relative to the **public** folder, typically `js/app.js`.

> [!Warning]
> The two options are shared by the Mix and the Vite setups, and the two tools expect different path shapes. The shipped defaults (`resources/css/app.css` and `resources/js/app.js`) are the **Vite** ones, since Vite is the Laravel default. When you set `laravel_asset_bundling => 'mix'`, change both options to their public folder relative form.

## Laravel Vite

> [!Important]
> Please, be sure you're familiar with [Laravel Vite](https://laravel.com/docs/vite) before changing or using this configuration.

To use the **Laravel Vite** assets bundling tool with this package, set the `laravel_asset_bundling` configuration option to `'vite'` or `'vite_js_only'` (if you expect to import your `CSS` via `JavaScript`) to enable the load of your bundled assets in the master layout. The `NPM` packages and the imports are the same ones listed on the [Laravel Mix](#laravel-mix) section.

Also, you can change the paths used to lookup for the bundled `JS` and `CSS` files using the next configuration options.

- __`laravel_css_path`__

  Path (including file name) to the bundled `CSS` file. This path should be relative to the **root** folder. The default value is `resources/css/app.css`.

- __`laravel_js_path`__

  Path (including file name) to the bundled `JS` file. This path should be relative to the **root** folder. The default value is `resources/js/app.js`.

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

## Single Page Navigation

Turbo Drive and the Livewire `wire:navigate` visits replace the `body` of the document without a full page load. Two things break on such a visit unless something re-runs them:

1. **The inline scripts of this package.** A script bound on `DOMContentLoaded` never runs a second time: the swapped body re-executes it, but the document is already loaded by then, so the event never fires again. Every inline script of the package now goes through a small `_AdminLTE_Ready()` helper instead, which runs the callback immediately when the document is already loaded. The handful of listeners bound to the `document` itself go through `_AdminLTE_Once()`, since they survive a body swap and would otherwise pile up on every visit.

2. **The AdminLTE plugins.** AdminLTE re-initializes them on the `turbo:load` event of Turbo Drive, but it knows nothing about Livewire, so after a `wire:navigate` visit the sidebar, the treeview and the card tools would stay dead. The package bridges the Livewire event to the AdminLTE lifecycle:

```php
'spa_navigation' => true,
```

- __`spa_navigation`__

  When enabled (the default), the package listens to `livewire:navigated` and calls `adminlte.initialize()`. That method tears the previous cycle down before re-running the plugin initializations, so calling it again is safe. Set the option to `false` when your application handles the lifecycle on its own.

> [!Note]
> Nothing has to be enabled for **Turbo Drive**: AdminLTE binds `turbo:load` and `turbo:before-render` itself, and the `_AdminLTE_Ready()` helper covers the inline scripts of the package in both cases.

> [!Tip]
> If you write your own inline scripts inside a page that is reached through `wire:navigate`, use the same helper instead of `DOMContentLoaded`:
>
> ```blade
> @push('js')
> <script>
>     window._AdminLTE_Ready(() => {
>         // Runs on the first load and after every in-app navigation.
>     });
> </script>
> @endpush
> ```

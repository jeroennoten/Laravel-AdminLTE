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

- __`assets.extended_colors`__

  Loads the optional `adminlte-colors.css` stylesheet, which provides the extended AdminLTE color palette (`navy`, `olive`, `sky`, …) as `bg-*`, `text-bg-*`, `bg-gradient-*`, `card-*` and `callout-*` classes. The AdminLTE v4 core stylesheet only knows the Bootstrap theme colors.

- __`assets.extended_colors_v3_aliases`__

  Loads `adminlte-colors-v3.css` instead, which keeps the AdminLTE v3 color names (for example `lightblue` and `maroon`, renamed to `sky` and `pink` in v4).

- __`assets.bootstrap_js`__, __`assets.bootstrap_icons`__, __`assets.overlayscrollbars`__

  Set any of them to `false` when you provide that resource on your own (for example through your asset bundling setup).

- __`assets.local`__ and __`assets.cdn`__

  The location of every asset. The `local` paths are relative to the public folder, the `cdn` ones are absolute URLs. The RTL variant of the AdminLTE stylesheets is selected automatically when the [RTL mode](./layout_and_styling.md#rtl-mode) is active.

To serve the third party assets locally, install them with `npm` and publish them:

```sh
npm i bootstrap@^5.3 bootstrap-icons@^1.13 overlayscrollbars@^2.11
php artisan adminlte:install --only=vendor_assets
```

## Laravel Mix

> [!Important]
> Please, be sure you're familiar with [Laravel Mix](https://laravel.com/docs/mix) before changing or using this configuration.

If you want to use **Laravel Mix** to compile the assets into single files instead of publishing them in the `/public/vendor` folder, start by installing the required `NPM` packages:

```sh
npm i admin-lte@^4.0 bootstrap@^5.3 bootstrap-icons@^1.13 overlayscrollbars@^2.11
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

This option provides support to the [Livewire](https://laravel-livewire.com/) package. Before enabling livewire support, you must install the livewire package using composer:

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

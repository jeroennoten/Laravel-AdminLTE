| Other Configuration
| -------------------
| [Laravel Mix](#laravel-mix)
| [Laravel Vite](#laravel-vite)
| [Livewire](#livewire)

## Laravel Mix

> [!Important]
> Please, be sure you're familiar with [Laravel Mix](https://laravel.com/docs/mix) before changing or using this configuration.

If you want to use **Laravel Mix** to compile assets and plugins into single files instead of publishing all the assets and plugins files in the `/public/vendor` folder, start by installing the following `NPM` packages:

```sh
npm i @fortawesome/fontawesome-free
npm i icheck-bootstrap
npm i overlayscrollbars
```

Now, add the following to your `bootstrap.js` file after `window.$ = window.jQuery = require('jquery');`:

```javascript
require('overlayscrollbars');
require('bootstrap');
require('../../vendor/almasaeed2010/adminlte/dist/js/adminlte');
```

Also, replace your `app.scss` content by the following:

```scss
// Fonts
@import url('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic');
@import '~@fortawesome/fontawesome-free/css/all.css';
// OverlayScrollbars
@import '~overlayscrollbars/css/OverlayScrollbars.css';
// iCheck
@import '~icheck-bootstrap/icheck-bootstrap.css';
// AdminLTE
@import '../../vendor/almasaeed2010/adminlte/dist/css/adminlte.css';
// Bootstrap
// Already imported by AdminLTE
//@import '~bootstrap/scss/bootstrap';
```

### Laravel Mix config before `v3.14.0`

After preparing the Laravel Mix vendor files, set the `enabled_laravel_mix` configuration option to `true` to enable the load of `css/app.css` & `js/app.js` files that are located in the public folder.

- __`enabled_laravel_mix`__

  Enables Laravel Mix specific `css/js` load in master layout.

Also, you can change the paths used to lookup for the compiled `JS` and `CSS` files using the next configuration options.

- __`laravel_mix_css_path`__

  Path (including file name) to the compiled `CSS` file. This path should be relative to the public folder. Default value is `css/app.css`

- __`laravel_mix_js_path`__

  Path (including file name) to the compiled `JS` file. This path should be relative to the public folder. Default value is `js/app.js`

### Laravel Mix config after `v3.14.0`

For backward compatibility, we still support the old **Laravel Mix** configuration in newest version, however we recommend migrating to the new one as explained below.

To use **Laravel Mix** assets bundling tool after <Badge type="tip">v3.14.0</Badge>, set the `laravel_asset_bundling` configuration option to `'mix'` to enable the load of `css/app.css` & `js/app.js` files that are located in the public folder.

Also, you can change the paths used to lookup for the compiled `JS` and `CSS` files using the next configuration options.

- __`laravel_css_path`__

  Path (including file name) to the compiled `CSS` file. This path should be relative to the public folder. Default value is `css/app.css`

- __`laravel_js_path`__

  Path (including file name) to the compiled `JS` file. This path should be relative to the public folder. Default value is `js/app.js`

## Laravel Vite

> [!Important]
> Please, be sure you're familiar with [Laravel Vite](https://laravel.com/docs/vite) before changing or using this configuration.

> [!Important]
> Native support to **Laravel Vite** was added on version <Badge type="tip">v3.14.0</Badge>, so avoid using the options explained below on old package versions.

To use the **Laravel Vite** assets bundling tool with this package, set the `laravel_asset_bundling` configuration option to `'vite'` or `'vite-js-only'` (if you expect to import your `CSS` via `JavaScript`) to enable the load of your bundled assets in the master layout.

Also, you can change the paths used to lookup for the bundled `JS` and `CSS` files using the next configuration options.

- __`laravel_css_path`__

  Path (including file name) to the bundled `CSS` file. This path should be relative to the root folder. Default value is `resources/css/app.css` if the configuration option does not exists.

- __`laravel_js_path`__

  Path (including file name) to the bundled `JS` file. This path should be relative to the root folder. Default value is `resources/js/app.js` if the configuration option does not exists.

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

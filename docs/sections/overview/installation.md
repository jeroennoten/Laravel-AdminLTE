# Installation

> [!Important]
> The next steps are only valid for a fresh installation procedure, if you are performing an update on the package, please refers to the [Updating](/sections/overview/updating) section.

## 1. Require the package

On the root folder of your **Laravel** project, require the package using the `composer` tool:

```sh
composer require jeroennoten/laravel-adminlte
```

## 2. Install the package resources

Install the required package resources using the next command:

```sh
php artisan adminlte:install
```

This command will install:

- The underlying **AdminLTE v4 distribution files** (stylesheets, scripts and the default logo, including the RTL variants) in your `public/vendor/adminlte` folder.
- The package configuration at the `config/adminlte.php` file.
- The package translations in the `lang/vendor/adminlte/` folder.

## 3. Publish the third party assets

AdminLTE v4 needs four resources that it does not distribute itself: the **Bootstrap 5 JavaScript bundle**, the **Bootstrap Icons** font, **OverlayScrollbars** (used by the main sidebar) and the **Source Sans 3** web font. Install them with `npm` **before** the previous step, and `adminlte:install` publishes them along with everything else:

```sh
npm i bootstrap@^5.3 bootstrap-icons@^1.13 overlayscrollbars@^2.11 @fontsource/source-sans-3@^5.0
php artisan adminlte:install
```

If you already installed the package, publish them on their own:

```sh
php artisan adminlte:install --only=vendor_assets
```

> [!important]
> **This step is what keeps your visitors' data on your own server.** Whatever is not published is loaded from `cdn.jsdelivr.net` instead, which means the browser of every visitor sends its ip address to a third party. In the European Union that alone needs a legal basis, so self-hosting is usually the option you want.
>
> The package cannot publish what `npm` never installed, so a plain `adminlte:install` without the `npm i` above silently falls back to the CDN. Verify what you ended up with using `php artisan adminlte:status`.
>
> If you prefer not to serve the web font at all, set `google_fonts.allowed => false` in the configuration. The panel then uses the font stack of the operating system and requests no font.

The package detects the published files automatically. See the [assets configuration](/sections/configuration/other#assets) for the details and for the `cdn` mode.

> [!tip]
> You can use the **`--force`** option to overwrite previous existing files.
>
> You can use the **`--interactive`** option to be guided through the process and choose what you want to install.
> 
> You can check the installation status of the package resources with the command **`php artisan adminlte:status`**.

### The `vendor:publish` alternative

The `adminlte:install` command is the recommended way to install the resources, since it knows the individual files of the AdminLTE distribution and can report their status. For the cases where the standard Laravel workflow fits better (an automated deployment, for example), the package also registers the usual publish tags:

```sh
php artisan vendor:publish --tag=adminlte-config    # config/adminlte.php
php artisan vendor:publish --tag=adminlte-views     # resources/views/vendor/adminlte
php artisan vendor:publish --tag=adminlte-lang      # lang/vendor/adminlte
php artisan vendor:publish --tag=adminlte-assets    # public/vendor/adminlte/dist
```

Two differences are worth knowing before you reach for them:

- The `adminlte-assets` tag copies the **whole** AdminLTE distribution folder (source maps included), while `adminlte:install` publishes only the files the package actually serves. In the same way, `adminlte-views` copies **every** view of the package, the `components/` folder included, while the `main_views` resource deliberately leaves that folder to the `components` resource.
- There is **no publish tag** for the `vendor_assets`, `auth_views`, `auth_routes`, `components` and `error_views` resources. Those five are only reachable through `php artisan adminlte:install`.

## 4. Install the legacy authentication scaffolding (optional)

Optionally, this package offers a set of **AdminLTE** styled authentication views that you can use in replacement of the ones provided by the legacy [laravel/ui](https://github.com/laravel/ui) authentication scaffolding. If you are planning to use these views, then first require the **laravel/ui** package using composer and install the `bootstrap` scaffolding:

```sh
composer require laravel/ui
php artisan ui bootstrap --auth
```

Then, you can make the view replacements by executing the next artisan command:

```sh
php artisan adminlte:install --only=auth_views
```

> [!Important]
> The authentication scaffolding offers features like login, logout and registration. It is a recommendation to always read the [Laravel Authentication Documentation](https://laravel.com/docs/authentication) for details about the authentication scaffolding. Note that **Laravel** offers some starter kits (like [Laravel-Breeze](https://laravel.com/docs/starter-kits#laravel-breeze)) besides the legacy [laravel/ui](https://github.com/laravel/ui) package. So, using the authentication views from this package is **OPTIONAL** and **UP TO YOU**.

## 5. Install the error views (optional)

The package ships **AdminLTE styled error pages** for the http status codes Laravel renders (`401`, `403`, `404`, `419`, `429`, `500` and `503`). Publish them with:

```sh
php artisan adminlte:install --only=error_views
```

The command writes one thin view per status code into `resources/views/errors/`, which is where Laravel looks for them:

```blade
{{-- resources/views/errors/404.blade.php --}}
@extends('adminlte::errors.404')
```

Because the published file only **extends** the package view, an update of the package reaches your error pages without republishing them. See [error views](/sections/configuration/views_customization#error-views) for how to customize their content.

> [!Note]
> The error views are not part of any `--type` option, since publishing them replaces the error pages your application may already have. They are only installed when you ask for them explicitly, either with `--only=error_views` or with `--with=error_views`.

## 6. Use the package

Jump to the [Usage Section](/sections/overview/usage) to read how to use the main **AdminLTE blade template** provided by this package.

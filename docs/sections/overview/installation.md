> [!Important]
> The next steps are only valid for a fresh installation procedure, if you are performing an update on the package, please refers to the [Updating](/sections/overview/updating) section.

### 1. Require the package

On the root folder of your **Laravel** project, require the package using the `composer` tool:

```sh
composer require jeroennoten/laravel-adminlte
```

### 2. Install the package resources

Install the required package resources using the next command:

```sh
php artisan adminlte:install
```

This command will install:

- The underlying **AdminLTE v4 distribution files** (stylesheets, scripts and the default logo, including the RTL variants) in your `public/vendor/adminlte` folder.
- The package configuration at the `config/adminlte.php` file.
- The package translations in the `lang/vendor/adminlte/` folder (or `resources/lang/vendor/adminlte/` on older Laravel versions).

### 3. Publish the third party assets (optional)

AdminLTE v4 needs a few resources that it does not distribute itself: the **Bootstrap 5 JavaScript bundle**, the **Bootstrap Icons** font and **OverlayScrollbars** (used by the main sidebar). Out of the box the package loads them from a CDN. To serve them from your own domain instead, install them with `npm` and publish them:

```sh
npm i bootstrap@^5.3 bootstrap-icons@^1.13 overlayscrollbars@^2.11
php artisan adminlte:install --only=vendor_assets
```

The package detects the published files automatically. See the [assets configuration](/sections/configuration/other#assets) for the details and for the `cdn` mode.

> [!tip]
> You can use the **`--force`** option to overwrite previous existing files.
>
> You can use the **`--interactive`** option to be guided through the process and choose what you want to install.
> 
> You can check the installation status of the package resources with the command **`php artisan adminlte:status`**.

### 4. Install the legacy authentication scaffolding (optional)

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

### 5. Use the package

Jump to the [Usage Section](/sections/overview/usage) to read how to use the main **AdminLTE blade template** provided by this package.

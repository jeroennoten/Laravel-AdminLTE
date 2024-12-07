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

- The underlying **AdminLTE distribution files**  and its dependencies (`Bootstrap`, `jQuery`, etc.) in your `public/vendor` folder.
- The package configuration at the `config/adminlte.php` file.
- The package translations in the `resources/lang/vendor/adminlte/` folder (or `lang/vendor/adminlte/` folder for `Laravel >= 9.x` versions).

> [!tip]
> You can use the **`--force`** option to overwrite previous existing files.
>
> You can use the **`--interactive`** option to be guided through the process and choose what you want to install.
> 
> You can check the installation status of the package resources with the command **`php artisan adminlte:status`**.

### 3. Install the legacy authentication scaffolding (optional)

Optionally, and only for **Laravel 7+ versions**, this package offers a set of **AdminLTE** styled authentication views that you can use in replacement of the ones provided by the legacy [laravel/ui](https://github.com/laravel/ui) authentication scaffolding. If you are planning to use these views, then first require the **laravel/ui** package using composer and install the `bootstrap` scaffolding:

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

### 4. Use the package

Jump to the [Usage Section](/sections/overview/usage) to read how to use the main **AdminLTE blade template** provided by this package.

# Easy AdminLTE integration with Laravel 5

[![Build Status](https://travis-ci.org/jeroennoten/Laravel-AdminLTE.svg?branch=master)](https://travis-ci.org/JeroenNoten/Laravel-AdminLTE)

This package provides an easy way to set up [AdminLTE](https://almsaeedstudio.com) with Laravel 5.
I really like AdminLTE and I use it in almost all of my Laravel projects.
So I wanted an easy way to pull this in, however I did not want a lot of
features baked in (like others do, e.g.
[Orchestra Platform](http://orchestraplatform.com/),
[SleepingOwl Admin](http://sleeping-owl.github.io/),
[Administrator](http://administrator.frozennode.com/)).
I just want a template to build my admin panel. Therefore, I made this package. It also includes a replacement for `make:auth` that uses AdminLTE styled views instead of the default ones.

- [Installation](#installation)
- [Updating](#updating)
- [Usage](#usage)
- [The `make:adminlte` artisan command](#the-makeadminlte-artisan-command)
- [Using the authentication views without the `make:adminlte` command](#using-the-authentication-views-without-the-makeadminlte-command)
- [Configuration](#configuration)
  - [Menu configuration at runtime](#menu-configuration-at-runtime)
- [Translations](#translations)
- [Customize views](#customize-views)

## Installation

1. Require the package using composer:

    ```
    composer require jeroennoten/laravel-adminlte
    ```

2. Add the service provider to the `providers` in `config/app.php`:

    ```php
    JeroenNoten\LaravelAdminLte\ServiceProvider::class,
    ```

3. Publish the public assets:

    ```
    php artisan vendor:publish --provider="JeroenNoten\LaravelAdminLte\ServiceProvider" --tag=assets
    ```

## Updating

1. To update this package, first update the composer package:

    ```
    composer update jeroennoten/laravel-adminlte
    ```

2. Then, publish the public assets with the `--force` flag to overwrite existing files

    ```
    php artisan vendor:publish --provider="JeroenNoten\LaravelAdminLte\ServiceProvider" --tag=assets --force
    ```

## Usage

To use the template, create a blade file and extend the layout with `@extends('adminlte::page')`.
This template yields the following sections:

- `title`: for in the `<title>` tag
- `content_header`: title of the page, above the content
- `content`: all of the page's content
- `css`: extra stylesheets (located in `<head>`)
- `js`: extra javascript (just before `</body>`)

All sections are in fact optional. Your blade template could look like the following.

```html
{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
```

You now just return this view from your controller, as usual. Check out [AdminLTE](https://almsaeedstudio.com) to find out how to build beautiful content for your admin panel.

### The `make:adminlte` artisan command

> Note: only for Laravel 5.2 and higher

This package ships with a `make:adminlte` command that behaves exactly like `make:auth` (introduced in Laravel 5.2) but replaces the authentication views with AdminLTE style views.

```
php artisan make:adminlte
```

This command should be used on fresh applications, just like the `make:auth` command

### Using the authentication views without the `make:adminlte` command

If you want to use the included authentication related views manually, you can create the following files and only add one line to each file:

- `resources/views/auth/login.blade.php`:
```
@extends('adminlte::login')
```
- `resources/views/auth/register.blade.php`
```
@extends('adminlte::register')
```
- `resources/views/auth/passwords/email.blade.php`
```
@extends('adminlte::passwords.email')
```
- `resources/views/auth/passwords/reset.blade.php`
```
@extends('adminlte::passwords.reset')
```

By default, the login form contains a link to the registration form.
If you don't want a registration form, set the `register_url` setting to `null` and the link will not be displayed.

## Configuration

First, publish the configuration file:

```
php artisan vendor:publish --provider="JeroenNoten\LaravelAdminLte\ServiceProvider" --tag=config
```

Now, edit `config/adminlte.php` to configure the title, skin, menu, URLs etc. All configuration options are explained in the comments. However, I want to shed some light on the `menu` configuration.
You can configure your menu as follows:

```php
'menu' => [
    'MAIN NAVIGATION',
    [
        'text' => 'Blog',
        'url' => 'admin/blog',
    ],
    [
        'text' => 'Pages',
        'url' => 'admin/pages',
        'icon' => 'file'
    ],
    'ACCOUNT SETTINGS',
    [
        'text' => 'Profile',
        'url' => 'admin/settings',
        'icon' => 'user'
    ],
    [
        'text' => 'Change Password',
        'url' => 'admin/settings',
        'icon' => 'lock'
    ],
],
```

With a single string, you specify a menu header item to separate the items.
With an array, you specify a menu item. `text` and `url` are required attributes.
The `icon` is optional, you get an [open circle](http://fontawesome.io/icon/circle-o/) if you leave it out.
The available icons that you can use are those from [Font Awesome](http://fontawesome.io/icons/).
Just specify the name of the icon and it will appear in front of your menu item.

### Menu configuration at runtime

It is also possible to configure the menu at runtime, e.g. in the boot of any service provider.
Use this if your menu is not static, for example when it depends on your database or the locale.
It is also possible to combine both approaches. The menus will simply be concatenated and the order of service providers
determines the order in the menu.

To configure the menu at runtime, register a handler or callback for the `MenuBuilding` event, for example in the `boot()` method of a service provider:

```php
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class AppServiceProvider extends ServiceProvider
{

    public function boot(Dispatcher $events)
    {
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $event->menu->add('MAIN NAVIGATION');
            $event->menu->add([
                'text' => 'Blog',
                'url' => 'admin/blog',
            ]);
        });
    }
    
}
```
The configuration options are the same as in the static configuration files.

A more practical example that actually uses translations and the database:

```php
    public function boot(Dispatcher $events)
    {
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $event->menu->add(trans('menu.pages'));

            $items = Page::all()->map(function (Page $page) {
                return [
                    'text' => $page['title'],
                    'url' => route('admin.pages.edit', $page)
                ];
            });

            $event->menu->add(...$items);
        });
    }
```

This event-based approach is used to make sure that your code that builds the menu runs only when the admin panel is actually displayed and not on every request.


## Translations

At the moment, English, German and Dutch translations are available out of the box.
Just specifiy the language in `config/app.php`.
If you need to modify the texts or add other languages, you can publish the language files:

```
php artisan vendor:publish --provider="JeroenNoten\LaravelAdminLte\ServiceProvider" --tag=translations
```

Now, you can edit translations or add languages in `resources/lang/vendor/adminlte`.

## Customize views

If you need full control over the provided views, you can publish them:

```
php artisan vendor:publish --provider="JeroenNoten\LaravelAdminLte\ServiceProvider" --tag=views
```

Now, you can edit the views in `resources/views/vendor/adminlte`.

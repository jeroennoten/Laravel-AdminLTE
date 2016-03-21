# Easy AdminLTE integration with Laravel 5

This package provides an easy way to set up [AdminLTE](https://almsaeedstudio.com) with Laravel 5.
I really like AdminLTE and I use it in almost all of my Laravel projects.
So I wanted an easy way to pull this in, however I did not want a lot of
features baked in (like others do, e.g.
[Orchestra Platform](http://orchestraplatform.com/),
[SleepingOwl Admin](http://sleeping-owl.github.io/),
[Administrator](http://administrator.frozennode.com/)).
I just want a template to build my admin panel. Therefore, I made this package.
Right now the functionality is very basic, I will add more features later, but let's
start simple.

- [Installation](#installation)
- [Updating](#updating)
- [Usage](#usage)
- [Login and Registration Form](#login-and-registration-form)
- [Configuration](#configuration)
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

### Login and Registration Form

AdminLTE also comes with nice [login](https://almsaeedstudio.com/themes/AdminLTE/pages/examples/login.html) and [registration](https://almsaeedstudio.com/themes/AdminLTE/pages/examples/register.html) forms.
To use these forms with Laravel, the only thing you need to do is to create a `auth.login` and `auth.register` view (`resources/views/auth/login.blade.php` and `resources/views/auth/register.blade.php`)
and put `@extends('adminlte::login')` or `@extends('adminlte::register')` at the top of the file. Don't forget to set up the routing for authentication, as [explained in the Laravel documentation](http://laravel.com/docs/5.1/authentication#included-routing) and you're good to go.
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

## Translations

At the moment, Dutch and English translations are available out of the box.
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

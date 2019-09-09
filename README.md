# Easy AdminLTE integration with Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeroennoten/Laravel-AdminLTE.svg?style=flat-square)](https://packagist.org/packages/jeroennoten/Laravel-AdminLTE)
[![Build Status](https://travis-ci.org/jeroennoten/Laravel-AdminLTE.svg?branch=master)](https://travis-ci.org/jeroennoten/Laravel-AdminLTE)
[![Quality Score](https://img.shields.io/scrutinizer/g/jeroennoten/Laravel-AdminLTE.svg?style=flat-square)](https://scrutinizer-ci.com/g/jeroennoten/Laravel-AdminLTE)
[![StyleCI](https://styleci.io/repos/38200433/shield?branch=master)](https://styleci.io/repos/38200433)
[![Total Downloads](https://img.shields.io/packagist/dt/jeroennoten/Laravel-AdminLTE.svg?style=flat-square)](https://packagist.org/packages/jeroennoten/Laravel-AdminLTE)

This package provides an easy way to quickly set up [AdminLTE v2](https://adminlte.io) with Laravel 6. It has no requirements and dependencies besides Laravel, so you can start building your admin panel immediately. The package just provides a Blade template that you can extend and advanced menu configuration possibilities. A replacement for the `make:auth` Artisan command that uses AdminLTE styled views instead of the default Laravel ones is also included.

1. [Requirements](#1-requirements)
2. [Installation](#2-installation)
3. [Updating](#3-updating)
4. [Usage](#4-usage)
5. [The `make:adminlte` artisan command](#5-the-makeadminlte-artisan-command)
   1. [Using the authentication views without the `make:adminlte` command](#41-using-the-authentication-views-without-the-makeadminlte-command)
6. [Configuration](#6-configuration)
   1. [Menu](#61-menu)
     - [Custom menu filters](#custom-menu-filters)
     - [Menu configuration at runtime](#menu-configuration-at-runtime)
     - [Active menu items](#active-menu-items)
   2. [Plugins](#62-plugins)
7. [Translations](#7-translations)
    1. [Menu Translations](#71-menu-translations)
8. [Customize views](#8-customize-views)
9. [Issues, Questions and Pull Requests](#9-issues-questions-and-pull-requests)

## 1. Requirements

- Laravel 6.0.x
- PHP >= 7.2

## 2. Installation

1. Require the package using composer:

    ```
    composer require jeroennoten/laravel-adminlte
    ```

2. Install the package using the command (For fresh laravel installations):

    ```
    php artisan adminlte:install
    ```
   
> You can use --basic to avoid authentication scaffolding installation
>
> You can use --force to overwrite any file
>
> You can also use --interactive to be guided through the process and choose what you want to install

## 3. Updating

1. To update this package, first update the composer package:

    ```
    composer update jeroennoten/laravel-adminlte
    ```

2. Then, we need to update the assets

    > If you using AdminLTE for Laravel 5.x and are upgrading Laravel 6 version, delete the folder adminlte inside your public/vendor folder.
    
    And then use this command to publish new assets
    
    ```
    php artisan vendor:publish --provider="JeroenNoten\LaravelAdminLte\AdminLteServiceProvider" --tag=assets --force
    ```
   
3. If you have [published](#8-customize-views) and modified the default master, page views or login views, you will need to update them too.

    Option 1:
    - Make a copy of the views you modified.
    - Publish the views again, using
        ```
       php artisan vendor:publish --provider="JeroenNoten\LaravelAdminLte\AdminLteServiceProvider" --tag=views
        ```
   - Redo the modifications you did.
  
   Option 2:
   - Modify in the css, js and other assets location in the master and page views.
   
### 3.1 Breaking Changes

1. Version 1.25.1 to 1.26 and up:

- Plugins configuration was modified, check the new usage here: [Plugins](#62-plugins)

2. Version 1.26 to 1.27 and up:

- iCheck plugin was replaced with [icheck-bootstrap](https://github.com/bantikyan/icheck-bootstrap). If you use the iCheck assets, make sure to check/modify the asset location.   

3. Laravel 6 version moved the assets file. Check the locations.

- If you modified the default views, edit then fixing the assets injection.

## 4. Usage

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

Note that you can also use `@stack` directive for `css` and `javascript`:

```html
{{-- resources/views/admin/dashboard.blade.php --}}

@push('css')

@push('js')
```

You now just return this view from your controller, as usual. Check out [AdminLTE](https://almsaeedstudio.com) to find out how to build beautiful content for your admin panel.

## 6. Configuration

After the installation, you will notice a adminlte.php file in you config folder. 

Use it to configure the title, skin, menu, URLs etc. All configuration options are explained in the comments.

### 6.1 Menu

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
        'icon' => 'fas fa-fw fa-file'
    ],
    [
        'text' => 'Show my website',
        'url' => '/',
        'target' => '_blank'
    ],
    'ACCOUNT SETTINGS',
    [
        'text' => 'Profile',
        'route' => 'admin.profile',
        'icon' => 'fas fa-fw fa-user'
    ],
    [
        'text' => 'Change Password',
        'route' => 'admin.password',
        'icon' => 'fas fa-fw fa-lock'
    ],
],
```

With a single string, you specify a menu header item to separate the items.
With an array, you specify a menu item. `text` and `url` or `route` are required attributes.
The `icon` is optional, you get an [open circle](https://fontawesome.com/icons/circle?style=regular&from=io) if you leave it out.
The available icons that you can use are those from [Font Awesome](https://fontawesome.io/icons/).
Just specify the name of the icon and it will appear in front of your menu item.

Use the `can` option if you want conditionally show the menu item. This integrates with Laravel's `Gate` functionality. If you need to conditionally show headers as well, you need to wrap it in an array like other menu items, using the `header` option:

```php
[
    [
        'header' => 'BLOG',
        'can' => 'manage-blog'
    ],
    [
        'text' => 'Add new post',
        'url' => 'admin/blog/new',
        'can' => 'add-blog-post'
    ],
]
```
#### Adding a Search Input

It's possible to add a search input in your menu, using a menu item with the following configuration:

```php
        [
            'search' => true,
            'href' => 'test',  //form action
            'method' => 'POST', //form method
            'input_name' => 'menu-search-input', //input name
            'text' => 'Search', //input placeholder
        ],
```

#### Custom Menu Filters

If you need custom filters, you can easily add your own menu filters to this package. This can be useful when you are using a third-party package for authorization (instead of Laravel's `Gate` functionality).

For example with Laratrust:

```php
<?php

namespace MyApp;

use JeroenNoten\LaravelAdminLte\Menu\Builder;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Laratrust;

class MyMenuFilter implements FilterInterface
{
    public function transform($item, Builder $builder)
    {
        if (isset($item['permission']) && ! Laratrust::can($item['permission'])) {
            return false;
        }

        return $item;
    }
}
```

And then add to `config/adminlte.php`:

```php
'filters' => [
    JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
    JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
    JeroenNoten\LaravelAdminLte\Menu\Filters\SubmenuFilter::class,
    JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
    //JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class, Comment this line out
    MyApp\MyMenuFilter::class,
]
```

#### Menu configuration at runtime

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

#### Active menu items

By default, a menu item is considered active if any of the following holds:
- The current path matches the `url` parameter
- The current path is a sub-path of the `url` parameter
- If it has a submenu containing an active menu item

To override this behavior, you can specify an `active` parameter with an array of active URLs, asterisks and regular expressions are supported. 

To utilize regex, simply prefix your pattern with `regex:` and it will get evaluated automatically. The pattern will attempt to match the path of the URL, returned by `request()->path()`, which returns the current URL without the domain name. Example:

```php
[
    'text' => 'Pages',
    'url' => 'pages',
    'active' => ['pages', 'content', 'content/*', 'regex:@^content/[0-9]+$@']
]
```

### 6.2 Plugins

By default the [DataTables](https://datatables.net/), [Select2](https://select2.github.io/), [ChartJS](https://www.chartjs.org/), [Pace](http://github.hubspot.com/pace/docs/welcome/) and [SweetAlert2](https://sweetalert2.github.io/) plugins are supported and active, automatically injecting their CDN files. 

You can also add and configure new plugins, modifying the plugin variable using the example structure below:

```php
'plugins' => [
    [
        'name' => 'Plugin Name',
        'active' => true,
        'files' => [
            [
                'type' => 'js',
                'asset' => false,
                'location' => '//cdn.plugin.net/plugin.min.js',
            ],
            [
                'type' => 'css',
                'asset' => true,
                'location' => 'css/plugin.min.css',
            ],
        ],
    ],
]
```

With the name string you specify the plugin name, and the active value will enable/disable the plugin injection.
Each plugin have a files array, with contain arrays with file type (`js` or `css`), and `location`. 

If the asset value is `true`, the injection will use the asset() function.

#### 6.2.1 Pace Plugin Configuration

You can change the Pace plugin appearence, when using the CDN injection modifying the css file location.
```
    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/{{color}}/pace-theme-{{theme}}.min.css',
``` 

Color values: black, blue (default), green, orange, pink, purple, red, silver, white & yellow

Theme values: barber-shop, big-counter, bounce, center-atom, center-circle, center-radar (default), center-simple, corner-indicator, fill-left, flash, flat-top, loading-bar, mac-osx, minimal

## 7. Translations

At the moment, English, German, French, Dutch, Portuguese and Spanish translations are available out of the box.
Just specifiy the language in `config/app.php`.
If you need to modify the texts or add other languages, you can publish the language files:

```
php artisan vendor:publish --provider="JeroenNoten\LaravelAdminLte\AdminLteServiceProvider" --tag=translations
```

Now, you can edit translations or add languages in `resources/lang/vendor/adminlte`.

### 7.1. Menu Translations

This resource allow you to use lang files, and is active by default.

#### Configurating Menu Using Lang:

First, configure the menu using the key `text` as translation string.
This is an example of configuration:

```php
    [
        'header' => 'account_settings'
    ],
        [
            'text' => 'profile',
            'url'  => 'admin/settings',
            'icon' => 'user',
        ],
```

#### Lang Files

All the translation strings must be added in the `menu.php` file of each language needed.
The translations files are located at `resources/lang/vendor/adminlte/`

This is an example of the `menu.php` lang file:

```php
return [
    'account_settings'  => 'ACCOUNT SETTINGS',
    'profile'           => 'Profile',
];

```

To translate the menu headers, just use the `header` param. Example:

```php
    [
        'header' => 'account_settings'
    ],
        [
            'text' => 'profile',
            'url'  => 'admin/settings',
            'icon' => 'user',
        ],
```

## 8. Customize views

If you need full control over the provided views, you can publish them:

```
php artisan vendor:publish --provider="JeroenNoten\LaravelAdminLte\AdminLteServiceProvider" --tag=views
```

Now, you can edit the views in `resources/views/vendor/adminlte`.

## 9. Issues, Questions and Pull Requests

You can report issues and ask questions in the [issues section](https://github.com/jeroennoten/Laravel-AdminLTE/issues). Please start your issue with `ISSUE: ` and your question with `QUESTION: `

If you have a question, check the closed issues first. Over time, I've been able to answer quite a few.

To submit a Pull Request, please fork this repository, create a new branch and commit your new/updated code in there. Then open a Pull Request from your new branch. Refer to [this guide](https://help.github.com/articles/about-pull-requests/) for more info.

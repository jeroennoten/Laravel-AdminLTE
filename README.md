# Easy AdminLTE integration with Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeroennoten/Laravel-AdminLTE.svg?style=flat-square)](https://packagist.org/packages/jeroennoten/Laravel-AdminLTE)
[![Build Status](https://travis-ci.org/jeroennoten/Laravel-AdminLTE.svg?branch=master)](https://travis-ci.org/jeroennoten/Laravel-AdminLTE)
[![Quality Score](https://img.shields.io/scrutinizer/g/jeroennoten/Laravel-AdminLTE.svg?style=flat-square)](https://scrutinizer-ci.com/g/jeroennoten/Laravel-AdminLTE)
[![StyleCI](https://styleci.io/repos/38200433/shield?branch=master)](https://styleci.io/repos/38200433)
[![Total Downloads](https://img.shields.io/packagist/dt/jeroennoten/Laravel-AdminLTE.svg?style=flat-square)](https://packagist.org/packages/jeroennoten/Laravel-AdminLTE)

This package provides an easy way to quickly set up [AdminLTE v3](https://adminlte.io) with Laravel 6. It has no requirements and dependencies besides Laravel, so you can start building your admin panel immediately. The package just provides a Blade template that you can extend and advanced menu configuration possibilities. A replacement for the `make:auth` Artisan command that uses AdminLTE styled views instead of the default Laravel ones is also included.

If you want use the older versions, please use the following versions:
- Version 1.x or branch laravel5-adminlte2:
This version supports Laravel 5 and included AdminLTE v2
- Version 2.x or branch laravel6-adminlte2:
This version supports Laravel 6 and included AdminLTE v2

1. [Requirements](#1-requirements)
2. [Installation](#2-installation)
3. [Updating](#3-updating)
4. [Usage](#4-usage)
5. [Artisan Console Commands](#5-artisan-console-commands)
   1. [The `adminlte:install` Command](#51-the-adminlteinstall-command)
      1. [Options](#511-options)
   2. [The `adminlte:plugins` Command](#52-the-adminlteplugins-command)
   3. [The `adminlte:update` Command](#53-the-adminlteupdate-command)
   4. [Authentication views](#54-authentication-views)
      1. [Using the authentication views without the `adminlte:install` command](#541-using-the-authentication-views-without-the-adminlteinstall-command)
6. [Configuration](#6-configuration)
   1. [Title](#61-title)
   2. [Logo](#62-logo)
   3. [Layout](#63-layout)
   4. [Classes](#64-classes)
   5. [Sidebar](#65-sidebar)
   6. [Control Sidebar (Right Sidebar)](#66-control-sidebar-right-sidebar)
   7. [URLs](#67-urls)
   8. [Laravel Mix](#68-laravel-mix)
   9. [Menu](#69-menu)
      1. [Adding a Search Input](#691-adding-a–search-input)
      2. [Custom Menu Filters](#692-custom-menu–filters)
      3. [Menu configuration at runtime](#693-menu-configuration–at-runtime)
      4. [Active menu items](#694-active-menu–items)
   10. [Menu Filters](#610-menu-filters)
   11. [Plugins](#611-plugins)
      1. [Pace Plugin Configuration](#6111-pace-plugin-configuration)
7. [Translations](#7-translations)
    1. [Menu Translations](#71-menu-translations)
8. [Customize views](#8-customize-views)
9. [Issues, Questions and Pull Requests](#9-issues-questions-and-pull-requests)

## 1. Requirements

- Laravel 6.x
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
    php artisan adminlte:update
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

You now just return this view from your controller, as usual. Check out [AdminLTE](https://almsaeedstudio.com) to find out how to build beautiful content for your admin panel.

## 5. Artisan Console Commands

### 5.1 The `adminlte:install` Command

You can install all required & additional resources with the `adminlte:install` command.

Without any option it will install AdminLTE assets, config & translations.
You can also install the Authentication Views with adding `--type=enhanced` or additional to the Authentication Views also the Basic Views & Routes with adding `--type=full` to the `adminlte:install` command.

#### 5.1.1 Options

 - `--force`: Overwrite existing views by default
 - `--type=`: Installation type, Available type: none, basic, enhanced & full.
 - `--only=`: Install only specific part, Available parts: assets, config, translations, auth_views, basic_views, basic_routes & main_views. This option can not used with the with option.
 - `--with=*`: Install basic assets with specific parts, Available parts: auth_views, basic_views & basic_routes. Can be use multiple
 - `--interactive` : The installation will guide you through the process


### 5.2 The `adminlte:plugins` Command

If you won't use cdn for the plugins, you can manage the optional plugins assets with the `adminlte:plugins` command.

You can list all available plugins, install/update/remove all or specific plugins. Here are some examples for the command:

Install all plugin assets
- `artisan adminlte:plugins install`
Install only Pace Progress & Select2 plugin assets
- `artisan adminlte:plugins install --plugin=paceProgress --plugin=select2`

Update all Plugin assets
- `artisan adminlte:plugins update`
Update only Pace Progress plugin assets
- `artisan adminlte:plugins update`

Remove all Plugin assets
- `artisan adminlte:plugins remove`
Remove only Select2 plugin assets
- `artisan adminlte:plugins remove --plugin=select2`

#### 5.2.1 Options

 - `operation`: Operation command, Available commands; list (default), install, update & remove.
 - `--plugin=`: Plugin Key. (Can used multiple times for the desired)
 - `--interactive`: The installation will guide you through the process.


### 5.3 The `adminlte:update` Command

This command is only a shortcut for `adminlte:install --force --only=assets`.


### 5.4 Authentication views

> Note: only for Laravel 5.2 and higher

This package ships the following command to replaces the authentication views with AdminLTE style views.

```
php artisan adminlte:install --only=auth_views
```

### 5.4.1 Using the authentication views without the `adminlte:install` command

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

## 6. Configuration

First, publish the configuration file:

```
php artisan adminlte:install --only=config
```

Now, edit `config/adminlte.php` to configure the title, skin, menu, URLs etc. All configuration options are explained in the comments. However, I want to shed some light on the `menu` configuration.

### 6.1 Title
The default title of your admin panel, this goes into the title tag of your page. You can override it per page with the title section.
You can optionally also specify a title prefix and/or postfix.

The following config options available:
- __`title`__

    Default title
- __`title_prefix`__

    Title prefix   
- __`title_postfix`__

    Title postfix


### 6.2 Logo
The logo is displayed at the upper left corner of your admin panel. You can use basic HTML here if you want for a simple text logo with a small image logo (e.g. 50 x 50 pixels), or you can use two images one big (e.g. 210 x 33 pixels) and one small (e.g. 50 x 50 pixels). You can also change the sizes of the images and the alt text for both logos.

- __`logo`__

    Text logo content, can be HTML.
- __`logo_img`__

    Small logo image, beside text logo.

    _Recommend size: 50x50px_
- __`logo_img_class`__

    Extra classes for small logo image.
- __`logo_img_xl`__

    Large logo image, if you set a img url it will replace the text logo & small logo with one big logo and on collapsed sidebar it displays the small logo.

    _Recommend size: 210x33px_
- __`logo_img_xl_class`__

    Extra classes for small logo image.
- __`logo_img_alt`__

    Logo image alt text.


### 6.3 Layout
It's possible change the layout, you can use a top navigation (navbar) only layout, a boxed layout with sidebar and you can enable fixed mode for sidebar, navbar and footer.

The following config options available:
- __`layout_topnav`__

    Enables/Disables top navigation only layout.

- __`layout_boxed`__

    Enables/Disables Enables/Disables boxed layout, can't used with `layout_topnav`.

- __`layout_fixed_sidebar`__

    Enables/Disables fixed sidebar, can't used with `layout_topnav`. 

- __`layout_fixed_navbar`__

    Enables/Disables fixed navbar (top navigation), here you can set `true` or pass an array for responsive usage.

- __`layout_fixed_footer`__

    Enables/Disables fixed footer, here you can set `true` or pass an array for responsive usage.


__Responsive Usage for `layout_fixed_navbar` & `layout_fixed_footer`__

With responsive you can disable or enable the fixed navbar/footer for specific viewport sizes.

The array the following keys available, you can set them to `true` or `false`:
- `xs` from 0px to 575.99px
- `sm` from 576px to 767.99px
- `md` from 768px to 991.99px
- `lg` from 992px to 1199.99px
- `xl` from 1200px

__Examples__

- `['xs' => true, 'lg' => false]`

    Fixed from mobile to small tablet (<= 991.99px)

- `['lg' => true]`

    Fixed starting from desktop (>= 992px)

- `['xs' => true, 'md' => false, 'xl' => true]`

    Fixed from mobile (<= 767.99px) and extra large desktops (>= 1200px) but not for small tablet and desktop (>= 768px & <= 1199.99px)


### 6.4 Classes
You can change the look and behavior of the admin panel, you can add extra classes to body, brand, sidebar, sidebar navigation, top navigation and top navigation container.

The following config options available:
- __`classes_body`__

    Extra classes for body.
- __`classes_brand`__

    Extra classes for brand.
- __`classes_brand_text`__

    Extra classes for brand text.
- __`classes_content_header`__

    Extra classes for content header container.
- __`classes_content`__

    Extra classes for content container.
- __`classes_sidebar`__

    Extra classes for sidebar.
- __`classes_sidebar_nav`__

    Extra classes for sidebar navigation.
- __`classes_topnav`__

    Extra classes for top navigation bar.
- __`classes_topnav_nav`__

    Extra classes for top navigation.
- __`classes_topnav_container`__

    Extra classes for top navigation bar container.


### 6.5 Sidebar
You can modify the sidebar, you can disable the collapsed mini sidebar, start with collapsed sidebar, enable sidebar auto collapse on specific screen size, enable sidebar collapse remember, change the scrollbar theme or auto hide option, disable sidebar navigation accordion and sidebar navigation menu item animation speed.

The following config options available:
- __`sidebar_mini`__

    Enables/Disables the collapsed mini sidebar for desktop and bigger screens (>= 992px), here you can set `true`, `false` or `'md'` to enable for small tablet and bigger screens (>= 768px).
- __`sidebar_collapse`__

    Enables/Disables collapsed by default.
- __`sidebar_collapse_auto_size`__

    Enables/Disables auto collapse by setting min width to collapse.
- __`sidebar_collapse_remember`__

    Enables/Disables collapse remeber script.
- __`sidebar_collapse_remember_no_transition`__

    Enables/Disables transition after reload page.
- __`sidebar_scrollbar_theme`__

    Changes sidebar scrollbar theme.
- __`sidebar_scrollbar_auto_hide`__

    Changes sidebar scrollbar auto hide trigger.
- __`sidebar_nav_accordion`__

    Enables/Disables sidebar navigation accordion feature.
- __`sidebar_nav_animation_speed`__

    Changes the sidebar navigation slide animation speed.


### 6.6 Control Sidebar (Right Sidebar)
Here you have the option to enable a right sidebar. When active, you can use @section('right-sidebar') The icon you configured will be displayed at the end of the top menu, and will show/hide the sidebar. The slide option will slide the sidebar over the content, while false will push the content, and have no animation. You can also choose the sidebar theme (dark or light).

The following config options available:

- __`right_sidebar`__

    Enables/Disables the right sidebar.
- __`right_sidebar_icon`__

    Changes the icon for the right sidebar button in main navigation.
- __`right_sidebar_theme`__

    Changes the theme of the right sidebar, the following options available: `dark` & `light`.
- __`right_sidebar_slide`__

    Enables/Disables slide animation.
- __`right_sidebar_push`__

    Enables/Disables push content instead of overlay for right sidebar.
- __`right_sidebar_scrollbar_theme`__

    Enables/Disables transition after reload page.
- __`right_sidebar_scrollbar_auto_hide`__

    Changes sidebar scrollbar auto hide trigger.


### 6.7 URLs
Here we have the url settings to setup the correct login/register link. Register here your dashboard, logout, login and register URLs.
- __`use_route_url`__

    Whether to use `route()` instead of `url()`.
- __`dashboard_url`__

    Changes the dashboard/logo URL.
- __`logout_url`__

    Changes the logout button URL.
- __`logout_method`__

    Changes the logout send method, available options: `GET`, `POST` & `null` (Laravel default).
    The logout URL automatically sends a POST request in Laravel 5.3 or higher. 
 - __`login_url`__

    Changes the login url.
- __`register_url`__
    
    Changes the register link or if set `false` it will hide.
 - __`password_reset_url`__

    Changes the password reset url.
 - __`password_email_url`__

    Changes the password email url.


### 6.8 Laravel Mix
If you want to use Laravel Mix instead of publishing the assets in your `/public/vendor` folder, start by installing the following NPM packages:

```
npm i @fortawesome/fontawesome-free
npm i icheck-bootstrap
npm i overlayscrollbars
```

Add the following to your `bootstrap.js` file after `window.$ = window.jQuery = require('jquery');`:

```
    require('overlayscrollbars');
    require('../../vendor/almasaeed2010/adminlte/dist/js/adminlte');
 ```

Replace your `app.scss` content by the following:

```
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

After preparing the Laravel Mix vendor files, set `enabled_laravel_mix` to `true` to enable load app.css & app.js .

- __`enabled_laravel_mix`__

    Enables Laravel Mix specific css/js load in master layout.
    __Warning__ If you enable this option, the sections `adminlte_css` & `adminlte_js` will not rendered.

### 6.9 Menu
Specify your menu items to display in the left sidebar. Each menu item should have a text and a URL. You can also specify an icon from Font Awesome. A string instead of an array represents a header in sidebar layout. The 'can' is a filter on Laravel's built in Gate functionality.

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
The `icon` attribute is optional, you get an [open circle](https://fontawesome.com/icons/circle?style=regular&from=io) if you leave it out.
The available icons that you can use are those from [Font Awesome](https://fontawesome.io/icons/).
Just specify the name of the icon and it will appear in front of your menu item.

It's also possible to add menu items to the top navigation while sidebar is enabled, you just need to set the `topnav` attribute to `true`. 
This will ignored if the top navigation layout is enabled, all menu items will appear in top navigation.

Use the `can` attribute if you want conditionally show the menu item. This integrates with Laravel's `Gate` functionality. If you need to conditionally show headers as well, you need to wrap it in an array like other menu items, using the `header` attribute:

```php
[
    [
        'header' => 'BLOG',
        'url' => 'admin/blog',
        'can' => 'manage-blog'
    ],
    [
        'text' => 'Add new post',
        'url' => 'admin/blog/new',
        'can' => 'add-blog-post'
    ],
]
```

#### 6.9.1 Adding a Search Input

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

#### 6.9.2 Custom Menu Filters

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

#### 6.9.3 Menu configuration at runtime

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

#### 6.9.4 Active menu items

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


### 6.10 Menu Filters
Here we can set the filters you want to include for rendering the menu.
You can add your own filters to this array after you've created them. You can comment out the GateFilter if you don't want to use Laravel's built in Gate functionality

- __`filters`__

    Array of menu filters


Default menu filters:
- `JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class`
- `JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class`
- `JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class`
- `JeroenNoten\LaravelAdminLte\Menu\Filters\SubmenuFilter::class`
- `JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class`
- `JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class`
- `JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class`


### 6.11 Plugins
Configure which JavaScript plugins should be included. At this moment, DataTables, Select2, Chartjs and SweetAlert are added out-of-the-box, including the Javascript and CSS files from a CDN via script and link tag. Plugin Name, active status and files array (even empty) are required. Files, when added, need to have type (js or css), asset (true or false) and location (string). When asset is set to true, the location will be output using asset() function.

By default the [DataTables](https://datatables.net/), [Select2](https://select2.github.io/), [ChartJS](https://www.chartjs.org/), [Pace](http://github.hubspot.com/pace/docs/welcome/) and [SweetAlert2](https://sweetalert2.github.io/) plugins are supported and but not active.
You can activate them with changing the config file to load it on every page or add a section in specific blade files, this will automatically injecting their CDN files. 

Section example: `@section('plugins.Datatables', true)`

You can use these plugins by default:
- `Datatables`
- `Select2`
- `Chartjs`
- `Sweetalert2`
- `Pace`

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

#### 6.11.1 Pace Plugin Configuration

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
php artisan adminlte:install --only=translations
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
php artisan adminlte:install --only=main_views
```

Now, you can edit the views in `resources/views/vendor/adminlte`.

## 9. Issues, Questions and Pull Requests

You can report issues and ask questions in the [issues section](https://github.com/jeroennoten/Laravel-AdminLTE/issues). Please start your issue with `ISSUE: ` and your question with `QUESTION: `

If you have a question, check the closed issues first. Over time, I've been able to answer quite a few.

To submit a Pull Request, please fork this repository, create a new branch and commit your new/updated code in there. Then open a Pull Request from your new branch. Refer to [this guide](https://help.github.com/articles/about-pull-requests/) for more info.

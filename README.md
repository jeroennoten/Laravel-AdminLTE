# Easy AdminLTE integration with Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeroennoten/Laravel-AdminLTE.svg?style=flat-square)](https://packagist.org/packages/jeroennoten/Laravel-AdminLTE)
[![Build Status](https://travis-ci.org/jeroennoten/Laravel-AdminLTE.svg?branch=master)](https://travis-ci.org/jeroennoten/Laravel-AdminLTE)
[![Quality Score](https://img.shields.io/scrutinizer/g/jeroennoten/Laravel-AdminLTE.svg?style=flat-square)](https://scrutinizer-ci.com/g/jeroennoten/Laravel-AdminLTE)
[![StyleCI](https://styleci.io/repos/38200433/shield?branch=master)](https://styleci.io/repos/38200433)
[![Total Downloads](https://img.shields.io/packagist/dt/jeroennoten/Laravel-AdminLTE.svg?style=flat-square)](https://packagist.org/packages/jeroennoten/Laravel-AdminLTE)

This package provides an easy way to quickly set up [AdminLTE v3](https://adminlte.io) with Laravel 6 or higher. It has no requirements and dependencies besides Laravel, so you can start building your admin panel immediately. The package just provides a Blade template that you can extend and advanced menu configuration possibilities. A replacement for the `make:auth` Artisan command that uses AdminLTE styled views instead of the default Laravel ones is also included.

If you want use the older versions, please use the following versions:
- Version 1.x or branch laravel5-adminlte2:
This version supports Laravel 5 and included AdminLTE v2
- Version 2.x or branch laravel6-adminlte2:
This version supports Laravel 6 and higher and included AdminLTE v2

## Table of Contents

1. [Requirements](#1-requirements)
2. [Installation](#2-installation)
3. [Updating](#3-updating)
4. [Usage](#4-usage)
5. [Artisan Console Commands](#5-artisan-console-commands)
   1. [The `adminlte:install` Command](#51-the-adminlteinstall-command)
      1. [Options](#511-options)
   2. [The `adminlte:plugins` Command](#52-the-adminlteplugins-command)
      1. [Options](#521-options)
   3. [The `adminlte:update` Command](#53-the-adminlteupdate-command)
   4. [Authentication Views](#54-authentication-views)
      1. [Using the Authentication Views Manually](#541-using-the-authentication-views-manually)
6. [Configuration](#6-configuration)
   1. [Title](#61-title)
   2. [Favicon](#62-favicon)
   3. [Logo](#63-logo)
   4. [User Menu](#64-user-menu)
      1. [Example Code of User Image and Description](#641-example-code-of-user-image-and-description)
   5. [Layout](#65-layout)
      1. [Responsive Usage](#651-responsive-usage)
   6. [Classes](#66-classes)
      1. [Authentication Views Classes](#661-authentication-views-classes)
      2. [Admin Panel Classes](#662-admin-panel-classes)
   7. [Sidebar](#67-sidebar)
   8. [Control Sidebar (Right Sidebar)](#68-control-sidebar-right-sidebar)
   9. [URLs](#69-urls)
   10. [Laravel Mix](#610-laravel-mix)
   11. [Menu](#611-menu)
       1. [Adding a Search Input](#6111-adding-a-search-input)
       2. [Custom Menu Filters](#6112-custom-menu-filters)
       3. [Menu Configuration at Runtime](#6113-menu-configuration-at-runtime)
       4. [Active Menu Items](#6114-active-menu-items)
   12. [Menu Filters](#612-menu-filters)
   13. [Plugins](#613-plugins)
       1. [Pace Plugin Configuration](#6131-pace-plugin-configuration)
7. [Translations](#7-translations)
   1. [Menu Translations](#71-menu-translations)
8. [Customize Views](#8-customize-views)
9. [Issues, Questions and Pull Requests](#9-issues-questions-and-pull-requests)


## 1. Requirements

- Laravel >= 6.x
- PHP >= 7.2


## 2. Installation

1. Require the package using composer:

   ```sh
   composer require jeroennoten/laravel-adminlte
   ```

2. (Laravel 7+ only) Require the laravel/ui package using composer:

   ```sh
   composer require laravel/ui
   php artisan ui:controllers
   ```

3. Install the package using the command (For fresh laravel installations):

   ```sh
   php artisan adminlte:install
   ```

   > You can use **--basic** to avoid authentication scaffolding installation
   >
   > You can use **--force** to overwrite any file
   >
   > You can also use **--interactive** to be guided through the process and choose what you want to install


## 3. Updating

1. To update this package, first update the composer package:

   ```sh
   composer update jeroennoten/laravel-adminlte
   ```

2. Then, update the assets

   > Note: If you using AdminLTE for Laravel 5.x and are upgrading to Laravel 6 version, delete the folder adminlte inside your `public/vendor` folder.

   Use next command to publish the new assets:

   ```sh
   php artisan adminlte:update
   ```

3. If you have [published](#8-customize-views) and modified the default master, page views or login views, you will need to update them too. Please, note there could be huge updates on these views, so it is highly recommended to backup your changes.

   - Make a copy (or backup) of the views you have modified, those inside the folder `resources/views/vendor/adminlte`.

   - Publish the views again, using `--force` to overwrite existing files.

     ```sh
     php artisan adminlte:install --only=main_views --force
     ```

   - Compare with your backup files and redo the modifications you previously did to those views.

4. From time to time, new configuration options are added or default values are changed, so it is a recommendation to also update the package config file.

   - Make a copy (or backup) of your current package configuration, the `config/adminlte.php` file.

   - Now, publish the new package configuration and accept the overwrite warning.

     ```sh
     php artisan adminlte:install --only=config
     ```

   - Compare with your backup config file and redo the modifications you previously made.


## 4. Usage

To use the template, create a new blade file and extend the layout with `@extends('adminlte::page')`.
This template yields the following main sections:

- `title`: for the `<title>` tag.
- `content_header`: title of the page, above the content.
- `content`: all of the page's content.
- `footer`: content of the page footer.
- `right-sidebar`: content of the right sidebar.
- `css`: extra stylesheets (located in `<head>`).
- `js`: extra javascript (just before `</body>`).

All sections are in fact optional. As an example, your most common blade template could look like the following:

```blade
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

You can install all required and additional resources with the `adminlte:install` command.

Without any option it will install AdminLTE package assets, config & translations.
You can also install the Authentication Views adding `--type=enhanced` option, or additional to the Authentication Views also the Basic Views and Routes adding the `--type=full` option to the `adminlte:install` command.

#### 5.1.1 Options

- `--force`: To force overwrite the existing files by default.
- `--type=`: The installation type, the available types are: **none**, **basic**, **enhanced** or **full**.
- `--only=`: To install only specific parts, the available parts are: **assets**, **config**, **translations**, **auth_views**, **basic_views**, **basic_routes** or **main_views**. This option can not be used with the `--with` option.
- `--with=*`: To install the basic assets with specific parts, the available parts are: **auth_views**, **basic_views** or **basic_routes**. This option can be used multiple times, example:
  ```sh
  php artisan adminlte:install --with=auth_views --with=basic_routes
  ```
- `--interactive` : The installation will guide you through the process.

### 5.2 The `adminlte:plugins` Command

If you won't use cdn for the plugins, you can manage the optional plugins assets with the `adminlte:plugins` command.
You can list all available plugins, or install/update/remove all or specific plugins. Here are some examples for the command:

- Install all plugin assets:
  ```sh
  php artisan adminlte:plugins install
  ```
- Install only Pace Progress & Select2 plugin assets:
  ```sh
  php artisan adminlte:plugins install --plugin=paceProgress --plugin=select2
  ```
- Update all plugin assets:
  ```sh
  php artisan adminlte:plugins update
  ```
- Update only Pace Progress plugin assets:
  ```sh
  php artisan adminlte:plugins update --plugin=paceProgress
  ```
- Remove all plugin assets:
  ```sh
  php artisan adminlte:plugins remove
  ```
- Remove only Select2 plugin assets:
  ```sh
  php artisan adminlte:plugins remove --plugin=select2
  ```

#### 5.2.1 Options

 - `operation`: The operation command, available commands are: **list** (default), **install**, **update** or **remove**.
 - `--plugin=`: The plugin key name (this option can be used multiple times).
 - `--interactive`: The installation will guide you through the process.

### 5.3 The `adminlte:update` Command

This command is only a shortcut for `adminlte:install --force --only=assets`.

### 5.4 Authentication Views

> Note: this is only available for Laravel 5.2 or higher versions.

This package ships the following command to replace the authentication views with AdminLTE style views.

```sh
php artisan adminlte:install --only=auth_views
```

By default, the login form contains a link to the registration and password reset forms.
If you don't want a registration or password reset form, set the `register_url` or `password_reset_url` setting to `null` and the respective link will not be displayed.

#### 5.4.1 Using the Authentication Views Manually

If you want to use the included authentication views manually, you can create the following files and only add one line to each one of these files:

- `resources/views/auth/login.blade.php`:
  ```blade
  @extends('adminlte::auth.login')
  ```
- `resources/views/auth/register.blade.php`
  ```blade  
  @extends('adminlte::auth.register')
  ```
- `resources/views/auth/verify.blade.php`
  ```blade
  @extends('adminlte::auth.verify')
  ```
- `resources/views/auth/passwords/confirm.blade.php`
  ```blade
  @extends('adminlte::auth.passwords.confirm')
  ```
- `resources/views/auth/passwords/email.blade.php`
  ```blade
  @extends('adminlte::auth.passwords.email')
  ```
- `resources/views/auth/passwords/reset.blade.php`
  ```blade
  @extends('adminlte::auth.passwords.reset')
  ```


## 6. Configuration

First, publish the configuration file (if you don't see the `adminlte.php` file inside the `config` folder):

```sh
php artisan adminlte:install --only=config
```

Now, edit `config/adminlte.php` to configure the title, layout, menu, URLs etc. All configuration options are explained in the comments. However, we going to give a fast review here.

### 6.1 Title

The default title of your admin panel, this goes into the title tag of your page. You can override it per page with the title section. You can optionally also specify a title prefix and/or postfix.

The following config options are available:

- __`title`__: Default title.
- __`title_prefix`__: The title prefix.
- __`title_postfix`__: The title postfix.

### 6.2 Favicon

Favicons could be used easily. There are two different ways to do this. Please add all favicons in the dir public/favicons/.

- __`['use_ico_only' => true, 'use_full_favicon' => false]`__

  Whit this configuration the file `public/favicons/favicon.ico` is used.

- __`['use_ico_only' => false, 'use_full_favicon' => true]`__

  Whit this configuration more favicon files in `public/favicons/` folder will be used. The activated code is:
  ```blade
  <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}"/>
  <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/apple-icon-57x57.png') }}">
  <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/apple-icon-60x60.png') }}">
  <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/apple-icon-72x72.png') }}">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/apple-icon-76x76.png') }}">
  <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/apple-icon-114x114.png') }}">
  <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/apple-icon-120x120.png') }}">
  <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/apple-icon-144x144.png') }}">
  <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/apple-icon-152x152.png') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-icon-180x180.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/favicon-96x96.png') }}">
  <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('favicons/android-icon-192x192.png') }}">
  <link rel="manifest" href="{{ asset('favicons/manifest.json') }}">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="{{ asset('favicons/ms-icon-144x144.png') }}">
  ```

### 6.3 Logo

The logo is displayed at the upper left corner of your admin panel. You can use basic HTML here if you want a simple text logo with a small image logo (e.g. 50 x 50 pixels), or you can use two images: one big (e.g. 210 x 33 pixels) and one small (e.g. 50 x 50 pixels). You can also change the sizes of the images and the alt text for both logos.

- __`logo`__: Text logo content, can be HTML.
- __`logo_img`__: Path to the small logo image, beside text logo. _Recommend size is: 50x50px_
- __`logo_img_class`__: Extra classes for the small logo image.
- __`logo_img_xl`__: Path to large logo image, if you set a img url it will replace the text logo & small logo with one big logo. When the sidebar is collapsed it will displays the small logo. _Recommend size is: 210x33px_
- __`logo_img_xl_class`__: Extra classes for the large logo image.
- __`logo_img_alt`__: Logo image alt text.

### 6.4 User Menu

The user menu is displayed at the upper right corner of your admin panel. The available options are:

- __`usermenu_enabled`__

  Whether to enable the user menu instead of the default logout button.

- __`usermenu_header`__

  Whether to enable the header inside the user menu.

- __`usermenu_header_class`__

  Extra classes for the header inside the user menu.

- __`usermenu_image`__

  Whether to enable the user image for the usermenu & lockscreen.
  _**Note:**_ For this, you will need an extra function named `adminlte_image()` inside the `App/User`.
  _Recommend size is: 160x160px_

- __`usermenu_desc`__

  Whether to enable the user description for the usermenu.
  _**Note:**_ For this, you will need an extra function named `adminlte_desc()` inside the `App/User`.

- __`usermenu_profile_url`__

  Whether to enable the user profile url can be set dynamically for the user instead of the config key `profile_url`.
  _**Note:**_ For this, you need an extra function named `adminlte_profile_url()` inside the `App/User`.
  The return value should be a string, not a route or url.

#### 6.4.1 Example Code of User Image and Description

Here you have an example code for the `App/User` with custom image, description and profile url functions.

```php
class User extends Authenticatable
{
    …

    public function adminlte_image()
    {
        return 'https://picsum.photos/300/300';
    }

    public function adminlte_desc()
    {
        return 'That\'s a nice guy';
    }

    public function adminlte_profile_url()
    {
        return 'profile/username';
    }
}
```

### 6.5 Layout

It's possible to change the layout, you can use a top navigation (navbar) only layout, a boxed layout with sidebar, and also you can enable fixed mode for the sidebar, the navbar or the footer.

> **NOTE:** Currently, you cannot use a boxed layout with a fixed navbar or a fixed footer. Also, do not enable `layout_topnav` and `layout_boxed` at the same time. Anything else can be mixed together.

The following config options are available:

- __`layout_topnav`__

  Enables/Disables the top navigation only layout, this will remove the sidebar and put your links at the top navbar. Can't be used with `layout_boxed`.

- __`layout_boxed`__

  Enables/Disables the boxed layout that stretches width only to 1250px. Can't be used with `layout_topnav`.

- __`layout_fixed_sidebar`__

  Enables/Disables the fixed sidebar mode. Can't be used with `layout_topnav`.

- __`layout_fixed_navbar`__

  Enables/Disables the fixed navbar (top navigation) mode, here you can set to `true` or an `array` for responsive usage. Can't be used with `layout_boxed`.

- __`layout_fixed_footer`__

  Enables/Disables the fixed footer mode, here you can set `true` or an `array` for responsive usage. Can't be used with `layout_boxed`.

#### 6.5.1 Responsive Usage

When using an array on the `layout_fixed_navbar` or `layout_fixed_footer` configuration options, you can disable or enable the fixed layout for specific viewport sizes.

The following keys are available to use inside the array, you can set them to `true` or `false`:
- `xs` represent screens from 0px to 575.99px width
- `sm` represent screens from 576px to 767.99px width
- `md` represent screens from 768px to 991.99px width
- `lg` represent screens from 992px to 1199.99px width
- `xl` represent screens from 1200px or more width

__Examples:__

- `['xs' => true, 'lg' => false]`:

  The element will be fixed from mobile to small tablet (<= 991.99px).

- `['lg' => true]`:

  The element will be fixed starting from desktop (>= 992px).

- `['xs' => true, 'md' => false, 'xl' => true]`:

  The element will be fixed for mobile (<= 767.99px) and extra large desktops (>= 1200px) but not for a small tablet and normal desktop (>= 768px & <= 1199.99px)

### 6.6 Classes

#### 6.6.1 Authentication Views Classes

You can change the look and behavior of the authentication views (login, register, email verification, etc).

The following config options are available:

- __`classes_auth_card`__

  Extra classes for the card box. Classes will be added to element `div.card`.

- __`classes_auth_header`__

  Extra classes for the card box header. Classes will be added to element `div.card-header`.

- __`classes_auth_body`__

  Extra classes for the card box body. Classes will be added to element `div.card-body`.

- __`classes_auth_footer`__

  Extra classes for the card box footer. Classes will be added to element `div.card-footer`.

- __`classes_auth_icon`__

  Extra classes for the input icons (font awesome icons related to input fields).

- __`classes_auth_btn`__

  Extra classes for the submit buttons.

The set of current default values is the next one:

```php
'classes_auth_card' => 'card-outline card-primary',
'classes_auth_header' => '',
'classes_auth_body' => '',
'classes_auth_footer' => '',
'classes_auth_icon' => '',
'classes_auth_btn' => 'btn-flat btn-primary',
```

However, you can customize the options as you want to get some particular themes, example:

__Dark Theme__

```php
'classes_auth_card' => 'bg-gradient-dark',
'classes_auth_header' => '',
'classes_auth_body' => 'bg-gradient-dark',
'classes_auth_footer' => 'text-center',
'classes_auth_icon' => 'text-light',
'classes_auth_btn' => 'btn-flat btn-light',
```

__Lightblue Theme__
  
```php
'classes_auth_card' => '',
'classes_auth_header' => 'bg-gradient-info',
'classes_auth_body' => '',
'classes_auth_footer' => 'text-center',
'classes_auth_icon' => 'fa-lg text-info',
'classes_auth_btn' => 'btn-flat btn-primary',
```

#### 6.6.2 Admin Panel Classes

You can change the look and behavior of the admin panel, you can add extra classes to body, brand, sidebar, sidebar navigation, top navigation and top navigation container.

The following config options are available:

- __`classes_body`__

  Extra classes for body.

- __`classes_brand`__

  Extra classes for the brand. Classes will be added to element `a.navbar-brand` if `layout_topnav` is used, otherwise they will be added to element `a.brand-link`.

- __`classes_brand_text`__

  Extra classes for the brand text. Classes will be added to element `span.brand-text`.

- __`classes_content_header`__

  Classes for the content header container. Classes will be added to the container of element `div.content-header`. If you left this empty, a default class `container` will be used when `layout_topnav` is used, otherwise `container-fluid` will be used as default.

- __`classes_content_wrapper`__

  Classes for content wrapper container. Classes will be added to the container of element `div.content-wrapper`.

- __`classes_content`__

  Classes for the content container. Classes will be added to the container of element `div.content`. If you left this empty, a default class `container` will be used when `layout_topnav` is used, otherwise `container-fluid` will be used as default.

- __`classes_sidebar`__

  Extra classes for sidebar. Classes will be added to element `aside.main-sidebar`. There are some built-in classes you can use here to customize the sidebar theme:

  - __`sidebar-dark-<color>`__
  - __`sidebar-light-<color>`__

  Where `<color>` is an [AdminLTE available color](https://adminlte.io/themes/v3/pages/UI/general.html).

- __`classes_sidebar_nav`__

  Extra classes for the sidebar navigation. Classes will be added to element `ul.nav.nav-pills.nav-sidebar`. There are some built-in classes that you can use here:

  - __`nav-child-indent`__ to indent child items.
  - __`nav-compact`__ to get a compact nav style.
  - __`nav-flat`__ to get a flat nav style.
  - __`nav-legacy`__ to get a legacy v2 nav style.

- __`classes_topnav`__

  Extra classes for the top navigation bar. Classes will be added to element `nav.main-header.navbar`. There are some built-in classes you can use here to customize the topnav theme:

  - __`navbar-<color>`__

  Where `<color>` is an [AdminLTE available color](https://adminlte.io/themes/v3/pages/UI/general.html).
  > Note: The recommendation is to combine `navbar-<color>` with `navbar-dark` or `navbar-light`.

- __`classes_topnav_nav`__

  Extra classes for the top navigation. Classes will be added to element `nav.main-header.navbar`.

- __`classes_topnav_container`__

  Extra classes for top navigation bar container. Classes will be added to the `div` wrapper inside element `nav.main-header.navbar`.

### 6.7 Sidebar

You can modify the sidebar, for example, you can disable the collapsed mini sidebar, start with collapsed sidebar, enable sidebar auto collapse on specific screen size, enable sidebar collapse remember, change the scrollbar theme or auto hide option, disable sidebar navigation accordion and change the sidebar navigation menu item animation speed.

The following config options are available:

- __`sidebar_mini`__

  Enables/Disables the collapsed mini sidebar for desktop and bigger screens (>= 992px), you can set this option to `true`, `false` or `'md'` to enable for small tablet and bigger screens (>= 768px).

- __`sidebar_collapse`__

  Enables/Disables the collapsed mode by default.

- __`sidebar_collapse_auto_size`__

  Enables/Disables auto collapse by setting a minimun width to auto collapse.

- __`sidebar_collapse_remember`__

  Enables/Disables the collapse remember script.

- __`sidebar_collapse_remember_no_transition`__

  Enables/Disables the transition after reload page.

- __`sidebar_scrollbar_theme`__

  Changes the sidebar scrollbar theme.

- __`sidebar_scrollbar_auto_hide`__

  Changes the sidebar scrollbar auto hide trigger.

- __`sidebar_nav_accordion`__

  Enables/Disables the sidebar navigation accordion feature.

- __`sidebar_nav_animation_speed`__

  Changes the sidebar slide animation speed.

### 6.8 Control Sidebar (Right Sidebar)

Here you have the option to enable a right sidebar. When active, you can use the `@section('right-sidebar')`. The icon you configure will be displayed at the end of the top menu, and will show/hide the sidebar. The slide option will slide the sidebar over the content, while false will push the content without animation. You can also choose the sidebar theme (dark or light).

The following config options are available:

- __`right_sidebar`__

  Enables/Disables the right sidebar.

- __`right_sidebar_icon`__

  Changes the icon for the right sidebar toggler button in the topnav navigation.

- __`right_sidebar_theme`__

  Changes the theme of the right sidebar, the following options available: `dark` & `light`.

- __`right_sidebar_slide`__

  Enables/Disables the slide animation.

- __`right_sidebar_push`__

  Enables/Disables push content instead of overlay for the right sidebar.

- __`right_sidebar_scrollbar_theme`__

  Change the sidebar scrollbar theme. Default value is `os-theme-light`.

- __`right_sidebar_scrollbar_auto_hide`__

    Changes the sidebar scrollbar auto hide trigger. Default value is `l`.

### 6.9 URLs

Here we have the url settings to setup the correct login/register links. Register here your dashboard, logout, login and register URLs.

- __`use_route_url`__

  Whether to use `route()` instead of `url()` Laravel method.

- __`dashboard_url`__

  Changes the dashboard/logo URL.

- __`logout_url`__

  Changes the logout button URL.

- __`logout_method`__

  Changes the logout send method, available options are: `GET`, `POST` & `null` (Laravel default).
  > Note: the logout URL automatically sends a `POST` request in Laravel 5.3 or higher.

- __`login_url`__

  Changes the login url.

- __`register_url`__

  Changes the register url. Set this option to `false` to hide the register link.

- __`password_reset_url`__

  Changes the password reset url. This url should point to the view that displays the password reset form. Set this option to `false` to hide the password reset link.

- __`password_email_url`__

  Changes the password email url. This url should point to the view that displays the send reset link form.

- __`profile_url`__

  Changes the user profile url. When not `false`, it will displays a button in the user menu.

### 6.10 Laravel Mix

If you want to use Laravel Mix instead of publishing the assets in your `/public/vendor` folder, start by installing the following NPM packages:

```sh
npm i @fortawesome/fontawesome-free
npm i icheck-bootstrap
npm i overlayscrollbars
```

Now, add the following to your `bootstrap.js` file after `window.$ = window.jQuery = require('jquery');`:

```javascript
require('overlayscrollbars');
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

After preparing the Laravel Mix vendor files, set `enabled_laravel_mix` to `true` to enable the load of `app.css` & `app.js` files.

- __`enabled_laravel_mix`__

  Enables Laravel Mix specific `css/js` load in master layout.

Also, you can change the paths used to lookup for the compiled `JS` and `CSS` files using the next configuration options.

- __`laravel_mix_css_path`__

  Path (including file name) to the compiled `CSS` file. This path should be relative to the public folder. Default value is `css/app.css`

- __`laravel_mix_js_path`__

  Path (including file name) to the compiled `JS` file. This path should be relative to the public folder. Default value is `js/app.js`

### 6.11 Menu

You can specify the menu items to display in the left sidebar. Each menu item should have a text and an URL (or Route). You can also specify an icon from Font Awesome. A string instead of an array represents a header in the sidebar. The `can` option is a filter on Laravel's built in Gate functionality.

Here is a basic example of a menu configuration:

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
        'icon' => 'fas fa-fw fa-file',
    ],
    [
        'text' => 'Show my website',
        'url' => '/',
        'target' => '_blank',
    ],
    'ACCOUNT SETTINGS',
    [
        'text' => 'Profile',
        'route' => 'admin.profile',
        'icon' => 'fas fa-fw fa-user',
    ],
    [
        'text' => 'Change Password',
        'route' => 'admin.password',
        'icon' => 'fas fa-fw fa-lock',
    ],
],
```

With a single string, you specify a menu header item to separate the items.
With an array, you specify a menu item, `text` and `url` or `route` are required attributes.
The `icon` attribute is optional, you get an [open circle](https://fontawesome.com/icons/circle?style=regular&from=io) if you leave it out.
The available icons that you can use are those from [Font Awesome](https://fontawesome.com/icons).
Just specify the name of the icon and it will appear in front of your menu item.

It's also possible to add menu items to the top navigation while the sidebar is enabled, you just need to set the `topnav` attribute to `true`. You can also set `topnav_right` for put the item on the right side of the topnav or `topnav_user` to place it in the user menu (above the user-body).
When the top navigation layout is enabled, all menu items will appear in the top navigation.

To place an item dynamically you can use the `key` attribute, with this option you set an unique identifier. You can use this identifier later to add new items before or after the item represented by this `key` identifier.

To add `data-attributes` to your menu links, your can simply add an associative array called `data`. Here is a basic example:

```php
[
    [
        'header' => 'BLOG',
        'url' => 'admin/blog',
        'data' => [
            'test' => 'content',
        ],
    ],
    [
        'text' => 'Add new post',
        'url' => 'admin/blog/new',
        'data' => [
            'test-one' => 'content-one',
            'test-two' => 'content-two',
        ],
    ],
]
```

Use the `can` attribute if you want to conditionally show the menu item. This integrates with the Laravel's `Gate` functionality. If you need to conditionally show headers as well, you need to wrap it in an array like other menu items, using the `header` attribute. You can also use multiples `can` entries with an array, see the second example:

```php
[
    [
        'header' => 'BLOG',
        'url' => 'admin/blog',
        'can' => 'manage-blog',
    ],
    [
        'text' => 'Add new post',
        'url' => 'admin/blog/new',
        'can' => ['add-blog-post', 'other-right'],
    ],
]
```

#### 6.11.1 Adding a Search Input

It's possible to add a search input in your menu, using a menu item with the following configuration:

```php
[
    'search' => true,
    'url' => 'test',                     // the form action
    'method' => 'POST',                  // the form method
    'input_name' => 'menu-search-input', // the input name
    'text' => 'Search',                  // the input placeholder
],
```

#### 6.11.2 Custom Menu Filters

If you need to use custom filters, you can easily add your own menu filters to this package. This can be useful when you are using a third-party package for authorization (instead of Laravel's `Gate` functionality).

For example, with Laratrust:

```php
<?php

namespace MyApp;

use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Laratrust\Laratrust;

class MyMenuFilter implements FilterInterface
{
    public function transform($item)
    {
        if (isset($item['permission']) && ! Laratrust::isAbleTo($item['permission'])) {
            $item['restricted'] = true;
        }

        return $item;
    }
}
```

And then add configuration to the `config/adminlte.php` file:

```php
'filters' => [
    JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
    JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
    JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
    // Comment next line out.
    //JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
    MyApp\MyMenuFilter::class,
]
```

#### 6.11.3 Menu Configuration at Runtime

It is also possible to configure the menu at runtime, e.g. in the boot of any service provider or from a controller.
You can add new menu items at end of the menu, before or after a specific menu item and also inside a menu item as a submenu item. 
Use this if your menu is not static, for example when it depends on your database or the locale.
It is also possible to combine both approaches. The menu will be simply concatenated and the order of the service providers
will determine the order in the menu.

The available Menu Builder methods are:

- __`add(...$newItems)`__

  Adds one or multiple menu items to the sidebar menu or topnav menus (right, left or usermenu).

- __`addAfter($itemKey, ...$newItems)`__

  Adds one or multiple menu items after a specific menu item to the sidebar menu or topnav menus (right, left or usermenu).

- __`addBefore($itemKey, ...$newItems)`__

  Adds one or multiple menu items before a specific menu item to the sidebar menu or topnav menus (right, left or usermenu).

- __`addIn($itemKey, ...$newItems)`__

  Adds one or multiple menu items inside a specific menu item as sub menu item to the sidebar menu or topnav menus (right, left or usermenu).

- __`remove($itemKey)`__

  Removes one specific menu item.

- __`itemKeyExists($itemKey)`__

  Checks if a specific menu item exists, searched by the `key` attribute.

To configure the menu at runtime, just register a handler or callback for the `MenuBuilding` event, for example, in the `boot()` method of a service provider:

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

The configuration options for a menu item are the same explained previously.

Here is a more practical example that uses translations and the database:

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

This event-based approach is used to make sure that the code that builds the menu runs only when the admin panel is actually displayed and not on every request.

__Basic `AddAfter`, `AddBefore` & `AddIn` Examples:__

In this example we add a `key` attribute to the pages menu item.

```php
[
    'key'         => 'pages',
    'text'        => 'pages',
    'url'         => 'admin/pages',
    'icon'        => 'far fa-fw fa-file',
    'label'       => 4,
    'label_color' => 'success',
],
```

Now we going to add the next menu items.

1. __Account Settings__ after __Pages__
2. __Notifications__ inside __Account Settings__
3. __Profile__ before __Notifications__

For this, we use the next code:

```php
$events->listen(BuildingMenu::class, function (BuildingMenu $event) {

    $event->menu->addAfter('pages', [
        'key' => 'account_settings',
        'header' => 'Account Settings',
    ]);

    $event->menu->addIn('account_settings', [
        'key' => 'account_settings_notifications',
        'text' => 'Notifications',
        'url' => 'account/edit/notifications',
    ]);

    $event->menu->addBefore('account_settings_notifications', [
        'key' => 'account_settings_profile',
        'text' => 'Profile',
        'url' => 'account/edit/profile',
    ]);
});
```

#### 6.11.4 Active Menu Items

By default, a menu item is considered active if any of the following conditions holds:
- The current path matches the `url` parameter
- The current path is a sub-path of the `url` parameter
- If it has a submenu containing an active menu item

To override this behavior, you can specify an `active` parameter with an array of active URLs, asterisks and regular expressions are supported.

To utilize a regex, simply prefix your pattern with `regex:` and it will get evaluated automatically. The pattern will attempt to match the path of the URL, returned by `request()->path()`, which returns the current URL without the domain name. Example:

```php
[
    'text' => 'Pages',
    'url' => 'pages',
    'active' => ['pages', 'content', 'content/*', 'regex:@^content/[0-9]+$@']
]
```

### 6.12 Menu Filters

We can set the filters you want to include for rendering the menu.
You can add your own filters to this array after you've created them. You can comment out the `GateFilter` if you don't want to use Laravel's built in Gate functionality.

- __`filters`__: An array of menu filters.

The default set of menu filters is:

```php
'filters' => [
    JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
    JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
    JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
    JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
    JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
    JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
    JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
],
```

### 6.13 Plugins

Lets you configure which JavaScript plugins should be included. At this moment, DataTables, Select2, Chartjs and SweetAlert are added out-of-the-box, including the Javascript and CSS files from a CDN via `<script>` and `<link>` tags. The plugin **active** status and the **files** array (even empty) are all required attributes. The **files**, when added, need to have a **type** attribute (`js` or `css`), an **asset** attribute (`true` or `false`) and a **location** (`string`). When **asset** is set to `true`, the **location** will be output using the Laravel's `asset()` function.

By default the [DataTables](https://datatables.net/), [Select2](https://select2.github.io/), [ChartJS](https://www.chartjs.org/), [Pace](http://github.hubspot.com/pace/docs/welcome/) and [SweetAlert2](https://sweetalert2.github.io/) plugins are supported but not active.
You can activate them with changing the config file to load it on every page, or add a section in specific blade files, this will automatically inject their CDN files.

To inject a plugin using a blade section use the following code example:

```blade
@section('plugins.Datatables', true)
```

By default, you can use the next plugins:
- `Datatables`
- `Select2`
- `Chartjs`
- `Sweetalert2`
- `Pace`

Also, you can add and configure new plugins modifying the plugin variable, using the example structure below:

```php
'plugins' => [
    'Plugin Name' => [
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

With the `name` string you specify the plugin name, and the `active` value will enable/disable the plugin injection.
Each plugin have a `files` array, that contain arrays with file type (`js` or `css`), and a `location`.
If the `asset` value is `true`, the injection will use the `asset()` function.

#### 6.13.1 Pace Plugin Configuration

You can change the Pace plugin theme, modifying the css file location when using the CDN injection.

```php
'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/{{color}}/pace-theme-{{theme}}.min.css',
``` 

- __Available colors are__: black, blue (default), green, orange, pink, purple, red, silver, white & yellow
- __Available themes are__: barber-shop, big-counter, bounce, center-atom, center-circle, center-radar (default), center-simple, corner-indicator, fill-left, flash, flat-top, loading-bar, mac-osx, minimal


## 7. Translations

At the moment, English, German, French, Dutch, Portuguese, Spanish and Turkish translations are available out of the box.
Just specify the language in `config/app.php`.
If you need to modify the texts or add other languages, you can publish the language files:

```
php artisan adminlte:install --only=translations
```

Now, you are able to edit translations or add languages in the `resources/lang/vendor/adminlte` folder.

### 7.1 Menu Translations

The menu translations are enabled by default and allows you to use lang files for menu items translation.

#### Configure Menu Item for Translation:

First, we need to configure the menu. We add translation `keys` to the `text` and `header` attributes. These are the currently supported menu attributes for translations.
This is an example of configuration:

```php
[
    'header' => 'account_settings_trans'
],
[
    'text' => 'profile_trans',
    'url'  => 'admin/settings',
    'icon' => 'user',
],
```

#### Lang Files

All the translation strings must be added in the `menu.php` file of each language needed. You need to declare a `key` for each one of the menu items translations.
The translations files are located at the `resources/lang/vendor/adminlte/` folder

This is an example of the `resources/lang/vendor/adminlte/en/menu.php` lang file for the previous sample of configuration:

```php
return [
    'account_settings_trans'  => 'ACCOUNT SETTINGS',
    'profile_trans'           => 'Profile',
];
```

## 8. Customize Views

If you need full control over the provided views, you can publish them:

```sh
php artisan adminlte:install --only=main_views
```

Now, you can edit the views in the `resources/views/vendor/adminlte` folder.


## 9. Issues, Questions and Pull Requests

You can report issues and ask questions in the [issues section](https://github.com/jeroennoten/Laravel-AdminLTE/issues). Please start your issue with `ISSUE: ` and your question with `QUESTION: `

If you have a question, check the closed issues first.

To submit a Pull Request, please fork this repository, create a new branch and commit your new/updated code in there. Then open a Pull Request from your new branch. Refer to [this guide](https://help.github.com/articles/about-pull-requests/) for more info.

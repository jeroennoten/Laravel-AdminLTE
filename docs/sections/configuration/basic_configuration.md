In order to change the package configuration, the configuration file should be published (a default task when installing this package). However, if you don't see the `adminlte.php` file inside your Laravel `config` folder, then publish the configuration file with the next command:

```sh
php artisan adminlte:install --only=config
```

Now, you will be able to edit the `config/adminlte.php` file and setup the title, layout, menu, URLs, etc. On the next sections, we are going to review all the available configuration options. Let's start with the most basic ones.

| Basic Configuration
| -------------------
| [Title](#title)
| [Favicons](#favicons)
| [Google Fonts](#google-fonts)
| [Admin Panel Logo](#admin-panel-logo)
| [Authentication Logo](#authentication-logo)
| [Preloader Animation](#preloader-animation)
| [User Menu](#user-menu)
| [URLs](#urls)



## Title

This is the default title for your admin panel, and goes into the title tag of your page. However, you can override it per page with the available title section. Optionally, you can also specify a title prefix and/or postfix.

The following config options are available:

- __`title`__: The default title.
- __`title_prefix`__: The title prefix.
- __`title_postfix`__: The title postfix.

## Favicons

Favicons could be used easily. There are two different ways to do this. Take in mind that all the favicons should be placed in the `public/favicons/` folder. The next two combinations determines how the favicons will be used:

- __`['use_ico_only' => true, 'use_full_favicon' => false]`__

  With the previous configuration, the file `public/favicons/favicon.ico` will be used.

- __`['use_ico_only' => false, 'use_full_favicon' => true]`__

  With the previous configuration, multiple favicon files located on the `public/favicons/` folder will be used. The current code to use multiple favicons is the next one:

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

## Google Fonts

> [!Important]
> The next configuration is only available for package versions greater than <Badge type="tip">v3.8.2</Badge>.

By default, the provided admin panel layout includes some **google fonts**, and you should note that they are an external resource. However, this may introduce performance issues in environments where the internet access is restricted somehow. For those scenarios, you may use the next option to disable the usage of the external **google fonts**.

- __`google_fonts.allowed`__: Whether to allow the inclusion of external google fonts.

## Admin Panel Logo

The logo is displayed at the upper left corner of your admin panel. You can use basic `HTML` code here if you want a simple text logo with a small image logo (e.g. 50 x 50 pixels), or you can use two images: one big (e.g. 210 x 33 pixels) and one small (e.g. 50 x 50 pixels). You can also change the sizes of the images and the alternate text for both logos. The available option are:

- __`logo`__: The text logo content, `HTML` markup is accepted.
- __`logo_img`__: The path to the small logo image. The recommend size is: _50x50px_.
- __`logo_img_class`__: Extra classes for the small logo image.
- __`logo_img_xl`__: The path to the large logo image, if you set a image url here, then it will replace the text & small logo with one big logo. When the sidebar is collapsed it will displays only the small logo. The recommend size is: _210x33px_.
- __`logo_img_xl_class`__: Extra classes for the large logo image.
- __`logo_img_alt`__: The alternate text for the logo images.

## Authentication Logo

> [!Important]
> The next configuration is only available for package versions greater than <Badge type="tip">v3.8.3</Badge>.

The next options allows you to enable, disable and/or configure the authentication logo. The authentication logo, when enabled, will be shown on the login and register pages as a replacement for the standard logo. When disabled, the standard admin panel logo will be shown on those pages instead.

- __`auth_logo.enabled`__: Whether to enable the authentication logo.
- __`auth_logo.img.path`__: The path to the logo image that will be used. This image should be available somewhere inside the `public` folder of your Laravel project (if you did not change the `asset_url` config).
- __`auth_logo.img.alt`__: The alternative text to use when the image can't be found or isn't available.
- __`auth_logo.img.class`__: The additional classes to use on the logo image.
- __`auth_logo.img.width`__: The width (on pixels) to use for the image.
- __`auth_logo.img.height`__: The height (on pixels) to use for the image.

## Preloader Animation

> [!Important]
> The next configuration is only available for package versions greater than <Badge type="tip">v3.8.2</Badge>. The `preloader.mode` configuration is only available for package versions greater than <Badge type="tip">v3.9.4</Badge>.

The next options allows you to enable, disable and configure the preloader animation. The preloader animation, when enabled, will be shown while a page is loading, and then will be hidden automatically.

- __`preloader.enabled`__: Whether to enable the preloader animation.
- __`preloader.mode`__: The preloader animantion mode: `fullscreen` or `cwrapper`. On `fullscreen` mode (the default), the preloader animation will cover the entire page. When using `cwrapper` mode, the preloader animation will be attached into the `content-wrapper` element to avoid covering the sidebars and navbars.
- __`preloader.img.path`__: The path to the logo image that will be used on the preloader animation. This image should be available somewhere inside the `public` folder of your Laravel project (if you did not change the `asset_url` config).
- __`preloader.img.alt`__: The alternative text to use when the image can't be found or isn't available.
- __`preloader.img.effect`__: The animation effect to use on the image, the available values are: `animation__shake` or `animation__wobble`.
- __`preloader.img.width`__: The width (on pixels) to use for the image.
- __`preloader.img.height`__: The height (on pixels) to use for the image.

If you don't want an image logo in your preloader (the one allowed by the configuration), you can setup a custom preloader content by using the yielded section `@section('preloader')`, for example:

```blade
@extends('adminlte::page')
...
...
...
{{-- Setup Custom Preloader Content --}}

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">Loading</h4>
@stop
```

## User Menu

The user menu is displayed at the upper right corner of your admin panel. The available options for the user menu are:

- __`usermenu_enabled`__

  Whether to enable the user menu instead of the default logout button.

- __`usermenu_header`__

  Whether to enable the header section inside the user menu.

- __`usermenu_header_class`__

  Extra classes for the header section of the user menu.

- __`usermenu_image`__

  Whether to enable the user image for the usermenu & lockscreen.

> [!Caution]
> For this feature to work, you will need to add an extra function named `adminlte_image()` that returns the user image path inside the `User` model, usually located on the `app/User.php` file. The recommend image size is: _160x160px_.

- __`usermenu_desc`__

  Whether to enable the user description for the usermenu.

> [!Caution]
> For this feature to work, you will need to add an extra function named `adminlte_desc()` that returns the user description, inside the `User` model, usually located on the `app/User.php` file.

- __`usermenu_profile_url`__

  Whether to enable if the user profile url can be set dynamically for the user instead of using the config option `profile_url`.

> [!Caution]
> For this feature to work, you will need to add an extra function named `adminlte_profile_url()` inside the `User` model. The return value should be a string with the profile url path, not a route.

### Code Example:

Here you have a code example for the `User` model with the custom image, description and profile url functions.

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
        return 'I\'m a nice guy';
    }

    public function adminlte_profile_url()
    {
        return 'profile/username';
    }
}
```

## URLs

The next configuration options provides a way to setup the urls for the login, register and other links used on the admin panel.

- __`use_route_url`__

  Whether to use `route()` instead of the `url()` Laravel method when internally generating the urls.

> [!Caution]
> When set to `true`, the next set of URLs should be defined by using route names. For example: `password.email` on __`password_email_url`__, `password.update` on __`password_reset_url`__, etc.

- __`dashboard_url`__

  Changes the dashboard/logo URL. This URL will be used, for example, when clicking on the upper left logo.

- __`logout_url`__

  Changes the logout URL. This URL will be used when you click on the logout button.

- __`logout_method`__

  Changes the logout send method, the available options are: `GET`, `POST` & `null` (Laravel default).

- __`login_url`__

  Changes the login URL. This URL will be used when you click on the login button.

- __`register_url`__

  Changes the register URL. Set this option to `false` or `null` to hide the register link shown on the login view.

- __`password_reset_url`__

  Changes the password reset URL. This url should point to the view that displays the password reset form. Set this option to `false` or `null` to hide the password reset link shown on the login view.

- __`password_email_url`__

  Changes the password email URL. This url should point to the view that displays the send reset link form.

- __`profile_url`__

  Changes the user profile URL. When not `false` or `null`, it will displays a button in the dropdown user menu.

- __`disable_darkmode_routes`__

  [**Only from <Badge type="tip">v3.14.0</Badge>**] When set to `true` the dark mode routes won't be registered in your Laravel's application. Please note dark mode routes are needed if you're planning to use the special menu item [Navbar Darkmode Widget](/sections/configuration/special_menu_items#navbar-darkmode-widget).

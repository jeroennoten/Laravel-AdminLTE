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

## Installation

1. Require the package using composer:


    composer require jeroennoten/laravel-adminlte

2. Add the service provider to the `providers` in `config/app.php`:


    JeroenNoten\LaravelAdminLte\ServiceProvider::class,

3. Publish the config file and assets:


    php artisan vendor:publish --provider="JeroenNoten\LaravelAdminLte\ServiceProvider"

## Usage

To use the template, create a blade file and extend the layout with `@extends('adminlte::page')`.
This template yields the following sections:

- `title`: for in the `<title>` tag
- `content_header`: title of the page, above the content
- `content`: all of the page's content
- `css`: extra stylesheets (located in `<head>`)
- `js`: extra javascript (just before `</body>`)

All sections are in fact optional. Your blade template could look like the following.

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

You now just return this view from your controller, as usual. Check out [AdminLTE](https://almsaeedstudio.com) to find out how to build beautiful content for your admin panel.

## Configuration

Edit `config/adminlte.php` to configure the title, skin, menu, URLs etc. All configuration options are explained in the comments. However, I want to shed some light on the `menu` configuration.
You can configure your menu as follows:

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

With a single string, you specify a menu header item to separate the items.
With an array, you specify a menu item. `text` and `url` are required attributes.
The `icon` is optional, you get an [open circle](http://fontawesome.io/icon/circle-o/) if you leave it out.
The available icons that you can use are those from [Font Awesome](http://fontawesome.io/icons/).
Just specify the name of the icon and it will appear in front of your menu item.
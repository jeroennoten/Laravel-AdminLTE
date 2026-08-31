# Easy AdminLTE integration with Laravel

This package provides an easy way to quickly set up [AdminLTE v4](https://adminlte.io/) with [Laravel](https://laravel.com/). Its only dependencies are **Laravel** itself and the [AdminLTE template](https://github.com/ColorlibHQ/AdminLTE) (pulled in by composer as `almasaeed2010/adminlte`), so you can start building your admin panel immediately. The package provides a [blade template](https://laravel.com/docs/blade) that you can extend and an advanced menu configuration system. Also, and optionally, the package offers a set of **AdminLTE** styled authentication views that you can use in replacement of the ones that are provided by the legacy [laravel/ui](https://github.com/laravel/ui) authentication scaffolding.

## Two version numbers, do not mix them up

This documentation talks about two different things that both carry a **v4**:

- **Laravel-AdminLTE v4** (also written as _package version_ `4.x`) is **this package**, its new major release. Whenever you read _"the package"_, _"this package"_ or _"the `4.x` releases"_, this is what is meant.
- **AdminLTE v4** is the **upstream admin template** ([ColorlibHQ/AdminLTE](https://github.com/ColorlibHQ/AdminLTE)), which this package integrates and installs as a composer dependency. The `4.x` package line requires **AdminLTE `^4.8`** (currently 4.8/4.9).

**AdminLTE v4** is built on top of **Bootstrap 5.3**, ships **Bootstrap Icons** as its icon set and is completely **jQuery free**.

## Supported versions

**Laravel-AdminLTE v4** supports **Laravel 12** and **Laravel 13** on **PHP 8.2 or higher**. See the [requirements](/sections/overview/requirements) page.

Only the **4.x** line of this package is maintained. The older lines are provided as they are:
- **Releases 1.x**: Laravel 5, AdminLTE v2
- **Releases 2.x**: Laravel 6, AdminLTE v2
- **Releases 3.x**: AdminLTE v3

> [!Tip]
> Coming from a **3.x** release of this package? Read the [Upgrading from 3.x](/sections/overview/upgrading_from_3x) page first, it lists every breaking change you are going to hit. Most public configuration keys and blade component attributes of the `3.x` releases are still accepted, and the options that became meaningless on **AdminLTE v4** are documented as deprecated no-ops on the page of the related feature.

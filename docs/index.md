# Easy AdminLTE integration with Laravel

This package provides an easy way to quickly set up [AdminLTE v4](https://adminlte.io/) with [Laravel](https://laravel.com/). It has no other requirements and dependencies besides **Laravel**, so you can start building your admin panel immediately. The package provides a [blade template](https://laravel.com/docs/blade) that you can extend and an advanced menu configuration system. Also, and optionally, the package offers a set of **AdminLTE** styled authentication views that you can use in replacement of the ones that are provided by the legacy [laravel/ui](https://github.com/laravel/ui) authentication scaffolding.

The current release line bundles **AdminLTE v4.8.5**, which is built on top of **Bootstrap 5.3**, ships **Bootstrap Icons** as its icon set and is completely **jQuery free**.

If you want to use an older **Laravel** or **AdminLTE** version, review the following package releases:
- **Releases 1.x**:
  These releases supports Laravel 5 and include AdminLTE v2
- **Releases 2.x**:
  These releases supports Laravel 6 and include AdminLTE v2
- **Releases 3.x <Badge type="tip"><= v3.15.x</Badge>**:
  These releases supports Laravel 6 or higher and include AdminLTE v3

> [!Important]
> Only the version **4.x** of this package is currently maintained, so be aware that older versions are unmaintained and you'll have to use as they are.

> [!Tip]
> Coming from **AdminLTE v3**? Every public configuration key and blade component attribute of the `3.x` releases is still accepted. The options that became meaningless on **AdminLTE v4** are documented as deprecated no-ops on the page of the related feature.

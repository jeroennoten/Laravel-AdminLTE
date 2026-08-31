# Easy AdminLTE integration with Laravel

[![Latest Packagist Version](https://img.shields.io/packagist/v/jeroennoten/Laravel-AdminLTE?logo=github&logoColor=white&style=flat-square)](https://packagist.org/packages/jeroennoten/Laravel-AdminLTE)
[![Total Downloads](https://img.shields.io/packagist/dt/jeroennoten/Laravel-AdminLTE.svg?logo=github&logoColor=white&style=flat-square)](https://packagist.org/packages/jeroennoten/Laravel-AdminLTE)
[![GitHub Checks Status](https://img.shields.io/github/checks-status/jeroennoten/Laravel-AdminLTE/master?logo=github-actions&logoColor=white&style=flat-square)](https://github.com/jeroennoten/Laravel-AdminLTE/actions)
[![StyleCI](https://styleci.io/repos/38200433/shield?branch=master)](https://styleci.io/repos/38200433)

This package provides an easy way to quickly set up [AdminLTE v4](https://adminlte.io/) with [Laravel](https://laravel.com/). Its only dependencies are **Laravel** itself and the [AdminLTE template](https://github.com/ColorlibHQ/AdminLTE) (pulled in by composer as `almasaeed2010/adminlte`), so you can start building your admin panel immediately. The package provides a [blade template](https://laravel.com/docs/blade) that you can extend and an advanced menu configuration system. Also, and optionally, the package offers a set of **AdminLTE** styled authentication views that you can use in replacement of the ones that are provided by the legacy [laravel/ui](https://github.com/laravel/ui) authentication scaffolding.

> **Note:** this is **Laravel-AdminLTE v4**, the new major release of this package. It ships **AdminLTE v4** (the upstream template), which is built on top of **Bootstrap 5.3**, uses **Bootstrap Icons** and is completely **jQuery free**. The two version numbers are unrelated: `4.x` of this package is not the same thing as `4.x` of the AdminLTE template, they just happen to match on this release line.

Older package release lines are unmaintained, but still available:
- **Releases 1.x**: Laravel 5, AdminLTE v2
- **Releases 2.x**: Laravel 6, AdminLTE v2
- **Releases 3.x**: AdminLTE v3

## Documentation

All documentation is available at [Laravel-AdminLTE Documentation](https://jeroennoten.github.io/Laravel-AdminLTE) site, we encourage you to read it. If you are new start with the [Installation Guide](https://jeroennoten.github.io/Laravel-AdminLTE/sections/overview/installation.html). To update the package consult the [Updating Guide](https://jeroennoten.github.io/Laravel-AdminLTE/sections/overview/updating.html). If you are coming from a `3.x` release, read the [Upgrading from 3.x Guide](https://jeroennoten.github.io/Laravel-AdminLTE/sections/overview/upgrading_from_3x.html) first.


## Requirements

The current package requirements are:

- Laravel 12.x or 13.x
- PHP >= 8.2
- AdminLTE 4.x (installed as a composer dependency, currently `^4.8`)


## Issues, Questions and Pull Requests

You can report issues or ask questions in the [issues section](https://github.com/jeroennoten/Laravel-AdminLTE/issues). Please, start your issue with `[BUG]` and your question with `[QUESTION]` in the subject.

If you have a question, it is recommended to make a search over the closed issues first.

To submit a Pull Request, fork this repository and create a new branch to commit your new changes there. Finally, open a Pull Request from your new branch. Refer to the [contribution guidelines](https://github.com/jeroennoten/Laravel-AdminLTE/blob/master/.github/CONTRIBUTING.md) for detailed instructions. When submitting a Pull Request take the next notes into consideration:

- Verify that the Pull Request doesn't introduce a high downgrade on the code quality.
- If the Pull Request adds a new feature, consider adding a proposal of the documentation for this feature too.
- Keep the package focused, don't add special support to other packages or to solve very particular situations. These changes will make the package harder to maintain.

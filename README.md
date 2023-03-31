# Easy AdminLTE integration with Laravel

[![Latest Packagist Version](https://img.shields.io/packagist/v/jeroennoten/Laravel-AdminLTE?logo=github&logoColor=white&style=flat-square)](https://packagist.org/packages/jeroennoten/Laravel-AdminLTE)
[![Total Downloads](https://img.shields.io/packagist/dt/jeroennoten/Laravel-AdminLTE.svg?logo=github&logoColor=white&style=flat-square)](https://packagist.org/packages/jeroennoten/Laravel-AdminLTE)
[![GitHub Checks Status](https://img.shields.io/github/checks-status/jeroennoten/Laravel-AdminLTE/master?logo=github-actions&logoColor=white&style=flat-square)](https://github.com/jeroennoten/Laravel-AdminLTE/actions)
[![Quality Score](https://img.shields.io/scrutinizer/quality/g/jeroennoten/Laravel-AdminLTE.svg?logo=scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/jeroennoten/Laravel-AdminLTE)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/jeroennoten/Laravel-AdminLTE.svg?logo=scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/jeroennoten/Laravel-AdminLTE)
[![StyleCI](https://styleci.io/repos/38200433/shield?branch=master)](https://styleci.io/repos/38200433)

This package provides an easy way to quickly set up [AdminLTE v3](https://adminlte.io/themes/v3/) with [Laravel](https://laravel.com/) (7 or higher). It has no others requirements and dependencies besides **Laravel**, so you can start building your admin panel immediately. The package provides a [blade template](https://laravel.com/docs/blade) that you can extend and an advanced menu configuration system. Also, and optionally, the package offers a set of **AdminLTE** styled authentication views that you can use in replacement of the ones that are provided by the legacy [laravel/ui](https://github.com/laravel/ui) authentication scaffolding.

If you want to use an older **Laravel** or **AdminLTE** version, review the following package releases:
- **Releases 1.x**:
  These releases supports Laravel 5 and include AdminLTE v2
- **Releases 2.x**:
  These releases supports Laravel 6 and include AdminLTE v2
- **Releases 3.x (<=3.8.6)**:
  These releases supports Laravel 6 and include AdminLTE v3

## Documentation

All documentation is available on the [Wiki Pages](https://github.com/jeroennoten/Laravel-AdminLTE/wiki), we encourage you to read it. If you are new start with the [Installation Guide](https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Installation). To update the package consult the [Updating Guide](https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Updating).


## Requirements

The current package requirements are:

- Laravel >= 7.x
- PHP >= 7.2.5


## Issues, Questions and Pull Requests

You can report issues or ask questions in the [issues section](https://github.com/jeroennoten/Laravel-AdminLTE/issues). Please, start your issue with `[BUG]` and your question with `[QUESTION]` in the subject.

You may also use the issues to propose changes for the **Wiki Pages**, in that case use `[WIKI]` at the beginning of the subject.

If you have a question, it is recommended to make a search over the closed issues first.

To submit a Pull Request, fork this repository and create a new branch to commit your new changes there. Finally, open a Pull Request from your new branch. Refer to the [contribution guidelines](https://github.com/jeroennoten/Laravel-AdminLTE/blob/master/.github/CONTRIBUTING.md) for detailed instructions. When submitting a Pull Request take the next notes into consideration:

- Verify that the Pull Request doesn't introduce a high downgrade on the code quality.
- If the Pull Request adds a new feature, consider adding a proposal of the documentation for the Wiki.
- Keep the package focused, don't add special support to other packages or to solve very particular situations. These changes will make the package harder to maintain.

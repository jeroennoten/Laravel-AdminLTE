# Easy AdminLTE integration with Laravel

[![Latest Packagist Version](https://img.shields.io/packagist/v/jeroennoten/Laravel-AdminLTE?logo=github&logoColor=white&style=flat-square)](https://packagist.org/packages/jeroennoten/Laravel-AdminLTE)
[![Total Downloads](https://img.shields.io/packagist/dt/jeroennoten/Laravel-AdminLTE.svg?logo=github&logoColor=white&style=flat-square)](https://packagist.org/packages/jeroennoten/Laravel-AdminLTE)
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/jeroennoten/Laravel-AdminLTE/run-tests?logo=github-actions&logoColor=white&style=flat-square)](https://github.com/jeroennoten/Laravel-AdminLTE/actions)
[![Quality Score](https://img.shields.io/scrutinizer/quality/g/jeroennoten/Laravel-AdminLTE.svg?logo=scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/jeroennoten/Laravel-AdminLTE)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/jeroennoten/Laravel-AdminLTE.svg?logo=scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/jeroennoten/Laravel-AdminLTE)
[![StyleCI](https://styleci.io/repos/38200433/shield?branch=master)](https://styleci.io/repos/38200433)

This package provides an easy way to quickly set up [AdminLTE v3](https://adminlte.io/themes/v3/) with [Laravel](https://laravel.com/) (6 or higher). It has no requirements and dependencies besides **Laravel**, so you can start building your admin panel immediately. The package provides a [blade template](https://laravel.com/docs/8.x/blade) that you can extend and advanced menu configuration possibilities. Also, and optionally, the package includes a set of **AdminLTE** styled authentication views that you can use in replacement of the ones that are provided by the [laravel/ui](https://github.com/laravel/ui) authentication scaffolding.

If you want to use an older **Laravel** or **AdminLTE** version, review the following package releases:
- **Releases 1.x**:
  These releases supports Laravel 5 and include AdminLTE v2
- **Releases 2.x**:
  These releases supports Laravel 6 or higher and include AdminLTE v2

## Documentation

All documentation is available on the [Wiki Pages](https://github.com/jeroennoten/Laravel-AdminLTE/wiki), we encourage you to read it. If you are new start with the [Installation Guide](https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Installation). To update the package consult the [Updating Guide](https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Updating).


## Requirements

The current package requirements are:

- Laravel >= 6.x
- PHP >= 7.2


## Issues, Questions and Pull Requests

You can report issues or ask questions in the [issues section](https://github.com/jeroennoten/Laravel-AdminLTE/issues). Please, start your issue with `[BUG]` and your question with `[QUESTION]` in the subject.

If you have a question, it is recommended to search and check the closed issues first.

To submit a Pull Request, please fork this repository, create a new branch and commit your new/updated code in there. Then open a Pull Request from your new branch. Refer to the [contribution guidelines](https://github.com/jeroennoten/Laravel-AdminLTE/blob/master/.github/CONTRIBUTING.md) for detailed instructions. When submitting a Pull Request take the next notes into consideration:

- Check that the Pull Request don't introduce a high downgrade on the code quality.
- If the Pull Request introduce new features, consider adding the related documentation of this feature on the Wiki documentation.
- Keep the package focused, don't add special support to other packages or to very particular situations. These changes will make the package more hard to maintain.

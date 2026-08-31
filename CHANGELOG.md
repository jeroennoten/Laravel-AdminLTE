# Changelog

All notable changes to this package are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [4.0.0] - Unreleased

The first release of the `4.x` line. It replaces the bundled **AdminLTE v3**
template by **AdminLTE v4**, which is a ground-up rewrite on Bootstrap 5.3
without jQuery. This is a **breaking release**, please read the
[Upgrading from 3.x](https://jeroennoten.github.io/Laravel-AdminLTE/sections/overview/upgrading_from_3x.html)
guide before updating.

### Added

- Right-to-left (RTL) support through the new `rtl` configuration section, with
  an automatic detection based on the application locale.
- Color mode support built on the Bootstrap 5.3 native color modes, through the
  new `color_mode` configuration section, including a script that prevents the
  flash of an incorrect theme before the first paint.
- Asset delivery configuration (`assets` section): the assets are served from
  the published files or from a CDN, with a per file CDN fallback. The CDN
  locations follow the AdminLTE version that composer installed.
- Support for the optional AdminLTE extended color palette, which also
  generates the `alert-*`, `btn-*` and `btn-outline-*` families that the
  palette stylesheet does not provide.
- New `vendor_assets` console resource, which publishes the third party assets
  that AdminLTE v4 no longer distributes (Bootstrap, Bootstrap Icons and
  OverlayScrollbars) from the node modules folder of the application.
- New `sidebar_expand`, `sidebar_without_hover`, `sidebar_theme`,
  `classes_footer`, `logout_method` and `right_sidebar_*` options.

### Changed

- The whole layout, all the partials and all the blade components were
  rewritten with the AdminLTE v4 markup and Bootstrap 5.3.
- Bootstrap Icons replaced Font Awesome on every default of the package.
- The right sidebar is now a Bootstrap offcanvas panel.
- The iframe mode is implemented by the package itself, since AdminLTE v4
  removed its iframe plugin.
- The plugins of the `adminlte:plugins` command are the jQuery free libraries
  recommended by AdminLTE v4, published from the node modules folder. The
  AdminLTE v3 plugin keys are still recognized and report their replacement.
- The advanced form components target the new libraries: Tom Select, Tabulator,
  Flatpickr, Quill and noUiSlider. The switch and the color input use the
  native Bootstrap 5.3 controls and need no plugin.
- Cards, progress bars and form groups provide their own default spacing, which
  a caller supplied margin utility overrides.

### Removed

- Support for Laravel 11 and older, and for PHP 8.1 and older. The package
  requires **Laravel 12 or 13** on **PHP 8.2 or higher**.
- The boxed layout and the sidebar skins, which AdminLTE v4 dropped.
- The legacy `enabled_laravel_mix`, `laravel_mix_css_path` and
  `laravel_mix_js_path` options. Use `laravel_asset_bundling => 'mix'` with
  `laravel_css_path` and `laravel_js_path` instead.

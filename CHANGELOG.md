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
- Palette tuning through the new `assets.palette` section: `primary` remaps the
  primary color of the whole template with the AdminLTE `data-lte-primary`
  attribute, and `contrast` applies the WCAG AA correction of the palette
  (`data-lte-contrast`), which is enabled by default on the v3 palette.
- New `layout_compact` option, which enables the AdminLTE v4 compact mode, and
  a new `classes_wrapper` option for the `.app-wrapper` element.
- New `content_top_area` and `content_bottom_area` blade sections, together
  with their `classes_content_top_area` and `classes_content_bottom_area`
  options. They fill the AdminLTE v4 custom content areas.
- New `color_mode.enabled` option, which opts out of the AdminLTE color mode
  plugin explicitly when the application does its own theming.
- Publish groups for the standard Laravel workflow: `adminlte-config`,
  `adminlte-views`, `adminlte-lang` and `adminlte-assets`.
- New blade components: `content-header` (title plus breadcrumbs), `timeline`
  with `timeline-item` and `timeline-label`, `ribbon`, `progress-group`,
  `user-block` and `toast`.
- New slots and options on the existing components, so fewer cases require
  publishing a view.
- The AdminLTE v4 **direct chat** widget, through the new `direct-chat`,
  `direct-chat-msg` and `direct-chat-contact` components. The contacts pane is
  driven by the AdminLTE plugin, so it needs no javascript of its own.
- Navbar dropdown menus, through the new `navbar-dropdown`,
  `navbar-dropdown-item` and `navbar-custom-menu` components. They cover the
  `dropdown-menu-lg`, `dropdown-menu-xl`, `dropdown-item-title` and
  `animated-dropdown-menu` families of the stylesheet.
- AdminLTE styled **error views** for the 401, 403, 404, 419, 429, 500 and 503
  status codes, published into the application with
  `adminlte:install --only=error_views`. The published files only extend the
  package ones, so a package update reaches them without republishing.
- Sidebar navigation variants (`sidebar_nav_compact`, `sidebar_nav_indent` and
  `sidebar_nav_pills`), the `sidebar_breakpoint` option of the push menu
  plugin, and the `sidebar_scrollbar_options` and
  `sidebar_scrollbar_disable_below` options of the scrollbars.
- The `maximizable="maximized"` initial card state, the `callout-link` support
  of the callout component through its new `url` and `url-text` options, and
  the stacked progress bars through the new `segments` option.
- The Datatables **Buttons** extension, as the new `DatatablesButtons` plugin
  entry and the `datatablesButtons` console catalog entry. The `with-buttons`
  attribute used to configure export buttons the installer could not provide.
- Social login buttons on the authentication views, through the new
  `auth_social_links` and `auth_social_links_separator` options.
- The `css_variables` and `css_variables_scope` options, which declare the
  Bootstrap and AdminLTE custom properties inline on the document head. Most
  brandings need no stylesheet of their own any more.
- The `lo` locale, which is the ISO 639-1 code of the Lao translations the
  package shipped under the wrong `la` folder. The old folder is kept as an
  alias of the new one.
- The missing `iframe.php` translations of 17 locales and the missing
  `menu.php` ones of 5, which used to fall back to English silently.
- A `composer test` script.
- A URL scheme allowlist on the social login buttons, and the way back to the
  login and register pages on the password reset view, which used to be a dead
  end.

### Changed

- The whole layout, all the partials and all the blade components were
  rewritten with the AdminLTE v4 markup and Bootstrap 5.3.
- The default menu ships the color mode selector, so the headline feature of
  AdminLTE v4 is visible on a fresh installation.
- The `laravel_css_path` and `laravel_js_path` defaults changed to
  `resources/css/app.css` and `resources/js/app.js`. The previous values were
  Laravel Mix shaped and made the Vite setup throw on the first request.
- The `right_sidebar_theme` default changed to `null`, so the offcanvas panel
  follows the color mode of the page instead of forcing the dark one.
- The progress bar emits `text-bg-{color}` instead of `bg-{color}`, which keeps
  its label readable over the light theme colors.
- The footer link of the small box follows the contrast of the box background,
  instead of always using the light variant.
- Every form component wires its validation state to the feedback block with
  `aria-invalid` and `aria-describedby`.
- The color mode of a themed modal header is derived from the palette, which
  fixes the invisible close button on the v3 color aliases.
- The datatable component configures the table through the Datatables 2.x
  `layout` option instead of the deprecated 1.x `dom` one, which is still
  honored when provided explicitly. Its export tooltips are translated.
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

### Fixed

- The user menu partial mixed the inline `@php(...)` and the block
  `@php ... @endphp` directives. The block form is matched lazily, so the
  compiler swallowed everything in between and emitted the `@else` and
  `@endif` of the url resolution as literal text, which broke the partial for
  every authenticated user. A test now compiles every shipped view and rejects
  that mix.
- The color mode of a themed modal header is derived from the palette, so the
  close button of a dark header stays visible with the v3 color aliases too.
- The `data-lte-toggle="toast"` controls resolve a `data-bs-target` that
  carries the leading `#` of the Bootstrap convention.
- A dummy configuration file left behind by an interrupted console test used to
  break every later test run. The suite drops it on startup.

### Removed

- Support for Laravel 11 and older, and for PHP 8.1 and older. The package
  requires **Laravel 12 or 13** on **PHP 8.2 or higher**.
- The boxed layout and the sidebar skins, which AdminLTE v4 dropped.
- The legacy `enabled_laravel_mix`, `laravel_mix_css_path` and
  `laravel_mix_js_path` options. Use `laravel_asset_bundling => 'mix'` with
  `laravel_css_path` and `laravel_js_path` instead.
- The `layout_boxed`, `sidebar_collapse_auto_size` and
  `sidebar_collapse_remember_no_transition` options left the shipped
  configuration file. They had no effect on AdminLTE v4.
- The duplicated `makeItemClass()` and `makeInputGroupClass()` overrides of the
  form components, and the size arrays of the `Progress`, `Modal`, `Ribbon` and
  `UserBlock` components. Every one of those methods is still available through
  the base class with identical output. The size arrays became `SIZES` class
  constants, so a subclass that redeclared the protected property has to
  redeclare the constant instead.

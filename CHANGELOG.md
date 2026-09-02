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

### Breaking Changes

Everything on this list needs an action on your side. The
[Upgrading from 3.x](https://jeroennoten.github.io/Laravel-AdminLTE/sections/overview/upgrading_from_3x.html)
guide walks through them with more context.

**Requirements**

- **Laravel 12 or 13** on **PHP 8.2 or higher** is required, and the template
  dependency moved to `almasaeed2010/adminlte:^4.8`. Upgrade the framework
  first, verify the application boots, and only then bump this package.

**Markup**

- Every layout view, every partial and every blade component was rewritten with
  the AdminLTE v4 markup. **Published copies still emit Bootstrap 4 markup and
  will not render correctly**, so back them up and re-publish them:
  `php artisan adminlte:install --only=main_views --force`, the same for
  `auth_views` and for `components`, and re-apply your changes on top.
- The layout element names changed: `.wrapper` is now `.app-wrapper`,
  `.content-wrapper` is now `main.app-main`, the navbar is `nav.app-header`,
  the sidebar is `aside.app-sidebar`, the footer is `footer.app-footer` and the
  content lives in `.app-content` and `.app-content-header`. The plugins are
  driven by `data-lte-toggle`, the v3 `data-widget` and `data-card-widget`
  attributes do not exist anymore, and **jQuery is not loaded by the package
  any longer**. Sweep your own views for all of it.
- Bootstrap Icons replaced Font Awesome on every default of the package, so an
  `'icon' => 'fas fa-user'` renders nothing. Translate your icons to `bi bi-*`,
  or keep loading the Font Awesome stylesheet yourself.
- The footer is no longer gated on a `footer` section being defined. It is also
  rendered whenever `layout_fixed_footer` is enabled, because the fixed layout
  has to reserve its space. Disable `layout_fixed_footer` to get no footer.

**Configuration options that were removed**

- `layout_boxed`, `layout_dark_mode`, `sidebar_collapse_auto_size`,
  `sidebar_collapse_remember_no_transition`, `right_sidebar_slide`,
  `right_sidebar_push`, `right_sidebar_scrollbar_theme` and
  `right_sidebar_scrollbar_auto_hide` left the shipped configuration file.
  Delete them from your published `config/adminlte.php`. The first two are
  still read as a legacy fallback, the other six are not read at all.
- `enabled_laravel_mix`, `laravel_mix_css_path` and `laravel_mix_js_path` are
  **not read anymore**. Use `laravel_asset_bundling => 'mix'` together with
  `laravel_css_path` and `laravel_js_path` instead.

**Configuration options that were renamed or split**

- `layout_dark_mode => true` becomes `color_mode.default => 'dark'`.
- `sidebar_mini` is a plain on/off switch now and the breakpoint moved to the
  new `sidebar_expand` option. The `'xs'`, `'md'` and `'lg'` values are still
  accepted, but they only mean *enabled*: AdminLTE v4 has no responsive
  variants of the mini sidebar.
- `sidebar_collapse_auto_size` becomes `sidebar_expand`.
- `sidebar_scrollbar_auto_hide` is handed over verbatim to OverlayScrollbars
  2.x, which only accepts `'never'`, `'scroll'`, `'leave'` and `'move'`. The
  one letter tokens are invalid, so `'l'` has to become `'leave'`.
- The `sidebar-dark-*` and `sidebar-light-*` skins of `classes_sidebar` were
  removed by AdminLTE v4. Put a Bootstrap background utility on
  `classes_sidebar` and set the color mode on the new `sidebar_theme` option.
- The sidebar navigation no longer carries a hardcoded `nav-pills` class. The
  pill, the compact and the indented variants are opt-in now, through the new
  `sidebar_nav_pills`, `sidebar_nav_compact` and `sidebar_nav_indent` options,
  which all default to `false`. Set `sidebar_nav_pills => true` to keep the
  `3.x` look.

**Defaults that changed**

| Option | `3.x` | `4.x` |
| ------ | ----- | ----- |
| `title` | `AdminLTE 3` | `AdminLTE 4` |
| `logo_img`, `auth_logo.img.path`, `preloader.img.path` | `vendor/adminlte/dist/img/…` | `vendor/adminlte/dist/assets/img/…` |
| `logo_img_class` | `brand-image img-circle elevation-3` | `brand-image opacity-75 shadow` |
| `logo_img_xl_class` | `brand-image-xs` | `brand-image-xs opacity-75` |
| `classes_body` | `''` | `bg-body-tertiary` |
| `classes_brand_text` | `''` | `fw-light` |
| `classes_sidebar` | `sidebar-dark-primary elevation-4` | `bg-body-secondary shadow` |
| `classes_topnav` | `navbar-white navbar-light` | `bg-body` |
| `classes_topnav_container` | `container` | `container-fluid` |
| `classes_auth_btn` | `btn-flat btn-primary` | `btn-primary` |
| `layout_fixed_sidebar` | `null` | `true` |
| `sidebar_mini` | `'lg'` | `true` |
| `sidebar_scrollbar_auto_hide` | `'l'` | `'leave'` |
| `right_sidebar_theme` | `'dark'` | `null` |
| `right_sidebar_icon` | `fas fa-cogs` | `bi bi-gear` |
| `laravel_css_path` | `css/app.css` | `resources/css/app.css` |
| `laravel_js_path` | `js/app.js` | `resources/js/app.js` |

- The three image paths are the ones that bite: **AdminLTE v4 moved its images
  to `dist/assets/img/`**, so a configuration that keeps the old value renders
  a broken brand logo, authentication logo and preloader.
- The shipped `menu` also changed: it ships a `darkmode-widget` entry, the
  `fullscreen-widget` entry is pinned with `topnav_right`, every icon is a
  `bi bi-*` one, and the label colors are the semantic `danger`, `warning` and
  `info` instead of the extended `red`, `yellow` and `cyan`.

**Public API**

- `NavbarDarkmodeWidget::makeIconDisabledClass()` and `makeIconEnabledClass()`
  were renamed to `makeIconDisabledClasses()` and `makeIconEnabledClasses()`,
  and joined by `makeIconAutoClasses()`. A published
  `navbar-darkmode-widget.blade.php` calling the old names fails.
- `LayoutHelper::makeBodyData()` returns an empty string now. The color mode
  moved to the `html` element, which `LayoutHelper::makeHtmlData()` builds, and
  the scrollbar settings are handed to OverlayScrollbars directly through
  `sidebar_scrollbar_options` instead of through body data attributes.
- The `makeItemClass()` and `makeInputGroupClass()` overrides of `InputDate`,
  `InputFile`, `InputSlider`, `InputSwitch` and `TextEditor` were deleted. The
  base `InputGroupComponent` still provides both with identical output, so a
  published view keeps working, but a subclass that called
  `parent::makeItemClass()` on one of those classes has to be reviewed.
  `InputSwitch::makeInputGroupClass()` became `makeSwitchWrapperClass()`.
- `ProfileColItem` and `ProfileRowItem` extend the new abstract `ProfileItem`
  class, which now declares their public properties and their
  `makeTextWrapperClass()` method.
- The protected `$sizes` property of `Progress` and `Modal` became a `SIZES`
  class constant. A subclass that redeclared the property has to redeclare the
  constant instead.
- The `InfoBox` `progress-theme` attribute defaults to `null` instead of
  `'white'`. Pass `progress-theme="white"` explicitly for the old look.
- The `ProfileWidget` `icon` attribute defaults to `bi bi-person-fill` instead
  of `fas fa-user`.

**Plugins**

- The `adminlte:plugins` catalogue was replaced. It went from 32 AdminLTE v3
  entries to 20 jQuery free ones, of which only `chartJs`, `datatables`,
  `fullcalendar`, `select2` and `sweetalert2` kept their key. The v3 keys are
  still recognized and report their v4 replacement.
- The plugin files are published from the `node_modules` folder of your
  application, since AdminLTE v4 bundles no plugin. Install the npm package
  first, the command tells you the exact `npm i` line when it is missing.
- The plugin backed form components target new libraries, so the
  `@section('plugins.<Key>', true)` keys and the `config` attributes of your
  views have to be reviewed. See the *Changed* section below.
- The CDN locations of the `plugins` configuration were bumped: Datatables
  `1.10.19` to `2.1.8` (with the Bootstrap 5 files instead of the Bootstrap 4
  ones), Select2 `4.0.3` to `4.1.0-rc.0` (plus the RTL stylesheet), Chart.js
  `2.7.0` to `4.4.6`, Pace `1.0.2` to `1.2.4` and SweetAlert2 `8` to `11`.

**Stale published assets**

- A `3.x` installation left `public/vendor/bootstrap`, `jquery`, `popper`,
  `fontawesome-free` and `overlayScrollbars` behind, and **nothing in the
  upgrade removes them**. The Bootstrap folder is the dangerous one: the
  default local path of the Bootstrap bundle is exactly the path the `3.x`
  releases used for **Bootstrap 4**, so the stale bundle keeps being served and
  every `data-bs-*` behavior dies without a console error. Delete those five
  folders, then either publish the new ones with
  `php artisan adminlte:install --only=vendor_assets` or switch
  `assets.mode` to `'cdn'`.

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
  `sidebar_nav_aria_label`, `classes_footer`, `logout_method` and
  `right_sidebar_*` options. The last family covers `right_sidebar_title`,
  `right_sidebar_placement`, `right_sidebar_backdrop`, `right_sidebar_scroll`
  and `right_sidebar_classes`.
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
  plugin explicitly when the application does its own theming, and a new
  `color_mode.routes` option that gates the route registration.
- Publish groups for the standard Laravel workflow: `adminlte-config`,
  `adminlte-views`, `adminlte-lang` and `adminlte-assets`.
- New blade components: `content-header` (title plus breadcrumbs), `timeline`
  with `timeline-item` and `timeline-label`, `ribbon`, `progress-group`,
  `user-block` and `toast`.
- New slots and options on the existing components, so fewer cases require
  publishing a view: the card header, title and tabs, the info box and small
  box title, text and footer, the progress label, the modal dialog and footer,
  the datatable wrapper and the slider element attributes.
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
  package ones, so a package update reaches them without republishing. They
  are deliberately outside every `--type` option, since publishing them
  replaces error pages the application may already have.
- A **lockscreen**, which keeps the visitor authenticated but locks the panel
  behind their password. It is opt-in through the new `lockscreen`
  configuration section and ships as a `LockscreenController`, its own route
  file with the `adminlte.lockscreen.lock`, `show` and `unlock` routes, an
  `adminlte::auth.lockscreen` view, a `RedirectIfLocked` middleware to protect
  the routes of the application, and the `ScreenWasLocked` and
  `ScreenWasUnlocked` events. The `lockscreen.guard`, `lockscreen.except` and
  `lockscreen.throttle` options tune the guard whose user provider verifies the
  password, the paths that stay reachable while the panel is locked, and the
  unlock attempt limit.
- Sidebar navigation variants (`sidebar_nav_compact`, `sidebar_nav_indent` and
  `sidebar_nav_pills`), the `sidebar_breakpoint` option of the push menu
  plugin, and the `sidebar_scrollbar_options`,
  `sidebar_scrollbar_click_scroll` and `sidebar_scrollbar_disable_below`
  options of the scrollbars.
- The `maximizable="maximized"` initial card state, the `callout-link` support
  of the callout component through its new `url` and `url-text` options, and
  the stacked progress bars through the new `segments` option.
- The Datatables **Buttons** extension, as the new `DatatablesButtons` plugin
  entry and the `datatablesButtons` console catalog entry. The `with-buttons`
  attribute used to configure export buttons the installer could not provide.
- New `plugins` configuration entries for the jQuery free libraries the v4
  components use: `TomSelect`, `Tabulator`, `Flatpickr`, `Quill` and
  `NoUiSlider`.
- Social login buttons on the authentication views, through the new
  `auth_social_links` and `auth_social_links_separator` options.
- The `css_variables`, `css_variables_scope` and `css_variables_sidebar`
  options, which declare the Bootstrap and AdminLTE custom properties inline on
  the document head. Most brandings need no stylesheet of their own any more.
  The sidebar properties need their own block, since AdminLTE redeclares them
  on the sidebar element under a color mode selector.
- A dedicated `Layout` namespace (`Layout`, `Sidebar`, `ColorMode`, `Palette`,
  `Direction`, `BodyClasses` and `Tokens`) that holds the class and attribute
  computation the layout views need, plus an `Assets` namespace
  (`AssetResolver`, `AdminLteVersion`) and an `AssetHelper` facade over it.
  `LayoutHelper` keeps its old surface and delegates to them, and it gained
  `getColorMode()`, `getHtmlDirection()`, `isRtlEnabled()`, `isRtlLocale()`,
  `isDarkModeEnabled()`, `isFixedNavbarEnabled()`, `isFixedFooterEnabled()`,
  `makeHtmlData()`, `makeWrapperClasses()`, `makeSidebarData()`,
  `makeSidebarNavClasses()` and `makeSidebarWrapperClasses()`.
- The `lo` locale, which is the ISO 639-1 code of the Lao translations the
  package shipped under the wrong `la` folder. The old folder is kept as an
  alias of the new one.
- The missing `iframe.php` translations of 17 locales and the missing
  `menu.php` ones of 5, which used to fall back to English silently. All 26
  locales carry the complete `adminlte.php` key set now, where 19 of them used
  to be incomplete.
- 47 new translation keys, covering the error views, the lockscreen, the
  color mode selector, the card tools, the direct chat, the datatable export
  buttons, the loading overlays and the accessibility labels of the skip links,
  the navigation landmarks and the toggles.
- A `composer test` and a `composer test:coverage` script, and package metadata
  (`homepage`, `support` and extra `keywords`) on `composer.json`.
- The way back to the login and register pages on the password reset view,
  which used to be a dead end.
- Support for the single page navigation of Turbo Drive and Livewire, through
  the new `spa_navigation` option. Every inline script of the package goes
  through a lifecycle helper that also runs after a body swap, and the Livewire
  navigation event is bridged to the AdminLTE lifecycle, which the template
  only wires for Turbo.
- The package author list names its two current maintainers.
- The test suite grew from 23 to 63 test files, covering the layout classes,
  the rendered views, the translations, the lockscreen and the blade
  compilation of every shipped view.

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
  The catalog itself moved into a dedicated `PluginsCatalog` class.
- The advanced form components target the new libraries: Tom Select, Tabulator,
  Flatpickr, Quill and noUiSlider. The switch and the color input use the
  native Bootstrap 5.3 controls and need no plugin. The constructor signatures
  did not change, so a component whose plugin is missing degrades to a plain
  Bootstrap control instead of breaking.
- Cards, progress bars and form groups provide their own default spacing, which
  a caller supplied margin utility overrides.
- The color mode and the lockscreen routes are gated on the service provider
  instead of inside the route files, so `route:cache` can no longer freeze the
  current value of the options into the compiled routes.
- The service provider methods are `protected` instead of `private`, so an
  application can override the loading of the views, the translations, the
  configuration, the commands, the components and the routes.
- The CI matrix only covers the supported combinations (Laravel 12 and 13 on
  PHP 8.2 to 8.5), runs `composer validate --strict` before installing, cancels
  superseded runs and no longer collects coverage.
- The package uses first class callable syntax for its internal handler maps,
  which makes the previously hidden `AdminLte` and `AdminLtePluginCommand`
  handlers reachable by static analysis, and the helper and support layer
  declares its return types.

### Deprecated

- `layout_boxed` and `LayoutHelper::isLayoutBoxedEnabled()`. AdminLTE v4 has no
  boxed layout, so the option is read but has no effect. Scheduled for removal
  on `5.0`.
- `layout_dark_mode`, which is still read as a legacy fallback
  (`true` behaves like `color_mode.default => 'dark'`). Use the `color_mode`
  section instead.
- `LayoutHelper::makeWrapperData()`, which returns an empty string. The color
  mode is applied on the `html` element, so use `makeHtmlData()`. Scheduled for
  removal on `5.0`.
- The `'xs'`, `'sm'`, `'md'`, `'lg'`, `'xl'` and `'xxl'` values of
  `sidebar_mini`, which are accepted but only mean *enabled*.
- The Datatables `dom` option of the `config` attribute, honored only when it
  is provided explicitly. Use the Datatables 2.x `layout` option.
- The AdminLTE v3 plugin keys of `adminlte:plugins`, and the AdminLTE v3 color
  names behind `assets.extended_colors_v3_aliases`.
- The `la` translation folder, kept as an alias of the correct `lo` one.

### Removed

- Support for Laravel 11 and older, and for PHP 8.1 and older. The package
  requires **Laravel 12 or 13** on **PHP 8.2 or higher**.
- The boxed layout and the sidebar skins, which AdminLTE v4 dropped.
- The legacy `enabled_laravel_mix`, `laravel_mix_css_path` and
  `laravel_mix_js_path` options. Use `laravel_asset_bundling => 'mix'` with
  `laravel_css_path` and `laravel_js_path` instead.
- The `layout_boxed`, `layout_dark_mode`, `sidebar_collapse_auto_size` and
  `sidebar_collapse_remember_no_transition` options left the shipped
  configuration file. The last two had no effect on AdminLTE v4, the first two
  are still read as a legacy fallback.
- The `right_sidebar_slide`, `right_sidebar_push`,
  `right_sidebar_scrollbar_theme` and `right_sidebar_scrollbar_auto_hide`
  options. The right sidebar is a Bootstrap offcanvas panel now, which honors
  none of them.
- The duplicated `makeItemClass()` and `makeInputGroupClass()` overrides of the
  form components, and the size arrays of the `Progress` and `Modal`
  components. Every one of those methods is still available through the base
  class with identical output. The size arrays became `SIZES` class constants,
  so a subclass that redeclared the protected property has to redeclare the
  constant instead.
- `NavbarDarkmodeWidget::makeIconDisabledClass()` and `makeIconEnabledClass()`,
  replaced by `makeIconDisabledClasses()`, `makeIconEnabledClasses()` and
  `makeIconAutoClasses()`, which also cover the new automatic color mode.
- The stale capitalized `Important`, `Warning` and `Information` keys of the
  `pt-br`, `pt-pt` and `vi` `menu.php` files. They duplicated the lowercase
  keys the package actually looks up.
- The `.github/ISSUE_TEMPLATE.md` file and the Scrutinizer coverage upload of
  the test workflow.

### Fixed

- A published configuration file that predates an asset key, or one trimmed by
  hand, resolved the AdminLTE stylesheet and script to null, and the layout
  rendered unstyled without a word. The shallow `mergeConfigFrom` cannot cover
  it, and it is skipped altogether once the configuration is cached, so the
  asset resolver falls back to the locations shipped by the package. An
  explicit null still opts out of an asset.
- The `compact-mode` class moved from the wrapper to the `body` element, and
  the `nav-compact`, `nav-indent` and `nav-pills` variants from the sidebar
  menu to the `body` too. AdminLTE compounds all four with `sidebar-mini` and
  `sidebar-collapse` on a single element and then reaches the sidebar as a
  descendant, so on the previous elements the refinements for the collapsed
  and the hover expanded sidebar never applied.
- The `content_top_area` section is emitted above the content header, as the
  reference layout does. Its border is designed to sit right under the header.
- The skip link pointed at no element, so the AdminLTE accessibility script
  fell back to the first `nav` of the document and "skip to navigation"
  focused the header toolbar. The sidebar navigation carries the id now.
- A bare `empty-option` or `placeholder` attribute on the options component
  rendered an option labelled `1`, since the boolean reached the entity
  decoder.
- The slider element and its extra attributes were emitted without a
  separator, producing `class="…"wire:ignore=""`.
- The progress bar carried an untranslated `aria-label`, while its stacked
  variant used the translation.
- The user menu pointed its profile entry at the logout url when the
  `profile_url` option was missing from a published configuration.
- The `--only` and `--with` help of the console commands never learned about
  the `vendor_assets` and `error_views` resources.
- Every inline script of the package bound on `DOMContentLoaded`, so none of
  them ran again after a Turbo or Livewire navigation: the swapped body
  re-executes them, but the document is already loaded and the event never
  fires a second time.

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
- The color mode route kill switch was evaluated inside the route file, so
  `route:cache` froze its value into the compiled routes. The check moved to
  the service provider.
- The package emits its own localized skip links instead of the English ones
  AdminLTE injects, and the card tools and the loading overlays are translated
  on every shipped locale.
- A dummy configuration file left behind by an interrupted console test used to
  break every later test run. The suite drops it on startup.

### Security

- The **lockscreen** verifies the password through the user provider of the
  configured guard, so a custom hasher of the application is honored. The
  password is never written to the session and never logged: a rejection is
  raised as a validation error, whose old input drops it.
- The lockscreen **throttles the unlock attempts** per user and IP address with
  the Laravel rate limiter, five attempts per 60 seconds by default and
  configurable through `lockscreen.throttle`. Exhausting them answers `429`.
  A successful unlock clears the counter.
- The `RedirectIfLocked` middleware answers a locked JSON request with `423`
  instead of leaking the response, and only lets the lockscreen endpoints, the
  login and logout paths and the explicitly configured `lockscreen.except`
  paths through.
- The **social login buttons** validate every value that reaches them from the
  configuration file, so it cannot inject markup or an arbitrary class into the
  authentication views. The theme is matched against the Bootstrap 5.3 button
  themes, the icon against a plain class token pattern, and the url is
  restricted to `http`, `https` and application relative paths, which keeps a
  `javascript:` url out of the markup.

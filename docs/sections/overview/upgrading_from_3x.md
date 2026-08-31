| Upgrading from 3.x
| ------------------
| [Before You Start](#before-you-start)
| [Laravel and PHP Requirements](#laravel-and-php-requirements)
| [The Stale Bootstrap 4 Assets Trap](#the-stale-bootstrap-4-assets-trap)
| [The AdminLTE v4 Markup Rewrite](#the-adminlte-v4-markup-rewrite)
| [Bootstrap Icons Instead of FontAwesome](#bootstrap-icons-instead-of-fontawesome)
| [The Boxed Layout Was Removed](#the-boxed-layout-was-removed)
| [The Control Sidebar Is Now an Offcanvas](#the-control-sidebar-is-now-an-offcanvas)
| [The Sidebar Skins Were Removed](#the-sidebar-skins-were-removed)
| [The Plugin Catalogue Now Comes From npm](#the-plugin-catalogue-now-comes-from-npm)
| [The Form Components Switched Their Plugin](#the-form-components-switched-their-plugin)
| [The Laravel Mix Options Were Removed](#the-laravel-mix-options-were-removed)
| [Configuration Defaults and Renamed Options](#configuration-defaults-and-renamed-options)
| [Smaller Behavior Changes](#smaller-behavior-changes)
| [Checklist](#checklist)

This page lists the **breaking changes** you actually hit when moving a project from a `3.x` release of this package to **Laravel-AdminLTE v4**.

> [!Important]
> Two different things carry a **v4** here. **Laravel-AdminLTE v4** is the new major release of **this package**. **AdminLTE v4** is the **upstream template** it ships, and it is the source of most of the breaking changes below. See the [home page](/) for the distinction.

> [!Tip]
> This page only covers what **breaks**. For the routine update steps (composer, `adminlte:update`, re-publishing the config), follow the [Updating](/sections/overview/updating) page as well.

## Before You Start

Upgrading rewrites published files. Before anything else:

- **Commit or back up** your project, in particular `resources/views/vendor/adminlte/`, `app/View/Components/Adminlte/` and `config/adminlte.php`.
- Plan for a **manual pass over your own blade views**. The package can re-publish its own files, but any AdminLTE v3 markup you wrote yourself has to be migrated by hand.

## Laravel and PHP Requirements

**Laravel-AdminLTE v4** requires **Laravel 12 or 13** on **PHP 8.2 or higher**. Support for every earlier Laravel and PHP version was dropped, and the compatibility code for them was deleted rather than deprecated.

If your project is not on Laravel 12 yet, upgrade the framework **first**, verify the application boots, and only then bump this package. See the [requirements](/sections/overview/requirements) page.

## The Stale Bootstrap 4 Assets Trap

> [!Warning]
> Read this one even if you skip everything else. It is the breaking change that produces the most confusing symptoms, because nothing errors and nothing looks obviously broken.

A `3.x` installation published a set of third party assets into the `public/vendor` folder of your project:

| Folder | What a `3.x` installation left there |
| ------ | ------------------------------------ |
| `public/vendor/bootstrap` | The **Bootstrap 4** Javascript bundle |
| `public/vendor/jquery` | jQuery |
| `public/vendor/popper` | Popper.js (a Bootstrap 4 requirement) |
| `public/vendor/fontawesome-free` | FontAwesome 5 Free |
| `public/vendor/overlayScrollbars` | OverlayScrollbars 1.x |

**Nothing in the upgrade removes those folders.** The `assets` resource of the `4.x` releases only manages `public/vendor/adminlte`, so neither `php artisan adminlte:install --type=full` nor `php artisan adminlte:update` publishes over them or deletes them, and `php artisan adminlte:remove assets` cannot clean them either.

Four of them are merely orphans wasting disk space. The fifth one **breaks your panel**:

- `assets.mode` defaults to `local`.
- The default local path of the Bootstrap bundle is `vendor/bootstrap/js/bootstrap.bundle.min.js`, which is **exactly the path the `3.x` releases used for Bootstrap 4**.
- The package only falls back to the CDN when the local file is **absent**.

So the stale **Bootstrap 4** bundle keeps being served and silently drives the new **Bootstrap 5.3** markup. Every `data-bs-*` driven behavior degrades without a single console error: dropdowns do not open, modals do not show, the offcanvas right sidebar does nothing, collapses and tooltips are dead.

### The Remedy

Delete the stale folders:

```sh
rm -rf public/vendor/bootstrap \
       public/vendor/jquery \
       public/vendor/popper \
       public/vendor/fontawesome-free \
       public/vendor/overlayScrollbars
```

This alone is already enough to unbreak the panel: with `assets.cdn_fallback` at its default `true`, a missing local file is served from the CDN. Then decide how you want the third party resources delivered:

**Self host them** with the new `vendor_assets` resource. It is the supported way to serve the Bootstrap Javascript bundle, the Bootstrap Icons font and OverlayScrollbars from your own domain. They are published from the `node_modules` folder of your project, so install the npm packages first:

```sh
npm i bootstrap bootstrap-icons overlayscrollbars
php artisan adminlte:install --only=vendor_assets
```

**Or serve them from the CDN**, by switching the delivery mode on `config/adminlte.php`:

```php
'assets' => [
    'mode' => 'cdn',
    ...
],
```

> [!Note]
> Once `vendor_assets` has been published, `php artisan adminlte:update` refreshes it together with the AdminLTE distribution files, so it stays in sync on the routine updates.

## The AdminLTE v4 Markup Rewrite

**AdminLTE v4** is a full rewrite on top of **Bootstrap 5.3**. Every view and every blade component of this package was rewritten with it. The consequences:

- The layout element names changed. The outermost `.wrapper` is now `.app-wrapper`, the old `.content-wrapper` is now `main.app-main`, the navbar is `nav.app-header`, the sidebar is `aside.app-sidebar`, the footer is `footer.app-footer`, and the content is wrapped in `.app-content` / `.app-content-header`.
- The AdminLTE plugins are driven by **`data-lte-toggle="..."`** attributes. The v3 `data-widget` and `data-card-widget` attributes do not exist any more.
- **jQuery is gone.** AdminLTE v4 bundles no jQuery and none of its own plugins need it.
- Bootstrap 4 classes that Bootstrap 5 renamed (`ml-*`/`mr-*` &rarr; `ms-*`/`me-*`, `float-left`/`float-right` &rarr; `float-start`/`float-end`, `data-toggle` &rarr; `data-bs-toggle`, `badge-*` &rarr; `text-bg-*`, …) no longer resolve.

### Re-Publish the Views and the Components

If you had published the layout views, the authentication views or the blade components, **your copies still carry the Bootstrap 4 markup and will not render correctly**. They must be re-published with `--force`:

```sh
# Back up your customized copies first!
cp -r resources/views/vendor/adminlte resources/views/vendor/adminlte.bak

php artisan adminlte:install --only=main_views --force
php artisan adminlte:install --only=auth_views --force
php artisan adminlte:install --only=components --force
```

Then re-apply your customizations on top of the new files, using a comparison tool against your backup. See [views customization](/sections/configuration/views_customization) and [components customization](/sections/components/components_customization).

> [!Warning]
> This is the single most common cause of a broken panel after the upgrade. A stale published `master.blade.php` silently keeps emitting AdminLTE v3 markup, and the result looks like the stylesheet failed to load.

### Re-Publish the Assets and the Configuration

```sh
php artisan adminlte:update
php artisan adminlte:install --only=config --force
```

The configuration file gained several new sections (`assets`, `color_mode`, `rtl`, `sidebar_theme`, the offcanvas `right_sidebar_*` keys). Diff the newly published file against your backup and carry your own values over, rather than keeping your old file.

## Bootstrap Icons Instead of FontAwesome

**AdminLTE v4 ships [Bootstrap Icons](https://icons.getbootstrap.com/)** as its icon set and does not bundle **FontAwesome** any more. Every default icon of this package (menu items, card tools, the color mode widget, the search buttons, the right sidebar toggle) is now a `bi bi-*` class.

So, an `'icon' => 'fas fa-user'` in your menu configuration renders nothing at all after the upgrade. You have two options:

**Migrate to Bootstrap Icons** (recommended). Translate your icon names, for example:

| AdminLTE v3 (FontAwesome) | Laravel-AdminLTE v4 (Bootstrap Icons) |
| ------------------------- | ------------------------------------- |
| `fas fa-user` | `bi bi-person` |
| `fas fa-lock` | `bi bi-lock` |
| `fas fa-file` | `bi bi-file-earmark` |
| `fas fa-cog` | `bi bi-gear` |
| `fas fa-share` | `bi bi-share` |
| `fas fa-search` | `bi bi-search` |

**Or keep FontAwesome.** The `icon` value is copied verbatim into the `class` attribute of an `<i>` element, so FontAwesome keeps working as long as you load its stylesheet yourself (through the [plugins](/sections/configuration/plugins) configuration or your asset bundling setup). Only the icons the package chooses on your behalf are Bootstrap Icons based.

> [!Note]
> The `fa-fw` fixed-width helper has no Bootstrap Icons equivalent. Bootstrap Icons are already visually consistent in width, so you can simply drop it.

## The Boxed Layout Was Removed

**AdminLTE v4 has no boxed layout.** The `layout_boxed` configuration option and the `layout_boxed` blade section are still read for backward compatibility, but they **have no effect** and can be deleted from your configuration.

If you relied on a constrained page width, use a centered Bootstrap container instead: set `classes_content_header` and `classes_content` to `container` (or `container-xxl`) in your configuration. See [layout & styling](/sections/configuration/layout_and_styling#layout).

## The Control Sidebar Is Now an Offcanvas

**AdminLTE v4 removed the control sidebar.** The right sidebar of this package is now built on the [Bootstrap 5 offcanvas](https://getbootstrap.com/docs/5.3/components/offcanvas/) component, rendered with the `adminlte-right-sidebar` identifier.

- The **section name is `right_sidebar`**, with an underscore. The hyphenated `right-sidebar` spelling of the older `3.x` releases is not recognized.
- These options **no longer have any effect** and can be deleted: `right_sidebar_slide`, `right_sidebar_push`, `right_sidebar_scrollbar_theme` and `right_sidebar_scrollbar_auto_hide`.
- These options are **new** and worth reviewing: `right_sidebar_title`, `right_sidebar_placement` (`start` / `end` / `top` / `bottom`), `right_sidebar_backdrop`, `right_sidebar_scroll` and `right_sidebar_classes`.
- If you wrote your own markup against the v3 `.control-sidebar` classes, it must be rewritten as offcanvas content.

See [right sidebar](/sections/configuration/layout_and_styling#right-sidebar).

## The Sidebar Skins Were Removed

**AdminLTE v4 removed the `sidebar-dark-<color>` and `sidebar-light-<color>` skin classes.** A value such as `'sidebar-dark-primary'` in `classes_sidebar` now does nothing.

The styling was split into two independent options:

- **`classes_sidebar`** takes a plain Bootstrap background utility, for example `bg-body-secondary shadow` (the new default), `bg-primary`, or `text-bg-navy` with the [extended colors](/sections/configuration/other#the-extended-color-palette) enabled.
- **`sidebar_theme`** sets the color mode of the sidebar through `data-bs-theme`. Use `'dark'` (the default) for the classic dark sidebar, `'light'`, or `null` to inherit the color mode of the page.

```php
// AdminLTE v3
'classes_sidebar' => 'sidebar-dark-primary elevation-4',

// Laravel-AdminLTE v4
'classes_sidebar' => 'bg-primary shadow',
'sidebar_theme' => 'dark',
```

The sidebar navigation skins went the same way: **`nav-child-indent`, `nav-flat` and `nav-legacy` no longer exist**. Only `nav-compact` survives on `classes_sidebar_nav`.

## The Plugin Catalogue Now Comes From npm

**AdminLTE v4 bundles no third party plugin** — the `plugins/` folder of the AdminLTE v3 distribution is gone. So `php artisan adminlte:plugins install` can no longer copy plugin files out of the composer package. It publishes them from the **`node_modules` folder of your project** instead, which means **you have to install the npm package first**:

```sh
npm i flatpickr
php artisan adminlte:plugins install --plugin=flatpickr
```

When a package is missing, the command tells you the exact `npm i` command to run. The AdminLTE v3 plugin keys are still recognized and the command reports their v4 replacement.

Because jQuery is gone, the default plugin set changed to the jQuery free libraries recommended by AdminLTE v4:

| AdminLTE v3 plugin | Laravel-AdminLTE v4 replacement |
| ------------------ | ------------------------------- |
| `bootstrap-select` | [Tom Select](https://tom-select.js.org/) |
| jQuery DataTables | [Tabulator](https://tabulator.info/) |
| `daterangepicker`, Tempus Dominus, Moment | [Flatpickr](https://flatpickr.js.org/) |
| Summernote | [Quill](https://quilljs.com/) |
| `bootstrap-slider` | [noUiSlider](https://refreshless.com/nouislider/) |
| Bootstrap Colorpicker | native Bootstrap 5 color input (or [Pickr](https://github.com/simonwep/pickr)) |
| Bootstrap Switch | native Bootstrap 5.3 switch |

**Select2** and the **Krajee file input** are still supported, but they keep requiring jQuery, which you now have to load yourself. The components that use them are guarded: without jQuery and the plugin, the element degrades to a plain Bootstrap 5 control instead of breaking.

See the [plugins configuration](/sections/configuration/plugins) and the [advanced form components](/sections/components/advanced_forms_components) pages.

## The Form Components Switched Their Plugin

Every plugin backed form component kept its name and its attributes, but the library behind it was replaced:

| Component | AdminLTE v3 plugin | Laravel-AdminLTE v4 plugin | Plugin key |
| --------- | ------------------ | -------------------------- | ---------- |
| [SelectBs](/sections/components/advanced_forms_components#selectbs) | `bootstrap-select` | [Tom Select](https://tom-select.js.org/) | `TomSelect` |
| [TextEditor](/sections/components/advanced_forms_components#texteditor) | Summernote | [Quill](https://quilljs.com/) | `Quill` |
| [InputSlider](/sections/components/advanced_forms_components#inputslider) | `bootstrap-slider` | [noUiSlider](https://refreshless.com/nouislider/) | `NoUiSlider` |
| [InputDate](/sections/components/advanced_forms_components#inputdate) | Tempus Dominus | [Flatpickr](https://flatpickr.js.org/) | `Flatpickr` |
| [DateRange](/sections/components/advanced_forms_components#daterange) | `daterangepicker` + Moment | [Flatpickr](https://flatpickr.js.org/) | `Flatpickr` |
| [InputSwitch](/sections/components/advanced_forms_components#inputswitch) | Bootstrap Switch | native Bootstrap 5.3 switch | – |
| [InputColor](/sections/components/advanced_forms_components#inputcolor) | Bootstrap Colorpicker | native `input[type=color]` | – |

> [!Warning]
> **The constructor signatures did not change, so nothing errors.** Your existing `<x-adminlte-select-bs>`, `<x-adminlte-text-editor>` or `<x-adminlte-input-slider>` tags keep rendering after the upgrade. When the new plugin is not loaded, the control simply **degrades to a plain input** — a `select-bs` that suddenly looks like an ordinary `form-select`, or a `text-editor` that looks like a plain `textarea`, is the symptom of a plugin that was not enabled.

So there are three things to review on every view that uses one of these components:

- **The plugin key** you enable with `@section('plugins.<Key>', true)`, as listed on the table above. The AdminLTE v3 keys do not resolve anymore.
- **The `config` attribute**, whose option names are now those of the new library. The legacy v3 properties that have an equivalent are translated on the fly, the rest are silently dropped. Each component section of the [advanced form components](/sections/components/advanced_forms_components) page lists exactly which ones became no-ops.
- **jQuery**, which is no longer loaded by the package. Only [Select2](/sections/components/basic_forms_components#select2) and [InputFileKrajee](/sections/components/advanced_forms_components#inputfilekrajee) still need it, and you now have to provide it yourself.

`InputSlider` is the one that deserves the closest look: **noUiSlider** describes the bounds with a `range` object instead of the `min` / `max` / `value` properties of `bootstrap-slider`. The common legacy properties (`min`, `max`, `step`, `value`) are translated for you, but the more exotic ones (`precision`, `orientation`, `ticks_tooltip`, `scale`, `rangeHighlights`, …) have no equivalent and are dropped.

## The Laravel Mix Options Were Removed

The legacy **`enabled_laravel_mix`**, **`laravel_mix_css_path`** and **`laravel_mix_js_path`** options **were removed** and are no longer read. They are superseded by the `laravel_asset_bundling` option, which also covers Vite:

```php
// AdminLTE v3
'enabled_laravel_mix' => true,
'laravel_mix_css_path' => 'css/app.css',
'laravel_mix_js_path' => 'js/app.js',

// Laravel-AdminLTE v4
'laravel_asset_bundling' => 'mix',   // or 'vite', or 'vite_js_only'
'laravel_css_path' => 'css/app.css',
'laravel_js_path' => 'js/app.js',
```

If you bundle the assets yourself, also review the new [assets](/sections/configuration/other#assets) section: the package stops emitting the AdminLTE core stylesheet and script, but it still emits the third party resources, so set `assets.bootstrap_js`, `assets.bootstrap_icons` and `assets.overlayscrollbars` to `false` when your bundle already includes them.

## Configuration Defaults and Renamed Options

### Dark Mode Moved to the `color_mode` Section

The **`layout_dark_mode`** option was **removed from the shipped configuration file**. It is still read as a legacy fallback (`layout_dark_mode => true` behaves exactly like `color_mode.default => 'dark'`), so an old configuration keeps working, but the color mode is now configured through the new `color_mode` section:

```php
// AdminLTE v3
'layout_dark_mode' => true,

// Laravel-AdminLTE v4
'color_mode' => [
    'default' => 'dark',        // 'light', 'dark' or 'auto'
    'remember' => true,         // store the visitor choice in the browser localStorage
    'no_flash_script' => true,
    'theme_color' => [
        'light' => '#007bff',
        'dark' => '#1a1a1a',
    ],
],
```

With `remember => true` (the default), the AdminLTE v4 color mode plugin persists the choice of the visitor in the **browser `localStorage`**. Pick `remember => false` together with an explicit `'light'` or `'dark'` default when the preference has to be resolved on the **server** instead (for example, persisted per user in your database through the `ReadingDarkModePreference` and `DarkModeWasToggled` events). See [color mode](/sections/configuration/layout_and_styling#color-mode).

### `sidebar_mini` Was Split in Two

**`sidebar_mini`** is now a plain on/off switch, and the breakpoint moved to the new **`sidebar_expand`** option:

```php
// AdminLTE v3
'sidebar_mini' => 'md',

// Laravel-AdminLTE v4
'sidebar_mini' => true,
'sidebar_expand' => 'lg',
```

The legacy `'xs'`, `'md'` and `'lg'` string values are still accepted on `sidebar_mini`, but they now only mean **enabled**: AdminLTE v4 has no `sidebar-mini-md` or `sidebar-mini-xs` classes anymore, so the responsive variants of the mini sidebar are gone.

### `sidebar_scrollbar_auto_hide` Changed Its Vocabulary

The value is handed over **verbatim** to **OverlayScrollbars 2.x**, which only accepts `'never'`, `'scroll'`, `'leave'` and `'move'`. The one letter tokens of the AdminLTE v3 releases are not valid anymore, so `'l'` has to become `'leave'`:

```php
// AdminLTE v3
'sidebar_scrollbar_auto_hide' => 'l',

// Laravel-AdminLTE v4
'sidebar_scrollbar_auto_hide' => 'leave',
```

### Menu Colors Are Not Translated

The `icon_color` and `label_color` [menu attributes](/sections/configuration/menu) are copied **verbatim** into the class name. Only the blade components map the AdminLTE v3 palette on the fly, the menu does not. So a menu item still carrying `'icon_color' => 'lightblue'` needs **both** `assets.extended_colors` and `assets.extended_colors_v3_aliases` enabled. The cleaner move is to rewrite those values with the v4 names (`sky`, `pink`, `violet`, `olive`, …).

### Defaults That Changed on the Published Configuration

Re-publishing `config/adminlte.php` (which you should do) brings a set of changed defaults. If you diff your old file against the new one, expect these:

| Option | 3.x default | 4.x default |
| ------ | ----------- | ----------- |
| `title` | `'AdminLTE 3'` | `'AdminLTE 4'` |
| `layout_fixed_sidebar` | `null` | `true` |
| `classes_body` | `''` | `'bg-body-tertiary'` |
| `classes_topnav` | `'navbar-white navbar-light'` | `'bg-body'` |
| `classes_topnav_container` | `'container'` | `'container-fluid'` |
| `logo_img`, `auth_logo.img.path`, `preloader.img.path` | `vendor/adminlte/dist/img/…` | `vendor/adminlte/dist/assets/img/…` |

> [!Important]
> The three image paths are not cosmetic. **AdminLTE v4 moved its images to the `dist/assets/img/` folder**, so a configuration that kept the old `vendor/adminlte/dist/img/AdminLTELogo.png` value renders a broken image on the brand logo, the authentication views and the preloader.

## Smaller Behavior Changes

A few remaining differences that are easy to miss:

- **The footer is rendered more often.** On the `3.x` releases the footer only appeared when your views defined a `footer` section. Now it is also rendered whenever `layout_fixed_footer` is enabled, because the fixed layout has to reserve the space for it. Disable `layout_fixed_footer` if you want no footer at all.

- **`InfoBox` changed its progress bar default.** The `progress-theme` attribute defaulted to `'white'` on the `3.x` releases, it defaults to `null` now. Without an explicit value, the bar is painted `primary` on an unthemed box, and it inherits the contrast color of the box when a `theme` is set. Pass `progress-theme="white"` explicitly to get the old look back.

## Checklist

- [ ] Laravel upgraded to 12 or 13, PHP to 8.2 or higher.
- [ ] Stale `public/vendor/bootstrap`, `jquery`, `popper`, `fontawesome-free` and `overlayScrollbars` folders deleted, and the third party assets either published with `--only=vendor_assets` or switched to `assets.mode => 'cdn'`.
- [ ] `composer update jeroennoten/laravel-adminlte` and `php artisan adminlte:update` run.
- [ ] `config/adminlte.php` re-published and merged (do not keep the old file as is), including the `dist/img/` &rarr; `dist/assets/img/` image paths.
- [ ] `main_views`, `auth_views` and `components` re-published with `--force`, customizations re-applied.
- [ ] Menu icons migrated to `bi bi-*`, or FontAwesome loaded explicitly.
- [ ] `layout_boxed` removed from the configuration.
- [ ] Right sidebar content migrated to offcanvas markup, `right_sidebar` section spelled with an underscore, dead `right_sidebar_*` options removed.
- [ ] `classes_sidebar` skin classes replaced by a background utility plus `sidebar_theme`.
- [ ] Plugins reinstalled from npm, jQuery loaded explicitly if you still use Select2 or the Krajee file input.
- [ ] Views using the plugin backed form components checked: new `@section('plugins.<Key>', true)` keys enabled and the `config` attributes reviewed against the new libraries.
- [ ] `enabled_laravel_mix` replaced by `laravel_asset_bundling`.
- [ ] `layout_dark_mode` migrated to the `color_mode` section.
- [ ] `sidebar_mini` reduced to a boolean and the breakpoint moved to `sidebar_expand`.
- [ ] `sidebar_scrollbar_auto_hide` rewritten with the OverlayScrollbars 2.x vocabulary (`'l'` &rarr; `'leave'`).
- [ ] Own blade views swept for Bootstrap 4 classes, the `.wrapper` element name and `data-widget` / `data-card-widget` attributes.
- [ ] `assets.extended_colors` enabled if you theme components with colors such as `navy`, `teal` or `olive` (plus `assets.extended_colors_v3_aliases` if your menu still uses the v3 color names).

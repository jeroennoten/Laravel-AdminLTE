| Upgrading from 3.x
| ------------------
| [Before You Start](#before-you-start)
| [Laravel and PHP Requirements](#laravel-and-php-requirements)
| [The AdminLTE v4 Markup Rewrite](#the-adminlte-v4-markup-rewrite)
| [Bootstrap Icons Instead of FontAwesome](#bootstrap-icons-instead-of-fontawesome)
| [The Boxed Layout Was Removed](#the-boxed-layout-was-removed)
| [The Control Sidebar Is Now an Offcanvas](#the-control-sidebar-is-now-an-offcanvas)
| [The Sidebar Skins Were Removed](#the-sidebar-skins-were-removed)
| [The Plugin Catalogue Now Comes From npm](#the-plugin-catalogue-now-comes-from-npm)
| [The Laravel Mix Options Were Removed](#the-laravel-mix-options-were-removed)
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

## The AdminLTE v4 Markup Rewrite

**AdminLTE v4** is a full rewrite on top of **Bootstrap 5.3**. Every view and every blade component of this package was rewritten with it. The consequences:

- The layout element names changed. The old `.content-wrapper` is now `main.app-main`, the navbar is `nav.app-header`, the sidebar is `aside.app-sidebar`, the footer is `footer.app-footer`, and the content is wrapped in `.app-content` / `.app-content-header`.
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

## Checklist

- [ ] Laravel upgraded to 12 or 13, PHP to 8.2 or higher.
- [ ] `composer update jeroennoten/laravel-adminlte` and `php artisan adminlte:update` run.
- [ ] `config/adminlte.php` re-published and merged (do not keep the old file as is).
- [ ] `main_views`, `auth_views` and `components` re-published with `--force`, customizations re-applied.
- [ ] Menu icons migrated to `bi bi-*`, or FontAwesome loaded explicitly.
- [ ] `layout_boxed` removed from the configuration.
- [ ] Right sidebar content migrated to offcanvas markup, `right_sidebar` section spelled with an underscore, dead `right_sidebar_*` options removed.
- [ ] `classes_sidebar` skin classes replaced by a background utility plus `sidebar_theme`.
- [ ] Plugins reinstalled from npm, jQuery loaded explicitly if you still use Select2 or the Krajee file input.
- [ ] `enabled_laravel_mix` replaced by `laravel_asset_bundling`.
- [ ] Own blade views swept for Bootstrap 4 classes and `data-widget` / `data-card-widget` attributes.
- [ ] `assets.extended_colors` enabled if you theme components with colors such as `navy`, `teal` or `olive`.

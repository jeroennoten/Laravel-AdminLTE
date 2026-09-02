The next set of configuration options enables you to change the layout and style of your admin panel.

| Layout & Styling Configuration
| ------------------------------
| [Layout](#layout)
| [Color Mode](#color-mode)
| [RTL Mode](#rtl-mode)
| [Auth View Classes](#authentication-views-classes)
| [Admin Panel Classes](#admin-panel-classes)
| [Sidebar](#sidebar)
| [Right Sidebar](#right-sidebar)

## Layout

It's possible to change the admin panel layout, you can use a top navigation (navbar) only layout, or enable the fixed mode for the sidebar, the navbar or the footer.

> [!Caution]
> AdminLTE v4 removed the **boxed layout**. The `layout_boxed` option is no longer part of the shipped configuration file. It is still read for backward compatibility, but it has no effect any more.

The following config options are available:

- __`layout_topnav`__

  Enables/Disables the top navigation only layout, this will remove the sidebar and put all your links at the top navbar.

> [!Tip]
> When enabling `layout_topnav`, the recommendation is to also tune the `classes_topnav_nav` configuration to add the class `navbar-expand-md` or `navbar-expand-lg` instead of `navbar-expand`, in order to get a correct functionality of the hamburger button at low screen sizes.

- __`layout_boxed`__ <Badge type="warning">removed</Badge>

  Removed from the shipped configuration file. Still read for backward compatibility, but it has no effect.

- __`layout_fixed_sidebar`__

  Enables/Disables the fixed mode for the sidebar (adds the `.layout-fixed` class to the body). Can't be mixed with `layout_topnav`.

- __`layout_fixed_navbar`__

  Enables/Disables the fixed mode for the navbar (adds the `.fixed-header` class to the body).

- __`layout_fixed_footer`__

  Enables/Disables the fixed mode for the footer (adds the `.fixed-footer` class to the body).

- __`layout_compact`__

  Enables/Disables the AdminLTE v4 compact mode (adds the `.compact-mode` class to the `body` element). It reduces the height of the header, the padding of the sidebar links and the spacing of the content, which fits more information on a screen.

> [!Important]
> AdminLTE v4 has no responsive fixed modes any more, the `.fixed-header` and `.fixed-footer` classes apply to every viewport size. For backward compatibility, an array value (the old [responsive usage](#responsive-usage-deprecated)) is still accepted on `layout_fixed_navbar` and `layout_fixed_footer`, and it enables the fixed mode when any of its entries is `true`.

### Responsive Usage <Badge type="warning">deprecated</Badge>

Up to AdminLTE v3 the fixed navbar and the fixed footer could be enabled per viewport size, by using an array like `['xs' => true, 'lg' => false]` on the `layout_fixed_navbar` and `layout_fixed_footer` options. AdminLTE v4 dropped the underlying responsive classes. The array notation is still accepted so existing configurations keep working, but the resulting layout is fixed on all viewport sizes.

## Color Mode

AdminLTE v4 replaces the old dark mode class by the [Bootstrap 5.3 color modes](https://getbootstrap.com/docs/5.3/customize/color-modes/): the `data-bs-theme` attribute is set on the `<html>` element and every component follows it automatically.

The following configuration options are available:

- __`color_mode.enabled`__

  Enables/Disables the AdminLTE color mode plugin. When disabled, the `data-lte-color-mode="off"` attribute is set on the `<html>` element, the plugin restores nothing from the browser storage, and the configured mode is applied as is. Use it when your application takes over the theming completely. Note the `'auto'` mode can only be resolved by that plugin, so it falls back to `'light'` here.

- __`color_mode.default`__

  The color mode used when the visitor has no stored preference. The supported values are: `'light'`, `'dark'` and `'auto'` (follow the operating system preference of the visitor).

- __`color_mode.remember`__

  When enabled (the default), the **AdminLTE v4 color mode plugin** stores the choice of the visitor in the **browser `localStorage`** and restores it on the next page load, which is the standard AdminLTE v4 behavior. The preference therefore lives only in that one browser: it is never sent to your application, it is lost when the visitor clears the site data or switches to another device or browser, and it cannot be read back on the server side.

  Pick `remember => false` (together with an explicit `'light'` or `'dark'` default) whenever the preference has to live **on the server** instead — for example persisted per user in your database. In that mode the client side plugin is switched off and the package resolves the color mode from your own `ReadingDarkModePreference` / `DarkModeWasToggled` listeners, as described next.

  When set to `false`, the **AdminLTE color mode plugin is switched off entirely**: the package adds `data-lte-color-mode="off"` to the `<html>` element and uses its own **server side toggle** instead (the `adminlte.darkmode.toggle` route together with the `ReadingDarkModePreference` and `DarkModeWasToggled` events). This is the mode to pick when you want to persist the preference of a logged in user in your database rather than in their browser, or when the panel must always start on the configured `color_mode.default`.

> [!Important]
> The `'auto'` default is the exception: it can only be resolved on the client side, from the operating system preference of the visitor. So with `color_mode.default => 'auto'` the plugin is **not** switched off, even when `color_mode.remember` is disabled. Combine `remember => false` with an explicit `'light'` or `'dark'` default.

> [!Note]
> The plugin has to be turned off in that case, otherwise it would restore its own stored value on load and fight with the preference your application resolved on the server.

- __`color_mode.no_flash_script`__

  When enabled (the default), a small inline script is added at the top of the `<head>` to apply the resolved color mode before the first paint, avoiding a flash of the incorrect theme.

- __`color_mode.theme_color`__

  The colors used for the `theme-color` meta tags (`light` and `dark` keys). Set an entry to `null` to omit the related meta tag.

To let your users switch the color mode, add the [dark mode menu item](./special_menu_items.md) to your menu. With `color_mode.remember` enabled it renders the AdminLTE v4 color mode selector (light / dark / auto), otherwise it renders the legacy two states toggle.

> [!Important]
> The legacy `layout_dark_mode` and `layout_theme_mode` options are still supported: `layout_dark_mode => true` behaves like `color_mode.default => 'dark'`.

## RTL Mode

AdminLTE v4 ships a right-to-left variant of every stylesheet. When the RTL mode is active, the package adds `dir="rtl"` to the `<html>` element and loads the `*.rtl.min.css` variant of the AdminLTE stylesheets. Nothing else has to be changed on your views: the Bootstrap 5.3 logical utilities (`ms-*`, `me-*`, `float-start`, `float-end`, …) mirror themselves.

The following configuration options are available:

- __`rtl.enabled`__

  Set it to `true` to always use the RTL mode, to `false` to never use it, or to `null` (the default) to enable the RTL mode automatically when the current application locale is a right-to-left one.

- __`rtl.locales`__

  The set of locales that are considered right-to-left when `rtl.enabled` is `null`. Both a full locale (`'uz-AF'`) and a language (`'ar'`, which also matches `ar_EG`) can be used.

__Example:__ enabling the RTL mode only for the Arabic and Persian locales:

```php
'rtl' => [
    'enabled' => null,
    'locales' => ['ar', 'fa'],
],
```

If you provide the AdminLTE assets through your own [asset bundling](./other.md) setup, remember to import the RTL stylesheet yourself when the RTL mode is active.

## Authentication Views Classes

You can change the look and behavior of the authentication views (login, register, email verification, etc). The following config options are available:

- __`classes_auth_card`__

  Extra classes for the card box. Classes will be added to the element `div.card`.

- __`classes_auth_header`__

  Extra classes for the card box header. Classes will be added to the element `div.card-header`.

- __`classes_auth_body`__

  Extra classes for the card box body. Classes will be added to the element `div.card-body`.

- __`classes_auth_footer`__

  Extra classes for the card box footer. Classes will be added to the element `div.card-footer`.

- __`classes_auth_icon`__

  Extra classes for the icons ([Bootstrap Icons](https://icons.getbootstrap.com/)) used on the input fields.

- __`classes_auth_btn`__

  Extra classes for the submit buttons.

### Default Style

The set of current default values and the rendered look is the next one:
<br>

<img src="/imgs/configuration/layout_and_styling/login-default.png" alt="Default Login"
    style="width:200px;margin-left:5px;float:right;"/>

```php
'classes_auth_card' => 'card-outline card-primary',
'classes_auth_header' => '',
'classes_auth_body' => '',
'classes_auth_footer' => '',
'classes_auth_icon' => '',
'classes_auth_btn' => 'btn-primary',
```

However, you can customize the options as you want to get some particular themes, for example:

### Dark Theme Style

A dark card with light buttons and icons.
<br>

```php
'classes_auth_card' => 'text-bg-dark',
'classes_auth_header' => '',
'classes_auth_body' => 'text-bg-dark',
'classes_auth_footer' => 'text-center',
'classes_auth_icon' => 'text-light',
'classes_auth_btn' => 'btn-light',
```

### Colored Header Style

A colored header background with matching icons.
<br>

```php
'classes_auth_card' => '',
'classes_auth_header' => 'text-bg-info',
'classes_auth_body' => '',
'classes_auth_footer' => 'text-center',
'classes_auth_icon' => 'fs-5 text-info',
'classes_auth_btn' => 'btn-primary',
```

> [!Important]
> AdminLTE v4 removed the `btn-flat` class and the `bg-gradient-<color>` classes of the extended palette are only available when the [extended colors](/sections/configuration/other#assets) stylesheet is enabled. Prefer the Bootstrap 5.3 `text-bg-<color>` utilities, which also take care of the text contrast.

## Admin Panel Classes

You can change the look and behavior of the admin panel by adding extra classes to the body, brand, sidebar, sidebar navigation, top navigation and top navigation container.

The following config options are available:

- __`classes_body`__

  Extra classes for the body. The default value is `bg-body-tertiary`. You may also use the experimental `sidebar-hidden` class to hide the sidebar.

- __`classes_brand`__

  Extra classes for the brand. Classes will be added to element `a.navbar-brand` if `layout_topnav` is used, otherwise they will be added to element `a.brand-link`.

- __`classes_brand_text`__

  Extra classes for the brand text. Classes will be added to the element `span.brand-text` of the sidebar brand, or to the plain `span` holding the brand text when `layout_topnav` is used (the AdminLTE v4 topnav brand carries no `brand-text` element).

- __`classes_content_header`__

  Classes for the content header container. Classes will be added to the container of the element `div.app-content-header`. If you left this empty, the default is `container-fluid`, except on the `layout_topnav` layout, where the value of the `classes_topnav_container` option is used instead.

- __`classes_wrapper`__

  Extra classes for the layout wrapper. Classes will be added to the element `div.app-wrapper`.

- __`classes_content_wrapper`__

  Classes for the content wrapper container. Classes will be added to the element `main.app-main`.

- __`classes_content`__

  Classes for the content container. Classes will be added to the container of the element `div.app-content`. If you left this empty, the default is `container-fluid`, except on the `layout_topnav` layout, where the value of the `classes_topnav_container` option is used instead.

- __`classes_content_top_area`__ / __`classes_content_bottom_area`__

  Classes for the containers of the optional `content_top_area` and `content_bottom_area` sections. They follow the same fallback rules as `classes_content`. Both sections are described on the [usage](/sections/overview/usage) page.

- __`classes_footer`__

  Extra classes for the footer. Classes will be added to the element `footer.app-footer`.

- __`classes_sidebar`__

  Extra classes for the sidebar. Classes will be added to the element `aside.app-sidebar`. The default value is `bg-body-secondary shadow`.

> [!Important]
> AdminLTE v4 removed the `sidebar-dark-<color>` and `sidebar-light-<color>` skins. Use a Bootstrap background utility here (for example `bg-body-secondary`, `bg-primary` or `text-bg-navy` with the [extended colors](/sections/configuration/other#assets) enabled), and set the color mode of the sidebar with the `sidebar_theme` option below.

- __`sidebar_theme`__

  The color mode applied to the sidebar through the `data-bs-theme` attribute. The available values are: `'dark'` (the default), `'light'` or `null` to inherit the color mode of the page.

- __`classes_sidebar_nav`__

  Extra classes for the sidebar navigation. Classes will be added to the element `ul.nav.sidebar-menu`. The three variants that AdminLTE v4 styles (`nav-compact`, `nav-indent` and `nav-pills`) have their own options on the [sidebar](#sidebar) section, and they are added to the `body` element instead, so there is no need to write them here.

> [!Important]
> The `nav-child-indent`, `nav-flat` and `nav-legacy` classes of AdminLTE v3 no longer exist.

- __`classes_topnav`__

  Extra classes for the top navigation bar. Classes will be added to the element `nav.app-header.navbar`. The default value is `bg-body`. Use the Bootstrap 5.3 background utilities here (`bg-body`, `bg-body-secondary`, `bg-primary`, …); the AdminLTE v3 `navbar-<color>`, `navbar-light` and `navbar-dark` classes were removed by Bootstrap 5.3 and AdminLTE v4.

- __`classes_topnav_nav`__

  Extra classes for the top navigation. Classes will be added to the element `nav.app-header.navbar`. When enabling `layout_topnav` the recommendation is to use `navbar-expand-md` or `navbar-expand-lg` to get the items auto collapsed into a hamburger button on low screen sizes. Otherwise, stay with the `navbar-expand` class.

- __`classes_topnav_container`__

  Extra classes for the top navigation bar container. Classes will be added to the `div` wrapper inside the element `nav.app-header.navbar`. The default value is `container-fluid`.

> [!Important]
> On the **`layout_topnav` layout the navbar and the content share this container**. The content header and the content fall back to `classes_topnav_container` (instead of the plain `container-fluid` used on the sidebar layout) whenever `classes_content_header` and `classes_content` are left empty. This is what keeps the brand in the navbar and the content below it aligned on the same left edge. So, if you switch `classes_topnav_container` to a centered `container`, the whole topnav layout is centered in one go — and if you want the navbar and the content to differ, set `classes_content_header` and `classes_content` explicitly.

## Sidebar

You can modify the sidebar properties, for example you can disable the collapsed mini sidebar mode, start with a collapsed sidebar, choose the breakpoint where the sidebar expands, remember the collapsed state between page loads, change the scrollbar theme or auto hide option, pick one of the built-in navigation styles, disable the sidebar navigation accordion and change the sidebar animation speed.

The following configuration options are available:

- __`sidebar_mini`__

  Enables/Disables the collapsed mini sidebar mode (adds the `.sidebar-mini` class to the body). Use `true` to enable it or `false` to disable it.

> [!Important]
> AdminLTE v4 has a single sidebar mini mode, the breakpoint where the sidebar switches to its offcanvas (mobile) behavior is controlled by the `sidebar_expand` option instead. The legacy breakpoint tokens (`'xs'`, `'sm'`, `'md'`, `'lg'`, `'xl'` and `'xxl'`) are still accepted and simply enable the mini mode.

- __`sidebar_expand`__

  The breakpoint where the sidebar is expanded (adds the `.sidebar-expand-*` class to the body). The supported values are: `'sm'`, `'md'`, `'lg'` (the default), `'xl'` and `'xxl'`. Below that breakpoint the sidebar behaves like an offcanvas panel.

- __`sidebar_without_hover`__

  When enabled, a collapsed (mini) sidebar does not expand on mouse hover (adds the `.sidebar-without-hover` class to the body).

- __`sidebar_collapse`__

  Enables/Disables the sidebar collapsed mode by default. If you set this option to `true` the sidebar will start on the collapsed mode.

- __`sidebar_collapse_remember`__

  Enables/Disables the remembering of the collapsed state of the sidebar between page loads. When set to `true`, the package adds the `data-enable-persistence="true"` attribute to the `aside.app-sidebar` element, which is the switch of the **AdminLTE v4 PushMenu plugin** for its own persistence feature. The plugin then stores the state in the browser and restores it on the next page load, all on the client side.

- __`sidebar_collapse_auto_size`__ <Badge type="warning">removed</Badge>

  Removed from the shipped configuration file and not read any more. The **AdminLTE v4 PushMenu plugin** collapses the sidebar on its own below the breakpoint configured through `sidebar_expand`, so there is no separate width bound to set. Use `sidebar_expand` instead.

- __`sidebar_collapse_remember_no_transition`__ <Badge type="warning">removed</Badge>

  Removed from the shipped configuration file and not read any more. The **AdminLTE v4 PushMenu plugin** suppresses the transition on the restored state by itself, so the flicker this option used to work around no longer happens.

- __`sidebar_breakpoint`__

  The viewport width (in pixels) where the sidebar switches between its desktop and its mobile behavior. When set, the package adds the `data-sidebar-breakpoint` attribute to the `aside.app-sidebar` element and, at the same time, selects the `.sidebar-expand-*` class that already uses that width — so the **AdminLTE v4 PushMenu plugin** and the media queries of the stylesheet agree on where the switch happens. It therefore takes precedence over `sidebar_expand`. Leave it `null` (the default) to let `sidebar_expand` decide.

  The accepted widths are the five AdminLTE ships a stylesheet for. Both the width itself and the upper bound of its media query are understood, so `768` and `767.98` both select `md`:

  | Width | Selected class |
  |---|---|
  | `576` | `sidebar-expand-sm` |
  | `768` | `sidebar-expand-md` |
  | `992` | `sidebar-expand-lg` |
  | `1200` | `sidebar-expand-xl` |
  | `1400` | `sidebar-expand-xxl` |

> [!Important]
> Any other value is ignored and `sidebar_expand` keeps deciding. AdminLTE hardcodes these five widths in the media queries of its stylesheet and makes the plugin read the width back from the active `.sidebar-expand-*` class, so an arbitrary width can not be honored: the plugin would treat the viewport as desktop while the stylesheet still renders the mobile overlay, and the sidebar could no longer be opened at all in between.

- __`sidebar_scrollbar_theme`__

  Changes the sidebar vertical scrollbar theme. Possible values are: `'os-theme-light'`, `'os-theme-dark'` or `'os-theme-none'` to hide the scrollbar.

- __`sidebar_scrollbar_auto_hide`__

  Changes the sidebar scrollbar auto hide trigger action. This option controls the possibility to hide the visible scrollbars automatically after a certain action. The possible values are:

  - `'never'`: The scrollbars never get hidden automatically.
  - `'scroll'`: The scrollbars get hidden automatically after a scroll.
  - `'leave'` (the default): The scrollbars get hidden automatically after the mouse has left the host-element.
  - `'move'`: The scrollbars get hidden automatically after a scroll and after the mouse has stopped moving.

- __`sidebar_scrollbar_click_scroll`__

  Enables/Disables the click scroll behavior of the sidebar scrollbar (clicking on the scrollbar track scrolls the sidebar).

- __`sidebar_scrollbar_options`__

  Extra options for the sidebar scrollbar, as an array. They are merged into the `scrollbars` object of the **OverlayScrollbars** setup, after the three options above, so they may also override the values those resolve. The default value is an empty array, which leaves the setup exactly as it was. Any non array value is ignored.

  ```php
  'sidebar_scrollbar_options' => [
      'visibility' => 'auto',
      'dragScroll' => false,
  ],
  ```

- __`sidebar_scrollbar_disable_below`__

  The viewport width (in pixels) at or below which the **OverlayScrollbars** instance is not created at all, so the sidebar uses the native scrollbar of the browser. The default value is `992`, which covers the phones and the small tablets. Use `0` to always create the instance. A non numeric value falls back to the default.

> [!Note]
> The `sidebar_scrollbar_*` options above configure the **OverlayScrollbars** instance that the package attaches to the `.sidebar-wrapper` element. That setup is emitted whenever the layout **has a left sidebar** (so it is skipped only on the [top navigation layout](#layout)) and the `assets.overlayscrollbars` resource is not disabled — it is **not** tied to the fixed sidebar mode. On top of that, the instance is not created when the viewport is at or below the `sidebar_scrollbar_disable_below` width, to avoid interfering with touch scrolling on mobile devices.
>
> The values are handed over to **OverlayScrollbars 2.x** verbatim, so they must be valid options of that library.

- __`sidebar_nav_compact`__

  Enables/Disables the compact style of the sidebar navigation (adds the `.nav-compact` class to the `body` element). It removes the rounded corners and the spacing between the links, which fits more items on a screen.

- __`sidebar_nav_indent`__

  Enables/Disables the indentation of the sidebar submenus (adds the `.nav-indent` class to the `body` element). The children of a treeview get an extra left padding, so the hierarchy of the menu is easier to follow. It can be combined with `sidebar_nav_compact`, AdminLTE styles that combination on its own.

- __`sidebar_nav_pills`__

  Enables/Disables the Bootstrap pills style of the sidebar navigation (adds the `.nav-pills` class to the `body` element). The active link is then painted with the `--bs-nav-pills-link-active-bg` color of Bootstrap instead of the sidebar one.

> [!Note]
> The three options above are the built-in style variants of the AdminLTE v4 sidebar menu. They are emitted on the **body** element, not on the menu, because AdminLTE compounds them with `sidebar-mini` and `sidebar-collapse` on a single element and then reaches the sidebar as a descendant. Placing them on the menu would silently lose the refinements for the collapsed and hover-expanded sidebar. The same applies to `layout_compact`.

- __`sidebar_nav_accordion`__

  Enables/Disables the sidebar accordion navigation feature. When enabled, any already opened menu will be collapsed when expanding another one.

- __`sidebar_nav_animation_speed`__

  Changes the sidebar slide up/down animation speed (in milliseconds).

## Right Sidebar

Here you have the option to enable a right sidebar on all your views. When enabled, you can use the `@section('right_sidebar')` section to setup its content. The icon you configure will be displayed at the end of the top menu, and will toggle the visibility (show/hide) of the sidebar.

> [!Important]
> AdminLTE v4 removed the old control sidebar. The right sidebar of the package is now built on top of the [Bootstrap 5 offcanvas](https://getbootstrap.com/docs/5.3/components/offcanvas/) component, and it is rendered with the `adminlte-right-sidebar` identifier. As a consequence, the `right_sidebar_slide`, `right_sidebar_push`, `right_sidebar_scrollbar_theme` and `right_sidebar_scrollbar_auto_hide` options no longer have any effect.

> [!TIP]
> The right sidebar is automatically shown if you fill out the section `right_sidebar` on some of your views (no matter whether it was enabled or not in the configuration file). This feature gives you the possibility to show the right sidebar only on some particular views instead of showing it in all views.

The following configuration options are available:

- __`right_sidebar`__

  Enables/Disables the right sidebar.

- __`right_sidebar_icon`__

  Changes the icon that will be used to toggle the right sidebar.

- __`right_sidebar_theme`__

  Changes the color mode of the right sidebar, the following options are available: `'dark'`, `'light'` or `null` (the default) to inherit the color mode of the page. Note the AdminLTE v4 right sidebar is a plain Bootstrap offcanvas, so forcing a mode here makes it fight the color mode of the rest of the page.

- __`right_sidebar_title`__

  The title shown on the offcanvas header. When empty, the panel title is only exposed to screen readers.

- __`right_sidebar_placement`__

  Where the panel is docked: `'end'` (the default), `'start'`, `'top'` or `'bottom'`. Note that `'start'` and `'end'` are direction aware, so they are mirrored on the [RTL mode](#rtl-mode).

- __`right_sidebar_backdrop`__

  Enables/Disables the offcanvas backdrop.

- __`right_sidebar_scroll`__

  Enables/Disables the body scrolling while the right sidebar is open.

- __`right_sidebar_classes`__

  Extra classes to add to the offcanvas element.

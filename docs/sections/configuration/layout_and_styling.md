The next set of configuration options enables you to change the layout and style of your admin panel.

| Layout & Styling Configuration
| ------------------------------
| [Layout](#layout)
| [Auth View Classes](#authentication-views-classes)
| [Admin Panel Classes](#admin-panel-classes)
| [Sidebar](#sidebar)
| [Control Sidebar](#control-sidebar-right-sidebar)

## Layout

It's possible to change the admin panel layout, you can use a top navigation (navbar) only layout, a boxed layout with sidebar, or enable the fixed mode for the sidebar, the navbar or the footer.

> [!Caution]
> Currently, you cannot use a **boxed layout** with a **fixed navbar** or a **fixed footer**. Also, do not enable `layout_topnav` and `layout_boxed` at the same time. Anything else can be mixed together.

The following config options are available:

- __`layout_topnav`__

  Enables/Disables the top navigation only layout, this will remove the sidebar and put all your links at the top navbar. Can't be mixed with `layout_boxed`.

> [!Tip]
> When enabling `layout_topnav`, the recommendation is to also tune the `classes_topnav_nav` configuration to add the class `navbar-expand-md` or `navbar-expand-lg` instead of `navbar-expand`, in order to get a correct functionality of the hamburger button at low screen sizes.

- __`layout_boxed`__

  Enables/Disables the boxed layout that stretches width only to 1250px. Can't be mixed with `layout_topnav`.

- __`layout_fixed_sidebar`__

  Enables/Disables the fixed mode for the sidebar. Can't be mixed with `layout_topnav`.

- __`layout_fixed_navbar`__

  Enables/Disables the fixed mode for the navbar (top navigation), here you can use a value of `true` or an `array` for [responsive usage](#responsive-usage). Can't be mixed with `layout_boxed`.

- __`layout_fixed_footer`__

  Enables/Disables the fixed mode for the footer, here you can use a value of `true` or an `array` for [responsive usage](#responsive-usage). Can't be mixed with `layout_boxed`.

- __`layout_dark_mode`__

  Enables/Disables the dark mode, set this option to the boolean `true` to enable the dark mode.

> [!Important]
> The `layout_dark_mode` configuration is only available for package version <Badge type="tip">v3.6.0</Badge> or greater.

### Responsive Usage

When using an array on the `layout_fixed_navbar` or `layout_fixed_footer` configuration options, you can disable or enable the fixed layout for specific viewport sizes.

The following keys are available to use inside the array, you can set them to `true` or `false`:

Key  | Description
-----|------------
`xs` | Represent screens from 0px to 575.99px width
`sm` | Represent screens from 576px to 767.99px width
`md` | Represent screens from 768px to 991.99px width
`lg` | Represent screens from 992px to 1199.99px width
`xl` | Represent screens from 1200px or more width

__Examples:__

- `['xs' => true, 'lg' => false]`:

  The element will be fixed from mobile to small tablet (<= 991.99px).

- `['lg' => true]`:

  The element will be fixed starting from desktop (>= 992px).

- `['xs' => true, 'md' => false, 'xl' => true]`:

  The element will be fixed for mobile (<= 767.99px) and extra large desktops (>= 1200px) but not for a small tablet and normal desktop (>= 768px & <= 1199.99px)

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

  Extra classes for the icons (Font Awesome icons) used on the input fields.

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
'classes_auth_btn' => 'btn-flat btn-primary',
```

However, you can customize the options as you want to get some particular themes, for example:

### Dark Theme Style

A dark background with light buttons and icons.
<br>

<img src="/imgs/configuration/layout_and_styling/login-dark.png" alt="Dark Login"
    style="width:200px;margin-left:5px;float:right;"/>

```php
'classes_auth_card' => 'bg-gradient-dark',
'classes_auth_header' => '',
'classes_auth_body' => 'bg-gradient-dark',
'classes_auth_footer' => 'text-center',
'classes_auth_icon' => 'fa-fw text-light',
'classes_auth_btn' => 'btn-flat btn-light',
```

### Lightblue Theme Style

A lightblue header background with lightblue icons.
<br>

<img src="/imgs/configuration/layout_and_styling/login-lblue.png" alt="Lightblue Login"
    style="width:200px;margin-left:5px;float:right;"/>

```php
'classes_auth_card' => '',
'classes_auth_header' => 'bg-gradient-info',
'classes_auth_body' => '',
'classes_auth_footer' => 'text-center',
'classes_auth_icon' => 'fa-lg text-info',
'classes_auth_btn' => 'btn-flat btn-primary',
```

## Admin Panel Classes

You can change the look and behavior of the admin panel by adding extra classes to the body, brand, sidebar, sidebar navigation, top navigation and top navigation container.

The following config options are available:

- __`classes_body`__

  Extra classes for the body. From version <Badge type="tip">v3.8.0</Badge> you may use the experimental `sidebar-hidden` class to hide the sidebar.

- __`classes_brand`__

  Extra classes for the brand. Classes will be added to element `a.navbar-brand` if `layout_topnav` is used, otherwise they will be added to element `a.brand-link`.

- __`classes_brand_text`__

  Extra classes for the brand text. Classes will be added to element `span.brand-text`.

- __`classes_content_header`__

  Classes for the content header container. Classes will be added to the container of the element `div.content-header`. If you left this empty, a default class `container` will be used when `layout_topnav` is used, otherwise `container-fluid` will be used as default.

- __`classes_content_wrapper`__

  Classes for the content wrapper container. Classes will be added to the container of the element `div.content-wrapper`.

- __`classes_content`__

  Classes for the content container. Classes will be added to the container of the element `div.content`. If you left this empty, a default class `container` will be used when `layout_topnav` is used, otherwise `container-fluid` will be used as default.

- __`classes_sidebar`__

  Extra classes for sidebar. Classes will be added to the element `aside.main-sidebar`. There are some built-in classes you can use here to customize the sidebar theme:

  - __`sidebar-dark-<color>`__
  - __`sidebar-light-<color>`__

  Where `<color>` is an [AdminLTE available color](https://adminlte.io/themes/v3/pages/UI/general.html).

- __`classes_sidebar_nav`__

  Extra classes for the sidebar navigation. Classes will be added to the element `ul.nav.nav-pills.nav-sidebar`. There are some built-in classes that you can use here:

  - __`nav-child-indent`__ to indent the child items.
  - __`nav-compact`__ to get a compact navigation style.
  - __`nav-flat`__ to get a flat navigation style.
  - __`nav-legacy`__ to get a legacy `v2` navigation style.

- __`classes_topnav`__

  Extra classes for the top navigation bar. Classes will be added to the element `nav.main-header.navbar`. There are some built-in classes you can use here to customize the topnav theme:

  - __`navbar-<color>`__

  Where `<color>` is an [AdminLTE available color](https://adminlte.io/themes/v3/pages/UI/general.html).

> [!Tip]
> The recommendation is to combine the classes `navbar-<color>` with `navbar-dark` or `navbar-light`.

- __`classes_topnav_nav`__

  Extra classes for the top navigation. Classes will be added to the element `nav.main-header.navbar`. When enabling `layout_topnav` the recommendation is to use `navbar-expand-md` to get items auto collapsed into an hamburger button on low screen sizes. Otherwise, stay with `navbar-expand` class.

- __`classes_topnav_container`__

  Extra classes for top navigation bar container. Classes will be added to the `div` wrapper inside element `nav.main-header.navbar`.

## Sidebar

You can modify the sidebar properties, for example you can disable the collapsed mini sidebar mode, start with a collapsed sidebar, enable sidebar auto collapse on a specific screen size, enable sidebar collapse remember option, change the scrollbar theme or auto hide option, disable the sidebar navigation accordion and change the sidebar animation speed.

The following configuration options are available:

- __`sidebar_mini`__

  Enables/Disables the collapsed mini sidebar mode. You can use the `'lg'` token to enable the sidebar mini mode for desktop and bigger screens (>= 992px), use the `'md'` token to enable it for small tablet and bigger screens (>= 768px), use the `'xs'` token to always enable the sidebar mini mode, or disable the sidebar mini mode at all with a `null` value.

> [!Important]
> For package versions previous or equal to <Badge type="tip">v3.6.0</Badge> you need to use `true` in replacement of the `'lg'` token. Also, the `'xs'` token is only available for package versions greater than <Badge type="tip">v3.6.0</Badge>.

- __`sidebar_collapse`__

  Enables/Disables the sidebar collapsed mode by default. If you set this option to `true` the sidebar will start on the collapsed mode.

- __`sidebar_collapse_auto_size`__

  Enables/Disables auto collapse of the sidebar by setting a minimum width bound that will be used to trigger the collapsed mode when reaching this bound or a lower size. The accepted values are: `false` to disable the feature or a positive `integer` value representing the minimum width bound.

- __`sidebar_collapse_remember`__

  Enables/Disables the collapsed remember script. If you set this option to `true` the state of the sidebar will be keep between page reloads.

- __`sidebar_collapse_remember_no_transition`__

  Enables/Disables the transition animation effect for the sidebar after a page reload. This option will only have effect if `sidebar_collapse_remember` option is enabled.

- __`sidebar_scrollbar_theme`__

  Changes the sidebar vertical scrollbar theme used when the sidebar is on the fixed mode. Possible values are: `'os-theme-light'`, `'os-theme-dark'` or `'os-theme-none'` to hide the scrollbar.

- __`sidebar_scrollbar_auto_hide`__

  Changes the sidebar scrollbar auto hide trigger action. This option controls the possibility to hide the visible scrollbars automatically after a certain action. The possible values are:

  - `'never'` or `'n'`: The scrollbars never get hidden automatically.
  - `'scroll'` or `'s'`: The scrollbars get hidden automatically after a scroll.
  - `'leave'` or `'l'`: The scrollbars get hidden automatically after the mouse has left the host-element.
  - `'move'` or `'m'`: The scrollbars get hidden automatically after a scroll and after the mouse has stopped moving.

- __`sidebar_nav_accordion`__

  Enables/Disables the sidebar accordion navigation feature. When enabled, any already opened menu will be collapsed when expanding another one.

- __`sidebar_nav_animation_speed`__

  Changes the sidebar slide up/down animation speed (in milliseconds).

## Control Sidebar (Right Sidebar)

Here you have the option to enable a right sidebar on all your views. When enabled, you can use the `@section('right_sidebar')` section to setup its content. The icon you configure will be displayed at the end of the top menu, and will toggle the visibility (show/hide) of the sidebar. The `slide` option will setup the sidebar to slide over the content with an animation, when `false` the sidebar will be shown over the content without any animation. You can also choose the sidebar theme (`dark` or `light`).

> [!IMPORTANT]
> The `right_sidebar` section was named `right-sidebar` before version <Badge type="tip">v3.14.0</Badge>. So be sure to use the correct name depending on the package version you're using.

> [!TIP]
> From version <Badge type="tip">v3.14.0</Badge> the right sidebar will be automatically shown if you fill out the section `right_sidebar` on some of your views (no matter whether it was enabled or not in the configuration file). This feature gives you the possibility to show the right sidebar only on some particular views instead of showing it in all views.

The following configuration options are available:

- __`right_sidebar`__

  Enables/Disables the right (control) sidebar.

- __`right_sidebar_icon`__

  Changes the icon that will be used to toggle the right sidebar.

- __`right_sidebar_theme`__

  Changes the theme of the right sidebar, the following options are available: `'dark'` or `'light'`.

- __`right_sidebar_slide`__

  Enables/Disables the slide animation.

- __`right_sidebar_push`__

  Enables/Disables pushing the content instead of overlaying for the right sidebar.

- __`right_sidebar_scrollbar_theme`__

  Change the right sidebar scrollbar theme. Possible values are: `'os-theme-light'` (default), `'os-theme-dark'` or `'os-theme-none'` to hide the scrollbar.

- __`right_sidebar_scrollbar_auto_hide`__

  Changes the sidebar scrollbar auto hide trigger action. This option controls the possibility to hide the visible scrollbars automatically after a certain action. Default value is `'l'`. The possible values are:

  - `'never'` or `'n'`: The scrollbars never get hidden automatically.
  - `'scroll'` or `'s'`: The scrollbars get hidden automatically after a scroll.
  - `'leave'` or `'l'`: The scrollbars get hidden automatically after the mouse has left the host-element.
  - `'move'` or `'m'`: The scrollbars get hidden automatically after a scroll and after the mouse has stopped moving.

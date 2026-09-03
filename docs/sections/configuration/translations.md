# Translations

At the moment, translations for **25 locales** are available out of the box: `ar`, `bn`, `ca`, `de`, `en`, `es`, `fa`, `fr`, `hr`, `hu`, `id`, `it`, `ja`, `lo`, `nl`, `pl`, `pt-br`, `pt-pt`, `ru`, `sk`, `sr`, `tr`, `uk`, `vi`, `zh-CN` (plus the `la` alias described below, which makes 26 folders). Each of them ships the three files the package uses: `adminlte.php` for the layout and the components, `menu.php` for the example menu, and `iframe.php` for the iframe mode. You just need to specify the `locale` configuration option in `config/app.php` file of your Laravel project. The translation files are published by default when installing this package, however if that's not the case, you can publish the language files with the next command:

```sh
php artisan adminlte:install --only=translations
```

Now, you will able to edit the translations files or add support for new languages inside the `lang/vendor/adminlte` folder of your Laravel project.

> [!Note]
> **The `la` folder holds Lao, not Latin.** Earlier releases shipped the Lao translations under `la`, which is the ISO 639-1 code of **Latin**; the code of Lao is `lo`. The canonical folder is `lo` now, and `la` is kept as a thin alias that returns the Lao files, so an application already configured with the wrong code keeps working. Switch your `locale` to `lo` — the alias will be removed on the next major release.

## What the `adminlte.php` File Contains

The `adminlte.php` file of each locale carries every string the package itself renders. They fall into five groups:

Group | Keys | Where they are shown
------|------|----------------------
**Authentication** | `full_name`, `email`, `password`, `retype_password`, `remember_me`, `remember_me_hint`, `register`, `register_a_new_membership`, `i_forgot_my_password`, `i_already_have_a_membership`, `sign_in`, `log_out`, `login_message`, `register_message`, `password_reset_message`, `reset_password`, `send_password_reset_link`, `verify_message`, `verify_email_sent`, `verify_check_your_email`, `verify_if_not_recieved`, `verify_request_another`, `confirm_password_message`, `social_auth_separator` | The [authentication views](/sections/overview/authentication_views)
**Lockscreen** | `lockscreen_message`, `lockscreen_wrong_password`, `lockscreen_throttle` | The [lockscreen](/sections/overview/authentication_views#the-lockscreen) page and the `423` json answer of its middleware
**Error pages** | `back_to_dashboard`, plus an `error_*_title` and an `error_*_message` pair for `unauthorized`, `forbidden`, `not_found`, `page_expired`, `too_many_requests`, `server_error` and `service_unavailable` | The [error views](/sections/configuration/views_customization#error-views)
**Visible labels** | `search`, `color_mode_light`, `color_mode_dark`, `color_mode_auto`, `direct_chat_messages`, `direct_chat_contacts`, `direct_chat_new_messages`, `datatable_print`, `datatable_csv`, `datatable_excel`, `datatable_pdf` | The search boxes, the color mode selector, the [Direct Chat](/sections/components/widget_components#direct-chat) widget and the [Datatables](/sections/components/tool_components#datatables) export buttons
**Accessibility strings** | See the table below | Never visible, read out by a screen reader

## Accessibility Strings

Besides the authentication views and the menu, the translation files also carry the **accessible names** of the controls the package renders on every page. These strings are never visible, but a screen reader reads them out, so a missing translation ships English text into a localized panel:

Key | Used by
----|--------
`toggle_navigation` | The hamburger button that collapses the sidebar
`toggle_fullscreen` | The [fullscreen widget](/sections/configuration/special_menu_items#navbar-fullscreen-widget) of the top navbar
`toggle_right_sidebar` | The button that opens the [right sidebar](/sections/configuration/layout_and_styling#right-sidebar)
`toggle_color_mode` | The [color mode widget](/sections/configuration/special_menu_items#navbar-darkmode-widget) of the top navbar
`card_maximize`, `card_collapse`, `card_remove`, `card_disabled` | The [Card](/sections/components/widget_components#card) tools and its disabled overlay
`loading` | The loading overlay of the [Small Box](/sections/components/widget_components#small-box)
`progress` | The [Progress](/sections/components/widget_components#progress) bar
`timeline_time` | The timestamp of a [Timeline](/sections/components/widget_components#timeline) item
`breadcrumb` | The breadcrumb navigation of the [Content Header](/sections/components/layout_components#content-header)
`notifications` | The badge of the [Navbar Notification](/sections/components/layout_components#navbar-notification) widget
`main_navigation` | The `aria-label` of the sidebar navigation. Override it per application with the `sidebar_nav_aria_label` configuration option
`skip_to_content`, `skip_to_navigation` | The skip links, see below
`no_matching_pages` | The empty state of the sidebar search
`close` | The dismiss controls of the [Alert](/sections/components/widget_components#alert), the [Modal](/sections/components/tool_components#modal) and the [Toast](/sections/components/widget_components#toast)

### Skip Links

**AdminLTE v4** injects a set of skip links at the top of the document, with hardcoded English text. The package emits its own **localized** container instead, which suppresses that injection. The links are visually hidden until they receive the keyboard focus, and they jump to the main content and to the navigation.

When you publish the views, keep that `.skip-links` container in the document: the AdminLTE script only skips its own injection when an element with that class already exists. Keep it as the first child of the `body` element too, so it is the first thing the keyboard focus reaches.

## Menu Translations

The menu translations are enabled by default and allows you to use the `lang` files for translating the text used on menu items.

### Configure Menu Item for Translation

You need to configure the menu items to support translations. For this, you need to add translation `keys` to the `text`, `header` or `label` attributes. Currently, these are the only menu attributes supported for translations.

Translation strings with parameters are supported using an array on the menu attribute, where the first value is the translation `key` and the second value is an array with the translation `parameters`. At next, we show an example of the menu configuration for both cases:

```php
[
    // Example using a translation key.
    'header' => 'account_settings_key',
],
[
    // Example using translation key with parameters.
    'text' => ['profile_key', ['name' => 'User']],
    'url' => 'user/profile',
    'icon' => 'bi bi-person',
],
```

### Setup Lang Files

All the translation keys configured on the menu items must be added in the `menu.php` file of each locale that will be used. So, you will need to declare a `key` for each one of the menu items you want to translate. At next, we show an example of the `lang/vendor/adminlte/en/menu.php` language file for the previous sample of configuration:

```php
return [
    'account_settings_key' => 'ACCOUNT SETTINGS',
    'profile_key' => ':name Profile',
];
```

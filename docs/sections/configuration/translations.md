At the moment, English, German, French, Dutch, Portuguese, Spanish, Turkish, and other translations are available out of the box. You just need to specify the `locale` configuration option in `config/app.php` file of your Laravel project. The translation files are published by default when installing this package, however if that's not the case, you can publish the language files with the next command:

```sh
php artisan adminlte:install --only=translations
```

Now, you will able to edit the translations files or add support for new languages inside the `lang/vendor/adminlte` folder of your Laravel project.

## Accessibility Strings

Besides the authentication views and the menu, the translation files also carry the **accessible names** of the controls the package renders on every page. These strings are never visible, but a screen reader reads them out, so a missing translation ships English text into a localized panel:

Key | Used by
----|--------
`card_maximize`, `card_collapse`, `card_remove`, `card_disabled` | The [Card](/sections/components/widget_components#card) tools and its disabled overlay
`loading` | The loading overlay of the [Small Box](/sections/components/widget_components#small-box)
`progress` | The [Progress](/sections/components/widget_components#progress) bar
`timeline_time` | The timestamp of a [Timeline](/sections/components/widget_components#timeline) item
`breadcrumb` | The breadcrumb navigation of the [Content Header](/sections/components/layout_components#content-header)
`notifications` | The badge of the [Navbar Notification](/sections/components/layout_components) widget
`main_navigation` | The `aria-label` of the sidebar navigation. Override it per application with the `sidebar_nav_aria_label` configuration option
`skip_to_content`, `skip_to_navigation` | The skip links, see below
`no_matching_pages` | The empty state of the sidebar search
`close` | The dismiss controls of the [Alert](/sections/components/widget_components#alert), the [Modal](/sections/components/tool_components#modal) and the [Toast](/sections/components/widget_components#toast)

### Skip Links

**AdminLTE v4** injects a set of skip links at the top of the document, with hardcoded English text. The package emits its own **localized** container instead, which suppresses that injection. The links are visually hidden until they receive the keyboard focus, and they jump to the main content and to the navigation.

When you publish the views, keep that container as the first child of the `body` element, otherwise the AdminLTE script takes over again and the links revert to English.

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

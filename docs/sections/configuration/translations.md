At the moment, English, German, French, Dutch, Portuguese, Spanish, Turkish, and other translations are available out of the box. You just need to specify the `locale` configuration option in `config/app.php` file of your Laravel project. The translation files are published by default when installing this package, however if that's not the case, you can publish the language files with the next command:

```sh
php artisan adminlte:install --only=translations
```

Now, you will able to edit the translations files or add support for new languages inside the `resources/lang/vendor/adminlte` folder, or `lang/vendor/adminlte` from Laravel `9.x` versions.

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
    'icon' => 'fas fa-fw fa-user',
],
```

### Setup Lang Files

All the translation keys configured on the menu items must be added in the `menu.php` file of each locale that will be used. So, you will need to declare a `key` for each one of the menu items you want to translate. At next, we show an example of the `resources/lang/vendor/adminlte/en/menu.php` language file for the previous sample of configuration:

```php
return [
    'account_settings_key' => 'ACCOUNT SETTINGS',
    'profile_key' => ':name Profile',
];
```

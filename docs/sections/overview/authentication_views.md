> [!Important]
> The authentication views no longer belong to the framework itself, they are part of the legacy [laravel/ui](https://github.com/laravel/ui) package. Laravel also offers several [starter kits](https://laravel.com/docs/starter-kits) for the authentication scaffolding besides the legacy `laravel/ui` package. So, it is always a recommendation to read the [Laravel Authentication Documentation](https://laravel.com/docs/authentication) before proceeding.

In case you still choose to use the legacy [laravel/ui](https://github.com/laravel/ui) package for the authentication scaffolding, this package provides the following command to replace the authentication views (those inside the folder `resources/views/auth`) with a set of **AdminLTE** styled views:

```sh
php artisan adminlte:install --only=auth_views
```

Please, note this command just replaces the authentication blade views. The controllers and routes of the authentication scaffolding that where installed in the **Laravel** framework are not touched. On the other hand, to get login, logout, and register features fully working you will need to setup a database and run the proper migrations as indicated on the **Laravel** documentation.

By default, the installed login view contains a link to the registration and password reset views. If you don't want a registration or password reset form, set the `register_url` or `password_reset_url` setting to `null` on the `adminlte.php` configuration file and the respective link will not be displayed.

> [!Note]
> On **AdminLTE v3** the login view required the **iCheck Bootstrap** plugin to style the _remember me_ checkbox. That plugin is not used anymore: the authentication views are built with the native **Bootstrap 5.3** form controls (`form-check`, `input-group`, `invalid-feedback`) and the icons come from **Bootstrap Icons**, so no extra plugin has to be installed.

The look of the authentication views can be tuned with the `classes_auth_card`, `classes_auth_header`, `classes_auth_body`, `classes_auth_footer`, `classes_auth_icon` and `classes_auth_btn` options of the `config/adminlte.php` file. See [Layout and Styling Configuration](/sections/configuration/layout_and_styling) for the details.

## Using the Authentication Views Manually

In the particular case you want to manually use any of the authentication views provided by this package, you can create one of the following files and add a single line to each one of these files.

- _resources/views/auth/login.blade.php_:
  ```blade
  @extends('adminlte::auth.login')
  ```
- _resources/views/auth/register.blade.php_
  ```blade  
  @extends('adminlte::auth.register')
  ```
- _resources/views/auth/verify.blade.php_
  ```blade
  @extends('adminlte::auth.verify')
  ```
- _resources/views/auth/passwords/confirm.blade.php_
  ```blade
  @extends('adminlte::auth.passwords.confirm')
  ```
- _resources/views/auth/passwords/email.blade.php_
  ```blade
  @extends('adminlte::auth.passwords.email')
  ```
- _resources/views/auth/passwords/reset.blade.php_
  ```blade
  @extends('adminlte::auth.passwords.reset')
  ```

In these cases, note that the interactions with controllers and routes will be up to you and you may need to customize the authentication views. Refer to [Views Customization](/sections/configuration/views_customization) for details.

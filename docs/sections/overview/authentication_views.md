# Authentication Views

> [!Important]
> The authentication views no longer belong to the framework itself, they are part of the legacy [laravel/ui](https://github.com/laravel/ui) package. Laravel also offers several [starter kits](https://laravel.com/docs/starter-kits) for the authentication scaffolding besides the legacy `laravel/ui` package. So, it is always a recommendation to read the [Laravel Authentication Documentation](https://laravel.com/docs/authentication) before proceeding.

In case you still choose to use the legacy [laravel/ui](https://github.com/laravel/ui) package for the authentication scaffolding, this package provides the following command to replace the authentication views (those inside the folder `resources/views/auth`) with a set of **AdminLTE** styled views:

```sh
php artisan adminlte:install --only=auth_views
```

> [!Note]
> The **email verification** view posts to the `verification.resend` route, which `Auth::routes()` only registers when it is called as `Auth::routes(['verify' => true])`. If your application uses the Laravel email verification, add that option to the `Auth::routes()` call in your `routes/web.php`, otherwise the view raises a `RouteNotFoundException`.

Please, note this command just replaces the authentication blade views. The controllers and routes of the authentication scaffolding that where installed in the **Laravel** framework are not touched. On the other hand, to get login, logout, and register features fully working you will need to setup a database and run the proper migrations as indicated on the **Laravel** documentation.

By default, the installed login view contains a link to the registration and password reset views. If you don't want a registration or password reset form, set the `register_url` or `password_reset_url` setting to `null` on the `adminlte.php` configuration file and the respective link will not be displayed.

> [!Note]
> On **AdminLTE v3** the login view required the **iCheck Bootstrap** plugin to style the _remember me_ checkbox. That plugin is not used anymore: the authentication views are built with the native **Bootstrap 5.3** form controls (`form-check`, `input-group`, `invalid-feedback`) and the icons come from **Bootstrap Icons**, so no extra plugin has to be installed.

The look of the authentication views can be tuned with the `classes_auth_card`, `classes_auth_header`, `classes_auth_body`, `classes_auth_footer`, `classes_auth_icon` and `classes_auth_btn` options of the `config/adminlte.php` file. See [Layout and Styling Configuration](/sections/configuration/layout_and_styling) for the details.

## Social Authentication Links

The **AdminLTE v4** login and register pages provide a `social-auth-links` block to offer a set of alternative sign in providers. This package renders that block on both the login and the register views when the `auth_social_links` option of the `config/adminlte.php` file is filled. When the option is empty (the default), no extra markup is added to the authentication views.

```php
'auth_social_links' => [
    [
        'url' => 'auth/facebook',
        'text' => 'Sign in using Facebook',
        'icon' => 'bi bi-facebook',
        'theme' => 'primary',
    ],
    [
        'url' => 'auth/google',
        'text' => 'Sign in using Google',
        'icon' => 'bi bi-google',
        'theme' => 'danger',
    ],
],
```

The available attributes of each link are:

- `url`: the target of the link. It is the only required attribute, the links without an `url` are ignored. The value is rendered as it is, so you may want to fill it with the helpers of your application (for example, `route('social.login', 'facebook')`). Only an `http`/`https` url or an application relative path is accepted: an entry carrying any other scheme (a `javascript:` url, for example) is dropped and no button is rendered for it.
- `text`: the label of the button. When not defined, the _Sign In_ (login view) or _Register_ (register view) translation is used instead.
- `icon`: an optional icon class, for example `bi bi-facebook`. Only plain class tokens (letters, digits, hyphens and underscores) are accepted, any other value is discarded and the button is rendered without an icon.
- `theme`: the [Bootstrap 5.3](https://getbootstrap.com/docs/5.3/components/buttons/) button theme, without the `btn-` prefix. The accepted values are `primary`, `secondary`, `success`, `danger`, `warning`, `info`, `light`, `dark` and `link`, plus their `outline-` variants. Any other value falls back to `primary`. Defaults to `primary`.

All these values are escaped when rendered, so a configuration value can never inject markup into the authentication views.

The separator label displayed above the links comes from the `social_auth_separator` translation key. It can be replaced with a fixed label, or hidden with an empty value, using the `auth_social_links_separator` option:

```php
'auth_social_links_separator' => '',
```

> [!Note]
> These options only add the buttons to the authentication views. The routes, the controllers and the [OAuth](https://laravel.com/docs/socialite) flow behind each provider are up to your application.

## The Lockscreen

The **AdminLTE v4** lockscreen page lets an authenticated user lock the session behind a password prompt, without signing out. Unlike the other authentication views, this one needs some server side support, so the package ships a controller, a set of routes, an optional middleware and the view itself.

The feature is opt-in. Enable it on the `config/adminlte.php` file:

```php
'lockscreen' => [

    // Enable the lockscreen feature (routes included).

    'enabled' => true,

    // Register the lockscreen routes of the package. Set it to false when
    // your application provides its own endpoints for them.

    'routes' => true,

    // The authentication guard used to resolve and to verify the user. When
    // it is null, the default guard of the application is used.

    'guard' => null,

    // The throttling of the unlock attempts. A 'max_attempts' value lower
    // than one disables the throttling.

    'throttle' => [
        'max_attempts' => 5,
        'decay_seconds' => 60,
    ],

    // Extra request paths that stay reachable while the screen is locked.
    // The lockscreen endpoints, the login url and the logout url are always
    // allowed. The values accept the wildcards of 'Request::is()'.

    'except' => [],
],
```

The available options are:

Option | Description | Default
-------|-------------|--------
`lockscreen.enabled` | Enables the feature. Nothing is registered while it is `false` | `false`
`lockscreen.routes` | Whether the package registers its own lockscreen routes. Set it to `false` when your application provides its own endpoints | `true`
`lockscreen.guard` | The [authentication guard](https://laravel.com/docs/authentication#adding-custom-guards) used to resolve the user and to verify the password. `null` uses the default guard of the application | `null`
`lockscreen.throttle.max_attempts` | How many unlock attempts are allowed before the endpoint answers with a `429`. A value lower than one disables the throttling | `5`
`lockscreen.throttle.decay_seconds` | How long the throttling of an exhausted user lasts, in seconds | `60`
`lockscreen.except` | Extra request paths that stay reachable while the screen is locked. The values accept the wildcards of `Request::is()` | `[]`

### The Routes

When the feature is enabled, the package registers the next routes:

| Route name                   | Method | Uri                          | Description                                                                                                                    |
| ---------------------------- | ------ | ---------------------------- | ------------------------------------------------------------------------------------------------------------------------------ |
| `adminlte.lockscreen.lock`   | POST   | `adminlte/lockscreen/lock`   | Flags the session as locked, remembers the current page and redirects to the lockscreen.                                        |
| `adminlte.lockscreen.show`   | GET    | `adminlte/lockscreen`        | Renders the lockscreen of the authenticated user. Redirects away when the session is not locked.                                |
| `adminlte.lockscreen.unlock` | POST   | `adminlte/lockscreen/unlock` | Verifies the submitted password and, when it matches, clears the flag and redirects to the page the user was on.                |

The unlock endpoint verifies the password against the user provider of the configured guard, so any custom hasher of your application is honored. The attempts are throttled per user and ip address with the Laravel rate limiter, and the submitted password is never logged nor stored on the session.

### Locking the Screen

Any request to the `adminlte.lockscreen.lock` route locks the session. For example, you can add the next form to the user menu of the layout, on your own blade view:

```blade
@section('usermenu_body')
    <form method="POST" action="{{ route('adminlte.lockscreen.lock') }}">
        @csrf
        <button type="submit" class="btn btn-outline-secondary w-100">
            <i class="bi bi-lock-fill me-1"></i>
            Lock screen
        </button>
    </form>
@stop
```

You may also lock the screen from your own code, without going through the route:

```php
app(\JeroenNoten\LaravelAdminLte\Http\Controllers\LockscreenController::class)->lockScreen();
```

### Protecting the Application

Locking the session does not block anything by itself, it only raises a flag. To send the locked users back to the lockscreen, register the `RedirectIfLocked` middleware of the package on the route groups you want to protect. On a **Laravel 12** or newer application, that is done on the `bootstrap/app.php` file:

```php
use JeroenNoten\LaravelAdminLte\Http\Middleware\RedirectIfLocked;

->withMiddleware(function (Middleware $middleware) {
    $middleware->appendToGroup('web', RedirectIfLocked::class);
})
```

Alternatively, register it as a middleware alias and attach it to the routes of your choice:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias(['adminlte.locked' => RedirectIfLocked::class]);
})
```

```php
Route::middleware(['auth', 'adminlte.locked'])->group(function () {
    // The protected routes of your application.
});
```

The middleware always lets the lockscreen endpoints, the login url and the logout url pass, so a locked user can still unlock the session or sign out. Add any other exception to the `lockscreen.except` option. The requests that expect a json answer get a `423 Locked` response instead of the redirection.

> [!Important]
> The middleware has to run after the session and the authentication middleware, so append it to the group instead of prepending it.

### The Events

The controller dispatches an event on each transition, so your application may react to them (for example, to persist the state on a database or to write an audit log):

- `JeroenNoten\LaravelAdminLte\Events\ScreenWasLocked`
- `JeroenNoten\LaravelAdminLte\Events\ScreenWasUnlocked`

Both events expose the `lockscreen` controller instance and the `user` whose screen was locked or unlocked:

```php
use JeroenNoten\LaravelAdminLte\Events\ScreenWasUnlocked;

Event::listen(ScreenWasUnlocked::class, function (ScreenWasUnlocked $event) {
    Log::info('Screen unlocked', ['user' => $event->user->getAuthIdentifier()]);
});
```

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

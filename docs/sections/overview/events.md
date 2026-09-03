# Events

This package dispatches a small set of [Laravel events](https://laravel.com/docs/events) so your application can hook into the admin panel without publishing and editing any view. This page is the reference of all of them, together with the payload each one carries.

> [!Note]
> An **event** is just a signal the package sends while it renders the panel. A **listener** is a piece of your own code that reacts to it. If you have never written one, read the [Laravel Events documentation](https://laravel.com/docs/events) first, the examples below only show what to put inside the listener.

## Summary

Event | Dispatched when | Payload | Typical use
------|-----------------|---------|-------------
[`BuildingMenu`](#buildingmenu) | The sidebar and topbar menu is being built | `$menu` | Build the menu from a database or from the current user
[`ReadingDarkModePreference`](#readingdarkmodepreference) | The layout is about to resolve the color mode | `$darkMode` | Restore a per user color mode from your storage
[`DarkModeWasToggled`](#darkmodewastoggled) | The server side dark mode toggle was hit | `$darkMode` | Persist the new color mode in your storage
[`ScreenWasLocked`](#screenwaslocked-and-screenwasunlocked) | The session was flagged as locked | `$lockscreen`, `$user` | Write an audit log entry
[`ScreenWasUnlocked`](#screenwaslocked-and-screenwasunlocked) | The password prompt was passed | `$lockscreen`, `$user` | Write an audit log entry

Every event class lives in the `JeroenNoten\LaravelAdminLte\Events` namespace.

## Where the Listener Goes

On a **Laravel 12** or newer application there are two usual places for a listener, and the package works with both of them:

- A **listener class** in `app/Listeners/`, which Laravel discovers automatically from the type hint of its `handle()` method. This is the recommended way.

  ```php
  <?php
  // app/Listeners/BuildAdminLteMenu.php

  namespace App\Listeners;

  use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

  class BuildAdminLteMenu
  {
      public function handle(BuildingMenu $event): void
      {
          // React to the event here...
      }
  }
  ```

- A **closure** registered on the `boot()` method of any service provider, usually `app/Providers/AppServiceProvider.php`:

  ```php
  <?php
  // app/Providers/AppServiceProvider.php

  namespace App\Providers;

  use Illuminate\Support\Facades\Event;
  use Illuminate\Support\ServiceProvider;
  use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

  class AppServiceProvider extends ServiceProvider
  {
      public function boot(): void
      {
          Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
              // React to the event here...
          });
      }
  }
  ```

> [!Important]
> The old `app/Providers/EventServiceProvider.php` file does not exist anymore on a fresh **Laravel 12** application. Some examples of this documentation still show it, because it keeps working when your project has one, but the two shapes above are the current ones.

## BuildingMenu

Dispatched every time the menu of the panel is built, which happens only when a page that extends the layout is actually rendered (so not on your API or AJAX requests). It is the way to build a menu that depends on the database, on the authenticated user or on the current locale.

Property | Type | Description
---------|------|-------------
`$menu` | `JeroenNoten\LaravelAdminLte\Menu\Builder` | The menu builder, with the statically configured items already added

```php
Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
    $event->menu->add([
        'text' => 'Blog',
        'url' => 'admin/blog',
        'icon' => 'bi bi-file-earmark',
    ]);
});
```

The builder methods (`add()`, `addBefore()`, `addAfter()`, `addIn()`, `remove()` and `itemKeyExists()`) and the available item attributes are documented on the [menu configuration](/sections/configuration/menu#dynamic-menu-config) page.

## ReadingDarkModePreference

Dispatched when the layout is about to decide whether the panel starts in dark mode. Use it to restore a preference your application stored on its own (in a database column, for example).

Property | Type | Description
---------|------|-------------
`$darkMode` | `JeroenNoten\LaravelAdminLte\Http\Controllers\DarkModeController` | The controller holding the current preference

The controller exposes three methods a listener may call:

Method | Description
-------|-------------
`enable()` | Start the panel in dark mode
`disable()` | Start the panel in light mode
`isEnabled()` | Whether dark mode is currently enabled

```php
Event::listen(ReadingDarkModePreference::class, function (ReadingDarkModePreference $event) {
    if (auth()->check() && auth()->user()->prefers_dark_mode) {
        $event->darkMode->enable();
    } else {
        $event->darkMode->disable();
    }
});
```

> [!Important]
> This event and the next one only take part when the **server side** color mode toggle is in use, that is, when the `color_mode.remember` option is set to `false`. With the default `remember => true`, the color mode is stored by the **AdminLTE v4** plugin in the browser and no request ever reaches your application. See [color mode](/sections/configuration/layout_and_styling#color-mode).

## DarkModeWasToggled

Dispatched after the server side toggle changed the preference, so you can persist the new value.

Property | Type | Description
---------|------|-------------
`$darkMode` | `JeroenNoten\LaravelAdminLte\Http\Controllers\DarkModeController` | The controller, already holding the **new** preference

```php
Event::listen(DarkModeWasToggled::class, function (DarkModeWasToggled $event) {
    auth()->user()?->update([
        'prefers_dark_mode' => $event->darkMode->isEnabled(),
    ]);
});
```

A complete example, with both dark mode events registered together, is available on the [special menu items](/sections/configuration/special_menu_items#persistence-of-dark-mode-state) page.

## ScreenWasLocked and ScreenWasUnlocked

Dispatched by the [lockscreen](/sections/overview/authentication_views#the-lockscreen) controller on each transition. Both carry the same payload.

Property | Type | Description
---------|------|-------------
`$lockscreen` | `JeroenNoten\LaravelAdminLte\Http\Controllers\LockscreenController` | The controller that performed the transition
`$user` | `Illuminate\Contracts\Auth\Authenticatable` or `null` | The user whose screen was locked or unlocked

```php
Event::listen(ScreenWasUnlocked::class, function (ScreenWasUnlocked $event) {
    Log::info('Screen unlocked', [
        'user' => $event->user?->getAuthIdentifier(),
    ]);
});
```

The controller of the payload also exposes the next public methods, useful when your application drives the lockscreen on its own:

Method | Description
-------|-------------
`lockScreen()` | Flags the session as locked, without going through the route
`unlockScreen()` | Clears the flag, without verifying a password
`isLocked()` | Whether the session is currently locked
`user()` | The authenticated user of the configured guard
`lockscreenUrl()` | The url of the lockscreen page
`unlockUrl()` | The url the unlock form submits to

## Routes Registered by the Package

Two of the features above need an endpoint, so the package registers its own routes. They all live under the `adminlte` url prefix, carry the `adminlte.` route name prefix and run through the `web` middleware group.

Route name | Method | Uri | Registered when
-----------|--------|-----|-----------------
`adminlte.darkmode.toggle` | POST | `adminlte/darkmode/toggle` | `color_mode.routes` is not `false` **and** `disable_darkmode_routes` is not `true`
`adminlte.lockscreen.lock` | POST | `adminlte/lockscreen/lock` | `lockscreen.enabled` is `true` **and** `lockscreen.routes` is not `false`
`adminlte.lockscreen.show` | GET | `adminlte/lockscreen` | idem
`adminlte.lockscreen.unlock` | POST | `adminlte/lockscreen/unlock` | idem

> [!Note]
> The checks are made when the routes are registered, not inside the route files, so a compiled route file never carries a condition of its own. The compiled routes do hold whatever was registered when they were built, though: on a deployment that caches its routes, changing one of these options needs a new `php artisan route:cache` run, exactly like any other route change.

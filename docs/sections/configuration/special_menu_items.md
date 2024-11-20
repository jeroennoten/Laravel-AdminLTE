In this section we introduce some special menu items available for the **sidebar** and/or the **top navbar**. They are treated on a separate section because they requires special or uncommon configuration. You can see a summary of these special menu items on the next table:

Special Item | Description
-------------|-------------
[Sidebar Search](#sidebar-search) | A custom search input for the sidebar.
[Sidebar Menu Item Search](#sidebar-search-over-menu-items) | A search input over menu items for the sidebar.
[Navbar Search](#navbar-search) | A custom search input for the top navbar.
[Navbar Fullscreen Widget](#navbar-fullscreen-widget) | A full screen toggle button for the top navbar.
[Navbar Notification](#navbar-notification) | A notification widget for the top navbar.
[Navbar Darkmode Widget](#navbar-darkmode-widget) | A dark mode toggle widget for the top navbar.

## Sidebar Search

It's possible to place a custom search input in your **sidebar** menu using an item with the following configuration of attributes:

```php
[
    'type' => 'sidebar-custom-search',
    'text' => 'search',                // Placeholder for the underlying input.
    'url' => 'sidebar/search',         // The url used to submit the data ('#' by default).
    'method' => 'post',                // 'get' or 'post' ('get' by default).
    'input_name' => 'searchVal',       // Name for the underlying input ('adminlteSearch' by default).
    'id' => 'sidebarSearch'            // ID attribute for the underlying input (optional).
]
```

> [!Important]
> For package versions under <Badge type="tip">v3.6.0</Badge> you need to use the legacy configuration by replacing `type => 'sidebar-custom-search'` by `search => true`. However, you should note that the legacy support will be discarded in the future.

For the previous definition, you may now define a route and a controller to catch the submitted keywords as explained on the [navbar search](#navbar-search) example. At next you can see an overview of the rendered search input item:

![Sidebar Search Example](/imgs/configuration/special_menu_items/sidebar-search-example.png)

## Sidebar Search Over Menu Items

> [!Important]
> This item is only available for package versions <Badge type="tip">>= v3.6.0</Badge>.

It's also possible to place a search input in your **sidebar** menu that will automatically search over the available menu items using the following configuration of attributes:

```php
[
    'type' => 'sidebar-menu-search',
    'text' => 'search',             // Placeholder for the underlying input.
    'id' => 'sidebarMenuSearch'     // ID attribute for the underlying input (optional).
]
```

Please, note the purpose of this item is to search over the set of available menu items in your sidebar and display the results automatically. It will not submit anything, so you don't need a route/controller definition for this item. Check the next image for an overview:

![Sidebar-Menu-Search](/imgs/configuration/special_menu_items/sidebar-menu-search.png)

## Navbar Search

It's possible to add a search input in the **top navbar** using a menu item with the following configuration of attributes:

```php
[
    'type' => 'navbar-search',
    'text' => 'search',          // Placeholder for the underlying input.
    'topnav_right' => true,      // Or "topnav => true" to place on the left.
    'url' => 'navbar/search',    // The url used to submit the data ('#' by default).
    'method' => 'post',          // 'get' or 'post' ('get' by default).
    'input_name' => 'searchVal', // Name for the underlying input ('adminlteSearch' by default).
    'id' => 'navbarSearch'       // ID attribute for the underlying input (optional).
]
```

> [!Important]
> For package versions under <Badge type="tip">v3.6.0</Badge> you need to use the legacy configuration by replacing `type => 'navbar-search'` by `search => true`. However, you should note that the legacy support will be discarded on the future.

The item will be rendered with a predefined icon, when you click on the icon the search bar will expand all over the **top navbar**. Check next image for an overview when it is expanded:

![Navbar Search Open](/imgs/configuration/special_menu_items/navbar-search-open.png)

### Server Side Processing

For the previous definition, you may define a route and a controller to catch the submitted keywords, as shown below:

```php
Route::match(
    ['get', 'post'],
    '/navbar/search',
    'SearchController@showNavbarSearchResults'
);
```
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    /**
     * Show the navbar search results.
     *
     * @param Request $request
     * @return View
     */
    public function showNavbarSearchResults(Request $request)
    {
        // Check that the search keyword is present.

        if (! $request->filled('searchVal')) {
            return back();
        }

        // Get the search keyword.

        $keyword = $request->input('searchVal');

        Log::info("A navbar search was triggered with next keyword => {$keyword}");

        // TODO: Create the search logic and return adequate response (maybe a view
        // with the results).
        // ...
    }
}
```

## Navbar Fullscreen Widget

> [!Important]
> This item is only available for package versions <Badge type="tip">>= v3.6.0</Badge>.

It's possible to place a full screen widget button in your **top navbar** using the following configuration of attributes:

```php
[
    'type' => 'fullscreen-widget',
    'topnav_right' => true,       // Or "topnav => true" to place on the left.
]
```

You should note that the widget is automatically handled by the underlying **AdminLTE** template and there is no extra configuration for it.

## Navbar Notification

> [!Important]
> This item is only available for package versions <Badge type="tip">>= v3.6.0</Badge> and <Badge type="tip">Laravel >= 7.x</Badge> (because the item is implemented with an underlying blade component).

It's possible to place a notification icon in your **top navbar**. This item will be rendered as an icon with a notification badge. The item supports two modes: a **default mode** and a **dropdown mode**. On the **default mode**, a click on the icon will redirect you to the configured `url` or `route` attribute. On the **dropdown mode** a click on the icon will open a dropdown with a footer link to the configured `url` or `route`, and whose main content may be obtained from an external source when fetching data using an AJAX request. The item supports periodically updates of the _badge_, the _badge color_, the _icon color_ and the _dropdown main content_ using an AJAX request to another configurable `url` or `route`. The summary of the configuration options are the next ones:

```php
[
    'type' => 'navbar-notification',
    'id' => 'my-notification',                // An ID attribute (required).
    'icon' => 'fas fa-bell',                  // A font awesome icon (required).
    'icon_color' => 'warning',                // The initial icon color (optional).
    'label' => 0,                             // The initial label for the badge (optional).
    'label_color' => 'danger',                // The initial badge color (optional).
    'url' => 'notifications/show',            // The url to access all notifications/elements (required).
    'topnav_right' => true,                   // Or "topnav => true" to place on the left (required).
    'dropdown_mode' => true,                  // Enables the dropdown mode (optional).
    'dropdown_flabel' => 'All notifications', // The label for the dropdown footer link (optional).
    'update_cfg' => [
        'url' => 'notifications/get',         // The url to periodically fetch new data (optional).
        'period' => 30,                       // The update period for get new data (in seconds, optional).
    ],
]
```

As mentioned, you can also use the `route` attribute (including optional parameters) in replacement of the `url` attribute to specify both, the route name for the redirect url or the route name for get new data (the one inside `update_cfg`). Example:

```php
[
    'type' => 'navbar-notification',
    'id' => 'my-notification',
    ...
    'route' => 'notifications.show',
    ...
    'update_cfg' => [
        'route' => ['notifications.get', ['param' => 'val']],
        'period' => 30,
    ],
]
```

Also, note the `update_cfg` is optional, so you can implement your own update procedure for the item if you don't like the internal one.

### Internal Updating Procedure

When fetching new data from the configured `url` or `route` (on the `update_cfg` array), the response should be a `json` containing any of the next properties (all optionals):

- **label**: The new label for the badge.
- **label_color**: The new color for the badge.
- **icon_color**: The new color for the icon.
- **dropdown**: The new `HTML` for the dropdown main content (only for **dropdown mode**).

So, you may define a route and a controller to catch the requests to the configured `url` or `route`. At next, you can see a basic example to get an overview:

```php
// On the menu configuration...

[
    'type' => 'navbar-notification',
    'id' => 'my-notification',
    'icon' => 'fas fa-bell',
    'url' => 'notifications/show',
    'topnav_right' => true,
    'dropdown_mode' => true,
    'dropdown_flabel' => 'All notifications',
    'update_cfg' => [
        'url' => 'notifications/get',
        'period' => 30,
    ],
]

// On the web routes...

Route::get(
    'notifications/get',
    [App\Http\Controllers\NotificationsController::class, 'getNotificationsData']
)->name('notifications.get');

// On a custom NotificationsController...

/**
 * Get the new notification data for the navbar notification.
 *
 * @param Request $request
 * @return Array
 */
public function getNotificationsData(Request $request)
{
    // For the sake of simplicity, assume we have a variable called
    // $notifications with the unread notifications. Each notification
    // have the next properties:
    // icon: An icon for the notification.
    // text: A text for the notification.
    // time: The time since notification was created on the server.
    // At next, we define a hardcoded variable with the explained format,
    // but you can assume this data comes from a database query.

    $notifications = [
        [
            'icon' => 'fas fa-fw fa-envelope',
            'text' => rand(0, 10) . ' new messages',
            'time' => rand(0, 10) . ' minutes',
        ],
        [
            'icon' => 'fas fa-fw fa-users text-primary',
            'text' => rand(0, 10) . ' friend requests',
            'time' => rand(0, 60) . ' minutes',
        ],
        [
            'icon' => 'fas fa-fw fa-file text-danger',
            'text' => rand(0, 10) . ' new reports',
            'time' => rand(0, 60) . ' minutes',
        ],
    ];

    // Now, we create the notification dropdown main content.

    $dropdownHtml = '';

    foreach ($notifications as $key => $not) {
        $icon = "<i class='mr-2 {$not['icon']}'></i>";

        $time = "<span class='float-right text-muted text-sm'>
                   {$not['time']}
                 </span>";

        $dropdownHtml .= "<a href='#' class='dropdown-item'>
                            {$icon}{$not['text']}{$time}
                          </a>";

        if ($key < count($notifications) - 1) {
            $dropdownHtml .= "<div class='dropdown-divider'></div>";
        }
    }

    // Return the new notification data.

    return [
        'label' => count($notifications),
        'label_color' => 'danger',
        'icon_color' => 'dark',
        'dropdown' => $dropdownHtml,
    ];
}
```

The result would be like the one shown below:

![Navbar Notification Example](/imgs/configuration/special_menu_items/navbar-notification-example.png)

## Navbar Darkmode Widget

> [!Important]
> This item is only available for package versions <Badge type="tip">>= v3.7.0</Badge> and <Badge type="tip">Laravel >= 7.x</Badge> (because the item is implemented with an underlying blade component).

It's possible to place a dark mode widget in your **top navbar** to enable/disable dark mode on the layout using the following configuration of attributes:

```php
[
    'type' => 'darkmode-widget',
    'topnav_right' => true,     // Or "topnav => true" to place on the left.
]
```

Also, you can setup next optional properties to customize the icons and the icon colors:

- `'icon_enabled'`: The Font Awesome icon to use when dark mode is enabled (`'fas fa-moon'` by default).
- `'icon_disabled'`: The Font Awesome icon to use when dark mode is disabled (`'far fa-moon'` by default).
- `'color_enabled'`: The AdminLTE color to use for the icon when dark mode is enabled (for example `'primary'`).
- `'color_disabled'`: The AdminLTE color to use for the icon when dark mode is disabled (for example `'info'`).

The default widget button will look like next one:

![Dark Mode Button](/imgs/configuration/special_menu_items/dark-mode-button.png)

### Persistence of Dark Mode State

Internally, the widget saves the dark mode preference into the session in order to keep the setup over multiple requests. However, this state will be lost when the session bag is destroyed. In the case you want to persist this state on some sort of storage tool (like a database), you will need to interact with the widget inside the `EventServiceProvider` of your Laravel application.

- The widget dispatchs a special `DarkModeWasToggled` event every time the button is clicked.
- The widget also dispatchs a `ReadingDarkModePreference` event when it is about to read dark mode preference to configure the layout.
- Methods `enable()` / `disable()` are provided within a special controller to initialize the state of the widget.

At next you can see an example of the logic needed on the `app/Providers/EventServiceProvider.php` file in order to interact with the dark mode widget. The example assumes the dark mode preference is stored on a database as an user preference.

```php
<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use JeroenNoten\LaravelAdminLte\Events\DarkModeWasToggled;
use JeroenNoten\LaravelAdminLte\Events\ReadingDarkModePreference;

class EventServiceProvider extends ServiceProvider
{
    ...
 
    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // Register listener for ReadingDarkModePreference event. We use this
        // event to setup dark mode initial status for AdminLTE package.

        Event::listen(
            ReadingDarkModePreference::class,
            [$this, 'handleReadingDarkModeEvt']
        );

        // Register listener for DarkModeWasToggled AdminLTE event.

        Event::listen(
            DarkModeWasToggled::class,
            [$this, 'handleDarkModeWasToggledEvt']
        );
    }

    /**
     * Handle the ReadingDarkModePreference AdminLTE event.
     *
     * @param ReadingDarkModePreference $event
     * @return void
     */
    public function handleReadingDarkModeEvt(ReadingDarkModePreference $event)
    {
        // TODO: Implement the next method to get the dark mode preference for the
        // current authenticated user. Usually this preference will be stored on a database,
        // it is your task to get it.

        $darkModeCfg = $this->getDarkModeSettingFromDB();

        // Setup initial dark mode preference.

        if ($darkModeCfg) {
            $event->darkMode->enable();
        } else {
            $event->darkMode->disable();
        }
    }

    /**
     * Handle the DarkModeWasToggled AdminLTE event.
     *
     * @param DarkModeWasToggled $event
     * @return void
     */
    public function handleDarkModeWasToggledEvt(DarkModeWasToggled $event)
    {
        // Get the new dark mode preference (enabled or not).

        $darkModeCfg = $event->darkMode->isEnabled();

        if ($darkModeCfg) {
            Log::debug("Dark mode preference is now enabled!");
        } else {
            Log::debug("Dark mode preference is now disabled!");
        }

        // Store the new dark mode preference on the database.

        $this->storeDarkModeSettingOnDB($darkModeCfg);

        // TODO: Implement previous method to store the new dark mode
        // preference for the authenticated user. Usually this preference will
        // be stored on a database, it is your task to store it.
    }
}
```

> [!Tip]
> The previous example shows how to manually register listeners for the events, but alternatively you can create **Listeners Classes** for those events too (the actual Laravel's state of the art). Read details on the [Laravel Events Documentation](https://laravel.com/docs/events).

In this section we'll explain how to configure the menu items that will be available on your admin panel.

| Menu Configuration
| ------------------
| [Static Menu Config](#static-menu-config)
| [Menu Filters](#menu-filters)
| [Dynamic Menu Config](#dynamic-menu-config)
| [Side Notes About Laravel Policies Support](#side-notes-about-laravel-policies-support)

## Static Menu Config

You can specify (in a static way) the set of menu items to display in the left **sidebar** and/or the **top navbar**. A menu item representing a **link** should have a `text` attribute and an `url` (or `route`) attribute. Also, and optionally, you can use the `icon` attribute to specify an icon from [Font Awesome](https://fontawesome.com) for every menu item. A single string instead of an array represents a **header** in the sidebar, a **header** is used to group items under a label. However, a **header** may also be represented by an array containing the `header` attribute. There is also a `can` attribute that may be used as a filter with the Laravel's built in [Gate](https://laravel.com/docs/authorization#gates) functionality. Even more, you can create a nested menu using the `submenu` attribute. Also, there are other options and attributes available that will be explained later.

Here is a basic example that will give you a quick overview of a menu configuration:

```php
'menu' => [
    'MAIN NAVIGATION',
    [
        'text' => 'Blog',
        'url' => 'admin/blog',
    ],
    [
        'text' => 'Pages',
        'url' => 'admin/pages',
        'icon' => 'fas fa-fw fa-file',
    ],
    [
        'text' => 'Show my website',
        'url' => '/',
        'target' => '_blank',
    ],
    [
        'header' => 'ACCOUNT SETTINGS',
    ],
    [
        'text' => 'Profile',
        'route' => 'admin.profile',
        'icon' => 'fas fa-fw fa-user',
    ],
    [
        'text' => 'Change Password',
        'route' => 'admin.password',
        'icon' => 'fas fa-fw fa-lock',
    ],
],
```

On the next table, we give a summary of the available attributes for the menu items. Take in consideration that most of these attributes are optional and will be explained later with more details.

Attribute                                   | Description
--------------------------------------------|------------
[active](#the-active-attribute)             | To define when the item should have the active style.
[can](#the-can-attribute)                   | Permissions of the item for use with Laravel's Gate.
[classes](#the-classes-attribute)           | To add custom classes to a menu item.
[data](#the-data-attribute)                 | An array with `data-*` attributes for the item.
[header](#the-header-attribute)             | Text representing the name of a header (only for headers).
[icon](#the-icon-and-icon-color-attributes) | A font awesome icon for the item.
[icon_color](#the-icon-and-icon-color-attributes) | An AdminLTE color for the icon (info, primary, etc).
[id](#the-id-attribute)                     | To define an `id` for the menu item.
[key](#the-key-attribute)                   | An unique identifier key for reference the item.
[label](#the-label-and-label-color-attributes) | Text for a badge associated with the item.
[label_color](#the-label-and-label-color-attributes) | An AdminLTE color for the badge (info, primary, etc).
[route](#the-route-attribute)               | A route name, usually used on link items.
[shift](#the-shift-attribute)               | **[Deprecated]** Classes to append to the list item (for shifting submenu items)
[submenu](#the-submenu-attribute)           | Array with child items that enables nested menus definition.
[target](#the-target-attribute)             | The underlying target attribute for link items.
[text](#the-text-attribute)                 | Text representing the name of the item.
[topnav](#the-topnav-topnav-right-and-topnav-user-attributes) | Bool to place the item on the top navbar.
[topnav_right](#the-topnav-topnav-right-and-topnav-user-attributes) | Bool to place the item in the right section of top navbar.
[topnav_user](#the-topnav-topnav-right-and-topnav-user-attributes) | Bool to place the item in the user menu.
[url](#the-url-attribute) | An URL path, normally used on link items.

Now, we're going to review all of these attributes with more detail:

#### The __`active`__ Attribute:

By default, a menu item is considered active if any of the following conditions holds:

- The current path exactly matches the `url` attribute.
- The current path without the query parameters matches the `url` attribute.
- If it has a submenu containing an active menu item.

In order to override this default behavior, you can specify an `active` attribute containing an array with one or multiple URLs that will be used to search for a match. Even more, you can use asterisks and regular expressions on these URLs definitions in order to support some particular cases. To utilize a regex, you need to prefix your pattern with the `regex:` token and it will get evaluated automatically. The regex pattern will attempt to match the path of the URL returned by `request()->path()`, which returns the current URL without the domain name. At next, we can see an example that uses multiple URL definitions for the active state:

```php
[
    'text' => 'Pages',
    'url' => 'pages',
    'active' => ['pages', 'content', 'content*', 'regex:@^content/[0-9]+$@']
]
```

In the previous case, the menu item will be considered active for all the next URLs:

- `http://my.domain.com/pages`
- `http://my.domain.com/content`
- `http://my.domain.com/content-user` (because `content*`)
- `http://my.domain.com/content/1234` (because `regex:@^content/[0-9]+$@`)

#### The __`can`__ Attribute:

You may use the `can` attribute if you want to conditionally show a menu item. This integrates with the [Laravel's Gate](https://laravel.com/docs/authorization#gates) functionality. If you need to conditionally show a header item, you need to wrap it in an array using the `header` attribute. You can also use multiple conditions entries with an array, check the next example for details:

```php
[
    [
        'header' => 'BLOG',
        'can' => 'manage-blog',
    ],
    [
        'text' => 'Add new post',
        'url' => 'admin/blog/new',
        'can' => ['add-blog-post', 'other-right'],
    ],
]
```

So, for the previous example the header will show only if the user has the `manage-blog` permission, and the link will show if the user has the `add-blog-post` or the `other-right` permissions.

#### The __`classes`__ Attribute:

This attribute provides a way to add custom classes to a particular menu item. The value should be a string with one or multiple class names, similar to the HTML `class` attribute. For example, you can make a colorful `HEADER` item centered on the left sidebar with the next definition:

```php
[
    'header' => 'account_settings',
    'classes' => 'text-yellow text-bold text-center',
]
```

Or you can highlight an important link item with something like this:

```php
[
    'text' => 'Important Link',
    'url' => 'important/link',
    'icon' => 'fas fa-fw fa-exclamation-triangle',
    'classes' => 'text-danger text-uppercase',
]
```

#### The __`data`__ Attribute:

In order to add `data-*` attributes to your menu items, you can simply add an associative array called `data` to the item. Here is a basic example:

```php
[
    'text' => 'New post',
    'url' => 'admin/blog/new',
    'data' => [
        'test-one' => 'content-one',
        'test-two' => 'content-two',
    ],
]
```

Then, the previous menu item will be rendered as this:

```html
<a class="nav-link" href="http://<domain>/admin/blog/new"
   data-test-one="content-one"
   data-test-two="content-two">
    <i class="far fa-fw fa-circle"></i>
    <p>New post</p>
</a>
```

#### The __`header`__ Attribute:

This attribute is exclusive for header items, and the value is just his descriptive text. Headers are only available for the left sidebar and they provide a way to group items under a label. Example:

```php
[
    'header' => 'REPORTS',
]
```

A header item can also be represented with a single string, for example `"REPORTS"`, but the array format provides a way to combine it with other attributes, like the `can` one. The `header` attribute supports translations, as explained on the [Translations](/sections/configuration/translations) section.

#### The __`icon`__ and __`icon_color`__ Attributes:

The `icon` attribute is optional, and you will get an [open circle](https://fontawesome.com/icons/circle?style=regular&from=io) if you leave it out. The available icons that you can use are those from [Font Awesome](https://fontawesome.com/icons). Just specify the name of the icon and it will appear in front of your menu item. The `ìcon_color` attribute provides a way to setup an [AdminLTE color](https://adminlte.io/themes/v3/pages/UI/general.html) for the icon. Example:

```php
[
    'text' => 'profile',
    'url' => 'user/profile',
    'icon' => 'fas fa-fw fa-user',
    'icon_color' => 'primary',
]
```

#### The __`id`__ Attribute:

This attribute is optional, and just provide a way to add an `id` to the element that wraps the menu item, generally a `<li>` tag. This can be useful when you need to target the menu item from `Javascript` or `jQuery` in order to perform updates on it.

```php
[
    'text' => 'profile',
    'url' => 'user/profile',
    'id' => 'profile-id',
]
```

#### The __`key`__ Attribute:

In order to place an item dynamically you can use the `key` attribute, with this attribute you set an unique identifier for the item. Then, you can use this identifier later to add new items before or after the item represented by this `key` identifier. For more details, checkout the section [Dynamic Menu Config](#dynamic-menu-config).

#### The __`label`__ and __`label_color`__ Attributes:

The `label` attribute provides a way to setup a right aligned [badge](https://getbootstrap.com/docs/4.0/components/badge/) for the menu item. The `label_color` is used to configure the badge color, example:

```php
[
    'text' => 'pages',
    'url' => 'admin/pages',
    'icon' => 'far fa-fw fa-file',
    'label' => 4,
    'label_color' => 'success',
]
```

#### The __`route`__ Attribute:

You can use this attribute to assign a Laravel route name to a link item, if you choose to use this attribute, then don't mix it with the `url` attribute, for example:

```php
[
    'text' => 'Profile',
    'route' => 'admin.profile',
    'icon' => 'fas fa-fw fa-user',
]
```

Even more, you can define a route with parameters using an array where the first value is the route name and the second value an array with the parameters, as shown next:

```php
[
    'text' => 'Profile',
    'route' => ['admin.profile', ['userID' => '673']],
    'icon' => 'fas fa-fw fa-user',
]
```

#### The __`shift`__ Attribute:

> [!Caution]
> This attribute will be dropped on the future. So, you should favor the usage of the [classes attribute](/sections/configuration/menu#the-classes-attribute) instead.

This attribute provides a way to inject classes into the list item for a given menu item. It's intended to allow the specification of custom margins on a submenu item to create an indented appearance. Example:

```php
[
    'text' => 'Personnel Management',
    'submenu' => [
        [
            'text' => 'Invites',
            'shift' => 'ml-3',
            'submenu' => [
                [
                    'text' => 'View Invites',
                    'route' => 'invite.index',
                    'icon' => 'fas  fa-users',
                    'shift' => 'ml-4',
                ],
            ],
        ],
    ],
]
```

#### The __`submenu`__ Attribute:

This attribute provides a way to create a menu item containing child items. With this feature you can create nested menus. You can create a menu with items in the sidebar and/or the top navbar. Example:

```php
[
    'text' => 'menu',
    'icon' => 'fas fa-fw fa-share',
    'submenu' => [
        [
            'text' => 'child 1',
            'url' => 'menu/child1',
        ],
        [
            'text' => 'child 2',
            'url' => 'menu/child2',
        ],
    ],
]
```

#### The __`target`__ Attribute:

This attribute is optional and intended to be used only with link items. It represents the underlying `HTML` target attribute for a link item. As an example, you can setup this attribute to the `'_blank'` value in order to open the link in a new tab.

#### The __`text`__ Attribute:

The value of this attribute is just the descriptive text for a menu item (except for headers). The `text` attribute supports translations, as explained on the [Translations](/sections/configuration/translations) section.

#### The __`topnav`__, __`topnav_right`__ and __`topnav_user`__ Attributes:

It's possible to add menu items to the top navigation while the sidebar is enabled, you need to set the `topnav` attribute to `true` for this feature. Also, you can set the `topnav_right` attribute for put the item on the right side of the topnav or set the `topnav_user` attribute to place the menu item in the user menu (above the user-body).

> [!Note]
> When the top navigation layout is enabled, all menu items will appear in the top navigation.

#### The __`url`__ Attribute:

The value of this attribute should be the URL for a link item. You can use a full URL with the domain part or without it. Don't mix this attribute with the `route` attribute. Examples:

```php
[
    'text' => 'profile',
    'url' => 'http://my.domain.com/user/profile',
    'icon' => 'fas fa-fw fa-user',
],
[
    'text' => 'change_password',
    'url' => 'admin/settings',
    'icon' => 'fas fa-fw fa-lock',
],
```

## Menu Filters

You can set the filters you want to include for rendering the menu using the `filters` configuration of the config file. You can also add your own custom filters to this array after you've created them. You can comment out the `GateFilter` if you don't want to use Laravel's built in Gate functionality. The current default set of menu filters is:

```php
'filters' => [
    JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
    JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
    JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
    JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
    JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
    JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
    JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
],
```

If you need to use a custom menu filter, you can add your own menu filter to the previous array. This can be useful, for example, when you are using a third-party package for authorization (instead of the Laravel's Gate functionality). In order to provide more details, we are going to show an example of how you can configure the [Laratrust Package](https://laratrust.santigarcor.me/). Start by creating your custom filter implementation:

> [!Important]
> Your custom filter needs to implement the `FilterInterface` provided by this package.

```php
<?php

namespace MyApp;

use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Laratrust\Laratrust;

class MyMenuFilter implements FilterInterface
{
    public function transform($item)
    {
        if (isset($item['permission']) && ! Laratrust::isAbleTo($item['permission'])) {
            $item['restricted'] = true;
        }

        return $item;
    }
}
```

And then add the following configuration to the `config/adminlte.php` file:

```php
'filters' => [
    ...
    JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
    JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
    JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
    // Comment next line out to remove the Gate filter.
    //JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
    MyApp\MyMenuFilter::class,
]
```

## Dynamic Menu Config

It is also possible to configure the menu dynamically at runtime, for example in the boot method of any service provider or from a controller. You can add new menu items at the end of the menu, before or after a specific menu item, and also inside a menu item as a submenu item. You can use this feature when your menu is not created statically, for example when it depends on your database or the locale configuration.

It is also possible to combine both approaches, a static configured menu with dynamics modifications. The menu will simply be concatenated and the order of the service providers will determine the order in the menu.

The available menu builder methods are:

- __`add(...$newItems)`__

  Adds one or multiple menu items, you can use the item's attributes to place the item/s in the sidebar or the topnav menus (right, left or user menu).

- __`addAfter($itemKey, ...$newItems)`__

  Adds one or multiple menu items after a specific menu item (distinguished by his `key` attribute).

- __`addBefore($itemKey, ...$newItems)`__

  Adds one or multiple menu items before a specific menu item (distinguished by his `key` attribute).

- __`addIn($itemKey, ...$newItems)`__

  Adds one or multiple menu items inside a specific menu item (distinguished by his `key` attribute) as submenu or child item/s.

- __`remove($itemKey)`__

  Removes one specific menu item (distinguished by his `key` attribute).

- __`itemKeyExists($itemKey)`__

  Checks if a specific menu item exists, searched by the `key` attribute.

In the next example we give a basic overview of how to use the methods. First, we add a `key` attribute to a particular menu item.

```php
[
    'key' => 'pages',
    'text' => 'Pages',
    'url' => 'admin/pages',
    'icon' => 'far fa-fw fa-file',
],
```

Then, we're going add the next menu items.

1. `Account Settings` after `Pages`
2. `Notifications` inside `Account Settings`
3. `Profile` before `Notifications`

So, after listening for the `BuildingMenu` event dispatched by this package, we can write the next lines in order to add the mentioned new items:

```php
$events->listen(BuildingMenu::class, function (BuildingMenu $event) {

    $event->menu->addAfter('pages', [
        'key' => 'account_settings',
        'header' => 'Account Settings',
    ]);

    $event->menu->addIn('account_settings', [
        'key' => 'account_settings_notifications',
        'text' => 'Notifications',
        'url' => 'account/edit/notifications',
    ]);

    $event->menu->addBefore('account_settings_notifications', [
        'key' => 'account_settings_profile',
        'text' => 'Profile',
        'url' => 'account/edit/profile',
    ]);
});
```

The event-based approach is used to make sure that the code that builds the menu runs only when the admin panel is actually displayed, and not on every request (for example on AJAX calls to the server).

### Config at the Event Service Provider

> [!Important]
> The next examples are somehow obsolete for the current Laravel state of art. In the newest version of Laravel you usually will define a `Listener` for the `BuildingMenu` event as explained in the [Laravel's Events Documentation](https://laravel.com/docs/events#registering-events-and-listeners). However, the code may still be used as reference.

To configure the menu at runtime on the Laravel `app/Providers/EventServiceProdider.php`, just register a handler or callback for the `BuildingMenu` event. For example, in the `boot()` method:

```php
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
            // Add some items to the menu...
            $event->menu->add('MAIN NAVIGATION');
            $event->menu->add([
                'text' => 'Blog',
                'url' => 'admin/blog',
            ]);
        });
    }
}
```

The attributes for a menu item are the same explained previously. Here is a more practical example that uses translations and the database:

```php
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {

            $event->menu->add(trans('menu.pages'));

            $items = Page::all()->map(function (Page $page) {
                return [
                    'text' => $page['title'],
                    'url' => route('admin.pages.edit', $page)
                ];
            });

            $event->menu->add(...$items);
        });
    }
}
```

## Side Notes About Laravel Policies Support

With [Laravel Policies](https://laravel.com/docs/authorization#creating-policies), you can create a policy class that will be attached to a model according naming conventions. For example, consider the next policies for a Post model:

```php
<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine whether the user can create posts.
     */
    public function create(User $user): bool
    {
        return $user->canCreatePost();
    }

    /**
     * Determine whether the user can update a post.
     */
    public function update(User $user, Post $post): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $post->user_id;
    }

    /**
     * Determine whether the user can delete a post.
     */
    public function delete(User $user, Post $post): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $post->user_id;
    }
}
```

After defining the policies, we can check whether a user has permission to perform some action using several ways ([read this for details](https://laravel.com/docs/authorization#authorizing-actions-using-policies)), some examples:

```php
// Check whether a user can create a post via the user model.
$user->can('create', \App\Models\Post:class);

// Check whether a user can create a post via the Gate facade.
Gate::authorize('create', \App\Models\Post:class);

// Check whether a user can update a post via the user model.
$user->can('update', $post);

// Check whether a user can update a post via the Gate facade.
Gate::authorize('update', $post);
```

Now let's see how this is supported in the menu configuration by using the `can` attribute.

#### Support to Policies Actions That Don't Require Models

If you're using a **static menu configuration**, the `can` attribute may be used with policies actions that don't require models. The next example shows a menu item that will only be visible for users that can create posts, as defined by the previous `App\Policies\PostPolicy`.

```php
[
    'text' => 'Create Post',
    'url' => '...',
    'can' => ['create'],
    'model' => \App\Models\Post::class,
],
```

#### Support to Policies Actions That Require Models:

The `can` attribute may be used with policies actions that require models, but **only if you're defining your [menu dynamically](#dynamic-menu-config)**. The next example shows a menu item that will only be visible for users that can manage (update, delete) a particular post, as defined by the previous `App\Policies\PostPolicy`. Note this example assumes we have a **Listener** for the `BuildingMenu` event dispatched by this package:

```php
<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class SetupAdminLteMenu
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BuildingMenu  $event
     * @return void
     */
    public function handle(BuildingMenu $event)
    {
        // Retrieve the most popular post.
        // The getMostPopularPost() method should be defined in the Post model.

        $post = \App\Models\Post::getMostPopularPost();

        // Add new item to the menu, dynamically...

        $event->menu->add([
            'text' => 'Manage Most Popular Post',
            'url' => '...',
            'can' => ['update', 'delete'],
            'model' => $post,
        ]);
    }
}
```

Thanks to @ruanpepe for contributing with the base of this documentation:

[<img src="https://github.com/ruanpepe.png" width="80px"/>](https://github.com/ruanpepe)

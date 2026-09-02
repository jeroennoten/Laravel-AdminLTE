<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/basic_configuration.html
    |
    */

    'title' => 'AdminLTE 4',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/basic_configuration.html
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Web Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of the external web font used by the
    | AdminLTE v4 template (Source Sans 3, served from a CDN). Disabling the
    | web fonts may be useful if your admin panel internet access is
    | restricted somehow. The font source can be changed on the 'assets'
    | section below.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/basic_configuration.html
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/basic_configuration.html
    |
    */

    'logo' => '<b>Admin</b>LTE',
    'logo_img' => 'vendor/adminlte/dist/assets/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image opacity-75 shadow',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs opacity-75',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/basic_configuration.html
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/assets/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/basic_configuration.html
    |
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/assets/img/AdminLTELogo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/basic_configuration.html
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Assets (AdminLTE v4)
    |--------------------------------------------------------------------------
    |
    | Here you can configure how the base assets of the admin panel are served.
    | AdminLTE v4 bundles Bootstrap 5.3 into its own stylesheet, but requires
    | some external resources at runtime (the Bootstrap JavaScript bundle, the
    | Bootstrap Icons font, the OverlayScrollbars plugin and the web font).
    |
    | The 'mode' option supports the next values:
    | 'local' => Serve the assets published into the public folder. When a file
    |            is not published (the third party assets require an extra step,
    |            see the artisan console commands section of the docs), the CDN
    |            location is used as a fallback when 'cdn_fallback' is enabled.
    | 'cdn'   => Always serve the assets from the configured CDN locations.
    |
    | Note the RTL variant of a stylesheet is picked automatically when the RTL
    | mode is active (see the 'rtl' section below).
    |
    | For detailed instructions you can look the assets section here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/other.html
    |
    */

    'assets' => [

        'mode' => 'local',
        'cdn_fallback' => true,

        // The AdminLTE version used on the CDN locations below. When left as
        // null, the version installed by composer is detected and used, so
        // the assets served from the CDN always match the local ones.

        'adminlte_version' => null,

        // Load the optional extended color palette (adminlte-colors.css). It
        // provides the .bg-*, .text-bg-*, .card-* and .callout-* classes for
        // the extended set of AdminLTE colors (navy, olive, sky, ...).

        'extended_colors' => false,

        // Load the v3 color aliases (adminlte-colors-v3.css) instead of the
        // v4 palette. Useful when your markup still uses the old color names.

        'extended_colors_v3_aliases' => false,

        // Palette tuning. Both options are provided by the palette
        // stylesheets, so they require the extended colors to be enabled.
        //
        // The 'primary' option remaps the primary color of the whole template
        // to any other color of the enabled palette (for example 'teal' or
        // 'navy'). Set it to null to keep the default blue.
        //
        // The 'contrast' option applies the WCAG AA correction of the palette.
        // Use null to apply it automatically on the v3 palette (where some of
        // the colors miss the 4.5:1 ratio), 'aa' to force it, or false to
        // always disable it.

        'palette' => [
            'primary' => null,
            'contrast' => null,
        ],

        // The third party resources required by the template. Set any of them
        // to false when you provide the resource on your own (for example,
        // through your Laravel asset bundling setup).

        'bootstrap_js' => true,
        'bootstrap_icons' => true,
        'overlayscrollbars' => true,

        // Paths (relative to the public folder) of the published assets.

        'local' => [
            'adminlte_css' => 'vendor/adminlte/dist/css/adminlte.min.css',
            'adminlte_rtl_css' => 'vendor/adminlte/dist/css/adminlte.rtl.min.css',
            'adminlte_js' => 'vendor/adminlte/dist/js/adminlte.min.js',
            'colors_css' => 'vendor/adminlte/dist/css/adminlte-colors.min.css',
            'colors_rtl_css' => 'vendor/adminlte/dist/css/adminlte-colors.rtl.min.css',
            'colors_v3_css' => 'vendor/adminlte/dist/css/adminlte-colors-v3.min.css',
            'colors_v3_rtl_css' => 'vendor/adminlte/dist/css/adminlte-colors-v3.rtl.min.css',
            'bootstrap_js' => 'vendor/bootstrap/js/bootstrap.bundle.min.js',
            'bootstrap_icons_css' => 'vendor/bootstrap-icons/font/bootstrap-icons.min.css',
            'overlayscrollbars_css' => 'vendor/overlayscrollbars/styles/overlayscrollbars.min.css',
            'overlayscrollbars_js' => 'vendor/overlayscrollbars/browser/overlayscrollbars.browser.es6.min.js',
            'fonts_css' => 'vendor/fonts/source-sans-3/index.css',
        ],

        // Locations used on the 'cdn' mode and as fallback of missing assets.

        'cdn' => [
            'adminlte_css' => 'https://cdn.jsdelivr.net/npm/admin-lte@{version}/dist/css/adminlte.min.css',
            'adminlte_rtl_css' => 'https://cdn.jsdelivr.net/npm/admin-lte@{version}/dist/css/adminlte.rtl.min.css',
            'adminlte_js' => 'https://cdn.jsdelivr.net/npm/admin-lte@{version}/dist/js/adminlte.min.js',
            'colors_css' => 'https://cdn.jsdelivr.net/npm/admin-lte@{version}/dist/css/adminlte-colors.min.css',
            'colors_rtl_css' => 'https://cdn.jsdelivr.net/npm/admin-lte@{version}/dist/css/adminlte-colors.rtl.min.css',
            'colors_v3_css' => 'https://cdn.jsdelivr.net/npm/admin-lte@{version}/dist/css/adminlte-colors-v3.min.css',
            'colors_v3_rtl_css' => 'https://cdn.jsdelivr.net/npm/admin-lte@{version}/dist/css/adminlte-colors-v3.rtl.min.css',
            'bootstrap_js' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js',
            'bootstrap_icons_css' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css',
            'overlayscrollbars_css' => 'https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css',
            'overlayscrollbars_js' => 'https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js',
            'fonts_css' => 'https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Color Mode (AdminLTE v4)
    |--------------------------------------------------------------------------
    |
    | AdminLTE v4 replaces the old 'dark mode' class by the Bootstrap 5.3
    | native 'data-bs-theme' attribute, which is set on the <html> element.
    |
    | The 'default' option supports the next values:
    | 'light' => Always start on light mode.
    | 'dark'  => Always start on dark mode.
    | 'auto'  => Follow the operating system preference of the visitor.
    |
    | When 'remember' is enabled, the AdminLTE color mode widget stores the
    | visitor choice on the browser local storage (the AdminLTE v4 default
    | behavior). Disable it to always start with the configured default.
    |
    | The 'no_flash_script' option adds a tiny inline script on the head of the
    | document to apply the resolved color mode before the first paint, and
    | thus avoid a flash of the incorrect theme.
    |
    | For detailed instructions you can look the color mode section here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/layout_and_styling.html
    |
    */

    'color_mode' => [
        'enabled' => true,
        'default' => 'auto',
        'remember' => true,
        'no_flash_script' => true,

        // Register the color mode routes of the package (the server side dark
        // mode toggle). Set it to false when your application provides its own
        // endpoint, or when the color mode is fully client side.

        'routes' => true,

        // The color used for the 'theme-color' meta tags. These are used by
        // some browsers to colorize parts of their user interface.

        'theme_color' => [
            'light' => '#0d6efd',
            'dark' => '#1a1a1a',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | RTL Mode (AdminLTE v4)
    |--------------------------------------------------------------------------
    |
    | Here you can enable the right-to-left (RTL) support of AdminLTE v4. When
    | the RTL mode is active, the 'dir="rtl"' attribute is added to the <html>
    | element and the RTL variant of the stylesheets is loaded.
    |
    | The 'enabled' option supports the next values:
    | true  => Always use the RTL mode.
    | false => Never use the RTL mode.
    | null  => Enable the RTL mode only when the current application locale is
    |          included on the 'locales' array (auto detection).
    |
    | For detailed instructions you can look the RTL section here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/layout_and_styling.html
    |
    */

    'rtl' => [
        'enabled' => null,
        'locales' => [
            'ar', 'arc', 'ckb', 'dv', 'fa', 'ha', 'he', 'khw', 'ks', 'ps',
            'sd', 'ug', 'ur', 'uz-AF', 'yi',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/layout_and_styling.html
    |
    */

    'layout_topnav' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_compact' => false,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/layout_and_styling.html
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-primary',

    // The social login buttons of the authentication views. Every entry
    // accepts an 'url', a 'text', an 'icon' and a 'theme'. Leave the array
    // empty (the default) and no block is rendered at all.
    //
    // 'auth_social_links' => [
    //     [
    //         'url' => '/auth/facebook',
    //         'text' => 'Sign in using Facebook',
    //         'icon' => 'bi bi-facebook',
    //         'theme' => 'primary',
    //     ],
    // ],

    'auth_social_links' => [],
    'auth_social_links_separator' => null,

    /*
    |--------------------------------------------------------------------------
    | Lockscreen
    |--------------------------------------------------------------------------
    |
    | The AdminLTE lockscreen keeps the visitor authenticated but locks the
    | panel behind their password. Enable it to register the package routes,
    | and add the 'RedirectIfLocked' middleware to protect your own routes.
    |
    | The 'guard' option selects the authentication guard whose user provider
    | verifies the password (null uses the default guard). The 'throttle'
    | options limit the unlock attempts per user and ip, set 'max_attempts' to
    | zero to disable the limit. The 'except' option lists extra paths that
    | stay reachable while the panel is locked.
    |
    | For detailed instructions you can look the lockscreen section here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/overview/authentication_views.html
    |
    */

    'lockscreen' => [
        'enabled' => false,
        'routes' => true,
        'guard' => null,

        'throttle' => [
            'max_attempts' => 5,
            'decay_seconds' => 60,
        ],

        'except' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/layout_and_styling.html
    |
    */

    'classes_body' => 'bg-body-tertiary',
    'classes_brand' => '',
    'classes_brand_text' => 'fw-light',
    'classes_wrapper' => '',
    'classes_content_wrapper' => '',
    'classes_footer' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_content_top_area' => '',
    'classes_content_bottom_area' => '',
    'classes_sidebar' => 'bg-body-secondary shadow',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'bg-body',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container-fluid',

    // The 'data-bs-theme' value applied to the main sidebar. Use 'dark' for
    // the classic AdminLTE dark sidebar, 'light' for a light one, or null to
    // inherit the color mode of the page.

    'sidebar_theme' => 'dark',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/layout_and_styling.html
    |
    */

    'sidebar_mini' => true,
    'sidebar_expand' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_without_hover' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_breakpoint' => null,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'leave',
    'sidebar_scrollbar_click_scroll' => true,
    'sidebar_scrollbar_options' => [],
    'sidebar_scrollbar_disable_below' => 992,
    'sidebar_nav_aria_label' => null,
    'sidebar_nav_compact' => false,
    'sidebar_nav_indent' => false,
    'sidebar_nav_pills' => false,
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Right Sidebar (Offcanvas)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar of the admin panel. Note the old
    | AdminLTE v3 control sidebar was removed on AdminLTE v4, so the right
    | sidebar is now built on top of the Bootstrap 5 offcanvas component.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/layout_and_styling.html
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'bi bi-gear',
    'right_sidebar_theme' => null,
    'right_sidebar_title' => null,
    'right_sidebar_placement' => 'end',
    'right_sidebar_backdrop' => true,
    'right_sidebar_scroll' => false,
    'right_sidebar_classes' => '',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/basic_configuration.html
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',

    // The HTTP method spoofed on the logout form. Set it to 'GET' when your
    // logout route is not a POST one, or leave it as null to post the form.

    'logout_method' => null,
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,
    'disable_darkmode_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | CSS Variables (AdminLTE v4)
    |--------------------------------------------------------------------------
    |
    | The AdminLTE v4 theming is driven by the Bootstrap 5.3 and the AdminLTE
    | custom properties, so overriding them is enough for most brandings and
    | needs no stylesheet of your own. The declarations are emitted on an
    | inline <style> block of the document head.
    |
    | Only well formed custom property names ('--name') and values that can
    | not break out of the declaration are accepted, any other entry is
    | silently dropped.
    |
    | For detailed instructions you can look the css variables section here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/layout_and_styling.html
    |
    */

    // The custom properties applied on the whole document. For example:
    // 'css_variables' => ['--bs-primary' => '#6f42c1'],

    /*
    |--------------------------------------------------------------------------
    | Single Page Navigation
    |--------------------------------------------------------------------------
    |
    | AdminLTE re-initializes its plugins on the 'turbo:load' event of Turbo
    | Drive, but it knows nothing about Livewire. So, after a 'wire:navigate'
    | visit the sidebar, the treeview and the card tools would stay dead.
    |
    | With this option enabled, the package bridges the Livewire navigation
    | event to the AdminLTE lifecycle. Disable it when your application takes
    | care of that on its own.
    |
    */

    'spa_navigation' => true,

    'css_variables' => [],

    // The selector of the block above. Only ':root' and 'body' are accepted.

    'css_variables_scope' => ':root',

    // The custom properties applied on the sidebar element only. AdminLTE
    // redeclares the sidebar properties under a color mode selector, so these
    // are emitted with a matching specificity. For example:
    // 'css_variables_sidebar' => ['--lte-sidebar-bg' => '#1f2d3d'],

    'css_variables_sidebar' => [],

    /*
    |--------------------------------------------------------------------------
    | Laravel Asset Bundling
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Asset Bundling option for the admin panel.
    | Currently, the next modes are supported: 'mix', 'vite' and 'vite_js_only'.
    | When using 'vite_js_only', it's expected that your CSS is imported using
    | JavaScript. Typically, in your application's 'resources/js/app.js' file.
    | If you are not using any of these, leave it as 'false'.
    |
    | For detailed instructions you can look the asset bundling section here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/other.html
    |
    */

    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'resources/css/app.css',
    'laravel_js_path' => 'resources/js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/menu.html
    |
    */

    'menu' => [
        // Navbar items:
        [
            'type' => 'navbar-search',
            'text' => 'search',
            'topnav_right' => true,
        ],
        [
            'type' => 'darkmode-widget',
            'topnav_right' => true,
        ],
        [
            'type' => 'fullscreen-widget',
            'topnav_right' => true,
        ],

        // Sidebar items:
        [
            'type' => 'sidebar-menu-search',
            'text' => 'search',
        ],
        [
            'text' => 'blog',
            'url' => 'admin/blog',
            'can' => 'manage-blog',
        ],
        [
            'text' => 'pages',
            'url' => 'admin/pages',
            'icon' => 'bi bi-file-earmark',
            'label' => 4,
            'label_color' => 'success',
        ],
        ['header' => 'account_settings'],
        [
            'text' => 'profile',
            'url' => 'admin/settings',
            'icon' => 'bi bi-person',
        ],
        [
            'text' => 'change_password',
            'url' => 'admin/settings',
            'icon' => 'bi bi-lock',
        ],
        [
            'text' => 'multilevel',
            'icon' => 'bi bi-share',
            'submenu' => [
                [
                    'text' => 'level_one',
                    'url' => '#',
                ],
                [
                    'text' => 'level_one',
                    'url' => '#',
                    'submenu' => [
                        [
                            'text' => 'level_two',
                            'url' => '#',
                        ],
                        [
                            'text' => 'level_two',
                            'url' => '#',
                            'submenu' => [
                                [
                                    'text' => 'level_three',
                                    'url' => '#',
                                ],
                                [
                                    'text' => 'level_three',
                                    'url' => '#',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'text' => 'level_one',
                    'url' => '#',
                ],
            ],
        ],
        ['header' => 'labels'],
        [
            'text' => 'important',
            'icon_color' => 'danger',
            'url' => '#',
        ],
        [
            'text' => 'warning',
            'icon_color' => 'warning',
            'url' => '#',
        ],
        [
            'text' => 'information',
            'icon_color' => 'info',
            'url' => '#',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/menu.html
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/plugins.html
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/2.1.8/js/dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css',
                ],
            ],
        ],
        // The Datatables 'Buttons' extension, required by the 'with-buttons'
        // attribute of the datatable component. JSZip powers the excel export
        // and pdfmake the pdf one, drop them when you don't need those.

        'DatatablesButtons' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/4.0.2/js/dataTables.buttons.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/4.0.2/js/buttons.bootstrap5.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/4.0.2/js/buttons.html5.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/4.0.2/js/buttons.print.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.3.3/pdfmake.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.3.3/vfs_fonts.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/4.0.2/css/buttons.bootstrap5.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css',
                ],

                // The AdminLTE v4 compatibility theme for Select2. Replace it
                // by the 'adminlte-select2.rtl.min.css' file on RTL mode.

                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/admin-lte@{version}/dist/css/adminlte-select2.min.css',
                    'rtl' => '//cdn.jsdelivr.net/npm/admin-lte@{version}/dist/css/adminlte-select2.rtl.min.css',
                ],
            ],
        ],
        'TomSelect' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css',
                ],
            ],
        ],
        'Tabulator' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/tabulator-tables@6.3.0/dist/js/tabulator.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/tabulator-tables@6.3.0/dist/css/tabulator_bootstrap5.min.css',
                ],
            ],
        ],
        'Flatpickr' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css',
                ],
            ],
        ],
        'Quill' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css',
                ],
            ],
        ],
        'NoUiSlider' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/nouislider@15.8.1/dist/nouislider.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/nouislider@15.8.1/dist/nouislider.min.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@11',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.2.4/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.2.4/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/iframe_mode.html
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://jeroennoten.github.io/Laravel-AdminLTE/sections/configuration/other.html
    |
    */

    'livewire' => false,
];

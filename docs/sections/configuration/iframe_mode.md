> [!Important]
> The **Tabbed IFrame mode** is only available from version <Badge type="tip">v3.7.0</Badge> of this package.

At next you can see the list of configuration options for the **Tabbed IFrame Mode** that you will find on the package configuration file (`config/adminlte.php`). See [Usage/Tabbed IFrame Mode](/sections/overview/usage#tabbed-iframe-mode) to known how to enable this mode.

Option | Type | Default | Description
----------|--------|------------|---------------
default_tab.url | string / null | `null` | An `url` for the default tab. If defined, it will enable a default tab on initialization
default_tab.title | string / null | `null` | The title for the default tab. When `null`, the translation of `Home` will be displayed
buttons.close | bool | `false` | Whether to enable a button to close the currently active tab
buttons.close_all | bool | `true` | Whether to enable a button to close all tabs
buttons.close_all_other | bool | `true` | Whether to enable a button to close all tabs except the active one
buttons.scroll_left | bool | `true` | Whether to enable the scroll left button
buttons.scroll_right | bool | `true` | Whether to enable the scroll right button
buttons.fullscreen | bool | `true` | Whether to enable the full screen button
options.loading_screen | bool / number | `true` | Use a `bool` to enable/disable a loading screen. Use a positive `number` to setup the loading screen hide delay (this implicitly enables the loading screen)
options.auto_show_new_tab | bool | `true` | Whether to automatically display a new opened tab
options.use_navbar_items | bool | `true` | Whether to also open the top navbar menu items in tabs, instead of open only sidebar menu items

> [!Note]
> Default values are only used when the related configuration option do not exists in the configuration file.

> [!Tip]
> You can tune the `loading_screen` value enough to hide visual anomalies that happens then loading a new **iframe tab**.

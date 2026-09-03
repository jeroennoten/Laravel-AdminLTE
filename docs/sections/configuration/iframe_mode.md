# Tabbed IFrame Mode

> [!Important]
> **AdminLTE v4** dropped the `IFrame` plugin that was bundled with **AdminLTE v3**. From the `4.x` releases of this package, the **Tabbed IFrame Mode** is implemented by the package itself, with a small **vanilla Javascript** helper (no jQuery) and a stylesheet that are pushed into the page only when the mode is enabled.

At next you can see the list of configuration options for the **Tabbed IFrame Mode** that you will find on the package configuration file (`config/adminlte.php`). See [Usage/Tabbed IFrame Mode](/sections/overview/usage#tabbed-iframe-mode) to known how to enable this mode.

Option | Type | Default | Description
----------|--------|------------|---------------
default_tab.url | string / null | `null` | An `url` for the default tab. If defined, it will enable a default tab on initialization
default_tab.title | string / null | `null` | The title for the default tab. When `null`, the translation of `Home` will be displayed
buttons.close | bool | `true` | Whether to enable a button to close the currently active tab
buttons.close_all | bool | `true` | Whether to enable a button to close all tabs
buttons.close_all_other | bool | `true` | Whether to enable a button to close all tabs except the active one
buttons.scroll_left | bool | `true` | Whether to enable the scroll left button
buttons.scroll_right | bool | `true` | Whether to enable the scroll right button
buttons.fullscreen | bool | `true` | Whether to enable the full screen button
options.loading_screen | number | `1000` | The time (in milliseconds) that the loading overlay stays visible while a new tab is loading. Use `0` (or a falsy value) to disable the loading overlay
options.auto_show_new_tab | bool | `true` | Whether to automatically display a new opened tab
options.use_navbar_items | bool | `true` | Whether to also open the top navbar menu items in tabs, instead of open only sidebar menu items

> [!Note]
> Default values are only used when the related configuration option do not exists in the configuration file. The configuration file published by the package already defines every option of the table above, using exactly these same values.

> [!Tip]
> You can tune the `loading_screen` value enough to hide visual anomalies that happens then loading a new **iframe tab**.

## How the Tabs Are Opened

The helper listens for clicks on the whole document and opens a tab for every link that satisfies **all** of the next conditions:

- The link lives inside the left sidebar (`.app-sidebar`) or, when `options.use_navbar_items` is enabled, inside the top navbar (`.app-header`).
- The link does not carry a `data-lte-toggle`, a `data-bs-toggle` or a `target` attribute. This is what keeps the sidebar treeview togglers, the dropdown togglers and the "open in a new tab" links working as usual.
- The `href` is not empty, is not `#`, is not a pure fragment (`#something`) and is not a `javascript:` URI.
- The `href` resolves to the **same origin** as the current page. Links pointing to another domain are always opened by the browser as usual.

The tab title is taken from the `<p>` element of the sidebar menu item (the menu item text), falling back to the text content of the link, and finally to the `href`.

Opening the same URL twice does not create a duplicated tab, the already existing one is activated instead.

## Available Controls

The buttons rendered on the tab bar are driven by the `buttons.*` options and use the next attributes:

Attribute | Description
----------|-------------
`data-lte-toggle="iframe-tab"` | Activates the tab pointed by the `href` of the element
`data-lte-toggle="iframe-close"` | Closes the currently active tab
`data-lte-toggle="iframe-close" data-type="only-this"` | Closes the tab that owns the button (the `×` on each tab)
`data-lte-toggle="iframe-close" data-type="all"` | Closes every tab
`data-lte-toggle="iframe-close" data-type="all-other"` | Closes every tab except the active one
`data-lte-toggle="iframe-scrollleft"` | Scrolls the tab bar to the start
`data-lte-toggle="iframe-scrollright"` | Scrolls the tab bar to the end
`data-lte-toggle="iframe-fullscreen"` | Toggles the full screen mode of the iframe area

When `buttons.close_all` or `buttons.close_all_other` are enabled, the three closing actions are grouped inside a single dropdown. When only `buttons.close` is enabled, a plain close link is rendered instead.

> [!Note]
> The tab bar is also keyboard accessible: with the focus on a tab, the <kbd>←</kbd> and <kbd>→</kbd> arrow keys move to the previous/next tab (the direction is flipped automatically on **RTL** mode).

## Styling and Color Mode

All the styles are scoped under the `.iframe-mode` element and are written on top of the **Bootstrap 5.3** CSS variables (`--bs-body-bg`, `--bs-border-color`, `--bs-secondary-bg`, ...), so the tabbed view follows the active [color mode](/sections/configuration/layout_and_styling) and the text direction without any extra setup. The loading spinner also honours the `prefers-reduced-motion` media query.

The texts of the tab bar (the close buttons, the `Home` title of the default tab, the loading message and the empty message) come from the `iframe.php` language file, so they can be translated as explained on the [Translations](/sections/configuration/translations) section.

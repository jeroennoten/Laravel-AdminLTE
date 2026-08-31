These components are classified under the **Widget** category and represents some of the available **AdminLTE** widgets. At next you can see the list of available components:

|Components
|-----------
| [Alert](#alert), [Callout](#callout), [Card](#card), [Info Box](#info-box), [ProfileColItem](#profile-col-item-profile-row-item), [ProfileRowItem](#profile-col-item-profile-row-item), [ProfileWidget](#profile-widget), [Progress](#progress), [Small Box](#small-box)

## About the `theme` Attribute

Every widget component below accepts a `theme` attribute (and some of them an `icon-theme`, `progress-theme` or `badge` attribute) with a color name. On **AdminLTE v4** the colors are resolved as follows:

- The eight **Bootstrap 5.3** theme colors (`primary`, `secondary`, `success`, `danger`, `warning`, `info`, `light` and `dark`) always work, they render as `text-bg-{color}`, `card-{color}`, `callout-{color}` or `bg-{color}` classes depending on the widget.
- Any color of the **AdminLTE v4 extended palette** (`navy`, `midnight`, `slate`, `steel`, `graphite`, `sky`, `teal`, `olive`, `amber`, `orange`, `pink`, `fuchsia`, `violet` and `indigo`) requires the `assets.extended_colors` option of the [assets configuration](/sections/configuration/other#assets) to be enabled, so that the `adminlte-colors.css` stylesheet is loaded.
- The **AdminLTE v3** color names are still accepted and are **translated on the fly** into their v4 equivalent, as shown on the next table.

v3 name | v4 name | v3 name | v4 name
--------|---------|---------|--------
`lightblue` | `sky` | `blue` | `primary`
`maroon` | `pink` | `red` | `danger`
`purple` | `violet` | `green` | `success`
`lime` | `olive` | `yellow` | `warning`
`gray` | `secondary` | `cyan` | `info`
`gray-dark` | `dark` | |

> [!Note]
> The translation is skipped when the `assets.extended_colors_v3_aliases` option is enabled, because in that case the `adminlte-colors-v3.css` stylesheet is loaded and the old names exist as real CSS classes.

> [!Warning]
> The `bg-gradient-{color}` classes are **not part of the AdminLTE v4 core stylesheet** anymore, they only exist on the extended colors stylesheet. The portable way to paint a gradient is to combine the color class with the Bootstrap `bg-gradient` helper, for example `class="text-bg-info bg-gradient"`, which is exactly what the components do internally.

# Alert

This component represents an `AdminLTE` styled alert notification. The following attributes are available:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
icon | An icon for the alert (Bootstrap Icons by default) | string | `null` | no
dismissable | Setup the alert as dismissable, a button will be available to dismiss the alert | any | `null` | no
theme | A theme color: dark, light, primary, secondary, info, success, warning or danger | string | `null` | no
title | The title for the alert | string | `null` | no

Any other attribute you define will be directly inserted into the underlying `div.alert` element. So, for example, you can define a `class`, `onclick`, `id` or any other attribute you may need.

When a `theme` is set and no `icon` is given, a default **Bootstrap Icon** is picked for the theme:

Theme | Default icon | Theme | Default icon
------|--------------|-------|-------------
`dark` | `bi bi-lightning-fill` | `info` | `bi bi-info-circle-fill`
`light` | `bi bi-lightbulb` | `success` | `bi bi-check-circle-fill`
`primary` | `bi bi-bell-fill` | `warning` | `bi bi-exclamation-triangle-fill`
`secondary` | `bi bi-tag-fill` | `danger` | `bi bi-x-octagon-fill`

> [!Note]
> The alert is rendered with the **Bootstrap 5** markup: the dismiss control is a `button.btn-close` with `data-bs-dismiss="alert"`, and a dismissable alert also gets the `fade` and `show` classes so the closing animation works. When no theme is given, the alert gets a plain `border` instead of a colored background.

### Examples

```blade
{{-- Minimal --}}
<x-adminlte-alert>Minimal example</x-adminlte-alert>

{{-- Minimal with title and dismissable --}}
<x-adminlte-alert title="Well done!" dismissable>
    Minimal example
</x-adminlte-alert>

{{-- Minimal with icon only --}}
<x-adminlte-alert icon="bi bi-person">
    User has logged in!
</x-adminlte-alert>

{{-- Themes --}}
<x-adminlte-alert theme="light" title="Tip">
    Light theme alert!
</x-adminlte-alert>
<x-adminlte-alert theme="dark" title="Important">
    Dark theme alert!
</x-adminlte-alert>
<x-adminlte-alert theme="primary" title="Primary Notification">
    Primary theme alert!
</x-adminlte-alert>
<x-adminlte-alert theme="secondary" icon="" title="Secondary Notification">
    Secondary theme alert!
</x-adminlte-alert>
<x-adminlte-alert theme="info" title="Info">
    Info theme alert!
</x-adminlte-alert>
<x-adminlte-alert theme="success" title="Success">
    Success theme alert!
</x-adminlte-alert>
<x-adminlte-alert theme="warning" title="Warning">
    Warning theme alert!
</x-adminlte-alert>
<x-adminlte-alert theme="danger" title="Danger">
    Danger theme alert!
</x-adminlte-alert>

{{-- Custom --}}
<x-adminlte-alert class="text-bg-teal text-uppercase" icon="bi bi-hand-thumbs-up-fill"
    title="Done" dismissable>
    Your payment was complete!
</x-adminlte-alert>
```

Use the next image as reference to check how every example is rendered. Please, note in the image the alerts were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/5.3/layout/grid/) to organize them.

![Alert Component](/imgs/components/widget_components/alert-component.png)

# Callout

This component represents an `AdminLTE` styled callout notification. The following attributes are available:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
icon | An icon for the callout (Bootstrap Icons by default) | string | `null` | no
theme | A theme color: primary, secondary, info, success, warning, danger, light or dark. See [About the `theme` Attribute](#about-the-theme-attribute) | string | `null` | no
title | The title for the callout | string | `null` | no
title-class | Extra classes for the title container (replaces the default `mb-1` class) | string | `null` | no

Any other attribute you define will be directly inserted into the underlying `div.callout` element. So, for example, you can define a `class`, `onclick`, `id` or any other attribute you may need.

### Examples

```blade
{{-- Minimal --}}
<x-adminlte-callout>Minimal example</x-adminlte-callout>

{{-- themes --}}
<x-adminlte-callout theme="info" title="Information">
    Info theme callout!
</x-adminlte-callout>
<x-adminlte-callout theme="success" title="Success">
    Success theme callout!
</x-adminlte-callout>
<x-adminlte-callout theme="warning" title="Warning">
    Warning theme callout!
</x-adminlte-callout>
<x-adminlte-callout theme="danger" title="Danger">
    Danger theme callout!
</x-adminlte-callout>

{{-- Custom --}}
<x-adminlte-callout theme="success" icon="bi bi-hand-thumbs-up-fill" title="Done">
    <i>Your payment was complete!</i>
</x-adminlte-callout>
<x-adminlte-callout theme="danger" title-class="text-danger text-uppercase"
    icon="bi bi-exclamation-circle-fill" title="Payment Error">
    <i>There was an error on the payment procedure!</i>
</x-adminlte-callout>
<x-adminlte-callout theme="info" title-class="fw-bold"
    icon="bi bi-bell-fill" title="Notification">
    This is a notification.
</x-adminlte-callout>
<x-adminlte-callout theme="warning" title-class="text-uppercase"
    icon="bi bi-flower1" title="observation">
    <i>A styled observation for the user.</i>
</x-adminlte-callout>
```

Use the next image as reference to check how every example is rendered. Please, note in the image the callouts were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/5.3/layout/grid/) to organize them.

![Callout Component](/imgs/components/widget_components/callout-component.png)

# Card

This component represents an `AdminLTE` card box. The following attributes are available:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
body-class | Additional classes for the `card-body` container | string | `null` | no
collapsible | Enables a collapsible card with a button to collapse/expand it. Use the `'collapsed'` string value to initiate the card on collapsed mode | any | `null` | no
disabled | Disables the card (an overlay will show over the card) | any | `null` | no
footer-class | Additional classes for the `card-footer` container | string | `null` | no
header-class | Additional classes for the `card-header` container | string | `null` | no
icon | An icon for the card header (Bootstrap Icons by default) | string | `null` | no
maximizable | Enables a maximizable card with a button to maximize it | any | `null` | no
removable | Enables a removable card with a button to remove it | any | `null` | no
theme | The card theme: light, dark, primary, secondary, info, success, warning, danger or any color of the AdminLTE extended palette like sky or teal. See [About the `theme` Attribute](#about-the-theme-attribute) | string | `null` | no
theme-mode | The theme mode (`full` or `outline`) | string | `null` | no
title | The title for the card header | string | `null` | no

Any other attribute you define will be directly inserted into the underlying `div.card` element. So, for example, you can define extra classes on the card by using `class`, or use `onclick`, `id` or any other attribute you may need.

> [!Note]
> **Default spacing.** The AdminLTE v4 stylesheet gives the cards no bottom margin of their own, so the component adds a **`mb-4`** utility to the `div.card` element, which is what every card of the AdminLTE reference layouts carries.
>
> The default is only added when **you do not provide a bottom margin yourself**. A `mb-*` or `my-*` class (values `0` to `5`, or `auto`) on the `class` attribute **wins** and suppresses the default:
>
> ```blade
> <x-adminlte-card title="Spaced"/>             {{-- class="card mb-4" --}}
> <x-adminlte-card title="Flush" class="mb-0"/> {{-- class="card mb-0" --}}
> <x-adminlte-card title="Roomy" class="my-5"/> {{-- class="card my-5" --}}
> ```
>
> This check exists because the Bootstrap spacing utilities are all declared with `!important` at the same specificity, so simply adding a second margin class would not reliably override the first one — the winner would be decided by the order of the rules in the stylesheet, not by the order you wrote them.
>
> Note the [ProfileWidget](#profile-widget) component is **not** covered by this: although it is also a card, it adds no default margin, so give it a `mb-*` class of your own when you stack several of them.

> [!Note]
> The `theme-mode` values render as follows on **AdminLTE v4**:
> - `outline` &rarr; `card card-{theme} card-outline` (the card title also gets a `text-{theme}` class).
> - `full` &rarr; `card text-bg-{theme} bg-gradient`, since the AdminLTE v3 `bg-gradient-{color}` classes are not part of the v4 core stylesheet anymore.
> - No `theme-mode` &rarr; `card card-{theme}`.

### Card Tools

The collapse, remove and maximize buttons are handled by the **AdminLTE v4** card plugin, which is driven by the `data-lte-toggle` attribute (the v3 `data-card-widget` attribute does not exist anymore):

Attribute | Enabled by
----------|-----------
`data-lte-toggle="card-collapse"` | `collapsible`
`data-lte-toggle="card-remove"` | `removable`
`data-lte-toggle="card-maximize"` | `maximizable`

If you add your own card tool buttons through the `toolsSlot`, use those attribute values.

> [!Note]
> The `disabled` attribute no longer renders the AdminLTE v3 `.overlay` element (it was removed on v4). The overlay is now built with **Bootstrap 5** utilities and shows a `bi bi-slash-circle` icon.

### Slots

- **toolsSlot**: Use this slot to add extra elements on the card header.
- **footerSlot**: Use this slot to fill the card footer.

### Examples

```blade
{{-- Minimal with a title / no body --}}
<x-adminlte-card title="A card without body"/>

{{-- Minimal without header / body only --}}
<x-adminlte-card theme="olive" theme-mode="outline">
    A card without header...
</x-adminlte-card>

{{-- Disabled --}}
<x-adminlte-card title="Disabled Card" theme="teal" disabled>
    A disabled card with teal theme...
</x-adminlte-card>

{{-- Themes --}}
<x-adminlte-card title="Dark Card" theme="dark" icon="bi bi-moon-fill">
    A dark theme card...
</x-adminlte-card>
<x-adminlte-card title="Sky Card" theme="sky" theme-mode="outline"
    icon="bi bi-envelope" header-class="text-uppercase rounded-bottom border-info"
    removable>
    A removable card with outline sky theme...
</x-adminlte-card>
<x-adminlte-card title="Violet Card" theme="violet" icon="bi bi-fan" removable collapsible>
    A removable and collapsible card with violet theme...
</x-adminlte-card>
<x-adminlte-card title="Success Card" theme="success" theme-mode="full"
    icon="bi bi-hand-thumbs-up" collapsible="collapsed">
    A collapsible card with full success theme and collapsed...
</x-adminlte-card>
<x-adminlte-card title="Info Card" theme="info" icon="bi bi-bell" collapsible removable maximizable>
    An info theme card with all the tool buttons...
</x-adminlte-card>

{{-- Complex / Extra tool / Footer --}}
<x-adminlte-card title="Form Card" theme="pink" theme-mode="outline"
    class="shadow" header-class="bg-body-secondary"
    footer-class="border-top rounded"
    icon="bi bi-bell" collapsible removable maximizable>
    <x-slot name="toolsSlot">
        <select class="form-select form-select-sm w-auto">
            <option>Skin 1</option>
            <option>Skin 2</option>
            <option>Skin 3</option>
        </select>
    </x-slot>
    <x-adminlte-input name="User" placeholder="Username"/>
    <x-adminlte-input name="Pass" type="password" placeholder="Password"/>
    <x-slot name="footerSlot">
        <x-adminlte-button class="d-flex ms-auto" theme="light" label="submit"
            icon="bi bi-box-arrow-in-right"/>
    </x-slot>
</x-adminlte-card>
```

Use the next image as reference to check how every example is rendered. Please, note in the image the cards were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/5.3/layout/grid/) to organize them.

![Card Component](/imgs/components/widget_components/card-component.png)

# Info Box

This component represents an `AdminLTE` info box widget. The following attributes are available:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
icon | An icon for the info box (Bootstrap Icons by default) | string | `null` | no
icon-theme | The icon wrapper theme (same values as `theme` property) | string | `null` | no
description | A long text/description for the info box | string | `null` | no
progress | Enables a progress bar for the box. The value should be an integer with the percentage of the progress bar | int | `null` | no
progress-theme | The progress bar theme (same values as `theme` property). When the box is themed and no value is given, the bar inherits the box contrast color | string | `null` | no
text | A short text/description for the info box | string | `null` | no
theme | The info box theme: light, dark, primary, secondary, info, success, warning, danger or any color of the AdminLTE extended palette like sky or teal. See [About the `theme` Attribute](#about-the-theme-attribute) | string | `null` | no
title | A title/header for the info box | string | `null` | no
url | An url for the info box. By default, will be placed on the `title` | string | `null` | no
url-target | The target element where to place the url: `title` or `text` | string | `'title'` | no

Any other attribute you define will be directly inserted into the underlying `div.info-box` element. So, for example, you can define extra classes using the `class` attribute, use the `onclick`, the `id` or any other attribute you may need.

> [!Warning]
> The **AdminLTE v3** `gradient-{color}` theme values (for example `theme="gradient-teal"`) are **not supported anymore**, they would render an invalid `text-bg-gradient-teal` class. Use a plain theme name and add the Bootstrap `bg-gradient` helper through the `class` attribute instead.

### Javascript Utility Class

This component also provides a `Javascript` utility class called **_AdminLTE_InfoBox**. You can use this class to interact or update an already rendered info box element. To use the class, first you need to assign an `id` attribute to your info box element, then you create an object using the `id` attribute previously assigned in the class constructor, for example:

```blade
{{-- On the blade file... --}}
<x-adminlte-info-box id="myInfoBox" title="Title" .../>
```

```js
// On your Javascript code...
let myInfoBox = new _AdminLTE_InfoBox("myInfoBox");
```

Then you can use the next methods from the instantiated object:

- **`myInfoBox.update(data)`**: To update the data of the info box element. The **data** should be an object with the new attributes, the supported object keys are: `title`, `text`, `description`, `icon` and `progress` (see examples for more details). The `url` attribute may be updated too.

### Examples

```blade
{{-- Minimal with title, text and icon --}}
<x-adminlte-info-box title="Title" text="some text" icon="bi bi-star"/>

{{-- Themes --}}
<x-adminlte-info-box title="Views" text="424" icon="bi bi-eye" theme="teal" class="bg-gradient"/>

<x-adminlte-info-box title="Downloads" text="1205" icon="bi bi-download" icon-theme="violet"/>

<x-adminlte-info-box title="528" text="User Registrations" icon="bi bi-person-plus"
    theme="primary" class="bg-gradient" icon-theme="light"/>

<x-adminlte-info-box title="Tasks" text="75/100" icon="bi bi-list-task" theme="warning"
    icon-theme="dark" progress=75 progress-theme="dark"
    description="75% of the tasks have been completed"/>

{{-- Updatable --}}
<x-adminlte-info-box title="Reputation" text="0/1000" icon="bi bi-award"
    theme="danger" id="ibUpdatable" progress=0 progress-theme="teal"
    description="0% reputation completed to reach next level"/>

@push('js')
<script>

    document.addEventListener('DOMContentLoaded', function () {

        let iBox = new _AdminLTE_InfoBox('ibUpdatable');

        let updateIBox = () =>
        {
            // Update data.
            let rep = Math.floor(1000 * Math.random());
            let idx = rep < 100 ? 0 : (rep > 500 ? 2 : 1);
            let progress = Math.round(rep * 100 / 1000);
            let text = rep + '/1000';
            let icon = 'bi bi-award ' + ['text-primary', 'text-light', 'text-warning'][idx];
            let description = progress + '% reputation completed to reach next level';

            let data = {text, icon, description, progress};
            iBox.update(data);
        };

        setInterval(updateIBox, 5000);
    })

</script>
@endpush
```

Use the next image as reference to check how every example is rendered. Please, note in the image the elements were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/5.3/layout/grid/) to organize them.

![Info Box Component](/imgs/components/widget_components/info-box-component.png)

# Profile col Item, Profile Row Item

Both of these components represents an item for the `AdminLTE` profile widget. The main difference is that on the **profile-col-item** the elements are stacked vertically, while on the **profile-row-item** the element are stacked horizontally. The following attributes are available:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
badge | A badge theme for the text attribute. When used, the text attribute will be wrapped inside a badge of the configured theme. | string | `null` | no
icon | An icon for the item (Bootstrap Icons by default) | string | `null` | no
text | The text/description for the item | string | `null` | no
text-tooltip | An extra tooltip (the HTML `title` attribute) for the text of the item | string | `null` | no
title | The title/header for the item | string | `null` | no
size | The item size. Used to wrap the item inside a `col-size` div | integer | `4` (col item) / `12` (row item) | no
url | An url for the item. By default, it'll be placed on the title attribute | string | `null` | no
url-target | The target element where to place the url: `title` (default) or `text` | string | `'title'` | no

The available themes for the badge are: light, dark, primary, secondary, info, success, warning, danger or any color of the **AdminLTE v4** extended palette like `sky` or `teal`. See [About the `theme` Attribute](#about-the-theme-attribute) for the details, the badge is rendered with the Bootstrap 5 `badge text-bg-{theme}` classes.

> [!TIP]
> You may prepend the `pill-` token to a theme (for example, `pill-primary`) to get a pill badge instead of a normal badge (it adds the Bootstrap 5 `rounded-pill` class).

> [!Note]
> On the **profile-row-item** the text is right aligned with the Bootstrap 5 `float-end` class (the Bootstrap 4 `float-right` class does not exist anymore).

Any other attribute you define will be directly inserted into the underlying `div.col-<size>` element. So, for example, you can define `class`, `onclick`, `id` or any other attribute you may need. To see usage examples, check the [Profile Widget Component](#profile-widget).

# Profile Widget

This component represents an `AdminLTE` profile widget. The following attributes are available:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
cover | A cover image url for the profile header section (overlays the theme) | string | `null` | no
desc | A description for the user profile | string | `null` | no
footer-class | Extra classes for the profile footer (to customize the footer section) | string | `null` | no
icon | To setup the default icon that will be used when no image is provided | string | `'bi bi-person-fill'` | no
img | An image url for the user profile | string | `null` | no
header-class | Extra classes for the profile header (to customize the header section) | string | `null` | no
layout-type | The profile header layout type (`modern` or `classic`). | string | `'modern'` | no
name | The user name of the profile | string | `null` | no
theme | The profile header theme: light, dark, primary, secondary, info, success, warning, danger or any color of the AdminLTE extended palette like sky or teal. See [About the `theme` Attribute](#about-the-theme-attribute) | string | `null` | no

Any other attribute you define will be directly inserted into the underlying `div.card.card-widget` element. So, for example, you can define `class`, `onclick`, `id` or any other attribute you may need. There is a main `slot` available to provide content into the footer section, usually by adding [Profile Col Item or Profile Row Item](#profile-col-item-profile-row-item) elements, but you can try with custom content also.

### Examples

Some examples with **modern** (default) layout:

```blade
{{-- Minimal with a name --}}
<x-adminlte-profile-widget name="User Name"/>

{{-- Themes --}}
<x-adminlte-profile-widget name="John Doe" desc="Administrator" theme="teal"
    img="https://picsum.photos/id/1/100">
    <x-adminlte-profile-col-item title="Followers" text="125" url="#"/>
    <x-adminlte-profile-col-item title="Following" text="243" url="#"/>
    <x-adminlte-profile-col-item title="Posts" text="37" url="#"/>
</x-adminlte-profile-widget>

<x-adminlte-profile-widget name="Sarah O'Donell" desc="Commercial Manager" theme="primary"
    img="https://picsum.photos/id/1011/100">
    <x-adminlte-profile-col-item class="text-primary border-end" icon="bi bi-gift fs-4"
        title="Sales" text="25" size=6 badge="primary"/>
    <x-adminlte-profile-col-item class="text-danger" icon="bi bi-people fs-4" title="Dependents"
        text="10" size=6 badge="danger"/>
</x-adminlte-profile-widget>

<x-adminlte-profile-widget name="Robert Gleeis" desc="Sound Manager" theme="warning"
    img="https://picsum.photos/id/304/100" header-class="text-start"
    footer-class="text-bg-dark bg-gradient">
    <x-adminlte-profile-col-item title="I'm also" text="Artist" size=3
        class="text-warning border-end border-warning"/>
    <x-adminlte-profile-col-item title="Loves" text="Music" size=6
        class="text-warning border-end border-warning"/>
    <x-adminlte-profile-col-item title="Like to" text="Travel" size=3
        class="text-warning"/>
</x-adminlte-profile-widget>

<x-adminlte-profile-widget name="Alice Viorich" desc="Community Manager" theme="violet"
    img="https://picsum.photos/id/454/100" footer-class="text-bg-pink bg-gradient">
    <x-adminlte-profile-col-item icon="bi bi-instagram fs-3" text="Instagram" badge="violet" size=4/>
    <x-adminlte-profile-col-item icon="bi bi-facebook fs-3" text="Facebook" badge="violet" size=4/>
    <x-adminlte-profile-col-item icon="bi bi-twitter-x fs-3" text="X" badge="violet" size=4/>
</x-adminlte-profile-widget>

{{-- Custom --}}
<x-adminlte-profile-widget class="shadow" name="Willian Dubling" desc="Web Developer"
    img="https://picsum.photos/id/177/100" cover="https://picsum.photos/id/541/550/200"
    header-class="text-white text-end" footer-class="text-bg-dark bg-gradient">
    <x-adminlte-profile-row-item title="4+ years of experience with"
        class="text-center border-bottom border-secondary"/>
    <x-adminlte-profile-col-item title="Javascript" icon="bi bi-filetype-js fs-3 text-warning" size=3/>
    <x-adminlte-profile-col-item title="PHP" icon="bi bi-filetype-php fs-3 text-warning" size=3/>
    <x-adminlte-profile-col-item title="HTML5" icon="bi bi-filetype-html fs-3 text-warning" size=3/>
    <x-adminlte-profile-col-item title="CSS3" icon="bi bi-filetype-css fs-3 text-warning" size=3/>
    <x-adminlte-profile-col-item title="Bootstrap" icon="bi bi-bootstrap fs-3 text-warning" size=4/>
    <x-adminlte-profile-col-item title="Databases" icon="bi bi-database fs-3 text-warning" size=4/>
    <x-adminlte-profile-col-item title="Git" icon="bi bi-git fs-3 text-warning" size=4/>
</x-adminlte-profile-widget>
```

Use the next image as reference to check how every example is rendered. Please, note in the image the elements were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/5.3/layout/grid/) to organize them.

![Profile Widget Component Modern](/imgs/components/widget_components/profile-widget-component-modern.png)

Some examples with **classic** layout:

```blade
{{-- Layout Classic / Minimal --}}
<x-adminlte-profile-widget name="User Name" layout-type="classic"/>

{{-- Layout Classic / Theme --}}
<x-adminlte-profile-widget name="John Doe" desc="Administrator" theme="sky"
    img="https://picsum.photos/id/1/100" layout-type="classic">
    <x-adminlte-profile-row-item icon="bi bi-people" title="Followers" text="125"
        url="#" badge="teal"/>
    <x-adminlte-profile-row-item icon="bi bi-person-check" title="Following"
        text="243" url="#" badge="sky"/>
    <x-adminlte-profile-row-item icon="bi bi-sticky" title="Posts" text="37"
        url="#" badge="navy"/>
</x-adminlte-profile-widget>

{{-- Layout Classic / Custom --}}
<x-adminlte-profile-widget name="Roxana Saziadko" desc="Graphic Designer" class="shadow"
    img="https://picsum.photos/id/1027/100" cover="https://picsum.photos/id/130/550/200"
    layout-type="classic" header-class="text-end" footer-class="text-bg-teal bg-gradient">
    <x-adminlte-profile-col-item class="border-end" icon="bi bi-list-task fs-4"
        title="Projects Done" text="25" size=6 badge="olive"/>
    <x-adminlte-profile-col-item icon="bi bi-list-task fs-4"
        title="Projects Pending" text="5" size=6 badge="danger"/>
    <x-adminlte-profile-row-item title="Contact me on:" class="text-center border-bottom"/>
    <x-adminlte-profile-row-item icon="bi bi-instagram fs-3" title="Instagram"
        url="#" size=4/>
    <x-adminlte-profile-row-item icon="bi bi-facebook fs-3" title="Facebook"
        url="#" size=4/>
    <x-adminlte-profile-row-item icon="bi bi-twitter-x fs-3" title="X"
        url="#" size=4/>
</x-adminlte-profile-widget>
```

Use the next image as reference to check how every example is rendered. Please, note in the image the elements were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/5.3/layout/grid/) to organize them.

![Profile Widget Component Classic](/imgs/components/widget_components/profile-widget-component-classic.png)

# Progress

This component represents an `AdminLTE` styled progress bar. The following attributes are available:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
animated | Enables the animated mode on the progress bar | any | `null` | no
size | The progress bar size (`sm`, `xs` or `xxs`) | string | `null` | no
striped | Enables stripes on the progress bar | any | `null` | no
theme | The progress bar theme: light, dark, primary, secondary, info, success, warning, danger or any color of the AdminLTE extended palette like sky or teal. See [About the `theme` Attribute](#about-the-theme-attribute). Set it to an empty value to inherit the color of the container | string | `info` | no
value | The progress bar percentage value (integer between 0 and 100) | int | `0` | no
vertical | Enables vertical mode on the progress bar | any | `null` | no
with-label | Enables a percentage label on the progress bar | any | `null` | no

Any other attribute you define will be directly inserted into the underlying `div.progress` element. So, for example, you can define a `class`, `onclick`, `id` or any other attribute you may need.

> [!Note]
> **Default spacing.** The AdminLTE v4 stylesheet gives the progress bars no margin of their own, so the component adds a **`mb-2`** utility to the `.progress` element, which is what the AdminLTE reference layouts use to separate stacked progress bars.
>
> The default is only added when **you do not provide a bottom margin yourself**. A `mb-*` or `my-*` class (values `0` to `5`, or `auto`) on the `class` attribute **wins** and suppresses the default:
>
> ```blade
> <x-adminlte-progress value="40"/>            {{-- class="progress mb-2" --}}
> <x-adminlte-progress value="40" class="mb-0"/> {{-- class="progress mb-0" --}}
> <x-adminlte-progress value="40" class="my-4"/> {{-- class="progress my-4" --}}
> ```
>
> This check exists because the Bootstrap spacing utilities are all declared with `!important` at the same specificity, so simply adding a second margin class would not reliably override the first one — the winner would be decided by the order of the rules in the stylesheet, not by the order you wrote them.

> [!Note]
> The component emits the **Bootstrap 5.3** progress markup: the `role="progressbar"` and the `aria-value*` attributes live on the `.progress` wrapper (not on the inner `.progress-bar` element as on Bootstrap 4). The theme is rendered as a `bg-{theme}` class on the inner bar.

### Javascript Utility Class

This component also provides a `Javascript` utility class called **_AdminLTE_Progress**. You can use this class to interact or update an already rendered progress bar element. To use the class, first you need to assign an `id` attribute to your progress bar element, then you create an object using the `id` attribute previously assigned in the class constructor, for example:

```blade
{{-- On the blade file... --}}
<x-adminlte-progress id="myProgress" .../>
```

```js
// On your Javascript code...
let myProgress = new _AdminLTE_Progress("myProgress");
```

Then you can use the next methods from the instantiated object:

- **`myProgress.getValue()`**: To get the current progress bar value.

- **`myProgress.setValue(value)`**: To update the progress bar value. The **value** should be an integer.

### Examples

```blade
{{-- Minimal --}}
<x-adminlte-progress/>

{{-- themes --}}
<x-adminlte-progress theme="light" value=55/>
<x-adminlte-progress theme="dark" value=30/>
<x-adminlte-progress theme="primary" value=95/>
<x-adminlte-progress theme="secondary" value=40/>
<x-adminlte-progress theme="info" value=85/>
<x-adminlte-progress theme="warning" value=25/>
<x-adminlte-progress theme="danger" value=50/>
<x-adminlte-progress theme="success" value=75/>

{{-- Custom (the extended palette requires 'assets.extended_colors') --}}
<x-adminlte-progress theme="teal" value=75 animated/>
<x-adminlte-progress size="sm" theme="indigo" value=85 animated/>
<x-adminlte-progress theme="pink" value=50 animated with-label/>

{{-- Vertical --}}
<x-adminlte-progress theme="violet" value=40 vertical/>
<x-adminlte-progress theme="orange" value=80 vertical animated/>
<x-adminlte-progress theme="navy" value=70 vertical striped with-label/>
<x-adminlte-progress theme="olive" size="xxs" value=90 vertical/>

{{-- Dinamic Change --}}
<x-adminlte-progress id="pbDinamic" value="5" theme="sky" animated with-label/>
{{-- Update the previous progress bar every 2 seconds, incrementing by 10% each step --}}
@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        let pBar = new _AdminLTE_Progress('pbDinamic');

        let inc = (val) => {
            let v = pBar.getValue() + val;
            return v > 100 ? 0 : v;
        };

        setInterval(() => pBar.setValue(inc(10)), 2000);
    })
</script>
@endpush
```

Use the next image as reference to check how every example is rendered. Please, note in the image the elements were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/5.3/layout/grid/) to organize them.

![Progress Component](/imgs/components/widget_components/progress-component.png)

# Small Box

This component represents an `AdminLTE` small box widget. The following attributes are available:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
icon | An icon for the small box (Bootstrap Icons by default) | string | `null` | no
loading | Enables a loading animation (an overlay with a Bootstrap spinner) | any | `null` | no
text | The text/description for the small box | string | `null` | no
theme | The small box theme: light, dark, primary, secondary, info, success, warning, danger or any color of the AdminLTE extended palette like sky or teal. See [About the `theme` Attribute](#about-the-theme-attribute) | string | `null` | no
title | The title/header for the small box | string | `null` | no
url | An url for the small box. When enabled, a link-styled footer section will be visible pointing to that url | string | `null` | no
url-text | A text/label associated with the footer url | string | `null` | no

Any other attribute you define will be directly inserted into the underlying `div.small-box` element. So, for example, you can define extra classes using the `class` attribute, use the `onclick`, the `id` or any other attribute you may need.

> [!Note]
> The **AdminLTE v3** `.overlay` element does not exist on v4, so the loading overlay is now built with **Bootstrap 5** utilities and renders a `spinner-border` element. The `small-box-overlay` class is kept only as a hook for the Javascript helper below. The footer link arrow is now a `bi bi-arrow-right-circle` icon.

### Javascript Utility Class

This component also provides a `Javascript` utility class called **_AdminLTE_SmallBox**. You can use this class to interact or update an already rendered small box element. To use the class, first you need to assign an `id` attribute to your small box element, then you create an object using the `id` attribute previously assigned in the class constructor, for example:

```blade
{{-- On the blade file... --}}
<x-adminlte-small-box id="mySmallBox" title="Title" .../>
```

```js
// On your Javascript code...
let mySmallBox = new _AdminLTE_SmallBox("mySmallBox");
```

Then you can use the next methods from the instantiated object:

- **`mySmallBox.toggleLoading()`**: To toggle the loading animation of the small box.

- **`mySmallBox.update(data)`**: To update the data of the small box element. The **data** should be an object with the new attributes, the supported object keys are: `title`, `text`, `icon` and `url` (see examples for more details).

### Examples

```blade
{{-- Minimal with title, text and icon --}}
<x-adminlte-small-box title="Title" text="some text" icon="bi bi-star"/>

{{-- Loading --}}
<x-adminlte-small-box title="Loading" text="Loading data..." icon="bi bi-bar-chart"
    theme="info" url="#" url-text="More info" loading/>

{{-- Themes --}}
<x-adminlte-small-box title="424" text="Views" icon="bi bi-eye"
    theme="teal" url="#" url-text="View details"/>

<x-adminlte-small-box title="Downloads" text="1205" icon="bi bi-download"
    theme="violet"/>

<x-adminlte-small-box title="528" text="User Registrations" icon="bi bi-person-plus"
    theme="primary" url="#" url-text="View all users"/>

{{-- Updatable --}}
<x-adminlte-small-box title="0" text="Reputation" icon="bi bi-award"
    theme="danger" url="#" url-text="Reputation history" id="sbUpdatable"/>

@push('js')
<script>

    document.addEventListener('DOMContentLoaded', function () {

        let sBox = new _AdminLTE_SmallBox('sbUpdatable');

        let updateBox = () =>
        {
            // Stop loading animation.
            sBox.toggleLoading();

            // Update data.
            let rep = Math.floor(1000 * Math.random());
            let idx = rep < 100 ? 0 : (rep > 500 ? 2 : 1);
            let text = 'Reputation - ' + ['Basic', 'Silver', 'Gold'][idx];
            let icon = 'bi bi-award ' + ['text-primary', 'text-light', 'text-warning'][idx];
            let url = ['url1', 'url2', 'url3'][idx];

            let data = {text, title: rep, icon, url};
            sBox.update(data);
        };

        let startUpdateProcedure = () =>
        {
            // Simulate loading procedure.
            sBox.toggleLoading();

            // Wait and update the data.
            setTimeout(updateBox, 2000);
        };

        setInterval(startUpdateProcedure, 10000);
    })

</script>
@endpush
```

Use the next image as reference to check how every example is rendered. Please, note in the image the elements were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/5.3/layout/grid/) to organize them.

![Small Box Component](/imgs/components/widget_components/small-box-component.png)

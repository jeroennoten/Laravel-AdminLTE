These components are classified under the **Widget** category and represents some of the available **AdminLTE** widgets. At next you can see the list of available components:

|Components
|-----------
| [Alert](#alert), [Callout](#callout), [Card](#card), [Info Box](#info-box), [ProfileColItem](#profile-col-item-profile-row-item), [ProfileRowItem](#profile-col-item-profile-row-item), [ProfileWidget](#profile-widget), [Progress](#progress), [Small Box](#small-box)

# Alert

This component represents an `AdminLTE` styled alert notification. The following attributes are available:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
icon | A `fontawesome` icon for the alert | string | `null` | no
dismissable | Setup the alert as dismissable, a button will be available to dismiss the alert | any | `null` | no
theme | An `AdminLTE` theme: dark, light, primary, secondary, info, success, warning or danger | string | `null` | no
title | The title for the alert | string | `null` | no

Any other attribute you define will be directly inserted into the underlying `div.alert` element. So, for example, you can define a `class`, `onclick`, `id` or any other attribute you may need.

### Examples

```blade
{{-- Minimal --}}
<x-adminlte-alert>Minimal example</x-adminlte-alert>

{{-- Minimal with title and dismissable --}}
<x-adminlte-alert title="Well done!" dismissable>
    Minimal example
</x-adminlte-alert>

{{-- Minimal with icon only --}}
<x-adminlte-alert icon="fas fa-user">
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
<x-adminlte-alert class="bg-teal text-uppercase" icon="fa fa-lg fa-thumbs-up" title="Done" dismissable>
    Your payment was complete!
</x-adminlte-alert>
```

Use the next image as reference to check how every example is rendered. Please, note in the image the alerts were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them.

![Alert Component](/imgs/components/widget_components/alert-component.png)

# Callout

This component represents an `AdminLTE` styled callout notification. The following attributes are available:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
icon | A `fontawesome` icon for the callout | string | `null` | no
theme | An `AdminLTE` theme: info, success, warning or danger | string | `null` | no
title | The title for the callout | string | `null` | no
title-class | Extra classes for the title container (to customize the title) | string | `null` | no

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
<x-adminlte-callout theme="success" class="bg-teal" icon="fas fa-lg fa-thumbs-up" title="Done">
    <i class="text-dark">Your payment was complete!</i>
</x-adminlte-callout>
<x-adminlte-callout theme="danger" title-class="text-danger text-uppercase"
    icon="fas fa-lg fa-exclamation-circle" title="Payment Error">
    <i>There was an error on the payment procedure!</i>
</x-adminlte-callout>
<x-adminlte-callout theme="info" class="bg-gradient-info" title-class="text-bold text-dark"
    icon="fas fa-lg fa-bell text-dark" title="Notification">
    This is a notification.
</x-adminlte-callout>
<x-adminlte-callout theme="danger" class="bg-gradient-pink" title-class="text-uppercase"
    icon="fas fa-lg fa-leaf text-purple" title="observation">
    <i>A styled observation for the user.</i>
</x-adminlte-callout>
```

Use the next image as reference to check how every example is rendered. Please, note in the image the callouts were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them.

![Callout Component](/imgs/components/widget_components/callout-component.png)

# Card

This component represents an `AdminLTE` card box. The following attributes are available:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
body-class | Additional classes for the `card-body` container (only from <Badge type="tip">v3.6.2</Badge>) | string | `null` | no
collapsible | Enables a collapsible card with a button to collapse/expand it. Use the `'collapsed'` string value to initiate the card on collapsed mode | any | `null` | no
disabled | Disables the card (an overlay will show over the card) | any | `null` | no
footer-class | Additional classes for the `card-footer` container (only from <Badge type="tip">v3.8.1</Badge>) | string | `null` | no
header-class | Additional classes for the `card-header` container (only from <Badge type="tip">v3.8.1</Badge>) | string | `null` | no
icon | A `fontawesome` icon for the card header | string | `null` | no
maximizable | Enables a maximizable card with a button to maximize it | any | `null` | no
removable | Enables a removable card with a button to remove it | any | `null` | no
theme | The card theme: light, dark, primary, secondary, info, success, warning, danger or any other `AdminLTE` color like lighblue or teal | string | `null` | no
theme-mode | The theme mode (`full` or `outline`) | string | `null` | no
title | The title for the card header | string | `null` | no

Any other attribute you define will be directly inserted into the underlying `div.card` element. So, for example, you can define extra classes on the card by using `class`, or use `onclick`, `id` or any other attribute you may need.

### Slots

- **toolsSlot**: Use this slot to add extra elements on the card header (only from <Badge type="tip">v3.8.1</Badge>).
- **footerSlot**: Use this slot to fill the card footer (only from <Badge type="tip">v3.8.1</Badge>).

### Examples

```blade
{{-- Minimal with a title / no body --}}
<x-adminlte-card title="A card without body"/>

{{-- Minimal without header / body only --}}
<x-adminlte-card theme="lime" theme-mode="outline">
    A card without header...
</x-adminlte-card>

{{-- Disabled --}}
<x-adminlte-card title="Disabled Card" theme="teal" disabled>
    A disabled card with teal theme...
</x-adminlte-card>

{{-- Themes --}}
<x-adminlte-card title="Dark Card" theme="dark" icon="fas fa-lg fa-moon">
    A dark theme card...
</x-adminlte-card>
<x-adminlte-card title="Lightblue Card" theme="lightblue" theme-mode="outline"
    icon="fas fa-lg fa-envelope" header-class="text-uppercase rounded-bottom border-info"
    removable>
    A removable card with outline lightblue theme...
</x-adminlte-card>
<x-adminlte-card title="Purple Card" theme="purple" icon="fas fa-lg fa-fan" removable collapsible>
    A removable and collapsible card with purple theme...
</x-adminlte-card>
<x-adminlte-card title="Success Card" theme="success" theme-mode="full" icon="fas fa-lg fa-thumbs-up"
    collapsible="collapsed">
    A collapsible card with full success theme and collapsed...
</x-adminlte-card>
<x-adminlte-card title="Info Card" theme="info" icon="fas fa-lg fa-bell" collapsible removable maximizable>
    An info theme card with all the tool buttons...
</x-adminlte-card>

{{-- Complex / Extra tool / Footer --}}
<x-adminlte-card title="Form Card" theme="maroon" theme-mode="outline"
    class="elevation-3" body-class="bg-maroon" header-class="bg-light"
    footer-class="bg-maroon border-top rounded border-light"
    icon="fas fa-lg fa-bell" collapsible removable maximizable>
    <x-slot name="toolsSlot">
        <select class="custom-select w-auto form-control-border bg-light">
            <option>Skin 1</option>
            <option>Skin 2</option>
            <option>Skin 3</option>
        </select>
    </x-slot>
    <x-adminlte-input name="User" placeholder="Username"/>
    <x-adminlte-input name="Pass" type="password" placeholder="Password"/>
    <x-slot name="footerSlot">
        <x-adminlte-button class="d-flex ml-auto" theme="light" label="submit"
            icon="fas fa-sign-in"/>
    </x-slot>
</x-adminlte-card>
```

Use the next image as reference to check how every example is rendered. Please, note in the image the cards were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them.

![Card Component](/imgs/components/widget_components/card-component.png)

# Info Box

This component represents an `AdminLTE` info box widget. The following attributes are available:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
icon | A Font Awesome icon for the info box | string | `null` | no
icon-theme | The icon wrapper theme (same values as `theme` property) | string | `null` | no
description | A long text/description for the info box | string | `null` | no
progress | Enables a progress bar for the box. The value should be an integer with the percentage of the progress bar | int | `null` | no
progress-theme | The progress bar theme (same values as `theme` property) | string | `null` | no
text | A short text/description for the info box | string | `null` | no
theme | The small box theme: light, dark, primary, secondary, info, success, warning, danger or any other AdminLTE color like lightblue or teal | string | `null` | no
title | A title/header for the info box | string | `null` | no
url | An url for the info box. By default, will be placed on the `title` (only from <Badge type="tip">v3.9.1</Badge>) | string | `null` | no
url-target | The target element where to place the url: `title` (default) or `text` (only from <Badge type="tip">v3.9.1</Badge>) | string | `null` | no

Any other attribute you define will be directly inserted into the underlying `div.info-box` element. So, for example, you can define extra classes using the `class` attribute, use the `onclick`, the `id` or any other attribute you may need.

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

- **`myInfoBox.update(data)`**: To update the data of the info box element. The **data** should be an object with the new attributes, the supported object keys are: `title`, `text`, `description`, `icon` and `progress` (see examples for more details). The `url` attribute may be also updated from version <Badge type="tip">v3.9.1</Badge>.

### Examples

```blade
{{-- Minimal with title, text and icon --}}
<x-adminlte-info-box title="Title" text="some text" icon="far fa-lg fa-star"/>

{{-- Themes --}}
<x-adminlte-info-box title="Views" text="424" icon="fas fa-lg fa-eye text-dark" theme="gradient-teal"/>

<x-adminlte-info-box title="Downloads" text="1205" icon="fas fa-lg fa-download" icon-theme="purple"/>

<x-adminlte-info-box title="528" text="User Registrations" icon="fas fa-lg fa-user-plus text-primary"
    theme="gradient-primary" icon-theme="white"/>

<x-adminlte-info-box title="Tasks" text="75/100" icon="fas fa-lg fa-tasks text-orange" theme="warning"
    icon-theme="dark" progress=75 progress-theme="dark"
    description="75% of the tasks have been completed"/>

{{-- Updatable --}}
<x-adminlte-info-box title="Reputation" text="0/1000" icon="fas fa-lg fa-medal text-dark"
    theme="danger" id="ibUpdatable" progress=0 progress-theme="teal"
    description="0% reputation completed to reach next level"/>

@push('js')
<script>

    $(document).ready(function() {

        let iBox = new _AdminLTE_InfoBox('ibUpdatable');

        let updateIBox = () =>
        {
            // Update data.
            let rep = Math.floor(1000 * Math.random());
            let idx = rep < 100 ? 0 : (rep > 500 ? 2 : 1);
            let progress = Math.round(rep * 100 / 1000);
            let text = rep + '/1000';
            let icon = 'fas fa-lg fa-medal ' + ['text-primary', 'text-light', 'text-warning'][idx];
            let description = progress + '% reputation completed to reach next level';

            let data = {text, icon, description, progress};
            iBox.update(data);
        };

        setInterval(updateIBox, 5000);
    })

</script>
@endpush
```

Use the next image as reference to check how every example is rendered. Please, note in the image the elements were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them.

![Info Box Component](/imgs/components/widget_components/info-box-component.png)

# Profile col Item, Profile Row Item

Both of these components represents an item for the `AdminLTE` profile widget. The main difference is that on the **profile-col-item** the elements are stacked vertically, while on the **profile-row-item** the element are stacked horizontally. The following attributes are available:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
badge | A badge theme for the text attribute. When used, the text attribute will be wrapped inside a badge of the configured theme. | string | `null` | no
icon | A `fontawesome` icon for the item | string | `null` | no
text | The text/description for the item | string | `null` | no
title | The title/header for the item | string | `null` | no
size | The item size. Used to wrap the item inside a `col-size` div | integer | `null` | no
url | An url for the item. By default, it'll be placed on the title attribute | string | `null` | no
url-target | The target element where to place the url: `title` (default) or `text` (only from <Badge type="tip">v3.12.0</Badge>) | string | `'title'` | no

The available themes for the badge are: light, dark, primary, secondary, info, success, warning, danger or any other `AdminLTE` color like lightblue or teal.

> [!TIP]
> From version <Badge type="tip">v3.12.0</Badge> you may prepend the `pill-` token to a theme (for example, `pill-primary`) to get a pill badge instead of a normal badge.

Any other attribute you define will be directly inserted into the underlying `div.col-<size>` element. So, for example, you can define `class`, `onclick`, `id` or any other attribute you may need. To see usage examples, check the [Profile Widget Component](#profile-widget).

# Profile Widget

This component represents an `AdminLTE` profile widget. The following attributes are available:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
cover | A cover image url for the profile header section (overlays the theme) | string | `null` | no
desc | A description for the user profile | string | `null` | no
footer-class | Extra classes for the profile footer (to customize the footer section) | string | `null` | no
icon | To setup the default icon that will be used when no image is provided (only from <Badge type="tip">v3.12.0</Badge>) | string | `'fas fa-user'` | no
img | An image url for the user profile | string | `null` | no
header-class | Extra classes for the profile header (to customize the header section) | string | `null` | no
layout-type | The profile header layout type (`modern` or `classic`). | string | `'modern'` | no
name | The user name of the profile | string | `null` | no
theme | The profile header theme: light, dark, primary, secondary, info, success, warning, danger or any other `AdminLTE` color like lightblue or teal | string | `null` | no

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
    <x-adminlte-profile-col-item class="text-primary border-right" icon="fas fa-lg fa-gift"
        title="Sales" text="25" size=6 badge="primary"/>
    <x-adminlte-profile-col-item class="text-danger" icon="fas fa-lg fa-users" title="Dependents"
        text="10" size=6 badge="danger"/>
</x-adminlte-profile-widget>

<x-adminlte-profile-widget name="Robert Gleeis" desc="Sound Manager" theme="warning"
    img="https://picsum.photos/id/304/100" header-class="text-left" footer-class="bg-gradient-dark">
    <x-adminlte-profile-col-item title="I'm also" text="Artist" size=3
        class="text-orange border-right border-warning"/>
    <x-adminlte-profile-col-item title="Loves" text="Music" size=6
        class="text-orange border-right border-warning"/>
    <x-adminlte-profile-col-item title="Like to" text="Travel" size=3
        class="text-orange"/>
</x-adminlte-profile-widget>

<x-adminlte-profile-widget name="Alice Viorich" desc="Community Manager" theme="purple"
    img="https://picsum.photos/id/454/100" footer-class="bg-gradient-pink">
    <x-adminlte-profile-col-item icon="fab fa-2x fa-instagram" text="Instagram" badge="purple" size=4/>
    <x-adminlte-profile-col-item icon="fab fa-2x fa-facebook" text="Facebook" badge="purple" size=4/>
    <x-adminlte-profile-col-item icon="fab fa-2x fa-twitter" text="Twitter" badge="purple" size=4/>
</x-adminlte-profile-widget>

{{-- Custom --}}
<x-adminlte-profile-widget class="elevation-4" name="Willian Dubling" desc="Web Developer"
    img="https://picsum.photos/id/177/100" cover="https://picsum.photos/id/541/550/200"
    header-class="text-white text-right" footer-class='bg-gradient-dark'>
    <x-adminlte-profile-row-item title="4+ years of experience with"
        class="text-center border-bottom border-secondary"/>
    <x-adminlte-profile-col-item title="Javascript" icon="fab fa-2x fa-js text-orange" size=3/>
    <x-adminlte-profile-col-item title="PHP" icon="fab fa-2x fa-php text-orange" size=3/>
    <x-adminlte-profile-col-item title="HTML5" icon="fab fa-2x fa-html5 text-orange" size=3/>
    <x-adminlte-profile-col-item title="CSS3" icon="fab fa-2x fa-css3 text-orange" size=3/>
    <x-adminlte-profile-col-item title="Angular" icon="fab fa-2x fa-angular text-orange" size=4/>
    <x-adminlte-profile-col-item title="Bootstrap" icon="fab fa-2x fa-bootstrap text-orange" size=4/>
    <x-adminlte-profile-col-item title="Laravel" icon="fab fa-2x fa-laravel text-orange" size=4/>
</x-adminlte-profile-widget>
```

Use the next image as reference to check how every example is rendered. Please, note in the image the elements were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them.

![Profile Widget Component Modern](/imgs/components/widget_components/profile-widget-component-modern.png)

Some examples with **classic** layout:

```blade
{{-- Layout Classic / Minimal --}}
<x-adminlte-profile-widget name="User Name" layout-type="classic"/>

{{-- Layout Classic / Theme --}}
<x-adminlte-profile-widget name="John Doe" desc="Administrator" theme="lightblue"
    img="https://picsum.photos/id/1/100" layout-type="classic">
    <x-adminlte-profile-row-item icon="fas fa-fw fa-user-friends" title="Followers" text="125"
        url="#" badge="teal"/>
    <x-adminlte-profile-row-item icon="fas fa-fw fa-user-friends fa-flip-horizontal" title="Following"
        text="243" url="#" badge="lightblue"/>
    <x-adminlte-profile-row-item icon="fas fa-fw fa-sticky-note" title="Posts" text="37"
        url="#" badge="navy"/>
</x-adminlte-profile-widget>

{{-- Layout Classic / Custom --}}
<x-adminlte-profile-widget name="Roxana Saziadko" desc="Graphic Designer" class="elevation-4"
    img="https://picsum.photos/id/1027/100" cover="https://picsum.photos/id/130/550/200"
    layout-type="classic" header-class="text-right" footer-class="bg-gradient-teal">
    <x-adminlte-profile-col-item class="border-right text-dark" icon="fas fa-lg fa-tasks"
        title="Projects Done" text="25" size=6 badge="lime"/>
    <x-adminlte-profile-col-item class="text-dark" icon="fas fa-lg fa-tasks"
        title="Projects Pending" text="5" size=6 badge="danger"/>
    <x-adminlte-profile-row-item title="Contact me on:" class="text-center text-dark border-bottom"/>
    <x-adminlte-profile-row-item icon="fab fa-fw fa-2x fa-instagram text-dark" title="Instagram"
        url="#" size=4/>
    <x-adminlte-profile-row-item icon="fab fa-fw fa-2x fa-facebook text-dark" title="Facebook"
        url="#" size=4/>
    <x-adminlte-profile-row-item icon="fab fa-fw fa-2x fa-twitter text-dark" title="Twitter"
        url="#" size=4/>
</x-adminlte-profile-widget>
```

Use the next image as reference to check how every example is rendered. Please, note in the image the elements were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them.

![Profile Widget Component Classic](/imgs/components/widget_components/profile-widget-component-classic.png)

# Progress

This component represents an `AdminLTE` styled progress bar. The following attributes are available:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
animated | Enables the animated mode on the progress bar | any | `null` | no
size | The progress bar size (`sm`, `xs` or `xxs`) | string | `null` | no
striped | Enables stripes on the progress bar | any | `null` | no
theme | The progress bar theme: light, dark, primary, secondary, info, success, warning, danger or any other `AdminLTE` color like lightblue or teal | string | `info` | no
value | The progress bar percentage value (integer between 0 and 100) | int | `0` | no
vertical | Enables vertical mode on the progress bar | any | `null` | no
with-label | Enables a percentage label on the progress bar | any | `null` | no

Any other attribute you define will be directly inserted into the underlying `div.progress` element. So, for example, you can define a `class`, `onclick`, `id` or any other attribute you may need.

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

{{-- Custom --}}
<x-adminlte-progress theme="teal" value=75 animated/>
<x-adminlte-progress size="sm" theme="indigo" value=85 animated/>
<x-adminlte-progress theme="pink" value=50 animated with-label/>

{{-- Vertical --}}
<x-adminlte-progress theme="purple" value=40 vertical/>
<x-adminlte-progress theme="orange" value=80 vertical animated/>
<x-adminlte-progress theme="navy" value=70 vertical striped with-label/>
<x-adminlte-progress theme="lime" size="xxs" value=90 vertical/>

{{-- Dinamic Change --}}
<x-adminlte-progress id="pbDinamic" value="5" theme="lighblue" animated with-label/>
{{-- Update the previous progress bar every 2 seconds, incrementing by 10% each step --}}
@push('js')
<script>
    $(document).ready(function() {

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

Use the next image as reference to check how every example is rendered. Please, note in the image the elements were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them.

![Progress Component](/imgs/components/widget_components/progress-component.png)

# Small Box

This component represents an `AdminLTE` small box widget. The following attributes are available:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
icon | A Font Awesome icon for the small box | string | `null` | no
loading | Enables a loading animation (an overlay with a loading icon) | any | `null` | no
text | The text/description for the small box | string | `null` | no
theme | The small box theme: light, dark, primary, secondary, info, success, warning, danger or any other AdminLTE color like lightblue or teal | string | `null` | no
title | The title/header for the small box | string | `null` | no
url | An url for the small box. When enabled, a link-styled footer section will be visible pointing to that url | string | `null` | no
url-text | A text/label associated with the footer url | string | `null` | no

Any other attribute you define will be directly inserted into the underlying `div.small-box` element. So, for example, you can define extra classes using the `class` attribute, use the `onclick`, the `id` or any other attribute you may need.

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
<x-adminlte-small-box title="Title" text="some text" icon="fas fa-star"/>

{{-- Loading --}}
<x-adminlte-small-box title="Loading" text="Loading data..." icon="fas fa-chart-bar"
    theme="info" url="#" url-text="More info" loading/>

{{-- Themes --}}
<x-adminlte-small-box title="424" text="Views" icon="fas fa-eye text-dark"
    theme="teal" url="#" url-text="View details"/>

<x-adminlte-small-box title="Downloads" text="1205" icon="fas fa-download text-white"
    theme="purple"/>

<x-adminlte-small-box title="528" text="User Registrations" icon="fas fa-user-plus text-teal"
    theme="primary" url="#" url-text="View all users"/>

{{-- Updatable --}}
<x-adminlte-small-box title="0" text="Reputation" icon="fas fa-medal text-dark"
    theme="danger" url="#" url-text="Reputation history" id="sbUpdatable"/>

@push('js')
<script>

    $(document).ready(function() {

        let sBox = new _AdminLTE_SmallBox('sbUpdatable');

        let updateBox = () =>
        {
            // Stop loading animation.
            sBox.toggleLoading();

            // Update data.
            let rep = Math.floor(1000 * Math.random());
            let idx = rep < 100 ? 0 : (rep > 500 ? 2 : 1);
            let text = 'Reputation - ' + ['Basic', 'Silver', 'Gold'][idx];
            let icon = 'fas fa-medal ' + ['text-primary', 'text-light', 'text-warning'][idx];
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

Use the next image as reference to check how every example is rendered. Please, note in the image the elements were wrapped inside a [Bootstrap Grid System](https://getbootstrap.com/docs/4.1/layout/grid/) to organize them.

![Small Box Component](/imgs/components/widget_components/small-box-component.png)

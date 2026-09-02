These components are classified under the **Layout** category and represents the pieces that fill the **AdminLTE** page layout. At next you can see the list of available components:

|Components
|-----------
| [Content Header](#content-header)

# Content Header

This component represents the header of the page content, the block that every **AdminLTE v4** reference layout places above the main content with the page title on the left and the breadcrumb trail on the right. The following attributes are available:

Attribute | Description | Type | Default | Required
----------|-------------|------|---------|---------
breadcrumbs | The breadcrumb trail. See [The `breadcrumbs` Attribute](#the-breadcrumbs-attribute) | array | `[]` | no
title | The title of the page | string | `null` | no
title-class | The classes for the title element (replaces the default `mb-0 fs-3` classes used by the AdminLTE v4 reference layouts) | string | `null` | no

Any other attribute you define will be directly inserted into the underlying `div.row` element. So, for example, you can define a `class`, `id` or any other attribute you may need.

> [!Note]
> The component only renders the `.row` of the header, because the layout already wraps the `content_header` section inside the `.app-content-header > .container-fluid` elements. The container of that wrapper is configurable through the `classes_content_header` option of the [layout & styling configuration](/sections/configuration/layout_and_styling#layout).

> [!Note]
> When there is no breadcrumb trail (and no `breadcrumbSlot`), the breadcrumb column is not rendered at all, only the title column is emitted.

### The `breadcrumbs` Attribute

The `breadcrumbs` attribute takes an array of entries. Each entry may be a plain string (used as the label) or an array with the following keys:

Key | Description | Type | Default
----|-------------|------|--------
label | The text of the entry. An entry without label is discarded | string | `null`
url | The URL of the entry. An entry without URL is rendered as plain text | string | `null`
active | Whether the entry is the active one. When not provided, an entry without URL is the active one | bool | `null`

The active entry gets the `active` class and an `aria-current="page"` attribute, as required by the [Bootstrap breadcrumb](https://getbootstrap.com/docs/5.3/components/breadcrumb/) markup.

### Slots

The component provides the following slots:

- **default slot**: Use this slot to fill the title column with your own markup. It takes precedence over the `title` attribute.
- **breadcrumbSlot**: Use this slot to fill the breadcrumb column with your own markup. It takes precedence over the `breadcrumbs` attribute.

### Examples

```blade
{{-- Minimal --}}
@section('content_header')
    <x-adminlte-content-header title="Dashboard"/>
@stop

{{-- With a breadcrumb trail --}}
@section('content_header')
    <x-adminlte-content-header title="Dashboard" :breadcrumbs="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Dashboard'],
    ]"/>
@stop

{{-- With a custom title style --}}
@section('content_header')
    <x-adminlte-content-header title="Users" title-class="mb-0 fs-2 fw-bold"
        :breadcrumbs="['Home', 'Users']"/>
@stop

{{-- With custom markup on both columns --}}
@section('content_header')
    <x-adminlte-content-header>
        <h1 class="mb-0 fs-3">
            Invoice <small class="text-muted">#1042</small>
        </h1>
        <x-slot name="breadcrumbSlot">
            <a class="btn btn-primary btn-sm float-sm-end" href="/invoices">
                Back to the list
            </a>
        </x-slot>
    </x-adminlte-content-header>
@stop
```

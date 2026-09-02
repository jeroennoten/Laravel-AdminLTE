In the particular case that you need full control or customization over the package views, you can publish them with the next command:

```sh
php artisan adminlte:install --only=main_views
```

Now, you can edit the views in the `resources/views/vendor/adminlte` folder to make any customization you want. As a recommendation, do not publish the views if you are not sure what you are doing, or if you do not expect to change the package original views.

> [!Important]
> If you have published the package views, then these ones won't be updated automatically on a package update procedure and you will need to take care of this manually. It is a recommendation to always follow the update procedure explained on section [Updating](/sections/overview/updating), particularly the section [Review the published views](/sections/overview/updating#_3-review-the-package-published-views-optional), if a new version of this package includes changes on these views.

> [!Warning]
> Views published while you were using a **3.x** release of this package carry the **AdminLTE v3 (Bootstrap 4)** markup and **will not render correctly** on **Laravel-AdminLTE v4**. They have to be re-published with `--force` and your customizations re-applied on top of the new files:
>
> ```sh
> php artisan adminlte:install --only=main_views --force
> ```
>
> See the [Upgrading from 3.x](/sections/overview/upgrading_from_3x) page for the full list of breaking changes.

## Error Views

The package ships AdminLTE styled error pages for the http status codes Laravel renders. Publish them with:

```sh
php artisan adminlte:install --only=error_views
```

Each published file is a one-liner that extends the package view, so a package update reaches your error pages without republishing:

```blade
{{-- resources/views/errors/404.blade.php --}}
@extends('adminlte::errors.404')
```

The following views are provided: `401`, `403`, `404`, `419`, `429`, `500` and `503`. All of them extend `adminlte::errors.error-page`, which centers the content, renders the status code in the theme color, and adds a link back to the configured `dashboard_url`. Every text comes from the [translation files](/sections/configuration/translations#accessibility-strings), so the pages follow the application locale out of the box.

### Customizing a single page

Replace the content of the published file with your own sections. The layout yields the following ones:

Section | Purpose
--------|--------
`error_icon` | Replaces the big status code. The `503` view uses it for the maintenance icon
`error_title` | Replaces the heading
`error_message` | Replaces the description paragraph
`error_content` | Extra content between the description and the actions (a search form, a support card, …)
`error_actions` | Replaces the "back to dashboard" button

```blade
{{-- resources/views/errors/404.blade.php --}}
@extends('adminlte::errors.error-page', ['errorCode' => '404', 'errorTheme' => 'primary'])

@section('error_title', 'This page moved')

@section('error_content')
    <form class="row g-2 justify-content-center mb-4" role="search" action="{{ route('search') }}">
        <div class="col-sm-8">
            <x-adminlte-input name="q" placeholder="Search…" fgroup-class="mb-0"/>
        </div>
        <div class="col-sm-auto">
            <x-adminlte-button type="submit" theme="primary" label="Search" class="w-100"/>
        </div>
    </form>
@stop
```

### Building your own status code

Laravel resolves the view by its name, so a status code the package does not cover only needs a file of that name in `resources/views/errors/`:

```blade
{{-- resources/views/errors/402.blade.php --}}
@extends('adminlte::errors.error-page', [
    'errorCode' => '402',
    'errorTheme' => 'warning',
    'errorTitle' => 'Payment required',
    'errorMessage' => 'This workspace needs an active subscription.',
])
```

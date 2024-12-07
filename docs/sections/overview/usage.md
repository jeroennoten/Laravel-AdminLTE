## Introduction

To use the main blade template provided by this package, just create a new blade file and extend the provided **AdminLTE layout** with `@extends('adminlte::page')`. The blade template yields some sections that are classified into two groups:

- **`main`**: Yielded sections that are commonly used when extending the layout.
- **`misc`**: Yielded sections that are useful for covering uncommon cases or particular situations.

Section | Type | Description
--------|------|-------------
`title` | **main** | To fill the content of the `<title>` tag, to define the title of the document that is shown in the browser page's tab
`content_header` | **main** | To fill the header element of the page (will be placed above the main content)
`content` | **main** | To fill all of the main content of the page
`footer` | **main** | To fill the content of the footer section
`right_sidebar` | **main** | To fill the content of the right sidebar (or control sidebar)
`css` | **main** | To add extra style sheets (inside the `<head>` tag)
`js` | **main** | To add extra scripts or javascript code (just before the closing `</body>` tag)
`adminlte_css_pre` | misc | To add custom style sheets before the style sheets required by AdminLTE
`content_top_nav_left` | misc | To add custom elements in the left section of the top navbar.
`content_top_nav_right` | misc | To add custom elements in the right section of the top navbar.
`meta_tags` | misc | To add extra meta tags inside the `<head>` tag
`preloader` | misc | To allow the replacement of the preloader animation default content. Requires package version <Badge type="tip">v3.9.5</Badge> or greater and the preloader animation enabled by configuration
`usermenu_header` | misc | To allow the replacement of the header in the usermenu dropdown by a custom version. Requires an authentication scaffolding and the usermenu enabled by configuration
`usermenu_body` | misc | To add a custom body element into the usermenu dropdown. Requires an authentication scaffolding and the usermenu enabled by configuration

> [!IMPORTANT]
> The `right_sidebar` section was named `right-sidebar` before version <Badge type="tip">v3.14.0</Badge>. So be sure to use the correct name depending on the package version you're using.

All the previously described sections are optional. As a basic example, your most common blade file extending the provided template could look like the following one:

```blade
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop
```

> [!Tip]
> On a fresh **Laravel** installation, and after installing this package, you can replace the `resources/views/welcome.blade.php` file with the previous code for a fast template review.

Now, and as usual, you just return this view from a controller. It's a recommendation to check out [AdminLTE v3](https://adminlte.io/themes/v3/) to find out how to build beautiful content for your admin panel. As a preview, the next image shows what you can get with the previous blade file definition:

> [!Note]
> Screenshot was taken from package version <Badge type="tip">v3.9.4</Badge>.

![laravel-adminlte-layout-example](/imgs/overview/usage/laravel-adminlte-layout-example.png)

More over, this package also provides defaults template views for login and register pages, which can be used with `@extends('adminlte::auth.login')` and `@extends('adminlte::auth.register')`. Read the [Authentication Views](/sections/overview/authentication_views) documentation for more details.

## Tabbed IFrame Mode

> [!Important]
> The **Tabbed IFrame mode** is only available from version <Badge type="tip">v3.7.0</Badge> of this package.

The **IFrame mode** provides the functionality to open the sidebar and top navbar links in a tabbed `iframe` view. You can try this feature on the [AdminLTE demo site](https://adminlte.io/themes/v3/iframe.html) to see what you can get. To use the `IFrame` mode, you should define your main/welcome/root view as just:

```blade
@extends('adminlte::page', ['iFrameEnabled' => true])
```

The documentation of the configuration options available for this mode can be found on [IFrame Mode Configuration](/sections/configuration/iframe_mode). Please, note that all the other blade views of your application should be defined as explained before with the `@extends('adminlte::page')` sentence and just the main entry view should be defined as explained before. Take next image as an example of what you will get:

![laravel-adminlte-iframe-example](/imgs/overview/usage/laravel-adminlte-iframe-example.png)

> [!Tip]
> The previous image was obtained from a fresh **Laravel** installation (after installing this package), by just replacing the `resources/views/welcome.blade.php` file with `@extends('adminlte::page', ['iFrameEnabled' => true])`.

## Recommended Way of Use

Normally, you will likely be extending the provided **AdminLTE blade layout** multiple times in order to create multiple views in your **Laravel** application, and this may lead to duplication of common sections and logic between those views. So, instead, it's recommended to create a new layout for your entire application and put all the common sections and logic there, for example:

#### `resources/views/layout/app.blade.php`

```blade
@extends('adminlte::page')

{{-- Extend and customize the browser title --}}

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

{{-- Extend and customize the page content header --}}

@section('content_header')
    @hasSection('content_header_title')
        <h1 class="text-muted">
            @yield('content_header_title')

            @hasSection('content_header_subtitle')
                <small class="text-dark">
                    <i class="fas fa-xs fa-angle-right text-muted"></i>
                    @yield('content_header_subtitle')
                </small>
            @endif
        </h1>
    @endif
@stop

{{-- Rename section content to content_body --}}

@section('content')
    @yield('content_body')
@stop

{{-- Create a common footer --}}

@section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>

    <strong>
        <a href="{{ config('app.company_url', '#') }}">
            {{ config('app.company_name', 'My company') }}
        </a>
    </strong>
@stop

{{-- Add common Javascript/Jquery code --}}

@push('js')
<script>

    $(document).ready(function() {
        // Add your common script logic here...
    });

</script>
@endpush

{{-- Add common CSS customizations --}}

@push('css')
<style type="text/css">

    {{-- You can add AdminLTE customizations here --}}
    /*
    .card-header {
        border-bottom: none;
    }
    .card-title {
        font-weight: 600;
    }
    */

</style>
@endpush
```

> [!Note]
> The previous defined layout is just an example, but you may use it as a reference to create your own application layout.

Then use your new defined layout for your views, for example:

#### `resources/views/welcome.blade.php`

```blade
@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Welcome')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Welcome')

{{-- Content body: main page content --}}

@section('content_body')
    <p>Welcome to this beautiful admin panel.</p>
@stop

{{-- Push extra CSS --}}

@push('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Push extra scripts --}}

@push('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@endpush
```

That will be rendered like next...

![laravel-adminlte-v3 9 4-app-layout-example](/imgs/overview/usage/laravel-adminlte-v3-9-4-app-layout-example.png)

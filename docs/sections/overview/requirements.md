**Laravel-AdminLTE v4** (the `4.x` release line of this package) requires:

- **Laravel** 12.x or 13.x
- **PHP** 8.2 or higher
- **AdminLTE** 4.x, currently `^4.8`, installed automatically as a composer dependency

> [!Note]
> The `4.x` line of **this package** does not support Laravel 11 or lower, nor PHP 8.1 or lower. If you are stuck on an older framework version, keep using an unmaintained `3.x` release of the package.

> [!Tip]
> Do not confuse **Laravel-AdminLTE v4** (this package) with **AdminLTE v4** (the upstream template it ships). See the [home page](/) for the distinction, and the [Upgrading from 3.x](/sections/overview/upgrading_from_3x) page if you are migrating an existing project.

At runtime, **AdminLTE v4** additionally needs a handful of third party resources that it does not distribute itself (the **Bootstrap 5** JavaScript bundle, the **Bootstrap Icons** font and **OverlayScrollbars**). The package loads them from a CDN out of the box, so there is nothing else to install. See the [assets configuration](/sections/configuration/other#assets) to serve them from your own domain instead.

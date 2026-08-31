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

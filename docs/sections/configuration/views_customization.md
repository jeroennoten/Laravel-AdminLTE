In the particular case that you need full control or customization over the package views, you can publish them with the next command:

```sh
php artisan adminlte:install --only=main_views
```

Now, you can edit the views in the `resources/views/vendor/adminlte` folder to make any customization you want. As a recommendation, do not publish the views if you are not sure what you are doing, or if you do not expect to change the package original views.

> [!Important]
> If you have published the package views, then these ones won't be updated automatically on a package update procedure and you will need to take care of this manually. It is a recommendation to always follow the update procedure explained on section [Updating](/sections/overview/updating), particularly the section [Review the published views](/sections/overview/updating#3-review-the-package-published-views-optional), if a new version of this package includes changes on these views.

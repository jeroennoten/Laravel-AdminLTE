To perform an update on this package, please use the next steps as reference:

### 1. Update the package with composer

On the root folder of your project, update the package with the next composer command:

```sh
composer update jeroennoten/laravel-adminlte
```

### 2. Update the underlying AdminLTE files

Update the underlying `AdminLTE` resources files by executing the next command:

```sh
php artisan adminlte:update
```

> [!Note]
> This command will only update the underlying **AdminLTE distribution files** and its dependencies (`Bootstrap`, `jQuery`, etc.) that were installed in your `public/vendor` folder.

### 3. Review the package published views (optional)

If you had previously [published](/sections/configuration/views_customization) and modified the default `master.blade.php` file, the `page.blade.php` file or any other view provided by this package, then you may need to update them too. Please, note there could be huge updates on those views, so it's highly recommended to **backup** your set of previously published view files.

> [!Tip]
> You may use a comparison tool (like [meld](https://meldmerge.org/)) in order to identify the differences between the previously published files and the new ones. For this, you will need to compare the published files at `resources/views/vendor/adminlte` with the package provided ones, at `vendor/jeroennoten/laravel-adminlte/resources/views/`.

To update the published package views, you may follow next steps:

- Make a copy (or backup) of the previously published views, those inside the folder `resources/views/vendor/adminlte`.

- Publish the new set of views, using the `--force` option to overwrite the already existing files.

  ```sh
  php artisan adminlte:install --only=main_views --force
  ```

- Compare the new installed views with your backup files and redo the modifications you had previously made to those views.

### 4. Review the package configuration (optional)

From time to time, new configuration options may be added or default values may be changed, so it's also a recommendation to verify and update your package configuration file if needed. To update the configuration, you may follow next steps:

- Make a copy (or backup) of your current package configuration file, the `config/adminlte.php` file.

- Publish the new package configuration file and accept the overwrite warning (or use `--force` option to avoid the warning).

  ```sh
  php artisan adminlte:install --only=config
  ```

- Compare the new file with your backup configuration file and redo the modifications you had previously made.

### 5. Update the underlying AdminLTE plugins (very optional)

In the particular case that the package update includes jumping to a new version of the underlying `AdminLTE` package, you may also want to update the plugins you had previously installed using the command `php artisan adminlte:plugins install --plugin=somePlugin`. In this case, you may proceed as explained below.

- Execute the command `php artisan adminlte:plugins` to get a status overview of the previously installed plugins.

- For those ones that you see the `mismatch` legend on the `status` column, you may want to perform an update of the plugin by executing the next command:

  ```sh
  php artisan adminlte:plugins install --plugin=thePluginKeyWithMismatchLegend
  ```

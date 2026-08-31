> [!Important]
> The blade components provided by this package can be published into your project, so you can customize them.
In the particular case that you need full control or customization over the available blade components of the package, you can publish them with the next command:

```sh
php artisan adminlte:install --only=components
```

Now, you can edit the component views in the `resources/views/vendor/adminlte/components` folder and the component classes in the `app/View/Components/Adminlte` folder. To use the published version of any component you will need to follow the Laravel's `dot` convention for accessing nested classes within a directory, for example, the published and customizable version of the [Input Component](/sections/components/basic_forms_components#input) should be used like:

```blade
<x-adminlte.form.input />
```

Instead of:

```blade
<x-adminlte-input />
```

This last one will still reference the original version of the component.

> [!Important]
> If you have published the components, then these ones won't be updated automatically on a package update procedure and you will need to take care of this manually if you want a new feature of the package to be included in your set of published components.

> [!Warning]
> The component views and classes were **rewritten for AdminLTE v4** (Bootstrap 5.3 markup, `data-bs-*` and `data-lte-toggle` attributes, Bootstrap Icons defaults and the new set of underlying plugins). If you published the components while using a `3.x` release of this package, your copies still carry the **Bootstrap 4** markup and will not render correctly. Re-publish them with the `--force` option and then re-apply your customizations:
>
> ```sh
> php artisan adminlte:install --only=components --force
> ```

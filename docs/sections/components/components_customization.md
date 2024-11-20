> [!Important]
> The ability to publish the components resources was introduced in version <Badge type="tip">v3.13.0</Badge>.

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

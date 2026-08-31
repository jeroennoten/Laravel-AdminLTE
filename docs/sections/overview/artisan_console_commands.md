This package provides some artisan commands in order to manage and publish its resources. These commands are explained in the following sections. First, we are going to describe a summary of the available resources and their installation target. The resources are distinguished by a key name and some of they are required by this package, they are listed below:

### Required Resources:

- __`assets`__: The AdminLTE v4 distribution files: the stylesheets (including the RTL and the extended colors variants), the scripts and the default logo image.

  **Target:** The assets will be installed inside the `public/vendor/adminlte` folder of your Laravel project.

- __`config`__: The package configuration file.

  **Target:** The `config/adminlte.php` file will be published on your project.

- __`translations`__: The set of translations files used by the package.

  **Target:** The translations files will be published in the `lang/vendor/adminlte/` folder of your Laravel project.

### Optional Resources:

- __`vendor_assets`__: The third party assets that AdminLTE v4 requires at runtime but does not distribute: the `Bootstrap` JavaScript bundle, the `Bootstrap Icons` font and `OverlayScrollbars`. They are published from the `node_modules` folder of your project, so install them first with `npm i bootstrap bootstrap-icons overlayscrollbars`. When they are not available, the resource is skipped and the package falls back to the CDN locations defined on the [assets configuration](/sections/configuration/other#assets).

  **Target:** The assets will be installed inside the `public/vendor/bootstrap`, `public/vendor/bootstrap-icons` and `public/vendor/overlayscrollbars` folders of your Laravel project.

- __`auth_views`__: A set of AdminLTE styled authentication views to replace the ones provided by the legacy [laravel/ui](https://github.com/laravel/ui) package.

  **Target:** The authentication views will be published inside the `resources/views/auth` folder of your Laravel project.

- __`auth_routes`__: The routes definitions needed for the authentication scaffolding provided by the legacy [laravel/ui](https://github.com/laravel/ui) package.

  **Target:** The routes will be inserted in the `routes/web.php` file of your Laravel project.

- __`main_views`__: The set of blade views that, in conjunction, defines the main layout you usually will extend. You may publish this resource if you need to make some customization on the provided template layout.

  **Target:** The main views will be published in the `resources/views/vendor/adminlte/` folder of your Laravel project.

- __`components`__: The set of blade components provided by this package. You may publish this resource if you need to make a customization in any of the available components.

  **Target:** The components views will be published in the `resources/views/vendor/adminlte/components/` folder of your Laravel project, and the components classes will be published in the `app/View/Components/Adminlte/` folder.

## The `adminlte:install` Command

You can install all the required and some additional package resources using the `php artisan adminlte:install` command. Without any options it will install the AdminLTE package assets, the configuration file and the translations. For the installation of additional resources check the available command options.

### Command Options

- `--force`: Use this option to force the overwrite of any existing files during the installation process.

- `--type=`: Use this option to set the installation type, the available types are: **basic** (the default value), **basic_with_auth** (a basic installation plus the `auth_views` and `auth_routes` resources), **basic_with_views** (a basic installation plus the `main_views` resource) or **full** (a basic installation plus the `auth_views`, `auth_routes`, `main_views` and `components` resources). Note that no installation type includes the optional `vendor_assets` resource, install it with `--with=vendor_assets` or `--only=vendor_assets`.

- `--only=*`: Use this option to install only specific resources, the available resources are: **assets**, **vendor_assets**, **config**, **translations**, **auth_views**, **auth_routes**, **main_views** or **components**. This option can not be used with the `--with` option. Also, you can use this option multiple times, for example:
  ```sh
  php artisan adminlte:install --only=config --only=main_views
  ```

- `--with=*`: Use this option to install with additional resources, the available resources are: **vendor_assets**, **main_views**, **auth_views**, **auth_routes** or **components**. This option can be used multiple times, examples:
  ```sh
  php artisan adminlte:install --with=auth_views --with=auth_routes
  php artisan adminlte:install --with=main_views
  ```

- `--interactive`: Use this option to allow be guided through the installation process and choose what you want to install.

> [!Important]
> When you are upgrading an existing project from a `3.x` release, the `main_views`, `auth_views` and `components` resources **must be re-published with `--force`**. Their AdminLTE v3 (Bootstrap 4) markup does not render correctly on AdminLTE v4. See the [Upgrading from 3.x](/sections/overview/upgrading_from_3x) page.

## The `adminlte:remove` Command

You can uninstall or remove an already published package resource using the `php artisan adminlte:remove {resource}...` command. The command will accept one or more resource names as its arguments. Examples:

```sh
# Remove the published main_views resource.
php artisan adminlte:remove main_views

# Remove multiple resources.
php artisan adminlte:remove main_views auth_views components
```

### Command Options

- `--force`: Use this option to force the removal of a package resource, avoiding confirmations.

- `--interactive`: Use this option to allow be guided through the process.

## The `adminlte:plugins` Command

If you won't use a content delivery network (`CDN`) to include the extra plugins, you can manage them locally with the `php artisan adminlte:plugins` command.

> [!Important]
> AdminLTE v4 does not bundle any third party plugin any more (the AdminLTE v3 `plugins/` folder is gone). The plugins catalogue of this command now publishes the AdminLTE v4 recommended, jQuery free libraries from the `node_modules` folder of your project, so you have to install the related npm package first. When a package is missing, the command tells you the exact `npm i` command to run. The AdminLTE v3 plugin keys are still recognized and the command reports their v4 replacement.

You can **list**, **install** or **remove** all the available plugins at once or some specifics plugins. It is recommended to first check which plugins are available by executing the command `php artisan adminlte:plugins` (the output of this command is similar to the one explained for the [adminlte:status command](#the-adminlte-status-command)). Note that after a plugin is installed locally, you still need to setup it on the configuration file in order to use it, refer to the [Plugins](/sections/configuration/plugins) section to checkout how to configure a plugin. Here are some examples that helps to explain the command options:

- List the status of all the available plugins:
  ```sh
  php artisan adminlte:plugins
  php artisan adminlte:plugins list
  ```
- List the status of the specified plugins:
  ```sh
  php artisan adminlte:plugins --plugin=flatpickr --plugin=tomSelect
  ```
- Install all the available plugins:
  ```sh
  php artisan adminlte:plugins install
  ```
- Install only the Flatpickr & Quill plugins:
  ```sh
  php artisan adminlte:plugins install --plugin=flatpickr --plugin=quill
  ```
- Remove all the available plugins:
  ```sh
  php artisan adminlte:plugins remove
  ```
- Remove only the Quill plugin:
  ```sh
  php artisan adminlte:plugins remove --plugin=quill
  ```

### Command Arguments

 - `operation`: The type of the operation to execute: **list** (default), **install** or **remove**.

### Command Options

 - `--plugin=*`: Use this option to apply the operation only over the specified plugins, the value of the option should be a plugin key. The option can be used multiple times.
 - `--force`: Use this option to force the overwrite of existing files.
 - `--interactive`: Use this option to enable be guided through the operation process and choose what you want to do on each step.

## The `adminlte:update` Command

This command is a shortcut for `php artisan adminlte:install --force --only=assets`, extended with two conveniences:

- When the optional `vendor_assets` resource was **previously published**, it is refreshed too, so the command becomes `php artisan adminlte:install --force --only=assets --only=vendor_assets`. When it was never published, it is left alone (the package falls back to the CDN for those resources).

- When the `main_views` resource was **previously published** and it differs from the one currently provided by the package, the command prints a warning telling you that your published layout views are outdated and may require a manual update.

> [!Note]
> This command only updates the assets located on the `public/vendor` folder. It will not update any other package resource (the configuration file, the translations or the published views), refer to section [Updating](/sections/overview/updating) to check how to make a complete update.

## The `adminlte:status` Command

This command is very useful to check the package resources installation status, to run it execute the command:

```sh
php artisan adminlte:status
```

Once completed, it will display a table with all the available package resources and they installation status. The status can be one of the nexts:

- **Installed**: This means that the resource was published/installed and exactly match with the original resource provided by the package.

- **Mismatch**: This means that the resource was published but mismatches with the original resource provided by the package. This can happen due to an update available or when you have made some local customization or change on the published resource.

- **Not Installed**: This means that the package resource is not installed or published.

The table also shows a column which tells what resources are required for the package to work correctly. So, for these resources you should read **Installed** or **Mismatch** on the status column, otherwise the package won't work.

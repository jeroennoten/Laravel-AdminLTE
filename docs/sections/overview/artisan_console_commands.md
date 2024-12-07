This package provides some artisan commands in order to manage and publish its resources. These commands are explained in the following sections. First, we are going to describe a summary of the available resources and their installation target. The resources are distinguished by a key name and some of they are required by this package, they are listed below:

### Required Resources:

- __`assets`__: The set of assets required by the AdminLTE package, including the AdminLTE package distribution files and its dependencies, like `Bootstrap`, `jQuery` and `Font Awesome`.

  **Target:** The assets will be installed inside the `public/vendor` folder of your Laravel project.

- __`config`__: The package configuration file.

  **Target:** The `config/adminlte.php` file will be published on your project.

- __`translations`__: The set of translations files used by the package.

  **Target:** The translations files will be published in the `resources/lang/vendor/adminlte/` folder of your Laravel project, or in `lang/vendor/adminlte` folder for `Laravel 9+` versions.

### Optional Resources:

- __`auth_views`__: A set of AdminLTE styled authentication views to replace the ones provided by the legacy [laravel/ui](https://github.com/laravel/ui) package.

  **Target:** The authentication views will be published inside the `resources/views/auth` folder of your Laravel project.

- __`auth_routes`__: The routes definitions needed for the authentication scaffolding provided by the legacy [laravel/ui](https://github.com/laravel/ui) package.

  **Target:** The routes will be inserted in the `routes/web.php` file of your Laravel project.

- __`main_views`__: The set of blade views that, in conjunction, defines the main layout you usually will extend. You may publish this resource if you need to make some customization on the provided template layout.

  **Target:** The main views will be published in the `resources/views/vendor/adminlte/` folder of your Laravel project.

- __`components`__: (Only from <Badge type="tip">v3.13.0</Badge>) The set of blade components provided by this package. You may publish this resource if you need to make a customization in any of the available components.

  **Target:** The components views will be published in the `resources/views/vendor/adminlte/components/` folder of your Laravel project, and the components classes will be published in the `app/View/Components/Adminlte/` folder.

## The `adminlte:install` Command

You can install all the required and some additional package resources using the `php artisan adminlte:install` command. Without any options it will install the AdminLTE package assets, the configuration file and the translations. For the installation of additional resources check the available command options.

### Command Options

- `--force`: Use this option to force the overwrite of any existing files during the installation process.

- `--type=`: Use this option to set the installation type, the available types are: **basic** (the default value), **basic_with_auth** (a basic installation plus the `auth_views` and `auth_routes` resources), **basic_with_views** (a basic installation plus the `main_views` resource) or **full** (all resources).

- `--only=*`: Use this option to install only specific resources, the available resources are: **assets**, **config**, **translations**, **auth_views**, **auth_routes**, **main_views** or **components**. This option can not be used with the `--with` option. Also, you can use this option multiple times, for example:
  ```sh
  php artisan adminlte:install --only=config --only=main_views
  ```

- `--with=*`: Use this option to install with additional resources, the available resources are: **main_views**, **auth_views**, **auth_routes** or **components**. This option can be used multiple times, examples:
  ```sh
  php artisan adminlte:install --with=auth_views --with=auth_routes
  php artisan adminlte:install --with=main_views
  ```

- `--interactive`: Use this option to allow be guided through the installation process and choose what you want to install.

> [!IMPORTANT]
> Prior to version <Badge type="tip">v3.12.0</Badge> the resource **`auth_routes`** was named **`basic_routes`**, and the available installation types were: **basic**, **enhanced** (a basic installation plus the `auth_views` resource) and **full** (all resources except the `main_views`). Also, the **`components`** resource was introduced in version <Badge type="tip">v3.13.0</Badge>.

## The `adminlte:remove` Command

> [!IMPORTANT]
> This command was introduced in version <Badge type="tip">v3.13.0</Badge>.

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

If you won't use the content delivery network (`CDN`) to include new plugins, then you are able to manage some optional plugins that already comes with the underlying **AdminLTE** package with the `php artisan adminlte:plugins` command.
You can **list**, **install** or **remove** all the available plugins at once or some specifics plugins. It is recommended to first check which plugins are available by executing the command `php artisan adminlte:plugins` (the output of this command is similar to the one explained for the [adminlte:status command](#the-adminlte-status-command)). Note that after a plugin is installed locally, you still need to setup it on the configuration file in order to use it, refer to the [Plugins](/sections/configuration/plugins) section to checkout how to configure a plugin. Here are some examples that helps to explain the command options:

- List the status of all the available plugins:
  ```sh
  php artisan adminlte:plugins
  php artisan adminlte:plugins list
  ```
- List the status of the specified plugins:
  ```sh
  php artisan adminlte:plugins --plugin=datatables --plugin=select2
  ```
- Install all the available plugins:
  ```sh
  php artisan adminlte:plugins install
  ```
- Install only Pace Progress & Select2 plugins:
  ```sh
  php artisan adminlte:plugins install --plugin=paceProgress --plugin=select2
  ```
- Remove all the available plugins:
  ```sh
  php artisan adminlte:plugins remove
  ```
- Remove only Select2 plugin:
  ```sh
  php artisan adminlte:plugins remove --plugin=select2
  ```

### Command Arguments

 - `operation`: The type of the operation to execute: **list** (default), **install** or **remove**.

### Command Options

 - `--plugin=*`: Use this option to apply the operation only over the specified plugins, the value of the option should be a plugin key. The option can be used multiple times.
 - `--force`: Use this option to force the overwrite of existing files.
 - `--interactive`: Use this option to enable be guided through the operation process and choose what you want to do on each step.

## The `adminlte:update` Command

This command is only a shortcut for `php artisan adminlte:install --force --only=assets`.

> [!Note]
> This command will only update the AdminLTE assets located on the `public/vendor` folder. It will not update any other package resources, refer to section [Updating](/sections/overview/updating) to check how to make a complete update.

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

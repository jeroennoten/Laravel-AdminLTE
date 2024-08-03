<?php

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\AdminlteAssetsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\AuthRoutesResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\AuthViewsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\BladeComponentsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\ConfigResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\LayoutViewsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\PackageResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\TranslationsResource;
use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;

class CommandTestCase extends TestCase
{
    /**
     * Array to store the set of package resources that may be published.
     *
     * @var array
     */
    protected $resources;

    /**
     * Setup this testing class.
     */
    public function setUp(): void
    {
        parent::setUp();

        // Setup the resources array.

        $this->resources = [
            'assets' => new AdminlteAssetsResource(),
            'config' => new ConfigResource(),
            'translations' => new TranslationsResource(),
            'main_views' => new LayoutViewsResource(),
            'auth_views' => new AuthViewsResource(),
            'auth_routes' => new AuthRoutesResource(),
            'components' => new BladeComponentsResource(),
        ];
    }

    /**
     * Gets the array of resources or a specific package resource.
     *
     * @param  string  $name  Name of the specific resource to get
     * @return array
     */
    protected function getResources($name = null)
    {
        return $name ? $this->resources[$name] : $this->resources;
    }

    /**
     * Creates a set of dummy files for the specified resource.
     *
     * @param  string  $name  The name of the resource
     * @param  PackageResource  $resource  The package resource instance
     * @return void
     */
    protected function createDummyResource($name, $resource)
    {
        // Uninstall the package resource. We need a clean target location
        // before creating dummy files.

        $resource->uninstall();

        // Create dummy files on the target location. This way, an overwrite
        // warning will be fired when trying to install the resource later.

        $target = $resource->target;

        if ($name === 'assets') {
            $target = $target.DIRECTORY_SEPARATOR.'adminlte';
            File::EnsureDirectoryExists($target);
        } elseif ($name === 'config') {
            $this->createDummyFile($target);
        } elseif ($name === 'translations') {
            $target = $target.DIRECTORY_SEPARATOR.'en/adminlte.php';
            $this->createDummyFile($target);
        } elseif ($name === 'main_views') {
            $target = $target.DIRECTORY_SEPARATOR.'master.blade.php';
            $this->createDummyFile($target);
        } elseif ($name === 'auth_views') {
            $target = $target.DIRECTORY_SEPARATOR.'login.blade.php';
            $loginContent = '@extends(\'adminlte::auth.login\')';
            $this->createDummyFile($target, $loginContent);
        } elseif ($name === 'auth_routes') {
            $stubFile = CommandHelper::getStubPath('routes.stub');
            $content = File::get($stubFile);
            $this->createDummyFile($target, $content);
        } elseif ($name === 'components') {
            $view = $target['views'].DIRECTORY_SEPARATOR.'form/input.blade.php';
            $this->createDummyFile($view);
            $class = $target['classes'].DIRECTORY_SEPARATOR.'Form/Input.php';
            $this->createDummyFile($class);
        }
    }

    /**
     * Creates a dummy file with some content at the specified path.
     *
     * @param  string  $filePath  The file path
     * @param  string  $content  The file content
     * @return void
     */
    protected function createDummyFile($filePath, $content = null)
    {
        $content = $content ?? 'dummy-content';
        File::ensureDirectoryExists(File::dirname($filePath));
        File::put($filePath, $content);
    }

    /**
     * Installs the required AdminLTE assets files ("vendor/almasaeed2010")
     * into the laravel testing project.
     *
     * @return void
     */
    protected function installVendorAssets()
    {
        $resource = CommandHelper::getPackagePath('vendor/almasaeed2010');
        $target = base_path('vendor/almasaeed2010');

        // Check if vendor assets are already installed.

        if (File::exists($target)) {
            return;
        }

        // Ensure the vendor folder exists on the laravel testing project. If
        // vendor folder do not exists, create it.

        File::ensureDirectoryExists(base_path('vendor'));

        // Create a symbolic link to the required vendor assets.

        File::link($resource, $target);
    }

    /**
     * Returns whether the expectsConfirmation() method is supported by the
     * underlying Laravel framework.
     *
     * @return bool
     */
    protected function canExpectsConfirmation()
    {
        return class_exists('Illuminate\Testing\PendingCommand')
            && method_exists(
                'Illuminate\Testing\PendingCommand',
                'expectsConfirmation'
            );
    }
}

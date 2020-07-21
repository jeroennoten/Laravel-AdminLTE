<?php

use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\AssetsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\ConfigResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\TranslationsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\MainViewsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\AuthViewsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\BasicViewsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\BasicRoutesResource;

class InstallOptionWithTest extends CommandTestCase
{
    /**
     * Tests over the --with=main_views option.
     */

    public function testInstallWithMainViews()
    {
        $assetsRes = new AssetsResource();
        $configRes = new ConfigResource();
        $transRes = new TranslationsResource();
        $viewsRes = new MainViewsResource();

        $assetsTarget = $assetsRes->get('target');
        $configTarget = $configRes->get('target');
        $transTarget = $transRes->get('target');
        $viewsTarget = $viewsRes->get('target');

        // Ensure the required vendor assets exists.

        $this->installVendorAssets();

        // Ensure the target resources do not exists.

        $this->ensureResourcesNotExists(
            $assetsTarget, $configTarget, $transTarget, $viewsTarget
        );

        // Install resources using the artisan command.

        $this->artisan('adminlte:install --with=main_views');

        // Assert that the resources are installed.

        $this->assertTrue($assetsRes->installed());
        $this->assertTrue($configRes->installed());
        $this->assertTrue($transRes->installed());
        $this->assertTrue($viewsRes->installed());

        // Clear installed resources.

        $this->ensureResourcesNotExists(
            $assetsTarget, $configTarget, $transTarget, $viewsTarget
        );
    }

    /**
     * Tests over the --with=auth_views option.
     */

    public function testInstallWithAuthViews()
    {
        $assetsRes = new AssetsResource();
        $configRes = new ConfigResource();
        $transRes = new TranslationsResource();
        $viewsRes = new AuthViewsResource();

        $assetsTarget = $assetsRes->get('target');
        $configTarget = $configRes->get('target');
        $transTarget = $transRes->get('target');
        $viewsTarget = $viewsRes->get('target');

        // Ensure the required vendor assets exists.

        $this->installVendorAssets();

        // Ensure the target resources do not exists.

        $this->ensureResourcesNotExists(
            $assetsTarget, $configTarget, $transTarget, $viewsTarget
        );

        // Install resources using the artisan command.

        $this->artisan('adminlte:install --with=auth_views');

        // Assert that the resources are installed.

        $this->assertTrue($assetsRes->installed());
        $this->assertTrue($configRes->installed());
        $this->assertTrue($transRes->installed());
        $this->assertTrue($viewsRes->installed());

        // Clear installed resources.

        $this->ensureResourcesNotExists(
            $assetsTarget, $configTarget, $transTarget, $viewsTarget
        );
    }

    /**
     * Tests over the --with=basic_views option.
     */

    public function testInstallWithBasicViews()
    {
        $assetsRes = new AssetsResource();
        $configRes = new ConfigResource();
        $transRes = new TranslationsResource();
        $viewsRes = new BasicViewsResource();

        $assetsTarget = $assetsRes->get('target');
        $configTarget = $configRes->get('target');
        $transTarget = $transRes->get('target');
        $viewsTarget = $viewsRes->get('target');

        // Ensure the required vendor assets exists.

        $this->installVendorAssets();

        // Ensure the target resources do not exists.

        $this->ensureResourcesNotExists(
            $assetsTarget, $configTarget, $transTarget
        );

        foreach ($viewsRes->get('source') as $file => $stub) {
            $this->ensureResourceNotExists($viewsTarget.'/'.$file);
        }

        // Install resources using the artisan command.

        $this->artisan('adminlte:install --with=basic_views');

        // Assert that the resources are installed.

        $this->assertTrue($assetsRes->installed());
        $this->assertTrue($configRes->installed());
        $this->assertTrue($transRes->installed());
        $this->assertTrue($viewsRes->installed());

        // Clear installed resources.

        $this->ensureResourcesNotExists(
            $assetsTarget, $configTarget, $transTarget
        );

        foreach ($viewsRes->get('source') as $file => $stub) {
            $this->ensureResourceNotExists($viewsTarget.'/'.$file);
        }
    }

    /**
     * Tests over the --with=basic_routes option.
     */

    public function testInstallWithBasicRoutes()
    {
        $assetsRes = new AssetsResource();
        $configRes = new ConfigResource();
        $transRes = new TranslationsResource();
        $routesRes = new BasicRoutesResource();

        $assetsTarget = $assetsRes->get('target');
        $configTarget = $configRes->get('target');
        $transTarget = $transRes->get('target');
        $routesTarget = $routesRes->get('target');

        // Ensure the required vendor assets exists.

        $this->installVendorAssets();

        // Ensure the target resources do not exists.

        $this->ensureResourcesNotExists(
            $assetsTarget, $configTarget, $transTarget, $routesTarget
        );

        // Install resources using the artisan command.

        $this->artisan('adminlte:install --with=basic_routes');

        // Assert that the resources are installed.

        $this->assertTrue($assetsRes->installed());
        $this->assertTrue($configRes->installed());
        $this->assertTrue($transRes->installed());
        $this->assertTrue($routesRes->installed());

        // Clear installed resources.

        $this->ensureResourcesNotExists(
            $assetsTarget, $configTarget, $transTarget, $routesTarget
        );
    }

    /**
     * Tests over the --with using multiple option.
     */

    public function testInstallWithMultipleResources()
    {
        $assetsRes = new AssetsResource();
        $configRes = new ConfigResource();
        $transRes = new TranslationsResource();
        $routesRes = new BasicRoutesResource();
        $viewsRes = new AuthViewsResource();

        $assetsTarget = $assetsRes->get('target');
        $configTarget = $configRes->get('target');
        $transTarget = $transRes->get('target');
        $routesTarget = $routesRes->get('target');
        $viewsTarget = $viewsRes->get('target');

        // Ensure the required vendor assets exists.

        $this->installVendorAssets();

        // Ensure the target resources do not exists.

        $this->ensureResourcesNotExists(
            $assetsTarget, $configTarget, $transTarget,
            $routesTarget, $viewsTarget
        );

        // Install resources using the artisan command.

        $this->artisan('adminlte:install --type=enhanced --with=basic_routes --with=auth_views');

        // Assert that the resources are installed.

        $this->assertTrue($assetsRes->installed());
        $this->assertTrue($configRes->installed());
        $this->assertTrue($transRes->installed());
        $this->assertTrue($routesRes->installed());
        $this->assertTrue($viewsRes->installed());

        // Clear installed resources.

        $this->ensureResourcesNotExists(
            $assetsTarget, $configTarget, $transTarget,
            $routesTarget, $viewsTarget
        );
    }
}

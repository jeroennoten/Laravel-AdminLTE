<?php

use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\AssetsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\ConfigResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\TranslationsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\AuthViewsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\BasicViewsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\BasicRoutesResource;

class InstallOptionTypeTest extends CommandTestCase
{
    /**
     * Tests over the --type=basic (default) option.
     */

    public function testInstallWithoutType()
    {
        $assetsRes = new AssetsResource();
        $configRes = new ConfigResource();
        $transRes = new TranslationsResource();

        $assetsTarget = $assetsRes->get('target');
        $configTarget = $configRes->get('target');
        $transTarget = $transRes->get('target');

        // Ensure the required vendor assets exists.

        $this->installVendorAssets();

        // Ensure the target resources do not exists.

        $this->ensureResourcesNotExists(
            $assetsTarget, $configTarget, $transTarget
        );

        // Install resources using the artisan command.

        $this->artisan('adminlte:install');

        // Assert that the resources are installed.

        $this->assertTrue($assetsRes->installed());
        $this->assertTrue($configRes->installed());
        $this->assertTrue($transRes->installed());

        // Clear installed resources.

        $this->ensureResourcesNotExists(
            $assetsTarget, $configTarget, $transTarget
        );
    }

    /**
     * Tests over the --type=enhanced option.
     */

    public function testInstallTypeEnhanced()
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

        $this->artisan('adminlte:install --type=enhanced');

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
     * Tests over the --type=full option.
     */

    public function testInstallTypeFull()
    {
        $assetsRes = new AssetsResource();
        $configRes = new ConfigResource();
        $transRes = new TranslationsResource();
        $authViewsRes = new AuthViewsResource();
        $basicViewsRes = new BasicViewsResource();
        $routesRes = new BasicRoutesResource();

        $assetsTarget = $assetsRes->get('target');
        $configTarget = $configRes->get('target');
        $transTarget = $transRes->get('target');
        $authViewsTarget = $authViewsRes->get('target');
        $routesTarget = $routesRes->get('target');

        $basicViewsTargets = array_map(
            function($v) use ($basicViewsRes) {
                return $basicViewsRes->get('target').'/'.$v;
            },
            array_keys($basicViewsRes->get('source'))
        );

        // Ensure the required vendor assets exists.

        $this->installVendorAssets();

        // Ensure the target resources do not exists.

        $this->ensureResourcesNotExists(
            $assetsTarget,
            $configTarget,
            $transTarget,
            $authViewsTarget,
            $routesTarget,
            ...$basicViewsTargets
        );

        // Install resources using the artisan command.

        $this->artisan('adminlte:install --type=full');

        // Assert that the resources are installed.

        $this->assertTrue($assetsRes->installed());
        $this->assertTrue($configRes->installed());
        $this->assertTrue($transRes->installed());
        $this->assertTrue($authViewsRes->installed());
        $this->assertTrue($basicViewsRes->installed());
        $this->assertTrue($routesRes->installed());

        // Clear installed resources.

        $this->ensureResourcesNotExists(
            $assetsTarget,
            $configTarget,
            $transTarget,
            $authViewsTarget,
            $routesTarget,
            ...$basicViewsTargets
        );
    }
}

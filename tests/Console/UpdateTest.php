<?php

use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\AssetsResource;

class UpdateTest extends CommandTestCase
{
    public function testUpdateAssets()
    {
        $res = new AssetsResource();
        $target = $res->get('target');

        // Ensure the required vendor assets exists.

        $this->installVendorAssets();

        // Ensure the target resources do not exists.

        $this->ensureResourceNotExists($target);

        // Update resource using the artisan command.

        $this->artisan('adminlte:update');
        $this->assertTrue($res->installed());

        // Clear installed resources.

        $this->ensureResourceNotExists($target);
    }

    public function testUpdateAssetsOverwrite()
    {
        $res = new AssetsResource();
        $target = $res->get('target');

        // Ensure the required vendor assets exists.

        $this->installVendorAssets();

        // Ensure a target resource exists.

        $adminAssetTarget = $res->get('source')['adminlte']['target'];
        $this->ensureResourceExists($adminAssetTarget);

        // Update resource using the artisan command.

        $this->artisan('adminlte:update');
        $this->assertTrue($res->installed());

        // Clear installed resources.

        $this->ensureResourceNotExists($target);
    }
}

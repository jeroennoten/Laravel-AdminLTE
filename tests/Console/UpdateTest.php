<?php

class UpdateTest extends CommandTestCase
{
    public function testUpdateAssets()
    {
        $res = $this->getResources('assets');

        // Ensure the required vendor assets exists.

        $this->installVendorAssets();

        // Ensure the target resource do not exists.

        $res->uninstall();

        // Update resource using the artisan command.

        $this->artisan('adminlte:update');
        $this->assertTrue($res->installed());

        // Clear installed resources.

        $res->uninstall();
        $this->assertFalse($res->installed());
    }

    public function testUpdateAssetsOverwrite()
    {
        $res = $this->getResources('assets');

        // Ensure the required vendor assets exists.

        $this->installVendorAssets();

        // Ensure a target resource exists.

        $this->createDummyResource('assets', $res);

        // Update resource using the artisan command.

        $this->artisan('adminlte:update');
        $this->assertTrue($res->installed());

        // Clear installed resources.

        $res->uninstall();
        $this->assertFalse($res->installed());
    }

    public function testUpdateShowsMainViewsWarning()
    {
        // Ensure the main views resources already exists.

        $res = $this->getResources('main_views');
        $this->createDummyResource('main_views', $res);

        // Update the package using the artisan command and check there is a
        // warning on the output.

        Artisan::call('adminlte:update');

        $this->assertStringContainsString(
            'Outdated layout views',
            Artisan::output()
        );

        // Clear installed resources.

        $res->uninstall();
        $this->assertFalse($res->installed());

        $res = $this->getResources('assets');

        $res->uninstall();
        $this->assertFalse($res->installed());
    }
}

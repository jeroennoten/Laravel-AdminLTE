<?php

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\VendorAssetsResource;

class VendorAssetsTest extends CommandTestCase
{
    /**
     * The third party assets resource instance.
     *
     * @var VendorAssetsResource
     */
    protected $vendorAssets;

    /**
     * Setup this testing class.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->vendorAssets = new VendorAssetsResource();

        // Ensure a clean state, the third party assets are published from the
        // node modules folder of the application, which does not exists on
        // the testing application.

        File::deleteDirectory(base_path('node_modules'));
        $this->vendorAssets->uninstall();
    }

    /**
     * Tear down this testing class.
     */
    public function tearDown(): void
    {
        File::deleteDirectory(base_path('node_modules'));
        $this->vendorAssets->uninstall();

        parent::tearDown();
    }

    /*
    |--------------------------------------------------------------------------
    | Helper methods.
    |--------------------------------------------------------------------------
    */

    /**
     * Creates a fake node modules folder holding the npm packages required by
     * the third party assets of the resource.
     *
     * @param  array  $only  The keys of the assets to fake (all by default)
     * @return void
     */
    protected function makeFakeNodeModules($only = null)
    {
        $assets = $this->vendorAssets->getSourceData();

        foreach ($assets as $key => $asset) {
            if (isset($only) && ! in_array($key, $only)) {
                continue;
            }

            foreach ($asset['resources'] as $res) {
                $path = $asset['source'].DIRECTORY_SEPARATOR.$res['source'];
                $this->createDummyFile($path.DIRECTORY_SEPARATOR.'dummy.js');
            }
        }
    }

    /**
     * Gets the set of published files of the specified third party asset.
     *
     * @param  string  $key  The asset key
     * @return array
     */
    protected function getPublishedFiles($key)
    {
        $asset = $this->vendorAssets->getSourceData($key);
        $files = [];

        foreach ($asset['resources'] as $res) {
            $target = $res['target'] ?? $res['source'];
            $target = $asset['target'].DIRECTORY_SEPARATOR.$target;
            $files[] = $target.DIRECTORY_SEPARATOR.'dummy.js';
        }

        return $files;
    }

    /*
    |--------------------------------------------------------------------------
    | Tests without the node modules folder.
    |--------------------------------------------------------------------------
    */

    public function testResourceData()
    {
        // The resource is optional, since the package provides a CDN fallback
        // for all the third party assets.

        $this->assertFalse($this->vendorAssets->required);
        $this->assertNotEmpty($this->vendorAssets->description);
        $this->assertEquals(public_path('vendor'), $this->vendorAssets->target);

        // Check the third party assets data.

        $assets = $this->vendorAssets->getSourceData();

        $this->assertCount(3, $assets);
        $this->assertArrayHasKey('bootstrap', $assets);
        $this->assertArrayHasKey('bootstrap-icons', $assets);
        $this->assertArrayHasKey('overlayscrollbars', $assets);

        // Check the data of a specific asset.

        $asset = $this->vendorAssets->getSourceData('bootstrap');

        $this->assertEquals('bootstrap', $asset['package']);
        $this->assertEquals(public_path('vendor/bootstrap'), $asset['target']);

        // An unknown asset key returns no data.

        $this->assertEquals([], $this->vendorAssets->getSourceData('dummy'));
    }

    public function testAllTheAssetsAreMissingWithoutTheNodeModulesFolder()
    {
        $missing = $this->vendorAssets->getMissingAssets();

        $this->assertCount(3, $missing);
        $this->assertContains('bootstrap', $missing);
        $this->assertContains('bootstrap-icons', $missing);
        $this->assertContains('overlayscrollbars', $missing);

        // The command to install the missing npm packages is suggested.

        $cmd = $this->vendorAssets->getInstallPackagesCommand();

        $this->assertStringStartsWith('npm i ', $cmd);
        $this->assertStringContainsString('bootstrap@', $cmd);
        $this->assertStringContainsString('bootstrap-icons@', $cmd);
        $this->assertStringContainsString('overlayscrollbars@', $cmd);
    }

    public function testInstallWithoutTheNodeModulesFolder()
    {
        // Without the npm packages there is nothing to publish, but the
        // installation does not fail.

        $this->vendorAssets->install();

        $this->assertFalse($this->vendorAssets->exists());
        $this->assertFalse($this->vendorAssets->installed());
    }

    public function testTheSuccessMessageNotifiesTheSkippedAssets()
    {
        $msg = $this->vendorAssets->getInstallMessage('success');

        $this->assertStringContainsString('Some third party assets were skipped', $msg);
        $this->assertStringContainsString('Bootstrap 5', $msg);
        $this->assertStringContainsString('Bootstrap Icons', $msg);
        $this->assertStringContainsString('OverlayScrollbars', $msg);
        $this->assertStringContainsString('CDN fallback', $msg);
        $this->assertStringContainsString($this->vendorAssets->getInstallPackagesCommand(), $msg);
        $this->assertStringContainsString('adminlte:install --only=vendor_assets', $msg);

        // The other messages are not adapted.

        $this->assertStringContainsString(
            'Do you want to publish',
            $this->vendorAssets->getInstallMessage('install')
        );

        $this->assertStringContainsString(
            'already published',
            $this->vendorAssets->getInstallMessage('overwrite')
        );

        $this->assertNull($this->vendorAssets->getInstallMessage('dummy'));
    }

    public function testUninstallWithoutPublishedAssets()
    {
        // Uninstalling a resource that was never published is a no-op.

        $this->vendorAssets->uninstall();

        $this->assertFalse($this->vendorAssets->exists());
    }

    /*
    |--------------------------------------------------------------------------
    | Tests with the node modules folder.
    |--------------------------------------------------------------------------
    */

    public function testInstallAndUninstallWithTheNodeModulesFolder()
    {
        $this->makeFakeNodeModules();

        // With all the npm packages available, nothing is missing.

        $this->assertEmpty($this->vendorAssets->getMissingAssets());
        $this->assertNull($this->vendorAssets->getInstallPackagesCommand());

        // So, the success message is not adapted.

        $this->assertStringNotContainsString(
            'skipped',
            $this->vendorAssets->getInstallMessage('success')
        );

        // Install the resource and check the published files.

        $this->vendorAssets->install();

        $this->assertTrue($this->vendorAssets->exists());
        $this->assertTrue($this->vendorAssets->installed());

        foreach (['bootstrap', 'bootstrap-icons', 'overlayscrollbars'] as $key) {
            foreach ($this->getPublishedFiles($key) as $file) {
                $this->assertFileExists($file);
            }
        }

        // Now uninstall the resource.

        $this->vendorAssets->uninstall();

        $this->assertFalse($this->vendorAssets->exists());
        $this->assertFalse($this->vendorAssets->installed());
    }

    public function testInstallWithSomeAvailableAssets()
    {
        $this->makeFakeNodeModules(['bootstrap']);

        // Only the assets without their npm package are missing.

        $missing = $this->vendorAssets->getMissingAssets();

        $this->assertCount(2, $missing);
        $this->assertNotContains('bootstrap', $missing);

        // The suggested npm command only installs the missing packages.

        $cmd = $this->vendorAssets->getInstallPackagesCommand();

        $this->assertStringContainsString('bootstrap-icons@', $cmd);
        $this->assertStringContainsString('overlayscrollbars@', $cmd);
        $this->assertStringNotContainsString(' bootstrap@', $cmd);

        // The success message notifies about the skipped assets only.

        $msg = $this->vendorAssets->getInstallMessage('success');

        $this->assertStringContainsString('Bootstrap Icons', $msg);
        $this->assertStringContainsString('OverlayScrollbars', $msg);
        $this->assertStringNotContainsString('Bootstrap 5', $msg);

        // The available assets are published, and the resource is considered
        // as installed (the skipped assets are not verified).

        $this->vendorAssets->install();

        $this->assertTrue($this->vendorAssets->exists());
        $this->assertTrue($this->vendorAssets->installed());

        foreach ($this->getPublishedFiles('bootstrap') as $file) {
            $this->assertFileExists($file);
        }

        foreach ($this->getPublishedFiles('bootstrap-icons') as $file) {
            $this->assertFileDoesNotExist($file);
        }
    }

    public function testAMismatchOnThePublishedAssetsIsDetected()
    {
        $this->makeFakeNodeModules();
        $this->vendorAssets->install();

        $this->assertTrue($this->vendorAssets->installed());

        // Change one of the published files.

        $file = $this->getPublishedFiles('bootstrap')[0];
        File::put($file, 'modified-content');

        $this->assertTrue($this->vendorAssets->exists());
        $this->assertFalse($this->vendorAssets->installed());

        // Publishing the resource again fixes the mismatch.

        $this->vendorAssets->install();

        $this->assertTrue($this->vendorAssets->installed());
    }

    public function testAMissingPublishedFileIsDetected()
    {
        $this->makeFakeNodeModules();
        $this->vendorAssets->install();

        // Remove one of the published files, but keep the target folder.

        File::delete($this->getPublishedFiles('overlayscrollbars')[0]);

        $this->assertTrue($this->vendorAssets->exists());
        $this->assertFalse($this->vendorAssets->installed());
    }

    /*
    |--------------------------------------------------------------------------
    | Tests of the artisan commands.
    |--------------------------------------------------------------------------
    */

    public function testInstallCommandWithOnlyVendorAssets()
    {
        $this->makeFakeNodeModules();

        $this->artisan('adminlte:install --only=vendor_assets --force')
             ->expectsOutput($this->vendorAssets->getInstallMessage('success'))
             ->assertExitCode(0);

        $this->assertTrue($this->vendorAssets->installed());
    }

    public function testInstallCommandWithOnlyVendorAssetsAndOverwrite()
    {
        $this->makeFakeNodeModules();

        // Publish the resource, and then modify it.

        $this->vendorAssets->install();
        File::put($this->getPublishedFiles('bootstrap')[0], 'modified-content');

        // Without the --force option, an overwrite confirmation is expected.

        $this->artisan('adminlte:install --only=vendor_assets')
             ->expectsConfirmation(
                 $this->vendorAssets->getInstallMessage('overwrite'),
                 'no'
             )
             ->assertExitCode(0);

        $this->assertFalse($this->vendorAssets->installed());

        // Now confirm the overwrite.

        $this->artisan('adminlte:install --only=vendor_assets')
             ->expectsConfirmation(
                 $this->vendorAssets->getInstallMessage('overwrite'),
                 'yes'
             )
             ->assertExitCode(0);

        $this->assertTrue($this->vendorAssets->installed());
    }

    public function testInstallCommandWithOnlyVendorAssetsInteractively()
    {
        $this->makeFakeNodeModules();

        // Reject the installation.

        $this->artisan('adminlte:install --only=vendor_assets --interactive')
             ->expectsConfirmation(
                 $this->vendorAssets->getInstallMessage('install'),
                 'no'
             )
             ->assertExitCode(0);

        $this->assertFalse($this->vendorAssets->exists());

        // Accept the installation.

        $this->artisan('adminlte:install --only=vendor_assets --interactive')
             ->expectsConfirmation(
                 $this->vendorAssets->getInstallMessage('install'),
                 'yes'
             )
             ->assertExitCode(0);

        $this->assertTrue($this->vendorAssets->installed());
    }

    public function testInstallCommandWithTheVendorAssetsAsAdditionalResource()
    {
        $this->makeFakeNodeModules();
        $this->installVendorAssets();

        // The third party assets can be added to a regular installation.

        $this->artisan('adminlte:install --with=vendor_assets --force')
             ->assertExitCode(0);

        $this->assertTrue($this->vendorAssets->installed());

        // Clear the other installed resources.

        foreach (['assets', 'config', 'translations'] as $name) {
            $this->getResources($name)->uninstall();
        }
    }

    public function testTheVendorAssetsAreNotInstalledByDefault()
    {
        $this->makeFakeNodeModules();
        $this->installVendorAssets();

        $this->artisan('adminlte:install --force')->assertExitCode(0);

        $this->assertFalse($this->vendorAssets->exists());

        // Clear the installed resources.

        foreach (['assets', 'config', 'translations'] as $name) {
            $this->getResources($name)->uninstall();
        }
    }

    public function testRemoveCommandWithTheVendorAssets()
    {
        $this->makeFakeNodeModules();
        $this->vendorAssets->install();

        $this->assertTrue($this->vendorAssets->exists());

        $this->artisan('adminlte:remove vendor_assets --force')
             ->assertExitCode(0);

        $this->assertFalse($this->vendorAssets->exists());
    }
}

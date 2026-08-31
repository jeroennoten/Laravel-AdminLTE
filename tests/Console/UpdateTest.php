<?php

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\VendorAssetsResource;

class UpdateTest extends CommandTestCase
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

    /**
     * Creates a fake node modules folder holding the npm packages required by
     * the third party assets resource.
     *
     * @return void
     */
    protected function makeFakeNodeModules()
    {
        foreach ($this->vendorAssets->getSourceData() as $asset) {
            foreach ($asset['resources'] as $res) {
                $path = $asset['source'].DIRECTORY_SEPARATOR.$res['source'];
                $this->createDummyFile($path.DIRECTORY_SEPARATOR.'dummy.js');
            }
        }
    }

    /**
     * Gets the path of a file published by the bootstrap third party asset.
     *
     * @return string
     */
    protected function getPublishedVendorFile()
    {
        $asset = $this->vendorAssets->getSourceData('bootstrap');
        $res = $asset['resources'][0];

        return implode(DIRECTORY_SEPARATOR, [
            $asset['target'],
            $res['target'] ?? $res['source'],
            'dummy.js',
        ]);
    }

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

    public function testUpdateDoesNotShowTheMainViewsWarningWhenNotPublished()
    {
        $this->installVendorAssets();

        // Ensure the main views were not published.

        $res = $this->getResources('main_views');
        $res->uninstall();

        Artisan::call('adminlte:update');

        $this->assertStringNotContainsString(
            'Outdated layout views',
            Artisan::output()
        );

        // Clear installed resources.

        $this->getResources('assets')->uninstall();
    }

    public function testUpdateSkipsTheVendorAssetsWhenTheyWereNotPublished()
    {
        $this->installVendorAssets();
        $this->makeFakeNodeModules();

        // The third party assets are optional, so they are only refreshed
        // when they were published before.

        $this->assertFalse($this->vendorAssets->exists());

        Artisan::call('adminlte:update');
        $output = Artisan::output();

        $this->assertFalse($this->vendorAssets->exists());
        $this->assertFalse($this->vendorAssets->installed());

        $this->assertStringNotContainsString(
            'Third party asset files published successfully',
            $output
        );

        // The AdminLTE assets are always updated.

        $res = $this->getResources('assets');
        $this->assertTrue($res->installed());

        $res->uninstall();
    }

    public function testUpdateRefreshesThePublishedVendorAssets()
    {
        $this->installVendorAssets();
        $this->makeFakeNodeModules();

        // Publish the third party assets, and then break one of them.

        $this->vendorAssets->install();
        $this->assertTrue($this->vendorAssets->installed());

        File::delete($this->getPublishedVendorFile());

        $this->assertTrue($this->vendorAssets->exists());
        $this->assertFalse($this->vendorAssets->installed());

        // Now the update command should refresh them.

        Artisan::call('adminlte:update');

        $this->assertTrue($this->vendorAssets->installed());
        $this->assertFileExists($this->getPublishedVendorFile());

        // Clear installed resources.

        $this->getResources('assets')->uninstall();
    }

    public function testUpdateOfThePublishedVendorAssetsWithoutTheNpmPackages()
    {
        $this->installVendorAssets();
        $this->makeFakeNodeModules();

        // Publish the third party assets and then remove the npm packages.

        $this->vendorAssets->install();
        File::deleteDirectory(base_path('node_modules'));

        // The update should not fail, the published files are just kept as
        // they are (the missing packages can't be republished).

        Artisan::call('adminlte:update');
        $output = Artisan::output();

        $this->assertTrue($this->vendorAssets->exists());
        $this->assertStringContainsString('Some third party assets were skipped', $output);

        // Clear installed resources.

        $this->getResources('assets')->uninstall();
    }
}

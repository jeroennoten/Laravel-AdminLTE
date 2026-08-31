<?php

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\VendorAssetsResource;

class StatusTest extends CommandTestCase
{
    /**
     * The names of all the resources listed by the status command.
     *
     * @var array
     */
    protected $statusResources = [
        'assets', 'vendor_assets', 'config', 'translations', 'main_views',
        'auth_views', 'auth_routes', 'components',
    ];

    /**
     * Tear down this testing class.
     */
    public function tearDown(): void
    {
        File::deleteDirectory(base_path('node_modules'));
        (new VendorAssetsResource())->uninstall();

        parent::tearDown();
    }

    /*
    |--------------------------------------------------------------------------
    | Helper methods.
    |--------------------------------------------------------------------------
    */

    /**
     * Runs the status command and returns its output.
     *
     * @return string
     */
    protected function getStatusOutput()
    {
        Artisan::call('adminlte:status');

        return Artisan::output();
    }

    /**
     * Gets the status table row of the specified resource.
     *
     * @param  string  $output  The output of the status command
     * @param  string  $name  The name of the package resource
     * @return string
     */
    protected function getStatusRow($output, $name)
    {
        foreach (explode(PHP_EOL, $output) as $line) {
            if (preg_match("/^\|\s*{$name}\s*\|/", $line)) {
                return $line;
            }
        }

        return '';
    }

    /*
    |--------------------------------------------------------------------------
    | Tests.
    |--------------------------------------------------------------------------
    */

    public function testBasicStatus()
    {
        $configRes = $this->getResources('config');
        $transRes = $this->getResources('translations');

        // Install some resources.

        $this->artisan('adminlte:install --only=config --only=translations');

        // Change the config file.

        $this->createDummyResource('config', $configRes);

        // Ensure state is correct.

        $this->assertFalse($configRes->installed());
        $this->assertTrue($transRes->installed());

        // Run the check of the resources status.

        $this->artisan('adminlte:status')
             ->expectsOutput('Verifying the installation of the resources...')
             ->expectsOutput('All resources verified successfully!')
             ->assertExitCode(0);

        // Clear installed resources.

        $configRes->uninstall();
        $transRes->uninstall();
    }

    public function testStatusListsAllThePackageResources()
    {
        $output = $this->getStatusOutput();

        // Every package resource has a row on the status table.

        foreach ($this->statusResources as $name) {
            $this->assertNotEmpty(
                $this->getStatusRow($output, $name),
                "The resource {$name} is not listed on the status table"
            );
        }

        // The table headers and the status legends are displayed too.

        $this->assertStringContainsString('Package Resource', $output);
        $this->assertStringContainsString('Publishing Target', $output);
        $this->assertStringContainsString('Required', $output);
        $this->assertStringContainsString('Status Legends:', $output);
        $this->assertStringContainsString('The resource is not published', $output);
    }

    public function testStatusOfTheUninstalledResources()
    {
        // Ensure all the resources are uninstalled.

        foreach ($this->getResources() as $resource) {
            $resource->uninstall();
        }

        (new VendorAssetsResource())->uninstall();

        $output = $this->getStatusOutput();

        foreach ($this->statusResources as $name) {
            $this->assertStringContainsString(
                'Not Installed',
                $this->getStatusRow($output, $name),
                "The resource {$name} is not reported as uninstalled"
            );
        }
    }

    public function testStatusOfAnInstalledResource()
    {
        $configRes = $this->getResources('config');
        $configRes->uninstall();

        // The resource is not installed yet.

        $row = $this->getStatusRow($this->getStatusOutput(), 'config');
        $this->assertStringContainsString('Not Installed', $row);

        // Now install the resource.

        $this->artisan('adminlte:install --only=config --force');
        $this->assertTrue($configRes->installed());

        $row = $this->getStatusRow($this->getStatusOutput(), 'config');

        $this->assertStringContainsString('Installed', $row);
        $this->assertStringNotContainsString('Not Installed', $row);
        $this->assertStringNotContainsString('Mismatch', $row);

        // Clear installed resources.

        $configRes->uninstall();
    }

    public function testStatusOfAMismatchedResource()
    {
        $configRes = $this->getResources('config');

        // Create a dummy resource on the target location, so the published
        // resource differs from the package one.

        $this->createDummyResource('config', $configRes);

        $this->assertTrue($configRes->exists());
        $this->assertFalse($configRes->installed());

        $row = $this->getStatusRow($this->getStatusOutput(), 'config');

        $this->assertStringContainsString('Mismatch', $row);
        $this->assertStringNotContainsString('Not Installed', $row);

        // Clear installed resources.

        $configRes->uninstall();
    }

    public function testStatusOfTheRequiredResources()
    {
        $output = $this->getStatusOutput();

        // The third party assets are the only optional resource, all the
        // other ones are required.

        $this->assertStringContainsString(
            'no',
            $this->getStatusRow($output, 'vendor_assets')
        );

        $this->assertStringContainsString(
            'yes',
            $this->getStatusRow($output, 'assets')
        );
    }

    public function testStatusOfTheVendorAssets()
    {
        $vendorAssets = new VendorAssetsResource();
        $vendorAssets->uninstall();

        // Without the npm packages, the third party assets can't be published.

        $row = $this->getStatusRow($this->getStatusOutput(), 'vendor_assets');
        $this->assertStringContainsString('Not Installed', $row);

        // Fake the npm packages and publish the third party assets.

        foreach ($vendorAssets->getSourceData() as $asset) {
            foreach ($asset['resources'] as $res) {
                $path = $asset['source'].DIRECTORY_SEPARATOR.$res['source'];
                $this->createDummyFile($path.DIRECTORY_SEPARATOR.'dummy.js');
            }
        }

        $vendorAssets->install();
        $this->assertTrue($vendorAssets->installed());

        $row = $this->getStatusRow($this->getStatusOutput(), 'vendor_assets');
        $this->assertStringContainsString('Installed', $row);
        $this->assertStringNotContainsString('Not Installed', $row);

        // Now break one of the published files.

        $asset = $vendorAssets->getSourceData('bootstrap');
        $res = $asset['resources'][0];
        $file = implode(DIRECTORY_SEPARATOR, [
            $asset['target'],
            $res['target'] ?? $res['source'],
            'dummy.js',
        ]);

        File::put($file, 'modified-content');

        $row = $this->getStatusRow($this->getStatusOutput(), 'vendor_assets');
        $this->assertStringContainsString('Mismatch', $row);
    }
}

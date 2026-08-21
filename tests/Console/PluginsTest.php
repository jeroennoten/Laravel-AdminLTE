<?php

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\PluginsResource;

class PluginsTest extends CommandTestCase
{
    /**
     * The plugin keys used by the tests that target specific plugins.
     *
     * @var array
     */
    protected $testPlugins = ['flatpickr', 'quill'];

    /**
     * Setup this testing class.
     */
    public function setUp(): void
    {
        parent::setUp();

        // AdminLTE v4 does not bundle any third party plugin, the plugins are
        // published from the node modules folder of the application. So, we
        // need to fake that folder in order to test the plugins resource.

        $this->makeFakeNodeModules();
    }

    /**
     * Tear down this testing class.
     */
    public function tearDown(): void
    {
        File::deleteDirectory(base_path('node_modules'));

        parent::tearDown();
    }

    /*
    |--------------------------------------------------------------------------
    | Helper methods.
    |--------------------------------------------------------------------------
    */

    /**
     * Creates a dummy node modules folder holding the npm packages required by
     * all the plugins of the plugins resource.
     *
     * @return void
     */
    protected function makeFakeNodeModules()
    {
        $plugins = (new PluginsResource())->getSourceData();

        foreach ($plugins as $plugin) {
            foreach ($this->getPluginSources($plugin) as $source) {
                $path = base_path("node_modules/{$source}");

                if (preg_match('/\.(js|css)$/', $source)) {
                    $this->createDummyFile($path, 'dummy');
                } else {
                    $this->createDummyFile("{$path}/dummy.js", 'dummy');
                }
            }
        }
    }

    /**
     * Gets the set of source paths (relative to the node modules folder) that
     * are published by the specified plugin.
     *
     * @param  array  $plugin  An array with the plugin data
     * @return array
     */
    protected function getPluginSources($plugin)
    {
        if (isset($plugin['resources'])) {
            return array_column($plugin['resources'], 'source');
        }

        return isset($plugin['source']) ? [$plugin['source']] : [];
    }

    /*
    |--------------------------------------------------------------------------
    | Basic tests.
    |--------------------------------------------------------------------------
    */

    public function testPluginsResourceWithInvalidPluginKey()
    {
        $plugins = new PluginsResource();

        $this->assertFalse($plugins->exists('dummy'));
        $this->assertFalse($plugins->installed('dummy'));
        $this->assertFalse($plugins->pluginAvailable('dummy'));
        $this->assertNull($plugins->getInstallPackageCommand('dummy'));
        $this->assertFalse($plugins->getLegacyPluginReplacement('dummy'));
    }

    public function testWithInvalidOperation()
    {
        $this->artisan('adminlte:plugins dummy-op')
             ->expectsOutput('The specified operation: dummy-op is not valid!')
             ->assertExitCode(0);
    }

    public function testLegacyPluginKeysAreResolved()
    {
        $plugins = new PluginsResource();

        // A legacy plugin with a replacement.

        $this->assertEquals('quill', $plugins->getLegacyPluginReplacement('summernote'));

        // A legacy plugin without replacement (covered natively now).

        $this->assertNull($plugins->getLegacyPluginReplacement('icheckBootstrap'));
    }

    public function testInstallLegacyPluginNotifiesTheReplacement()
    {
        $this->artisan('adminlte:plugins install --plugin=summernote --force')
             ->expectsOutput('The plugin: summernote is not available on AdminLTE v4!')
             ->expectsOutput("Use the 'quill' plugin instead.")
             ->assertExitCode(0);
    }

    public function testInstallPluginWithoutTheNpmPackage()
    {
        $plugins = new PluginsResource();

        // Remove the fake node modules folder to simulate a package that was
        // not installed by the final user.

        File::deleteDirectory(base_path('node_modules'));

        $this->assertFalse($plugins->pluginAvailable('flatpickr'));

        $this->artisan('adminlte:plugins install --plugin=flatpickr --force')
             ->expectsOutput($plugins->getInstallPackageCommand('flatpickr')
                 ? 'Install them first with: '.$plugins->getInstallPackageCommand('flatpickr')
                 : '')
             ->assertExitCode(0);

        $this->assertFalse($plugins->installed('flatpickr'));
    }

    /*
    |--------------------------------------------------------------------------
    | Tests over operation = install / remove.
    |--------------------------------------------------------------------------
    */

    public function testInstallAndUninstallAllPlugins()
    {
        $plugins = new PluginsResource();
        $pluginsKeys = array_keys($plugins->getSourceData());

        // Uninstall all the plugins.

        foreach ($pluginsKeys as $pKey) {
            $plugins->uninstall($pKey);
        }

        // Test install all the plugins.

        $this->artisan('adminlte:plugins install --force');

        // Check that all the plugins are installed.

        foreach ($pluginsKeys as $pKey) {
            $this->assertTrue($plugins->installed($pKey));
        }

        // Test uninstall all the plugins.

        $this->artisan('adminlte:plugins remove');

        // Check that all the plugins are removed.

        foreach ($pluginsKeys as $pKey) {
            $this->assertFalse($plugins->installed($pKey));
        }
    }

    public function testInstallAndUninstallSpecificPlugins()
    {
        $plugins = new PluginsResource();
        $pluginsKeys = $this->testPlugins;

        // Uninstall the plugins.

        foreach ($pluginsKeys as $pKey) {
            $plugins->uninstall($pKey);
        }

        // Test install the plugins.

        $this->artisan('adminlte:plugins install --plugin=flatpickr --plugin=dummy --plugin=quill --force')
             ->expectsOutput('The plugin key: dummy is not valid!');

        // Check that the plugins are installed.

        foreach ($pluginsKeys as $pKey) {
            $this->assertTrue($plugins->installed($pKey));
        }

        // Test uninstall the plugins.

        $this->artisan('adminlte:plugins remove --plugin=flatpickr --plugin=dummy --plugin=quill')
             ->expectsOutput('The plugin key: dummy is not valid!');

        // Check that the plugins are removed.

        foreach ($pluginsKeys as $pKey) {
            $this->assertFalse($plugins->installed($pKey));
        }
    }

    public function testInstallAndUninstallSpecificPLuginInteractively()
    {
        $plugins = new PluginsResource();
        $pluginKey = 'flatpickr';
        $installMsg = strtr(
            $plugins->getInstallMessage('install'),
            [':plugin' => $pluginKey]
        );
        $removeMsg = strtr(
            $plugins->getInstallMessage('remove'),
            [':plugin' => $pluginKey]
        );

        // Uninstall the plugin.

        $plugins->uninstall($pluginKey);

        // Test install with --interactive option (response with no).

        $this->artisan("adminlte:plugins install --plugin={$pluginKey} --interactive --force")
             ->expectsConfirmation($installMsg, 'no');

        $this->assertFalse($plugins->installed($pluginKey));

        // Test install with --interactive option (response with yes).

        $this->artisan("adminlte:plugins install --plugin={$pluginKey} --interactive --force")
             ->expectsConfirmation($installMsg, 'yes');

        $this->assertTrue($plugins->installed($pluginKey));

        // Test uninstall with --interactive option (response with no).

        $this->artisan("adminlte:plugins remove --plugin={$pluginKey} --interactive")
             ->expectsConfirmation($removeMsg, 'no');

        $this->assertTrue($plugins->installed($pluginKey));

        // Test uninstall with --interactive option (response with yes).

        $this->artisan("adminlte:plugins remove --plugin={$pluginKey} --interactive")
             ->expectsConfirmation($removeMsg, 'yes');

        $this->assertFalse($plugins->installed($pluginKey));
    }

    public function testInstallAndUninstallSpecificPluginWithOverwrite()
    {
        $plugins = new PluginsResource();
        $pluginKey = 'flatpickr';
        $overwriteMsg = strtr(
            $plugins->getInstallMessage('overwrite'),
            [':plugin' => $pluginKey]
        );

        // Create the plugin folder to force an overwrite.

        File::ensureDirectoryExists(public_path('vendor/flatpickr'));

        // Test install when an overwrite will occurs (response with no).

        $this->artisan("adminlte:plugins install --plugin={$pluginKey}")
             ->expectsConfirmation($overwriteMsg, 'no');

        $this->assertFalse($plugins->installed($pluginKey));

        // Test install when an overwrite will occurs (response with yes).

        $this->artisan("adminlte:plugins install --plugin={$pluginKey}")
             ->expectsConfirmation($overwriteMsg, 'yes');

        $this->assertTrue($plugins->installed($pluginKey));

        // Clear installed resources.

        $plugins->uninstall($pluginKey);
    }

    /*
    |--------------------------------------------------------------------------
    | Tests over operation = list (default)
    |--------------------------------------------------------------------------
    */

    public function testGetAllPluginStatus()
    {
        $plugins = new PluginsResource();

        // Install some plugins.

        $plugins->install('flatpickr');
        File::ensureDirectoryExists(public_path('vendor/quill'));

        // Ensure state is correct.

        $this->assertTrue($plugins->installed('flatpickr'));
        $this->assertFalse($plugins->installed('quill'));
        $this->assertTrue($plugins->exists('quill'));

        // Run the check of the plugin status.

        $this->artisan('adminlte:plugins')
             ->expectsOutput('Verifying the installation of the plugins...')
             ->assertExitCode(0);

        // Clear installed resources.

        $plugins->uninstall('flatpickr');
        File::deleteDirectory(public_path('vendor/quill'));
    }

    public function testSpecificPluginStatus()
    {
        $plugins = new PluginsResource();

        // Install some plugins.

        $plugins->install('flatpickr');

        // Ensure state is correct.

        $this->assertTrue($plugins->installed('flatpickr'));

        // Run the check of the plugin status.

        $this->artisan('adminlte:plugins --plugin=flatpickr --plugin=dummy')
             ->expectsOutput('Verifying the installation of the plugins...')
             ->expectsOutput('The plugin key: dummy is not valid!')
             ->expectsOutput('All plugins verified successfully!')
             ->assertExitCode(0);

        // Clear installed resources.

        $plugins->uninstall('flatpickr');
    }
}

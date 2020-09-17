<?php

use JeroenNoten\LaravelAdminLte\Console\PackageResources\PluginsResource;

class PluginsTest extends CommandTestCase
{
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
    }

    public function testWithInvalidOperation()
    {
        $this->artisan('adminlte:plugins dummy-op')
             ->expectsOutput('The specified operation: dummy-op is not valid!')
             ->assertExitCode(0);
    }

    /*
    |--------------------------------------------------------------------------
    | Tests over operation = install / remove.
    |--------------------------------------------------------------------------
    */

    public function testInstallAndUninstallAll()
    {
        $plugins = new PluginsResource();
        $pluginsKeys = array_keys($plugins->getSourceData());

        // Uninstall all the plugins.

        foreach ($pluginsKeys as $pKey) {
            $plugins->uninstall($pKey);
        }

        // Test install all the plugins.

        $this->artisan('adminlte:plugins install');

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

    public function testInstallAndUninstallSpecific()
    {
        $plugins = new PluginsResource();
        $pluginsKeys = ['datatables', 'icheckBootstrap'];

        // Uninstall he plugins.

        foreach ($pluginsKeys as $pKey) {
            $plugins->uninstall($pKey);
        }

        // Test install the plugins.

        $this->artisan('adminlte:plugins install --plugin=datatables --plugin=dummy --plugin=icheckBootstrap')
             ->expectsOutput('The plugin key: dummy is not valid!');

        // Check that the plugins are installed.

        foreach ($pluginsKeys as $pKey) {
            $this->assertTrue($plugins->installed($pKey));
        }

        // Test uninstall the plugins.

        $this->artisan('adminlte:plugins remove --plugin=datatables --plugin=dummy --plugin=icheckBootstrap')
             ->expectsOutput('The plugin key: dummy is not valid!');

        // Check that the plugins are removed.

        foreach ($pluginsKeys as $pKey) {
            $this->assertFalse($plugins->installed($pKey));
        }
    }

    public function testInstallAndUninstallSpecificInteractive()
    {
        $plugins = new PluginsResource();
        $pluginKey = 'icheckBootstrap';
        $installMsg = strtr(
            $plugins->getInstallMessage('install'),
            [':plugin' => $pluginKey]
        );
        $removeMsg = strtr(
            $plugins->getInstallMessage('remove'),
            [':plugin' => $pluginKey]
        );

        // We can't do these test on old laravel versions.

        if (! class_exists('Illuminate\Testing\PendingCommand')) {
            $this->assertTrue(true);

            return;
        }

        if (! method_exists('Illuminate\Testing\PendingCommand', 'expectsConfirmation')) {
            $this->assertTrue(true);

            return;
        }

        // Uninstall the plugin.

        $plugins->uninstall($pluginKey);

        // Test install with --interactive option (response with no).

        $this->artisan('adminlte:plugins install --plugin=icheckBootstrap --interactive')
             ->expectsConfirmation($installMsg, 'no');

        $this->assertFalse($plugins->installed($pluginKey));

        // Test install with --interactive option (response with yes).

        $this->artisan('adminlte:plugins install --plugin=icheckBootstrap --interactive')
             ->expectsConfirmation($installMsg, 'yes');

        $this->assertTrue($plugins->installed($pluginKey));

        // Test uninstall with --interactive option (response with no).

        $this->artisan('adminlte:plugins remove --plugin=icheckBootstrap --interactive')
             ->expectsConfirmation($removeMsg, 'no');

        $this->assertTrue($plugins->installed($pluginKey));

        // Test uninstall with --interactive option (response with yes).

        $this->artisan('adminlte:plugins remove --plugin=icheckBootstrap --interactive')
             ->expectsConfirmation($removeMsg, 'yes');

        $this->assertFalse($plugins->installed($pluginKey));
    }

    public function testInstallAndUninstallSpecificOverwrite()
    {
        $plugins = new PluginsResource();
        $pluginKey = 'icheckBootstrap';
        $overwriteMsg = strtr(
            $plugins->getInstallMessage('overwrite'),
            [':plugin' => $pluginKey]
        );

        // We can't do these test on old laravel versions.

        if (! class_exists('Illuminate\Testing\PendingCommand')) {
            $this->assertTrue(true);

            return;
        }

        if (! method_exists('Illuminate\Testing\PendingCommand', 'expectsConfirmation')) {
            $this->assertTrue(true);

            return;
        }

        // Create plugin folder to force overwrite.

        mkdir(public_path('vendor/icheck-bootstrap'));

        // Test install when an overwrite will occurs (response with no).

        $this->artisan('adminlte:plugins install --plugin=icheckBootstrap')
             ->expectsConfirmation($overwriteMsg, 'no');

        $this->assertFalse($plugins->installed($pluginKey));

        // Test install when an overwrite will occurs (response with yes).

        $this->artisan('adminlte:plugins install --plugin=icheckBootstrap')
             ->expectsConfirmation($overwriteMsg, 'yes');

        $this->assertTrue($plugins->installed($pluginKey));

        // Clear installed resources.

        $plugins->uninstall('icheckBootstrap');
    }

    /*
    |--------------------------------------------------------------------------
    | Tests over operation = list (default)
    |--------------------------------------------------------------------------
    */

    public function testAllPluginStatus()
    {
        $plugins = new PluginsResource();

        // Install some plugins.

        $plugins->install('icheckBootstrap');
        mkdir(public_path('vendor/datatables'));

        // Ensure state is correct.

        $this->assertTrue($plugins->installed('icheckBootstrap'));
        $this->assertFalse($plugins->installed('datatables'));
        $this->assertTrue($plugins->exists('datatables'));

        // Run the check of the plugin status.

        $this->artisan('adminlte:plugins')
             ->expectsOutput('Checking the plugins installation ...')
             ->expectsOutput('All plugins checked succesfully!')
             ->assertExitCode(0);

        // Clear installed resources.

        $plugins->uninstall('icheckBootstrap');
        rmdir(public_path('vendor/datatables'));
    }

    public function testSpecificPluginStatus()
    {
        $plugins = new PluginsResource();

        // Install some plugins.

        $plugins->install('icheckBootstrap');

        // Ensure state is correct.

        $this->assertTrue($plugins->installed('icheckBootstrap'));

        // Run the check of the plugin status.

        $this->artisan('adminlte:plugins --plugin=icheckBootstrap --plugin=dummy')
             ->expectsOutput('Checking the plugins installation ...')
             ->expectsOutput('The plugin key: dummy is not valid!')
             ->expectsOutput('All plugins checked succesfully!')
             ->assertExitCode(0);

        // Clear installed resources.

        $plugins->uninstall('icheckBootstrap');
    }
}

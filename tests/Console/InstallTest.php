<?php

class InstallTest extends CommandTestCase
{
    /*
    |--------------------------------------------------------------------------
    | Basic tests.
    |--------------------------------------------------------------------------
    */

    public function testResourceGetInstallMsg()
    {
        $res = $this->getResources('config');

        $this->assertNotNull($res->getInstallMessage('install'));
        $this->assertEmpty($res->getInstallMessage('foo'));
    }

    public function testInstallWithInvalidOption()
    {
        $this->artisan('adminlte:install --only=dummy')
             ->expectsOutput('The option --only=dummy is invalid!')
             ->assertExitCode(0);
    }

    /*
    |--------------------------------------------------------------------------
    | Tests over the --only=<resource> option.
    |--------------------------------------------------------------------------
    */

    public function testInstallOnly()
    {
        // Test installation of the resources.

        foreach ($this->getResources() as $name => $res) {

            // Ensure the required vendor assets exists, if needed.

            if ($name === 'assets') {
                $this->installVendorAssets();
            }

            // Ensure the target resource do not exists.

            $res->uninstall();

            // Install resource using the artisan command.

            $this->artisan("adminlte:install --only={$name}");
            $this->assertTrue($res->installed());

            // Clear the installed resource.

            $res->uninstall();
            $this->assertFalse($res->installed());
        }
    }

    public function testInstallOnlyInteractive()
    {
        // We can't do these test on old laravel versions.

        if (! class_exists('Illuminate\Testing\PendingCommand')) {
            $this->assertTrue(true);

            return;
        }

        if (! method_exists('Illuminate\Testing\PendingCommand', 'expectsConfirmation')) {
            $this->assertTrue(true);

            return;
        }

        // Test installation of the resources when using --interactive.

        foreach ($this->getResources() as $name => $res) {
            $confirmMsg = $res->getInstallMessage('install');

            // Ensure the required vendor assets exists, if needed.

            if ($name === 'assets') {
                $this->installVendorAssets();
            }

            // Ensure the target resource do not exists.

            $res->uninstall();

            // Test with --interactive option (response with no).

            $this->artisan("adminlte:install --only={$name} --interactive")
                 ->expectsConfirmation($confirmMsg, 'no');

            $this->assertFalse($res->installed());

            // Test with --interactive option (response with yes).

            $this->artisan("adminlte:install --only={$name} --interactive")
                 ->expectsConfirmation($confirmMsg, 'yes');

            $this->assertTrue($res->installed());

            // Clear the installed resource.

            $res->uninstall();
            $this->assertFalse($res->installed());
        }
    }

    public function testInstallOnlyOverwrite()
    {
        // We can't do these test on old laravel versions.

        if (! class_exists('Illuminate\Testing\PendingCommand')) {
            $this->assertTrue(true);

            return;
        }

        if (! method_exists('Illuminate\Testing\PendingCommand', 'expectsConfirmation')) {
            $this->assertTrue(true);

            return;
        }

        // Test installation of the resources when an overwrite will occurs.

        foreach ($this->getResources() as $name => $res) {
            $confirmMsg = $res->getInstallMessage('overwrite');

            // Ensure the required vendor assets exists, if needed.

            if ($name === 'assets') {
                $this->installVendorAssets();
            }

            // Create dummy files for the resource.

            $this->createDummyResource($name, $res);

            // Test when not confirm the overwrite.

            $this->artisan("adminlte:install --only={$name}")
                 ->expectsConfirmation($confirmMsg, 'no');

            if ($name === 'basic_routes') {
                $this->assertTrue($res->installed());
            } else {
                $this->assertFalse($res->installed());
            }

            // Test when confirm the overwrite.

            $this->artisan("adminlte:install --only={$name}")
                 ->expectsConfirmation($confirmMsg, 'yes');

            $this->assertTrue($res->installed());

            // Test when using --force.

            $this->createDummyResource($name, $res);
            $this->artisan("adminlte:install --only={$name} --force");
            $this->assertTrue($res->installed());

            // Clear the installed resource.

            $res->uninstall();
            $this->assertFalse($res->installed());
        }
    }

    public function testInstallOnlyMultipleResources()
    {
        $resources = [
            $this->getResources('auth_views'),
            $this->getResources('config'),
            $this->getResources('translations'),
        ];

        // Uninstall the resources.

        foreach ($resources as $res) {
            $res->uninstall();
        }

        // Install all resources using the artisan command.

        $this->artisan('adminlte:install --only=config --only=translations --only=auth_views');

        // Assert that the resources are installed.

        foreach ($resources as $res) {
            $this->assertTrue($res->installed());
        }

        // Clear installed resources.

        foreach ($resources as $res) {
            $res->uninstall();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Tests over the --type=<type> option.
    |--------------------------------------------------------------------------
    */

    public function testInstallWithoutType()
    {
        $resources = [
            $this->getResources('assets'),
            $this->getResources('config'),
            $this->getResources('translations'),
        ];

        // Ensure the required vendor assets exists.

        $this->installVendorAssets();

        // Ensure the target resources do not exists.

        foreach ($resources as $res) {
            $res->uninstall();
        }

        // Install resources using the artisan command.

        $this->artisan('adminlte:install');

        // Assert that the resources are installed.

        foreach ($resources as $res) {
            $this->assertTrue($res->installed());
        }

        // Clear installed resources.

        foreach ($resources as $res) {
            $res->uninstall();
        }
    }

    public function testInstallWithTypeEnhanced()
    {
        $resources = [
            $this->getResources('assets'),
            $this->getResources('config'),
            $this->getResources('translations'),
            $this->getResources('auth_views'),
        ];

        // Ensure the required vendor assets exists.

        $this->installVendorAssets();

        // Ensure the target resources do not exists.

        foreach ($resources as $res) {
            $res->uninstall();
        }

        // Install resources using the artisan command.

        $this->artisan('adminlte:install --type=enhanced');

        // Assert that the resources are installed.

        foreach ($resources as $res) {
            $this->assertTrue($res->installed());
        }

        // Clear installed resources.

        foreach ($resources as $res) {
            $res->uninstall();
        }
    }

    public function testInstallWithTypeFull()
    {
        $resources = [
            $this->getResources('assets'),
            $this->getResources('config'),
            $this->getResources('translations'),
            $this->getResources('auth_views'),
            $this->getResources('basic_views'),
            $this->getResources('basic_routes'),
        ];

        // Ensure the required vendor assets exists.

        $this->installVendorAssets();

        // Ensure the target resources do not exists.

        foreach ($resources as $res) {
            $res->uninstall();
        }

        // Install resources using the artisan command.

        $this->artisan('adminlte:install --type=full');

        // Assert that the resources are installed.

        foreach ($resources as $res) {
            $this->assertTrue($res->installed());
        }

        // Clear installed resources.

        foreach ($resources as $res) {
            $res->uninstall();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Tests over the --with=<resource> option.
    |--------------------------------------------------------------------------
    */

    public function testInstallWithAdditionalResource()
    {
        $baseResources = [
            $this->getResources('assets'),
            $this->getResources('config'),
            $this->getResources('translations'),
        ];

        $newRes = ['main_views', 'auth_views', 'basic_views', 'basic_routes'];

        // Ensure the required vendor assets exists.

        $this->installVendorAssets();

        // Test using --with over the array of additional resources.

        foreach ($newRes as $name) {
            $allResources = array_merge(
                $baseResources,
                [$this->getResources($name)]
            );

            // Ensure the target resources do not exists.

            foreach ($allResources as $res) {
                $res->uninstall();
            }

            // Install resources using the artisan command.

            $this->artisan("adminlte:install --with={$name}");

            // Assert that the resources are installed.

            foreach ($allResources as $res) {
                $this->assertTrue($res->installed());
            }

            // Clear installed resources.

            foreach ($allResources as $res) {
                $res->uninstall();
            }
        }
    }

    public function testInstallWithMultipleAdditionalResources()
    {
        $resources = [
            $this->getResources('assets'),
            $this->getResources('config'),
            $this->getResources('translations'),
            $this->getResources('basic_routes'),
            $this->getResources('auth_views'),
        ];

        // Ensure the required vendor assets exists.

        $this->installVendorAssets();

        // Ensure the target resources do not exists.

        foreach ($resources as $res) {
            $res->uninstall();
        }

        // Remove routes file if exists.

        if (is_file(base_path('routes/web.php'))) {
            unlink(base_path('routes/web.php'));
        }

        // Install resources using the artisan command.

        $this->artisan('adminlte:install --type=enhanced --with=basic_routes --with=auth_views');

        // Assert that the resources are installed.

        foreach ($resources as $res) {
            $this->assertTrue($res->installed());
        }

        // Clear installed resources.

        foreach ($resources as $res) {
            $res->uninstall();
        }
    }
}

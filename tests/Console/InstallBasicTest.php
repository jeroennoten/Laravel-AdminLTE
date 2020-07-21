<?php

use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\PackageResource;

class DummyResource extends PackageResource
{
    public function __construct()
    {
        // Fill the resource data.

        $this->resource = [
            'package_path' => $this->getPackagePath(),
            'stub_path' => $this->getStubPath(),
        ];

        // Fill the installation messages.

        $this->messages = [
            'install'   => 'Install the dummy resource?',
        ];
    }

    public function install()
    {
        return;
    }

    public function exists()
    {
        return false;
    }

    public function installed()
    {
        return false;
    }
}

class InstallBasicTest extends CommandTestCase
{
    public function testResourceGetData()
    {
        $res = new DummyResource();

        $this->assertNotNull($res->get('stub_path'));
        $this->assertNull($res->get('foo'));
    }

    public function testResourceGetInstallMsg()
    {
        $res = new DummyResource();

        $this->assertNotNull($res->getInstallMessage('install'));
        $this->assertEmpty($res->getInstallMessage('foo'));
    }

    public function testResourceGetPackagePath()
    {
        $res = new DummyResource();

        $this->assertEquals(
            realpath($res->get('package_path')),
            realpath(__DIR__.'/../../')
        );
    }

    public function testResourceGetStubPath()
    {
        $res = new DummyResource();

        $this->assertEquals(
            realpath($res->get('stub_path')),
            realpath(__DIR__.'/../../src/Console/stubs')
        );
    }

    public function testInstallWithInvalidOption()
    {
        $this->artisan('adminlte:install --only=dummy')
             ->expectsOutput('The option --only=dummy is invalid!')
             ->assertExitCode(0);
    }
}

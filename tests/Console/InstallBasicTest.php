<?php

use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\PackageResource;

class DummyResource extends PackageResource
{
    public function __construct()
    {
        // Fill the resource data.

        $this->resource = [
            'source' => 'source',
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

        $this->assertNotNull($res->get('source'));
        $this->assertNull($res->get('foo'));
    }

    public function testResourceGetInstallMsg()
    {
        $res = new DummyResource();

        $this->assertNotNull($res->getInstallMessage('install'));
        $this->assertEmpty($res->getInstallMessage('foo'));
    }

    public function testInstallWithInvalidOption()
    {
        $this->artisan('adminlte:install --only=dummy')
             ->expectsOutput('The option --only=dummy is invalid!')
             ->assertExitCode(0);
    }
}

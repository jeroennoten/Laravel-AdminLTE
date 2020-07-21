<?php

use JeroenNoten\LaravelAdminLte\Console\PackageResources\ConfigResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\TranslationsResource;

class StatusTest extends CommandTestCase
{
    public function testBasicStatus()
    {
        $configRes = new ConfigResource();
        $transRes = new TranslationsResource();

        $configTarget = $configRes->get('target');
        $transTarget = $transRes->get('target');

        // Install some resources.

        $this->artisan('adminlte:install --only=config --only=translations');

        // Change the config file.

        $this->ensureResourceExists($configTarget);

        // Ensure state is correct.

        $this->assertFalse($configRes->installed());
        $this->assertTrue($transRes->installed());

        // Run the check of the resource status.

        $this->artisan('adminlte:status')
             ->expectsOutput('Checking the resources installation ...')
             ->expectsOutput('All resources checked succesfully!')
             ->assertExitCode(0);

        // Clear installed resources.

        $this->ensureResourcesNotExists($configTarget, $transTarget);
    }
}

<?php

class StatusTest extends CommandTestCase
{
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
}

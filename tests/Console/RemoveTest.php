<?php

class RemoveTest extends CommandTestCase
{
    public function testRemoveWithInvalidResource()
    {
        $this->artisan('adminlte:remove dummy')
             ->expectsOutput('The provided resource: dummy is invalid!')
             ->assertExitCode(0);
    }

    public function testRemoveOnAllResources()
    {
        // We can't perfom these tests on old Laravel versions. We need support
        // for the expect confirmation method.

        if (! $this->canExpectsConfirmation()) {
            $this->assertTrue(true);

            return;
        }

        // Test remove command over the available resources.

        foreach ($this->getResources() as $name => $res) {
            // Ensure the required vendor assets exists, if needed.

            if ($name === 'assets') {
                $this->installVendorAssets();
            }

            // Ensure the target resource is installed.

            $res->install();

            // Remove resource using the artisan command.

            if ($res->required) {
                $msg = 'This resource is required by the package, ';
                $msg .= 'do you really want to uninstall it?';

                $this->artisan("adminlte:remove {$name}")
                    ->expectsConfirmation($msg, 'yes');
            } else {
                $this->artisan("adminlte:remove {$name}");
            }

            $this->assertFalse($res->installed());
        }
    }

    public function testNotConfirmRemoveOnRequiredResources()
    {
        // We can't perfom these tests on old Laravel versions. We need support
        // for the expect confirmation method.

        if (! $this->canExpectsConfirmation()) {
            $this->assertTrue(true);

            return;
        }

        // Get set of required resources.

        $resources = array_filter(
            $this->getResources(),
            function ($r) { return $r->required; }
        );

        // Test remove command over the required resources, without
        // confirming the action.

        foreach ($resources as $name => $res) {
            // Ensure the required vendor assets exists, if needed.

            if ($name === 'assets') {
                $this->installVendorAssets();
            }

            // Ensure the target resource is installed.

            $res->install();

            // Remove resource using the artisan command, but don't confirm the
            // action.

            $msg = 'This resource is required by the package, ';
            $msg .= 'do you really want to uninstall it?';

            $this->artisan("adminlte:remove {$name}")
                ->expectsConfirmation($msg, 'no');

            // Assert the resource is still installed.

            $this->assertTrue($res->installed());

            // Clear the installed resource.

            $res->uninstall();
            $this->assertFalse($res->installed());
        }
    }

    public function testNotConfirmRemoveWithInteractiveFlag()
    {
        // We can't perfom these tests on old Laravel versions. We need support
        // for the expect confirmation method.

        if (! $this->canExpectsConfirmation()) {
            $this->assertTrue(true);

            return;
        }

        // Test remove command over the resources when using --interactive.

        foreach ($this->getResources() as $name => $res) {
            $confirmMsg = 'Do you really want to uninstall the resource: :res?';
            $confirmMsg = str_replace(':res', $name, $confirmMsg);

            // Ensure the required vendor assets exists, if needed.

            if ($name === 'assets') {
                $this->installVendorAssets();
            }

            // Ensure the target resource is installed.

            $res->install();

            // Test with --interactive option and respond with NO.

            $this->artisan("adminlte:remove {$name} --interactive")
                 ->expectsConfirmation($confirmMsg, 'no');

            // Assert the resource is still installed.

            $this->assertTrue($res->installed());

            // Clear the installed resource.

            $res->uninstall();
            $this->assertFalse($res->installed());
        }
    }
}

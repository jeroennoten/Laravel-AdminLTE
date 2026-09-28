<?php

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\AuthRoutesResource;

/**
 * The published routes call 'Auth::routes()', which only exists when the
 * Laravel/UI package is installed. Publishing them without it raises on every
 * request and on every artisan command, including the command that would
 * remove them again, so the resource has to skip instead.
 */
class AuthRoutesScaffoldingTest extends CommandTestCase
{
    public function testTheScaffoldingIsDetected()
    {
        $res = new AuthRoutesResource();

        // Laravel/UI is a development dependency of this package, so the real
        // resource finds it while the suite runs.

        $this->assertTrue($res->isScaffoldingAvailable());
    }

    public function testTheRoutesAreSkippedWithoutTheScaffolding()
    {
        $res = new AuthRoutesResourceWithoutScaffolding();

        $target = base_path('routes/web.php');
        $before = File::isFile($target) ? File::get($target) : null;

        $res->install();

        // Nothing may reach the routes file, otherwise the application stops
        // booting.

        $after = File::isFile($target) ? File::get($target) : null;

        $this->assertSame($before, $after);
        $this->assertFalse($res->installed());
    }

    public function testTheSuccessMessageExplainsTheSkip()
    {
        $res = new AuthRoutesResourceWithoutScaffolding();
        $msg = $res->getInstallMessage('success');

        // The message has to name the missing package and the way out.

        $this->assertStringContainsString('skipped', $msg);
        $this->assertStringContainsString('Laravel/UI', $msg);
        $this->assertStringContainsString('composer require laravel/ui', $msg);
        $this->assertStringContainsString('--only=auth_routes', $msg);

        // The other messages are untouched.

        $this->assertSame(
            (new AuthRoutesResource())->getInstallMessage('install'),
            $res->getInstallMessage('install')
        );
    }

    public function testTheSuccessMessageIsTheUsualOneWithTheScaffolding()
    {
        $res = new AuthRoutesResource();

        $this->assertSame(
            'Auth routes published successfully',
            $res->getInstallMessage('success')
        );
    }
}

/**
 * An auth routes resource that behaves like one on an application without the
 * Laravel/UI package.
 */
class AuthRoutesResourceWithoutScaffolding extends AuthRoutesResource
{
    public function isScaffoldingAvailable()
    {
        return false;
    }
}

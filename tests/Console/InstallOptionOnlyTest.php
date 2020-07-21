<?php

use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\AssetsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\ConfigResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\TranslationsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\MainViewsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\AuthViewsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\BasicViewsResource;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\BasicRoutesResource;

class InstallOptionOnlyTest extends CommandTestCase
{
    /**
     * Tests over the --only=assets option.
     */

    public function testInstallOnlyAssets()
    {
        $res = new AssetsResource();
        $target = $res->get('target');

        // Ensure the required vendor assets exists.

        $this->installVendorAssets();

        // Ensure the target resources do not exists.

        $this->ensureResourceNotExists($target);

        // Install resource using the artisan command.

        $this->artisan('adminlte:install --only=assets');
        $this->assertTrue($res->installed());

        // Clear installed resources.

        $this->ensureResourceNotExists($target);
    }

    public function testInstallOnlyAssetsInteractive()
    {
        $res = new AssetsResource();
        $target = $res->get('target');
        $confirmMsg = $res->getInstallMessage('install');

        // Ensure the required vendor assets exists.

        $this->installVendorAssets();

        // Ensure the target resources do not exists.

        $this->ensureResourceNotExists($target);

        // Test with --interactive option (response with no).

        $this->artisan('adminlte:install --only=assets --interactive')
             ->expectsConfirmation($confirmMsg, 'no');

        $this->assertFalse($res->installed());

        // Test with --interactive option (response with yes).

        $this->artisan('adminlte:install --only=assets --interactive')
             ->expectsConfirmation($confirmMsg, 'yes');

        $this->assertTrue($res->installed());

        // Clear installed resources.

        $this->ensureResourceNotExists($target);
    }

    public function testInstallOnlyAssetsOverwrite()
    {
        $res = new AssetsResource();
        $target = $res->get('target');
        $confirmMsg = $res->getInstallMessage('overwrite');

        // Ensure the required vendor assets exists.

        $this->installVendorAssets();

        // Ensure a target resources exists.

        $adminAssetTarget = $res->get('source')['adminlte']['target'];
        $this->ensureResourceExists($adminAssetTarget);

        // Test when not confirm the overwrite.

        $this->artisan('adminlte:install --only=assets')
             ->expectsConfirmation($confirmMsg, 'no');

        $this->assertFalse($res->installed());

        // Test when confirm the overwrite.

        $this->artisan('adminlte:install --only=assets')
             ->expectsConfirmation($confirmMsg, 'yes');

        $this->assertTrue($res->installed());

        // Test when using --force.

        $this->ensureResourceNotExists($adminAssetTarget);
        $this->artisan('adminlte:install --only=assets --force');
        $this->assertTrue($res->installed());

        // Clear installed resources.

        $this->ensureResourceNotExists($target);
    }

    /**
     * Tests over the --only=config option.
     */

    public function testInstallOnlyConfig()
    {
        $res = new ConfigResource();
        $target = $res->get('target');

        // Ensure the target resource do not exists.

        $this->ensureResourceNotExists($target);

        // Install resource using the artisan command.

        $this->artisan('adminlte:install --only=config');
        $this->assertTrue($res->installed());

        // Clear installed resources.

        $this->ensureResourceNotExists($target);
    }

    public function testInstallOnlyConfigInteractive()
    {
        $res = new ConfigResource();
        $target = $res->get('target');
        $confirmMsg = $res->getInstallMessage('install');

        $this->ensureResourceNotExists($target);

        // Test with --interactive option (response with no).

        $this->artisan('adminlte:install --only=config --interactive')
             ->expectsConfirmation($confirmMsg, 'no');

        $this->assertFalse($res->installed());

        // Test with --interactive option (response with yes).

        $this->artisan('adminlte:install --only=config --interactive')
             ->expectsConfirmation($confirmMsg, 'yes');

        $this->assertTrue($res->installed());

        // Clear installed resources.

        $this->ensureResourceNotExists($target);
    }

    public function testInstallOnlyConfigOverwrite()
    {
        $res = new ConfigResource();
        $target = $res->get('target');
        $confirmMsg = $res->getInstallMessage('overwrite');

        $this->ensureResourceExists($target);

        // Test when not confirm the overwrite.

        $this->artisan('adminlte:install --only=config')
             ->expectsConfirmation($confirmMsg, 'no');

        $this->assertFalse($res->installed());

        // Test when confirm the overwrite.

        $this->artisan('adminlte:install --only=config')
             ->expectsConfirmation($confirmMsg, 'yes');

        $this->assertTrue($res->installed());

        // Test when using --force.

        $this->ensureResourceExists($target);
        $this->artisan('adminlte:install --only=config --force');
        $this->assertTrue($res->installed());

        // Clear installed resources.

        $this->ensureResourceNotExists($target);
    }

    /**
     * Tests over the --only=translation option.
     */

    public function testInstallOnlyTranslations()
    {
        $res = new TranslationsResource();
        $target = $res->get('target');

        // Ensure the target resource do not exists.

        $this->ensureResourceNotExists($target);

        // Install resource using the artisan command.

        $this->artisan('adminlte:install --only=translations');
        $this->assertTrue($res->installed());

        // Clear installed resources.

        $this->ensureResourceNotExists($target);
    }

    public function testInstallOnlyTranslationsInteractive()
    {
        $res = new TranslationsResource();
        $target = $res->get('target');
        $confirmMsg = $res->getInstallMessage('install');

        $this->ensureResourceNotExists($target);

        // Test with --interactive option (response with no).

        $this->artisan('adminlte:install --only=translations --interactive')
             ->expectsConfirmation($confirmMsg, 'no');

        $this->assertFalse($res->installed());

        // Test with --interactive option (response with yes).

        $this->artisan('adminlte:install --only=translations --interactive')
             ->expectsConfirmation($confirmMsg, 'yes');

        $this->assertTrue($res->installed());

        // Clear installed resources.

        $this->ensureResourceNotExists($target);
    }

    public function testInstallOnlyTranslationsOverwrite()
    {
        $res = new TranslationsResource();
        $target = $res->get('target');
        $confirmMsg = $res->getInstallMessage('overwrite');

        $this->ensureResourceExists($target);

        // Test when not confirm the overwrite.

        $this->artisan('adminlte:install --only=translations')
             ->expectsConfirmation($confirmMsg, 'no');

        $this->assertFalse($res->installed());

        // Test when confirm the overwrite.

        $this->artisan('adminlte:install --only=translations')
             ->expectsConfirmation($confirmMsg, 'yes');

        $this->assertTrue($res->installed());

        // Test when using --force.

        $this->ensureResourceExists($target.'/en/adminlte.php');
        $this->artisan('adminlte:install --only=translations --force');
        $this->assertTrue($res->installed());

        // Clear installed resources.

        $this->ensureResourceNotExists($target);
    }

    /**
     * Tests over the --only=main_views option.
     */

    public function testInstallOnlyMainViews()
    {
        $res = new MainViewsResource();
        $target = $res->get('target');

        // Ensure the target resource do not exists.

        $this->ensureResourceNotExists($target);

        // Install resource using the artisan command.

        $this->artisan('adminlte:install --only=main_views');
        $this->assertTrue($res->installed());

        // Clear installed resources.

        $this->ensureResourceNotExists($target);
    }

    public function testInstallOnlyMainViewsInteractive()
    {
        $res = new MainViewsResource();
        $target = $res->get('target');
        $confirmMsg = $res->getInstallMessage('install');

        $this->ensureResourceNotExists($target);

        // Test with --interactive option (response with no).

        $this->artisan('adminlte:install --only=main_views --interactive')
             ->expectsConfirmation($confirmMsg, 'no');

        $this->assertFalse($res->installed());

        // Test with --interactive option (response with yes).

        $this->artisan('adminlte:install --only=main_views --interactive')
             ->expectsConfirmation($confirmMsg, 'yes');

        $this->assertTrue($res->installed());

        // Clear installed resources.

        $this->ensureResourceNotExists($target);
    }

    public function testInstallOnlyMainViewsOverwrite()
    {
        $res = new MainViewsResource();
        $target = $res->get('target');
        $confirmMsg = $res->getInstallMessage('overwrite');

        $this->ensureResourceExists($target);

        // Test when not confirm the overwrite.

        $this->artisan('adminlte:install --only=main_views')
             ->expectsConfirmation($confirmMsg, 'no');

        $this->assertFalse($res->installed());

        // Test when confirm the overwrite.

        $this->artisan('adminlte:install --only=main_views')
             ->expectsConfirmation($confirmMsg, 'yes');

        $this->assertTrue($res->installed());

        // Test when using --force.

        $this->ensureResourceExists($target.'/master.blade.php');
        $this->artisan('adminlte:install --only=main_views --force');
        $this->assertTrue($res->installed());

        // Clear installed resources.

        $this->ensureResourceNotExists($target);
    }

    /**
     * Tests over the --only=auth_views option.
     */

    public function testInstallOnlyAuthViews()
    {
        $res = new AuthViewsResource();
        $target = $res->get('target');

        // Ensure the target resource do not exists.

        $this->ensureResourceNotExists($target);

        // Install resource using the artisan command.

        $this->artisan('adminlte:install --only=auth_views');
        $this->assertTrue($res->installed());

        // Clear installed resources.

        $this->ensureResourceNotExists($target);
    }

    public function testInstallOnlyAuthViewsInteractive()
    {
        $res = new AuthViewsResource();
        $target = $res->get('target');
        $confirmMsg = $res->getInstallMessage('install');

        $this->ensureResourceNotExists($target);

        // Test with --interactive option (response with no).

        $this->artisan('adminlte:install --only=auth_views --interactive')
             ->expectsConfirmation($confirmMsg, 'no');

        $this->assertFalse($res->installed());

        // Test with --interactive option (response with yes).

        $this->artisan('adminlte:install --only=auth_views --interactive')
             ->expectsConfirmation($confirmMsg, 'yes');

        $this->assertTrue($res->installed());

        // Clear installed resources.

        $this->ensureResourceNotExists($target);
    }

    public function testInstallOnlyAuthViewsWhenOverwrite()
    {
        $res = new AuthViewsResource();
        $target = $res->get('target');
        $source = $res->get('source');
        $confirmMsg = $res->getInstallMessage('overwrite');

        $view = array_keys($source)[0];
        $this->ensureResourceExists($target.'/'.$view);

        // Test when not confirm the overwrite.

        $this->artisan('adminlte:install --only=auth_views')
             ->expectsConfirmation($confirmMsg, 'no');

        $this->assertFalse($res->installed());

        // Test when confirm the overwrite.

        $this->artisan('adminlte:install --only=auth_views')
             ->expectsConfirmation($confirmMsg, 'yes');

        $this->assertTrue($res->installed());

        // Test when using --force.

        $view = array_keys($source)[1];
        $this->ensureResourceExists($target.'/'.$view);
        $this->artisan('adminlte:install --only=auth_views --force');
        $this->assertTrue($res->installed());

        // Clear installed resources.

        $this->ensureResourceNotExists($target);
    }

    /**
     * Tests over the --only=basic_views option.
     */

    public function testInstallOnlyBasicViews()
    {
        $res = new BasicViewsResource();
        $target = $res->get('target');
        $source = $res->get('source');

        // Ensure the target resources do not exists.

        foreach ($source as $file => $stub) {
            $this->ensureResourceNotExists($target.'/'.$file);
        }

        // Install resources using the artisan command.

        $this->artisan('adminlte:install --only=basic_views');
        $this->assertTrue($res->installed());

        // Clear installed resources.

        foreach ($source as $file => $stub) {
            $this->ensureResourceNotExists($target.'/'.$file);
        }
    }

    public function testInstallOnlyBasicViewsInteractive()
    {
        $res = new BasicViewsResource();
        $target = $res->get('target');
        $source = $res->get('source');
        $confirmMsg = $res->getInstallMessage('install');

        foreach ($source as $file => $stub) {
            $this->ensureResourceNotExists($target.'/'.$file);
        }

        // Test with --interactive option (response with no).

        $this->artisan('adminlte:install --only=basic_views --interactive')
             ->expectsConfirmation($confirmMsg, 'no');

        $this->assertFalse($res->installed());

        // Test with --interactive option (response with yes).

        $this->artisan('adminlte:install --only=basic_views --interactive')
             ->expectsConfirmation($confirmMsg, 'yes');

        $this->assertTrue($res->installed());

        // Clear installed resources.

        foreach ($source as $file => $stub) {
            $this->ensureResourceNotExists($target.'/'.$file);
        }
    }

    public function testInstallOnlyBasicViewsOverwrite()
    {
        $res = new BasicViewsResource();
        $target = $res->get('target');
        $source = $res->get('source');
        $confirmMsg = $res->getInstallMessage('overwrite');

        // Ensure a basic view already exists.

        $view = array_keys($source)[0];
        $this->ensureResourceExists($target.'/'.$view);

        // Test when not confirm the overwrite.

        $this->artisan('adminlte:install --only=basic_views')
             ->expectsConfirmation($confirmMsg, 'no');

        $this->assertFalse($res->installed());

        // Test when confirm the overwrite.

        $this->artisan('adminlte:install --only=basic_views')
             ->expectsConfirmation($confirmMsg, 'yes');

        $this->assertTrue($res->installed());

        // Test when using --force.

        $this->ensureResourceExists($target.'/'.$view);
        $this->artisan('adminlte:install --only=basic_views --force');
        $this->assertTrue($res->installed());

        // Clear installed resources.

        foreach ($source as $file => $stub) {
            $this->ensureResourceNotExists($target.'/'.$file);
        }
    }

    /**
     * Tests over the --only=basic_routes option.
     */

    public function testInstallOnlyBasicRoutes()
    {
        $res = new BasicRoutesResource();
        $target = $res->get('target');

        // Ensure the target resources do not exists.

        $this->ensureResourceNotExists($target);

        // Install resources using the artisan command.

        $this->artisan('adminlte:install --only=basic_routes');
        $this->assertTrue($res->installed());

        // Clear installed resources.

        $this->ensureResourceNotExists($target);
    }

    public function testInstallOnlyBasicRoutesInteractive()
    {
        $res = new BasicRoutesResource();
        $target = $res->get('target');
        $confirmMsg = $res->getInstallMessage('install');

        // Ensure the target resources do not exists.

        $this->ensureResourceNotExists($target);

        // Test with --interactive option (response with no).

        $this->artisan('adminlte:install --only=basic_routes --interactive')
             ->expectsConfirmation($confirmMsg, 'no');

        $this->assertFalse($res->installed());

        // Test with --interactive option (response with yes).

        $this->artisan('adminlte:install --only=basic_routes --interactive')
             ->expectsConfirmation($confirmMsg, 'yes');

        $this->assertTrue($res->installed());

        // Clear installed resources.

        $this->ensureResourceNotExists($target);
    }

    public function testInstallOnlyBasicRoutesOverwrite()
    {
        $res = new BasicRoutesResource();
        $target = $res->get('target');
        $confirmMsg = $res->getInstallMessage('overwrite');

        // Ensure the routes are already installed.

        $this->ensureResourceExists($target);
        $this->artisan('adminlte:install --only=basic_routes');

        // Test when not confirm the overwrite.

        $this->artisan('adminlte:install --only=basic_routes')
             ->expectsConfirmation($confirmMsg, 'no');

        $this->assertTrue($res->installed());

        // Test when confirm the overwrite.

        $this->artisan('adminlte:install --only=basic_routes')
             ->expectsConfirmation($confirmMsg, 'yes');

        $this->assertTrue($res->installed());

        // Test when using --force.

        $this->ensureResourceExists($target);
        $this->artisan('adminlte:install --only=basic_routes --force');
        $this->assertTrue($res->installed());

        // Clear installed resources.

        $this->ensureResourceNotExists($target);
    }

    /**
     * Tests over the --only using multiple option.
     */

    public function testInstallOnlyMultipleResources()
    {
        $configRes = new ConfigResource();
        $transRes = new TranslationsResource();
        $viewsRes = new AuthViewsResource();

        $configTarget = $configRes->get('target');
        $transTarget = $transRes->get('target');
        $viewsTarget = $viewsRes->get('target');

        // Ensure the target resources do not exists.

        $this->ensureResourcesNotExists(
            $configTarget, $transTarget, $viewsTarget
        );

        // Install resources using the artisan command.

        $this->artisan('adminlte:install --only=config --only=translations --only=auth_views');

        // Assert that the resources are installed.

        $this->assertTrue($configRes->installed());
        $this->assertTrue($transRes->installed());
        $this->assertTrue($viewsRes->installed());

        // Clear installed resources.

        $this->ensureResourcesNotExists(
            $configTarget, $transTarget, $viewsTarget
        );
    }
}

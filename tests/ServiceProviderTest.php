<?php

use JeroenNoten\LaravelAdminLte\AdminLte;
use JeroenNoten\LaravelAdminLte\AdminLteServiceProvider;

class ServiceProviderTest extends TestCase
{
    public function testRegisterSingletonInstance()
    {
        // Check the instance of AdminLte resolver.

        $adminlte = $this->app->make(AdminLte::class);
        $this->assertInstanceOf(AdminLte::class, $adminlte);

        // Check that a singleton instance is registered.

        $this->assertSame($adminlte, $this->app->make(AdminLte::class));
    }

    public function testBootLoadViews()
    {
        // Check that the main views are loaded.

        $this->assertTrue(View::exists('adminlte::master'));
        $this->assertTrue(View::exists('adminlte::page'));
        $this->assertTrue(View::exists('adminlte::auth.auth-page'));
        $this->assertTrue(View::exists('adminlte::auth.login'));
        $this->assertTrue(View::exists('adminlte::auth.register'));
        $this->assertTrue(View::exists('adminlte::auth.verify'));
        $this->assertTrue(View::exists('adminlte::auth.passwords.confirm'));
        $this->assertTrue(View::exists('adminlte::auth.passwords.email'));
        $this->assertTrue(View::exists('adminlte::auth.passwords.reset'));
    }

    public function testBootLoadTranslations()
    {
        // Check that the main translations are loaded.

        $this->assertTrue(Lang::has('adminlte::adminlte.sign_in'));
        $this->assertTrue(Lang::has('adminlte::menu.main_navigation'));
    }

    public function testBootLoadConfig()
    {
        // Check that config values are loaded.

        $this->assertTrue(Config::has('adminlte.title'));
        $this->assertEquals('AdminLTE 4', Config::get('adminlte.title'));

        $this->assertTrue(Config::has('adminlte.menu'));
        $this->assertTrue(is_array(Config::get('adminlte.menu')));
    }

    public function testBootLoadConfigOverAMalformedPublishedFile()
    {
        // A published configuration file that does not return an array used to
        // abort the boot of the whole application with a type error.

        Config::set('adminlte', 'dummy-content');

        $this->app->register(AdminLteServiceProvider::class)->boot();

        $this->assertTrue(is_array(Config::get('adminlte')));
        $this->assertEquals('AdminLTE 4', Config::get('adminlte.title'));
    }

    public function testBootRegisterCommands()
    {
        // Check that the artisan commands are registered.

        $commands = Artisan::all();
        $this->assertTrue(Arr::has($commands, 'adminlte:install'));
        $this->assertTrue(Arr::has($commands, 'adminlte:status'));
        $this->assertTrue(Arr::has($commands, 'adminlte:update'));
        $this->assertTrue(Arr::has($commands, 'adminlte:plugins'));
        $this->assertTrue(Arr::has($commands, 'adminlte:remove'));
    }

    public function testBootRegisterMenuFilters()
    {
        // The default set of menu filters must be available.

        $filters = Config::get('adminlte.filters');

        $this->assertIsArray($filters);
        $this->assertNotEmpty($filters);

        foreach ($filters as $filter) {
            $this->assertInstanceOf(
                \JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface::class,
                $this->app->make($filter)
            );
        }
    }

    public function testBootRegisterViewComposers()
    {
        // Check that the AdminLte instance exists on the page blade.

        $view = View::make('adminlte::page');
        View::callComposer($view);
        $viewData = $view->getData();

        $this->assertTrue(Arr::has($viewData, 'adminlte'));
    }

    public function testBootRegisterMenu()
    {
        $adminlte = $this->app->make(AdminLte::class);
        $menu = $adminlte->menu();

        $this->assertCount(13, $menu);
        $this->assertEquals('search', $menu[0]['text']);
        $this->assertEquals('darkmode-widget', $menu[1]['type']);
    }

    public function testBootLoadComponents()
    {
        // Check that some of the blade component views are loaded.

        $this->assertTrue(View::exists('adminlte::components.form.input'));
        $this->assertTrue(View::exists('adminlte::components.form.select2'));
        $this->assertTrue(View::exists('adminlte::components.widget.card'));
        $this->assertTrue(View::exists('adminlte::components.tool.modal'));

        // Check that the class components aliases are registered.

        $aliases = Blade::getClassComponentAliases();

        $this->assertTrue(isset($aliases['adminlte-input']));
        $this->assertTrue(isset($aliases['adminlte-select2']));
        $this->assertTrue(isset($aliases['adminlte-card']));
        $this->assertTrue(isset($aliases['adminlte-modal']));
    }

    public function testBootLoadDarkModeRoutes()
    {
        // Disable dark mode routes and check.

        config(['adminlte.disable_darkmode_routes' => true]);
        $this->clearRoutesAndReRegisterProvider();

        $this->assertFalse(Route::has('adminlte.darkmode.toggle'));

        // Enable dark mode routes and check again.

        config(['adminlte.disable_darkmode_routes' => false]);
        $this->clearRoutesAndReRegisterProvider();

        $this->assertTrue(Route::has('adminlte.darkmode.toggle'));
    }

    public function testBootLoadTheLockscreenRoutesOnlyWhenEnabled()
    {
        // The lockscreen is opt in, so its routes must not exist by default.

        config(['adminlte.lockscreen.enabled' => false]);
        $this->clearRoutesAndReRegisterProvider();

        $this->assertFalse(Route::has('adminlte.lockscreen.show'));
        $this->assertFalse(Route::has('adminlte.lockscreen.lock'));
        $this->assertFalse(Route::has('adminlte.lockscreen.unlock'));

        config(['adminlte.lockscreen.enabled' => true]);
        $this->clearRoutesAndReRegisterProvider();

        $this->assertTrue(Route::has('adminlte.lockscreen.show'));
        $this->assertTrue(Route::has('adminlte.lockscreen.lock'));
        $this->assertTrue(Route::has('adminlte.lockscreen.unlock'));
    }

    public function testTheLockscreenRoutesCanBeDisabledOnTheirOwn()
    {
        // An application may register its own endpoints for the feature.

        config([
            'adminlte.lockscreen.enabled' => true,
            'adminlte.lockscreen.routes' => false,
        ]);

        $this->clearRoutesAndReRegisterProvider();

        $this->assertFalse(Route::has('adminlte.lockscreen.show'));

        // The color mode routes are unaffected by that switch.

        $this->assertTrue(Route::has('adminlte.darkmode.toggle'));
    }

    public function testBothRouteGroupsCanBeDisabledAtOnce()
    {
        config([
            'adminlte.color_mode.routes' => false,
            'adminlte.lockscreen.enabled' => false,
        ]);

        $this->clearRoutesAndReRegisterProvider();

        $this->assertFalse(Route::has('adminlte.darkmode.toggle'));
        $this->assertFalse(Route::has('adminlte.lockscreen.show'));
    }

    /**
     * Clear routes and re-register the service provider.
     */
    protected function clearRoutesAndReRegisterProvider()
    {
        // Clear the current route collection.

        Route::setRoutes(new \Illuminate\Routing\RouteCollection());

        // Unregister and register the provider again.

        $provider = $this->app->register(AdminLteServiceProvider::class);
        $provider->boot();

        // Refresh route names after loading routes again.

        Route::getRoutes()->refreshNameLookups();
    }

    public function testBootRegisterThePublishGroups()
    {
        // The vendor:publish workflow is what every Laravel user tries first,
        // so the package provides its own tags for it.

        $groups = \Illuminate\Support\ServiceProvider::$publishGroups;

        foreach (['adminlte-config', 'adminlte-views', 'adminlte-lang', 'adminlte-assets'] as $tag) {
            $this->assertArrayHasKey($tag, $groups);
            $this->assertNotEmpty($groups[$tag]);
        }

        // The config group targets the application config file.

        $target = reset($groups['adminlte-config']);
        $this->assertStringEndsWith('adminlte.php', $target);
    }

    public function testBootLoadTheNewComponents()
    {
        $components = [
            'layout.content-header', 'widget.timeline', 'widget.timeline-item',
            'widget.timeline-label', 'widget.ribbon', 'widget.progress-group',
            'widget.user-block', 'widget.toast',
        ];

        foreach ($components as $component) {
            $this->assertTrue(
                View::exists("adminlte::components.{$component}"),
                $component
            );
        }
    }

    public function testEveryRegisteredComponentIsDocumented()
    {
        // The component list and the documentation drift apart easily, so the
        // inventory is checked instead of trusted.

        $provider = file_get_contents(__DIR__.'/../src/AdminLteServiceProvider.php');

        preg_match_all(
            "/'([a-z0-9-]+)' => (?:Layout|Form|Tool|Widget)\\\\/",
            $provider,
            $matches
        );

        $components = $matches[1];

        $this->assertGreaterThan(40, count($components));

        $docs = '';

        foreach (glob(__DIR__.'/../docs/sections/**/*.md') as $file) {
            $docs .= file_get_contents($file);
        }

        foreach ($components as $component) {
            $this->assertStringContainsString(
                $component,
                $docs,
                "The '{$component}' component is not mentioned in the docs."
            );
        }
    }
}

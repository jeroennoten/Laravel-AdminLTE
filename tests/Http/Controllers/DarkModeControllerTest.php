<?php

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use JeroenNoten\LaravelAdminLte\Events\DarkModeWasToggled;
use JeroenNoten\LaravelAdminLte\Events\ReadingDarkModePreference;
use JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper;
use JeroenNoten\LaravelAdminLte\Http\Controllers\DarkModeController;

class DarkModeControllerTest extends TestCase
{
    /**
     * Define the environment setup. The toggle route uses the 'web' middleware
     * group, that requires an application encryption key.
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('a', 32)));
    }

    public function testDarkModeFallbackToConfig()
    {
        $darkModeCtrl = new DarkModeController();

        // Test dark mode fallback to config disabled.

        config(['adminlte.layout_dark_mode' => null]);
        Session::forget('adminlte_dark_mode');

        $this->assertNull(session('adminlte_dark_mode', null));
        $this->assertFalse($darkModeCtrl->isEnabled());

        // Test dark mode fallback to config enabled.

        config(['adminlte.layout_dark_mode' => true]);
        Session::forget('adminlte_dark_mode');

        $this->assertNull(session('adminlte_dark_mode', null));
        $this->assertTrue($darkModeCtrl->isEnabled());
    }

    public function testToggleDarkModeFromConfig()
    {
        $darkModeCtrl = new DarkModeController();
        Event::fake();

        // Test dark mode is toggled to enabled state and event is emitted.

        config(['adminlte.layout_dark_mode' => null]);
        Session::forget('adminlte_dark_mode');

        $this->assertNull(session('adminlte_dark_mode', null));

        $darkModeCtrl->toggle();

        $this->assertTrue($darkModeCtrl->isEnabled());
        $this->assertTrue(session('adminlte_dark_mode', null));

        Event::assertDispatched(DarkModeWasToggled::class, function ($event) {
            return $event->darkMode->isEnabled() === true;
        });

        // Test dark mode is toggled to disabled state and event is emitted.

        config(['adminlte.layout_dark_mode' => true]);
        Session::forget('adminlte_dark_mode');

        $this->assertNull(session('adminlte_dark_mode', null));

        $darkModeCtrl->toggle();

        $this->assertFalse($darkModeCtrl->isEnabled());
        $this->assertFalse(session('adminlte_dark_mode', null));

        Event::assertDispatched(DarkModeWasToggled::class, function ($event) {
            return $event->darkMode->isEnabled() === false;
        });
    }

    public function testToggleDarkModeFromSession()
    {
        $darkModeCtrl = new DarkModeController();
        Event::fake();

        // Test dark mode is toggled to enabled state and event is emitted.

        Session::put('adminlte_dark_mode', false);

        $this->assertFalse(session('adminlte_dark_mode', null));

        $darkModeCtrl->toggle();

        $this->assertTrue($darkModeCtrl->isEnabled());
        $this->assertTrue(session('adminlte_dark_mode', null));

        Event::assertDispatched(DarkModeWasToggled::class, function ($event) {
            return $event->darkMode->isEnabled() === true;
        });

        // Test dark mode is toggled to disabled state and event is emitted.

        Session::put('adminlte_dark_mode', true);

        $this->assertTrue(session('adminlte_dark_mode', null));

        $darkModeCtrl->toggle();

        $this->assertFalse($darkModeCtrl->isEnabled());
        $this->assertFalse(session('adminlte_dark_mode', null));

        Event::assertDispatched(DarkModeWasToggled::class, function ($event) {
            return $event->darkMode->isEnabled() === false;
        });
    }

    public function testEnableDisableDarkModeManually()
    {
        $darkModeCtrl = new DarkModeController();

        // Test dark mode will be enabled.

        config(['adminlte.layout_dark_mode' => null]);
        Session::forget('adminlte_dark_mode');

        $this->assertNull(session('adminlte_dark_mode', null));
        $this->assertFalse($darkModeCtrl->isEnabled());

        $darkModeCtrl->enable();

        $this->assertTrue($darkModeCtrl->isEnabled());

        // Test dark mode will be disabled.

        $darkModeCtrl->disable();

        $this->assertFalse($darkModeCtrl->isEnabled());
    }

    public function testToggleRouteIsRegistered()
    {
        $this->assertTrue(Route::has('adminlte.darkmode.toggle'));

        $route = Route::getRoutes()->getByName('adminlte.darkmode.toggle');

        $this->assertEquals('adminlte/darkmode/toggle', $route->uri());
        $this->assertContains('POST', $route->methods());
    }

    public function testToggleDarkModeThroughTheRoute()
    {
        config(['adminlte.layout_dark_mode' => false]);
        Session::forget('adminlte_dark_mode');

        // The first request enables the dark mode.

        $response = $this->post(route('adminlte.darkmode.toggle'));

        $response->assertOk();
        $response->assertSessionHas('adminlte_dark_mode', true);

        // The next one disables it again.

        $response = $this->post(route('adminlte.darkmode.toggle'));

        $response->assertOk();
        $response->assertSessionHas('adminlte_dark_mode', false);
    }

    public function testTheToggleRouteDispatchesTheToggledEvent()
    {
        Event::fake();

        config(['adminlte.layout_dark_mode' => false]);
        Session::forget('adminlte_dark_mode');

        $this->post(route('adminlte.darkmode.toggle'))->assertOk();

        Event::assertDispatched(DarkModeWasToggled::class, function ($event) {
            return $event->darkMode instanceof DarkModeController;
        });
    }

    public function testTheToggleRouteOnlyAcceptsPostRequests()
    {
        $this->get(route('adminlte.darkmode.toggle'))->assertStatus(405);
    }

    public function testTheReadingPreferenceEventIsDispatched()
    {
        Event::fake([ReadingDarkModePreference::class]);

        LayoutHelper::isDarkModeEnabled();

        Event::assertDispatched(ReadingDarkModePreference::class, function ($event) {
            return $event->darkMode instanceof DarkModeController;
        });
    }

    public function testAListenerMayResolveTheDarkModePreference()
    {
        config(['adminlte.layout_dark_mode' => false]);
        Session::forget('adminlte_dark_mode');

        $this->assertFalse(LayoutHelper::isDarkModeEnabled());

        // A listener may resolve the preference from an external source (for
        // example, a database) by using the controller of the event.

        Event::listen(ReadingDarkModePreference::class, function ($event) {
            $event->darkMode->enable();
        });

        $this->assertTrue(LayoutHelper::isDarkModeEnabled());

        // And it may also disable the dark mode.

        Event::forget(ReadingDarkModePreference::class);

        Event::listen(ReadingDarkModePreference::class, function ($event) {
            $event->darkMode->disable();
        });

        $this->assertFalse(LayoutHelper::isDarkModeEnabled());
    }

    public function testTheDarkModePreferenceIsSharedBetweenControllers()
    {
        Session::forget('adminlte_dark_mode');
        config(['adminlte.layout_dark_mode' => false]);

        // The preference is stored on the session, so any instance of the
        // controller resolves the same value.

        (new DarkModeController())->enable();

        $this->assertTrue((new DarkModeController())->isEnabled());
        $this->assertTrue(LayoutHelper::isDarkModeEnabled());
        $this->assertEquals('dark', LayoutHelper::getColorMode());
    }
}

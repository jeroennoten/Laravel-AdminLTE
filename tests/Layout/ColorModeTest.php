<?php

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Session;
use JeroenNoten\LaravelAdminLte\Events\ReadingDarkModePreference;
use JeroenNoten\LaravelAdminLte\Layout\ColorMode;

class ColorModeTest extends TestCase
{
    /**
     * Setup this testing class.
     */
    public function setUp(): void
    {
        parent::setUp();

        // Disable the legacy options and the server side preference, so every
        // test starts on a known state.

        config([
            'adminlte.layout_theme_mode' => null,
            'adminlte.layout_dark_mode' => null,
        ]);

        Session::forget('adminlte_dark_mode');
    }

    public function testGetWithTheSupportedColorModes()
    {
        foreach (['light', 'dark', 'auto'] as $mode) {
            config(['adminlte.color_mode.default' => $mode]);
            $this->assertEquals($mode, ColorMode::get());
        }
    }

    public function testGetWithAnUnsupportedColorMode()
    {
        // Any unsupported value falls back to the automatic mode.

        foreach (['invalid', '', null, true, ['dark'], 0] as $mode) {
            config(['adminlte.color_mode.default' => $mode]);
            $this->assertEquals('auto', ColorMode::get());
        }
    }

    public function testGetWithoutAnyConfiguration()
    {
        config(['adminlte' => []]);

        $this->assertEquals('auto', ColorMode::get());
    }

    public function testGetWithTheLegacyThemeModeOption()
    {
        config(['adminlte.color_mode.default' => 'auto']);

        // The legacy option takes precedence over the current one.

        foreach (['light', 'dark', 'auto'] as $mode) {
            config(['adminlte.layout_theme_mode' => $mode]);
            $this->assertEquals($mode, ColorMode::get());
        }

        // An invalid legacy value is ignored.

        config([
            'adminlte.layout_theme_mode' => 'invalid',
            'adminlte.color_mode.default' => 'light',
        ]);

        $this->assertEquals('light', ColorMode::get());
    }

    public function testGetWithTheLegacyDarkModeOption()
    {
        config([
            'adminlte.color_mode.default' => 'light',
            'adminlte.layout_dark_mode' => true,
        ]);

        $this->assertEquals('dark', ColorMode::get());

        // A truthy value is not accepted by the legacy option, but it is the
        // default of the server side preference, which enables it anyway.

        config(['adminlte.layout_dark_mode' => 1]);
        $this->assertEquals('dark', ColorMode::get());

        config(['adminlte.layout_dark_mode' => false]);
        $this->assertEquals('light', ColorMode::get());
    }

    public function testTheLegacyThemeModeOptionWinsOverTheDarkModeOne()
    {
        config([
            'adminlte.color_mode.default' => 'auto',
            'adminlte.layout_dark_mode' => true,
            'adminlte.layout_theme_mode' => 'light',
        ]);

        $this->assertEquals('light', ColorMode::get());
    }

    public function testGetWithTheServerSidePreference()
    {
        config(['adminlte.color_mode.default' => 'light']);

        $this->assertEquals('light', ColorMode::get());

        // A listener of the 'ReadingDarkModePreference' event may enable the
        // dark mode on the server side, and it wins over the configured mode.

        Event::listen(ReadingDarkModePreference::class, function ($event) {
            $event->darkMode->enable();
        });

        $this->assertEquals('dark', ColorMode::get());
    }

    public function testIsDarkModeEnabled()
    {
        // Without any preference, the dark mode is disabled.

        config(['adminlte.layout_dark_mode' => false]);
        $this->assertFalse(ColorMode::isDarkModeEnabled());

        // The legacy configuration option is the default preference.

        config(['adminlte.layout_dark_mode' => true]);
        $this->assertTrue(ColorMode::isDarkModeEnabled());

        // The preference stored on the session wins over the configuration.

        config(['adminlte.layout_dark_mode' => true]);
        Session::put('adminlte_dark_mode', false);
        $this->assertFalse(ColorMode::isDarkModeEnabled());

        Session::put('adminlte_dark_mode', true);
        $this->assertTrue(ColorMode::isDarkModeEnabled());
    }

    public function testIsDarkModeEnabledWithAnEventListener()
    {
        config(['adminlte.layout_dark_mode' => false]);

        Event::listen(ReadingDarkModePreference::class, function ($event) {
            $event->darkMode->enable();
        });

        $this->assertTrue(ColorMode::isDarkModeEnabled());
    }

    public function testIsRemembered()
    {
        // The persistence on the browser is enabled by default.

        config(['adminlte' => []]);
        $this->assertTrue(ColorMode::isRemembered());

        config(['adminlte.color_mode.remember' => true]);
        $this->assertTrue(ColorMode::isRemembered());

        config(['adminlte.color_mode.remember' => false]);
        $this->assertFalse(ColorMode::isRemembered());

        // The value is cast to a boolean one.

        config(['adminlte.color_mode.remember' => 0]);
        $this->assertFalse(ColorMode::isRemembered());

        config(['adminlte.color_mode.remember' => 'yes']);
        $this->assertTrue(ColorMode::isRemembered());
    }

    public function testMakeHtmlAttributesWithTheAutomaticMode()
    {
        // The automatic mode is resolved on the client side, so it declares
        // no attribute at all (the AdminLTE plugin must stay enabled).

        config([
            'adminlte.color_mode.default' => 'auto',
            'adminlte.color_mode.remember' => true,
        ]);

        $this->assertEquals([], ColorMode::makeHtmlAttributes());

        config(['adminlte.color_mode.remember' => false]);

        $this->assertEquals([], ColorMode::makeHtmlAttributes());
    }

    public function testMakeHtmlAttributesWithTheExplicitModes()
    {
        config(['adminlte.color_mode.remember' => true]);

        foreach (['light', 'dark'] as $mode) {
            config(['adminlte.color_mode.default' => $mode]);

            $this->assertEquals(
                ["data-bs-theme=\"{$mode}\""],
                ColorMode::makeHtmlAttributes()
            );
        }
    }

    public function testMakeHtmlAttributesWhenTheModeIsNotRemembered()
    {
        // Without the client side persistence, the AdminLTE color mode plugin
        // must be disabled, so it does not restore its stored value.

        config([
            'adminlte.color_mode.default' => 'dark',
            'adminlte.color_mode.remember' => false,
        ]);

        $this->assertEquals(
            ['data-bs-theme="dark"', 'data-lte-color-mode="off"'],
            ColorMode::makeHtmlAttributes()
        );
    }

    public function testMakeHtmlAttributesWithAnInvalidMode()
    {
        // An invalid mode behaves like the automatic one.

        config([
            'adminlte.color_mode.default' => 'invalid',
            'adminlte.color_mode.remember' => false,
        ]);

        $this->assertEquals([], ColorMode::makeHtmlAttributes());
    }

    public function testMakeHtmlAttributesWithTheLegacyOptions()
    {
        config([
            'adminlte.color_mode.default' => 'auto',
            'adminlte.color_mode.remember' => true,
            'adminlte.layout_dark_mode' => true,
        ]);

        $this->assertEquals(
            ['data-bs-theme="dark"'],
            ColorMode::makeHtmlAttributes()
        );
    }
}

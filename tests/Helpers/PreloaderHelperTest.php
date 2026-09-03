<?php

use JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper;

class PreloaderHelperTest extends TestCase
{
    public function testEnableDisablePreloaderFullscreenMode()
    {
        // Test with config enabled.

        config(['adminlte.preloader.enabled' => true]);

        $this->assertTrue(PreloaderHelper::isPreloaderEnabled());
        $this->assertTrue(PreloaderHelper::isPreloaderEnabled('fullscreen'));
        $this->assertFalse(PreloaderHelper::isPreloaderEnabled('cwrapper'));

        // Test with config disabled.

        config(['adminlte.preloader.enabled' => false]);

        $this->assertFalse(PreloaderHelper::isPreloaderEnabled());
        $this->assertFalse(PreloaderHelper::isPreloaderEnabled('fullscreen'));
        $this->assertFalse(PreloaderHelper::isPreloaderEnabled('cwrapper'));
    }

    public function testEnableDisablePreloaderCWrapperMode()
    {
        // Test with config enabled.

        config([
            'adminlte.preloader.enabled' => true,
            'adminlte.preloader.mode' => 'cwrapper',
        ]);

        $this->assertFalse(PreloaderHelper::isPreloaderEnabled());
        $this->assertFalse(PreloaderHelper::isPreloaderEnabled('fullscreen'));
        $this->assertTrue(PreloaderHelper::isPreloaderEnabled('cwrapper'));

        // Test with config disabled.

        config([
            'adminlte.preloader.enabled' => false,
            'adminlte.preloader.mode' => 'cwrapper',
        ]);

        $this->assertFalse(PreloaderHelper::isPreloaderEnabled());
        $this->assertFalse(PreloaderHelper::isPreloaderEnabled('fullscreen'));
        $this->assertFalse(PreloaderHelper::isPreloaderEnabled('cwrapper'));
    }

    public function testMakePreloaderClasses()
    {
        // Test without config.

        $data = PreloaderHelper::makePreloaderClasses();
        $this->assertEquals(
            'preloader flex-column justify-content-center align-items-center',
            $data
        );

        // Test with cwrapper mode enabled.

        config([
            'adminlte.preloader.enabled' => true,
            'adminlte.preloader.mode' => 'cwrapper',
        ]);

        $data = PreloaderHelper::makePreloaderClasses();

        $this->assertStringContainsString(
            'preloader flex-column justify-content-center align-items-center',
            $data
        );

        $this->assertStringContainsString('position-absolute', $data);
    }

    public function testMakePreloaderStyle()
    {
        // Test without config.

        $data = PreloaderHelper::makePreloaderStyle();
        $this->assertEquals('', $data);

        // Test with cwrapper mode enabled.

        config([
            'adminlte.preloader.enabled' => true,
            'adminlte.preloader.mode' => 'cwrapper',
        ]);

        $data = PreloaderHelper::makePreloaderStyle();
        $this->assertStringContainsString('z-index:', $data);
    }

    public function testPreloaderIsDisabledByDefault()
    {
        // Without any configuration, the preloader is disabled.

        config(['adminlte' => []]);

        $this->assertFalse(PreloaderHelper::isPreloaderEnabled());
        $this->assertFalse(PreloaderHelper::isPreloaderEnabled('cwrapper'));

        // So, the class and style builders return the base values.

        $this->assertEquals(
            'preloader flex-column justify-content-center align-items-center',
            PreloaderHelper::makePreloaderClasses()
        );

        $this->assertEquals('', PreloaderHelper::makePreloaderStyle());
    }

    public function testPreloaderWithAnUnknownMode()
    {
        // An unknown mode falls back to the fullscreen one, otherwise it
        // would silently disable the preloader.

        config([
            'adminlte.preloader.enabled' => true,
            'adminlte.preloader.mode' => 'dummy',
        ]);

        $this->assertEquals('fullscreen', PreloaderHelper::getMode());

        $this->assertTrue(PreloaderHelper::isPreloaderEnabled());
        $this->assertTrue(PreloaderHelper::isPreloaderEnabled('fullscreen'));
        $this->assertFalse(PreloaderHelper::isPreloaderEnabled('cwrapper'));

        // And an unknown mode is never enabled on its own name.

        $this->assertFalse(PreloaderHelper::isPreloaderEnabled('dummy'));

        // The style and classes of the fullscreen mode are the base ones.

        $this->assertStringNotContainsString(
            'position-absolute',
            PreloaderHelper::makePreloaderClasses()
        );

        $this->assertEquals('', PreloaderHelper::makePreloaderStyle());
    }

    public function testPreloaderModeIsNormalized()
    {
        config(['adminlte.preloader.enabled' => true]);

        // The mode is matched case insensitively and without surrounding
        // whitespace.

        config(['adminlte.preloader.mode' => ' CWrapper ']);

        $this->assertEquals('cwrapper', PreloaderHelper::getMode());
        $this->assertTrue(PreloaderHelper::isPreloaderEnabled('cwrapper'));

        // A value that is not even a string falls back to the default. Note a
        // truthy one used to enable every mode at once, and thus render the
        // preloader twice on the same page.

        foreach ([true, 1, null, ['cwrapper']] as $mode) {
            config(['adminlte.preloader.mode' => $mode]);

            $this->assertEquals('fullscreen', PreloaderHelper::getMode());
            $this->assertTrue(PreloaderHelper::isPreloaderEnabled('fullscreen'));
            $this->assertFalse(PreloaderHelper::isPreloaderEnabled('cwrapper'));
        }
    }

    public function testPreloaderModeDefaultsToFullscreen()
    {
        // Without an explicit mode, the fullscreen one is used.

        config(['adminlte.preloader' => ['enabled' => true]]);

        $this->assertTrue(PreloaderHelper::isPreloaderEnabled());
        $this->assertTrue(PreloaderHelper::isPreloaderEnabled('fullscreen'));
        $this->assertFalse(PreloaderHelper::isPreloaderEnabled('cwrapper'));
    }

    public function testMakePreloaderStyleOnTheCWrapperMode()
    {
        config([
            'adminlte.preloader.enabled' => true,
            'adminlte.preloader.mode' => 'cwrapper',
        ]);

        // The z-index must be below the one of the sidebars, the navbar and
        // the footer (they are between 1030 and 1040).

        $this->assertEquals('z-index:1000', PreloaderHelper::makePreloaderStyle());
    }
}

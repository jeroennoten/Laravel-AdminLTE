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
        // An unknown mode does not match any of the supported ones.

        config([
            'adminlte.preloader.enabled' => true,
            'adminlte.preloader.mode' => 'dummy',
        ]);

        $this->assertFalse(PreloaderHelper::isPreloaderEnabled());
        $this->assertFalse(PreloaderHelper::isPreloaderEnabled('fullscreen'));
        $this->assertFalse(PreloaderHelper::isPreloaderEnabled('cwrapper'));

        // But the mode may be checked explicitly.

        $this->assertTrue(PreloaderHelper::isPreloaderEnabled('dummy'));

        // The style and classes of an unknown mode are the base ones.

        $this->assertStringNotContainsString(
            'position-absolute',
            PreloaderHelper::makePreloaderClasses()
        );

        $this->assertEquals('', PreloaderHelper::makePreloaderStyle());
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

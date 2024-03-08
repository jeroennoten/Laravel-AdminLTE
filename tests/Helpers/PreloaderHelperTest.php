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
}

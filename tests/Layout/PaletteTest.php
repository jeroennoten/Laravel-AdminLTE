<?php

use JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;
use JeroenNoten\LaravelAdminLte\Layout\Palette;

class PaletteTest extends TestCase
{
    /**
     * Enables the extended palette, which provides the palette attributes.
     *
     * @param  bool  $v3Aliases  Whether to enable the v3 color aliases
     * @return void
     */
    protected function enablePalette($v3Aliases = false)
    {
        config([
            'adminlte.assets.extended_colors' => true,
            'adminlte.assets.extended_colors_v3_aliases' => $v3Aliases,
        ]);
    }

    public function testNoAttributesAreMadeWithoutConfig()
    {
        config(['adminlte' => []]);

        $this->assertEquals([], Palette::makeHtmlAttributes());
        $this->assertNull(Palette::getPrimary());
        $this->assertNull(Palette::getContrast());
    }

    public function testTheAvailableColorsRequireTheExtendedPalette()
    {
        // The palette attributes are only provided by the palette stylesheets,
        // so no color is available while those are disabled.

        config(['adminlte.assets.extended_colors' => false]);
        $this->assertEquals([], Palette::getAvailableColors());

        $this->enablePalette();
        $colors = Palette::getAvailableColors();

        $this->assertContains('teal', $colors);
        $this->assertContains('secondary', $colors);

        // The primary color itself is not a valid replacement of itself.

        $this->assertNotContains('primary', $colors);
    }

    public function testTheAvailableColorsFollowTheEnabledPalette()
    {
        $this->enablePalette();
        $this->assertContains('midnight', Palette::getAvailableColors());
        $this->assertNotContains('maroon', Palette::getAvailableColors());

        $this->enablePalette(true);
        $this->assertContains('maroon', Palette::getAvailableColors());
        $this->assertNotContains('midnight', Palette::getAvailableColors());
    }

    public function testThePrimaryColorIsIgnoredWithoutTheExtendedPalette()
    {
        config([
            'adminlte.assets.extended_colors' => false,
            'adminlte.assets.palette.primary' => 'teal',
        ]);

        $this->assertNull(Palette::getPrimary());
        $this->assertEquals([], Palette::makeHtmlAttributes());
    }

    public function testThePrimaryColorIsDeclaredOnTheHtmlTag()
    {
        $this->enablePalette();
        config(['adminlte.assets.palette.primary' => 'teal']);

        $this->assertEquals('teal', Palette::getPrimary());
        $this->assertContains(
            'data-lte-primary="teal"',
            Palette::makeHtmlAttributes()
        );
    }

    public function testAnUnknownPrimaryColorIsIgnored()
    {
        $this->enablePalette();

        foreach (['unknown', '', null, 42, ['teal'], 'maroon'] as $color) {
            config(['adminlte.assets.palette.primary' => $color]);
            $this->assertNull(Palette::getPrimary());
        }
    }

    public function testTheContrastCorrectionIsAutomaticOnTheV3Palette()
    {
        // The v3 palette misses the AA ratio on a set of its colors, so the
        // correction applies by default there, and only there.

        $this->enablePalette(true);
        $this->assertEquals('aa', Palette::getContrast());

        $this->enablePalette();
        $this->assertNull(Palette::getContrast());

        config(['adminlte.assets.extended_colors' => false]);
        $this->assertNull(Palette::getContrast());
    }

    public function testTheContrastCorrectionCanBeForcedAndDisabled()
    {
        $this->enablePalette();
        config(['adminlte.assets.palette.contrast' => 'aa']);
        $this->assertEquals('aa', Palette::getContrast());

        $this->enablePalette(true);
        config(['adminlte.assets.palette.contrast' => false]);
        $this->assertNull(Palette::getContrast());

        config(['adminlte.assets.palette.contrast' => 'unknown']);
        $this->assertNull(Palette::getContrast());
    }

    public function testTheContrastAttributeIsDeclaredOnTheHtmlTag()
    {
        $this->enablePalette(true);

        $this->assertContains(
            'data-lte-contrast="aa"',
            Palette::makeHtmlAttributes()
        );
    }

    public function testThePaletteAttributesReachTheLayoutHelper()
    {
        $this->enablePalette();
        config(['adminlte.assets.palette.primary' => 'navy']);

        $data = LayoutHelper::makeHtmlData();

        $this->assertStringContainsString('data-lte-primary="navy"', $data);
        $this->assertStringNotContainsString('data-lte-contrast', $data);
    }

    public function testThePaletteAttributesCombineWithTheOtherHtmlData()
    {
        $this->enablePalette(true);

        config([
            'adminlte.rtl.enabled' => true,
            'adminlte.color_mode.default' => 'dark',
            'adminlte.assets.palette.primary' => 'maroon',
        ]);

        $data = LayoutHelper::makeHtmlData();

        $this->assertStringContainsString('dir="rtl"', $data);
        $this->assertStringContainsString('data-bs-theme="dark"', $data);
        $this->assertStringContainsString('data-lte-primary="maroon"', $data);
        $this->assertStringContainsString('data-lte-contrast="aa"', $data);
    }

    public function testHasDarkTextOnTheBootstrapColors()
    {
        foreach (['info', 'warning', 'light'] as $color) {
            $this->assertTrue(UtilsHelper::hasDarkText($color), $color);
        }

        foreach (['primary', 'secondary', 'success', 'danger', 'dark'] as $c) {
            $this->assertFalse(UtilsHelper::hasDarkText($c), $c);
        }
    }

    public function testHasDarkTextOnAnEmptyColor()
    {
        foreach ([null, '', 0, [], false] as $color) {
            $this->assertFalse(UtilsHelper::hasDarkText($color));
        }
    }

    public function testHasDarkTextFollowsTheContrastCorrection()
    {
        // Without the correction, only the base set has a dark text.

        $this->enablePalette();
        $this->assertFalse(UtilsHelper::hasDarkText('teal'));

        // The correction of the v3 palette flips a set of its colors.

        $this->enablePalette(true);
        $this->assertTrue(UtilsHelper::hasDarkText('teal'));
        $this->assertTrue(UtilsHelper::hasDarkText('lightblue'));
        $this->assertFalse(UtilsHelper::hasDarkText('maroon'));

        config(['adminlte.assets.palette.contrast' => false]);
        $this->assertFalse(UtilsHelper::hasDarkText('teal'));
    }
}

<?php

use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class UtilsHelperTest extends TestCase
{
    public function testApplyHtmlEntityDecoder()
    {
        $this->assertEquals(
            '<b>Admin</b>LTE',
            UtilsHelper::applyHtmlEntityDecoder('&lt;b&gt;Admin&lt;/b&gt;LTE')
        );

        // A plain string is returned untouched.

        $this->assertEquals('AdminLTE', UtilsHelper::applyHtmlEntityDecoder('AdminLTE'));

        // A null value is returned as is.

        $this->assertNull(UtilsHelper::applyHtmlEntityDecoder(null));
    }

    public function testHasBottomMarginClassWithBottomMargins()
    {
        // Every 'mb-*' utility of the Bootstrap spacing scale is detected.

        foreach (range(0, 5) as $size) {
            $this->assertTrue(UtilsHelper::hasBottomMarginClass("mb-{$size}"));
        }

        // The 'auto' value is detected too.

        $this->assertTrue(UtilsHelper::hasBottomMarginClass('mb-auto'));
    }

    public function testHasBottomMarginClassWithVerticalMargins()
    {
        // The 'my-*' utilities also define a bottom margin.

        foreach (range(0, 5) as $size) {
            $this->assertTrue(UtilsHelper::hasBottomMarginClass("my-{$size}"));
        }

        $this->assertTrue(UtilsHelper::hasBottomMarginClass('my-auto'));
    }

    public function testHasBottomMarginClassOnASetOfClasses()
    {
        // The utility may be placed anywhere on the set of classes.

        $this->assertTrue(UtilsHelper::hasBottomMarginClass('p-2 mb-3 text-end'));
        $this->assertTrue(UtilsHelper::hasBottomMarginClass('shadow mb-0'));
        $this->assertTrue(UtilsHelper::hasBottomMarginClass('my-4 shadow'));
    }

    public function testHasBottomMarginClassWithoutBottomMargins()
    {
        // Other margin utilities are not a bottom margin.

        $this->assertFalse(UtilsHelper::hasBottomMarginClass('mt-3'));
        $this->assertFalse(UtilsHelper::hasBottomMarginClass('mx-3'));
        $this->assertFalse(UtilsHelper::hasBottomMarginClass('ms-3 me-3'));

        // A size out of the Bootstrap spacing scale is not detected.

        $this->assertFalse(UtilsHelper::hasBottomMarginClass('mb-6'));
        $this->assertFalse(UtilsHelper::hasBottomMarginClass('mb-10'));

        // A partial match is not a bottom margin utility.

        $this->assertFalse(UtilsHelper::hasBottomMarginClass('xmb-2'));
        $this->assertFalse(UtilsHelper::hasBottomMarginClass('mb-2x'));
        $this->assertFalse(UtilsHelper::hasBottomMarginClass('card-mb-2'));
    }

    public function testHasBottomMarginClassWithEmptyValues()
    {
        $this->assertFalse(UtilsHelper::hasBottomMarginClass(''));
        $this->assertFalse(UtilsHelper::hasBottomMarginClass('   '));
        $this->assertFalse(UtilsHelper::hasBottomMarginClass(null));
    }

    public function testGetExtendedColorsWhenDisabled()
    {
        config(['adminlte.assets.extended_colors' => false]);

        $this->assertEquals([], UtilsHelper::getExtendedColors());

        // The v3 aliases option has no effect when the extended colors are
        // disabled.

        config(['adminlte.assets.extended_colors_v3_aliases' => true]);

        $this->assertEquals([], UtilsHelper::getExtendedColors());
    }

    public function testGetExtendedColorsWithTheV4Palette()
    {
        config([
            'adminlte.assets.extended_colors' => true,
            'adminlte.assets.extended_colors_v3_aliases' => false,
        ]);

        $colors = UtilsHelper::getExtendedColors();

        $this->assertNotEmpty($colors);
        $this->assertContains('navy', $colors);
        $this->assertContains('olive', $colors);
        $this->assertContains('sky', $colors);
        $this->assertContains('midnight', $colors);

        // The v3 exclusive colors are not part of the v4 palette.

        $this->assertNotContains('lightblue', $colors);
        $this->assertNotContains('gray-dark', $colors);
    }

    public function testGetExtendedColorsWithTheV3Aliases()
    {
        config([
            'adminlte.assets.extended_colors' => true,
            'adminlte.assets.extended_colors_v3_aliases' => true,
        ]);

        $colors = UtilsHelper::getExtendedColors();

        $this->assertNotEmpty($colors);
        $this->assertContains('lightblue', $colors);
        $this->assertContains('gray-dark', $colors);
        $this->assertContains('maroon', $colors);

        // The v4 exclusive colors are not part of the v3 palette.

        $this->assertNotContains('midnight', $colors);
        $this->assertNotContains('graphite', $colors);
    }
}

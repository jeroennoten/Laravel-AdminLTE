<?php

use JeroenNoten\LaravelAdminLte\Layout\Direction;

class DirectionTest extends TestCase
{
    /**
     * Tear down this testing class.
     */
    public function tearDown(): void
    {
        app()->setLocale('en');

        parent::tearDown();
    }

    public function testIsRtlEnabledWithAnExplicitConfiguration()
    {
        // An explicit boolean configuration always wins over the locale.

        config([
            'adminlte.rtl.enabled' => true,
            'adminlte.rtl.locales' => ['ar'],
        ]);

        app()->setLocale('en');
        $this->assertTrue(Direction::isRtlEnabled());

        config(['adminlte.rtl.enabled' => false]);

        app()->setLocale('ar');
        $this->assertFalse(Direction::isRtlEnabled());
    }

    public function testIsRtlEnabledFallsBackToTheLocale()
    {
        config([
            'adminlte.rtl.enabled' => null,
            'adminlte.rtl.locales' => ['ar', 'fa', 'uz-AF'],
        ]);

        app()->setLocale('en');
        $this->assertFalse(Direction::isRtlEnabled());

        app()->setLocale('fa');
        $this->assertTrue(Direction::isRtlEnabled());

        // Any non boolean configuration enables the locale detection.

        foreach (['yes', 0, 1, [], 'auto'] as $cfg) {
            config(['adminlte.rtl.enabled' => $cfg]);

            app()->setLocale('en');
            $this->assertFalse(Direction::isRtlEnabled());

            app()->setLocale('ar');
            $this->assertTrue(Direction::isRtlEnabled());
        }
    }

    public function testIsRtlLocaleWithTheConfiguredLocales()
    {
        config(['adminlte.rtl.locales' => ['ar', 'he']]);

        $this->assertTrue(Direction::isRtlLocale('ar'));
        $this->assertTrue(Direction::isRtlLocale('he'));
        $this->assertFalse(Direction::isRtlLocale('en'));
        $this->assertFalse(Direction::isRtlLocale('es'));
    }

    public function testIsRtlLocaleIsCaseInsensitive()
    {
        config(['adminlte.rtl.locales' => ['AR', 'he']]);

        $this->assertTrue(Direction::isRtlLocale('ar'));
        $this->assertTrue(Direction::isRtlLocale('Ar'));
        $this->assertTrue(Direction::isRtlLocale('HE'));
    }

    public function testIsRtlLocaleWithRegionalLocales()
    {
        config(['adminlte.rtl.locales' => ['ar', 'uz_AF', 'he-IL']]);

        // The language part of a regional locale is checked too.

        $this->assertTrue(Direction::isRtlLocale('ar_EG'));
        $this->assertTrue(Direction::isRtlLocale('ar-eg'));

        // Both separators are normalized on the configured locales and on the
        // checked one.

        $this->assertTrue(Direction::isRtlLocale('uz-AF'));
        $this->assertTrue(Direction::isRtlLocale('uz_af'));
        $this->assertTrue(Direction::isRtlLocale('he_IL'));

        // A regional locale is not matched by its language alone.

        $this->assertFalse(Direction::isRtlLocale('uz'));
        $this->assertFalse(Direction::isRtlLocale('he'));
        $this->assertFalse(Direction::isRtlLocale('en_US'));
    }

    public function testIsRtlLocaleWithInvalidLocales()
    {
        config(['adminlte.rtl.locales' => ['ar']]);

        // Only a string locale can be checked.

        $this->assertFalse(Direction::isRtlLocale(null));
        $this->assertFalse(Direction::isRtlLocale(['ar']));
        $this->assertFalse(Direction::isRtlLocale(true));
        $this->assertFalse(Direction::isRtlLocale(''));
    }

    public function testIsRtlLocaleWithAnInvalidConfiguration()
    {
        // A non array configuration disables the detection.

        config(['adminlte.rtl.locales' => 'ar']);
        $this->assertFalse(Direction::isRtlLocale('ar'));

        config(['adminlte.rtl.locales' => null]);
        $this->assertFalse(Direction::isRtlLocale('ar'));

        // And so does an empty set of locales.

        config(['adminlte.rtl.locales' => []]);
        $this->assertFalse(Direction::isRtlLocale('ar'));
    }

    public function testIsRtlLocaleWithNonStringConfiguredLocales()
    {
        // The configured locales are cast to string before comparing them.

        config(['adminlte.rtl.locales' => [null, 123, 'ar']]);

        $this->assertTrue(Direction::isRtlLocale('ar'));
        $this->assertFalse(Direction::isRtlLocale('en'));
    }

    public function testGet()
    {
        config(['adminlte.rtl.enabled' => true]);
        $this->assertEquals('rtl', Direction::get());

        config(['adminlte.rtl.enabled' => false]);
        $this->assertEquals('ltr', Direction::get());

        // The direction is also resolved from the locale.

        config([
            'adminlte.rtl.enabled' => null,
            'adminlte.rtl.locales' => ['ar'],
        ]);

        app()->setLocale('ar');
        $this->assertEquals('rtl', Direction::get());

        app()->setLocale('en');
        $this->assertEquals('ltr', Direction::get());
    }
}

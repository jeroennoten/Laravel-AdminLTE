<?php

use Illuminate\Support\Facades\View;
use JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper;
use JeroenNoten\LaravelAdminLte\Layout\Layout;

class WrapperClassesTest extends TestCase
{
    /**
     * Tear down this testing class.
     */
    public function tearDown(): void
    {
        View::flushSections();

        parent::tearDown();
    }

    public function testMakeWithoutConfig()
    {
        config(['adminlte' => []]);

        $this->assertEquals(['app-wrapper'], Layout::makeWrapperClasses());
        $this->assertEquals('app-wrapper', LayoutHelper::makeWrapperClasses());
    }

    public function testMakeWithTheCompactMode()
    {
        config(['adminlte.layout_compact' => true]);

        $this->assertEquals(
            ['app-wrapper', 'compact-mode'],
            Layout::makeWrapperClasses()
        );
    }

    public function testTheCompactModeRequiresAnExplicitTrue()
    {
        foreach ([false, null, 0, '', 'yes', 1] as $value) {
            config(['adminlte.layout_compact' => $value]);

            $this->assertNotContains(
                'compact-mode',
                Layout::makeWrapperClasses(),
                var_export($value, true)
            );
        }
    }

    public function testMakeWithTheConfiguredClasses()
    {
        config(['adminlte.classes_wrapper' => 'my-cls other-cls']);

        $this->assertEquals(
            ['app-wrapper', 'my-cls other-cls'],
            Layout::makeWrapperClasses()
        );
    }

    public function testTheConfiguredClassesRequireAString()
    {
        foreach ([null, 0, false, ['my-cls'], ''] as $value) {
            config(['adminlte.classes_wrapper' => $value]);

            $this->assertEquals(
                ['app-wrapper'],
                Layout::makeWrapperClasses(),
                var_export($value, true)
            );
        }
    }

    public function testMakeWithEveryOption()
    {
        config([
            'adminlte.layout_compact' => true,
            'adminlte.classes_wrapper' => 'my-cls',
        ]);

        $this->assertEquals(
            'app-wrapper compact-mode my-cls',
            LayoutHelper::makeWrapperClasses()
        );
    }
}

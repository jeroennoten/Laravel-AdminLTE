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

        $this->assertEquals(['app-wrapper'], Layout::wrapperClasses());
        $this->assertEquals('app-wrapper', LayoutHelper::makeWrapperClasses());
    }

    public function testTheCompactModeIsNotAWrapperClass()
    {
        // Two AdminLTE rules compound 'compact-mode' with the sidebar tokens
        // on a single element, and those live on the body, so the class
        // belongs there. See BodyClassesTest for its coverage.

        config(['adminlte.layout_compact' => true]);

        $this->assertEquals(['app-wrapper'], Layout::wrapperClasses());
    }

    public function testMakeWithTheConfiguredClasses()
    {
        config(['adminlte.classes_wrapper' => 'my-cls other-cls']);

        $this->assertEquals(
            ['app-wrapper', 'my-cls other-cls'],
            Layout::wrapperClasses()
        );
    }

    public function testTheConfiguredClassesRequireAString()
    {
        foreach ([null, 0, false, ['my-cls'], ''] as $value) {
            config(['adminlte.classes_wrapper' => $value]);

            $this->assertEquals(
                ['app-wrapper'],
                Layout::wrapperClasses(),
                var_export($value, true)
            );
        }
    }

    public function testTheInnerMethodsDoNotCollideWithTheFacadeOnes()
    {
        // The facade methods return the classes joined into a string, while
        // the inner ones return the set of classes as an array. So, they can't
        // share their names.

        $this->assertIsArray(Layout::wrapperClasses());
        $this->assertIsArray(Layout::contentWrapperClasses());

        $this->assertIsString(LayoutHelper::makeWrapperClasses());
        $this->assertIsString(LayoutHelper::makeContentWrapperClasses());

        foreach (['makeWrapperClasses', 'makeContentWrapperClasses'] as $method) {
            $this->assertFalse(
                method_exists(Layout::class, $method),
                "The 'Layout::{$method}' method should not exist"
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
            'app-wrapper my-cls',
            LayoutHelper::makeWrapperClasses()
        );
    }
}

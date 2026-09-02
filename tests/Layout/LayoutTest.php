<?php

use Illuminate\Support\Facades\View;
use JeroenNoten\LaravelAdminLte\Layout\Layout;

class LayoutTest extends TestCase
{
    /**
     * The set of options that can be enabled by config or by a view section,
     * mapped to the method of the class that resolves them.
     *
     * @var array
     */
    protected $sectionOptions = [
        'layout_topnav' => 'isTopnavEnabled',
        'layout_boxed' => 'isBoxedEnabled',
        'right_sidebar' => 'isRightSidebarEnabled',
    ];

    /**
     * Tear down this testing class.
     */
    public function tearDown(): void
    {
        View::flushSections();

        parent::tearDown();
    }

    public function testTheOptionsResolvedFromTheConfiguration()
    {
        foreach ($this->sectionOptions as $option => $method) {
            config(["adminlte.{$option}" => true]);
            $this->assertTrue(Layout::{$method}(), "Failed on {$option}");

            config(["adminlte.{$option}" => false]);
            $this->assertFalse(Layout::{$method}(), "Failed on {$option}");

            // A missing option is disabled by default.

            config(["adminlte.{$option}" => null]);
            $this->assertFalse(Layout::{$method}(), "Failed on {$option}");
        }
    }

    public function testTheOptionsResolvedFromTheViewSections()
    {
        foreach ($this->sectionOptions as $option => $method) {
            config(["adminlte.{$option}" => false]);

            View::inject($option, 'dummy-content');
            $this->assertTrue(Layout::{$method}(), "Failed on {$option}");

            View::flushSections();
            $this->assertFalse(Layout::{$method}(), "Failed on {$option}");
        }
    }

    public function testTheOptionsIgnoreAnEmptyViewSection()
    {
        foreach ($this->sectionOptions as $option => $method) {
            config(["adminlte.{$option}" => false]);

            View::inject($option, '');
            $this->assertFalse(Layout::{$method}(), "Failed on {$option}");

            View::flushSections();
        }
    }

    public function testTheOptionsAcceptTruthyConfigurationValues()
    {
        // Any truthy value enables an option, and the result is always a
        // boolean value.

        foreach ($this->sectionOptions as $option => $method) {
            config(["adminlte.{$option}" => 1]);
            $this->assertTrue(Layout::{$method}(), "Failed on {$option}");

            config(["adminlte.{$option}" => 'yes']);
            $this->assertTrue(Layout::{$method}(), "Failed on {$option}");

            config(["adminlte.{$option}" => 0]);
            $this->assertFalse(Layout::{$method}(), "Failed on {$option}");
        }
    }

    public function testMakeContentWrapperClassesWithoutConfig()
    {
        config(['adminlte' => []]);

        $this->assertEquals(['app-main'], Layout::contentWrapperClasses());
    }

    public function testMakeContentWrapperClassesWithCustomClasses()
    {
        config([
            'adminlte.preloader.enabled' => false,
            'adminlte.classes_content_wrapper' => 'class1 class2',
        ]);

        $this->assertEquals(
            ['app-main', 'class1 class2'],
            Layout::contentWrapperClasses()
        );
    }

    public function testMakeContentWrapperClassesWithAnInvalidConfig()
    {
        config(['adminlte.preloader.enabled' => false]);

        // Only a non empty string is accepted as custom classes.

        foreach (['', null, false, ['p-3'], 10] as $cfg) {
            config(['adminlte.classes_content_wrapper' => $cfg]);

            $this->assertEquals(
                ['app-main'],
                Layout::contentWrapperClasses()
            );
        }
    }

    public function testMakeContentWrapperClassesWithThePreloaderModes()
    {
        config([
            'adminlte.classes_content_wrapper' => '',
            'adminlte.preloader.enabled' => true,
            'adminlte.preloader.mode' => 'cwrapper',
        ]);

        // The preloader of the content wrapper is positioned over it.

        $this->assertEquals(
            ['app-main', 'position-relative'],
            Layout::contentWrapperClasses()
        );

        // The fullscreen preloader does not affect the content wrapper.

        config(['adminlte.preloader.mode' => 'fullscreen']);
        $this->assertEquals(['app-main'], Layout::contentWrapperClasses());

        // Neither does a disabled preloader.

        config([
            'adminlte.preloader.enabled' => false,
            'adminlte.preloader.mode' => 'cwrapper',
        ]);

        $this->assertEquals(['app-main'], Layout::contentWrapperClasses());
    }

    public function testMakeContentWrapperClassesWithAllTheOptions()
    {
        config([
            'adminlte.classes_content_wrapper' => 'p-3',
            'adminlte.preloader.enabled' => true,
            'adminlte.preloader.mode' => 'cwrapper',
        ]);

        $this->assertEquals(
            ['app-main', 'p-3', 'position-relative'],
            Layout::contentWrapperClasses()
        );
    }
}

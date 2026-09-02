<?php

use Illuminate\Support\Facades\View;
use JeroenNoten\LaravelAdminLte\Layout\Sidebar;

class SidebarTest extends TestCase
{
    /**
     * Tear down this testing class.
     */
    public function tearDown(): void
    {
        View::flushSections();

        parent::tearDown();
    }

    public function testMakeBodyClassesWithoutConfig()
    {
        config(['adminlte' => []]);

        // The expand breakpoint and the mini mode are the defaults.

        $this->assertEquals(
            ['sidebar-expand-lg', 'sidebar-mini'],
            Sidebar::makeBodyClasses()
        );
    }

    public function testMakeBodyClassesWithTheExpandBreakpoints()
    {
        config(['adminlte.sidebar_mini' => false]);

        foreach (['sm', 'md', 'lg', 'xl', 'xxl'] as $breakpoint) {
            config(['adminlte.sidebar_expand' => $breakpoint]);

            $this->assertEquals(
                ["sidebar-expand-{$breakpoint}"],
                Sidebar::makeBodyClasses()
            );
        }

        // An unsupported breakpoint adds no class at all.

        foreach (['xs', 'invalid', null, '', true] as $breakpoint) {
            config(['adminlte.sidebar_expand' => $breakpoint]);

            $this->assertEquals([], Sidebar::makeBodyClasses());
        }
    }

    public function testMakeBodyClassesWithTheMiniMode()
    {
        config(['adminlte.sidebar_expand' => null]);

        config(['adminlte.sidebar_mini' => true]);
        $this->assertEquals(['sidebar-mini'], Sidebar::makeBodyClasses());

        config(['adminlte.sidebar_mini' => false]);
        $this->assertEquals([], Sidebar::makeBodyClasses());

        // A null value disables the mini mode too.

        config(['adminlte.sidebar_mini' => null]);
        $this->assertEquals([], Sidebar::makeBodyClasses());

        // And any truthy non string value enables it.

        config(['adminlte.sidebar_mini' => 1]);
        $this->assertEquals(['sidebar-mini'], Sidebar::makeBodyClasses());
    }

    public function testMakeBodyClassesWithTheLegacyMiniTokens()
    {
        config([
            'adminlte.sidebar_expand' => null,
            'adminlte.sidebar_mini' => 'xs',
        ]);

        // The legacy breakpoint tokens of the option mean 'enabled'.

        foreach (['xs', 'sm', 'md', 'lg', 'xl', 'xxl'] as $token) {
            config(['adminlte.sidebar_mini' => $token]);

            $this->assertEquals(
                ['sidebar-mini'],
                Sidebar::makeBodyClasses(),
                "The legacy token '{$token}' did not enable the mini sidebar"
            );
        }

        // Any other string disables the mini mode.

        foreach (['invalid', '', 'true'] as $token) {
            config(['adminlte.sidebar_mini' => $token]);
            $this->assertEquals([], Sidebar::makeBodyClasses());
        }
    }

    public function testMakeBodyClassesWithTheCollapsedState()
    {
        config([
            'adminlte.sidebar_expand' => null,
            'adminlte.sidebar_mini' => false,
        ]);

        config(['adminlte.sidebar_collapse' => true]);
        $this->assertEquals(['sidebar-collapse'], Sidebar::makeBodyClasses());

        config(['adminlte.sidebar_collapse' => false]);
        $this->assertEquals([], Sidebar::makeBodyClasses());

        // The view section enables the collapsed state as well.

        View::inject('sidebar_collapse', 'dummy-content');
        $this->assertEquals(['sidebar-collapse'], Sidebar::makeBodyClasses());

        View::flushSections();
        $this->assertEquals([], Sidebar::makeBodyClasses());
    }

    public function testMakeBodyClassesWithTheWithoutHoverOption()
    {
        config([
            'adminlte.sidebar_expand' => null,
            'adminlte.sidebar_mini' => false,
        ]);

        config(['adminlte.sidebar_without_hover' => true]);

        $this->assertEquals(
            ['sidebar-without-hover'],
            Sidebar::makeBodyClasses()
        );

        config(['adminlte.sidebar_without_hover' => false]);
        $this->assertEquals([], Sidebar::makeBodyClasses());
    }

    public function testMakeBodyClassesWithEveryOptionEnabled()
    {
        config([
            'adminlte.sidebar_expand' => 'md',
            'adminlte.sidebar_mini' => true,
            'adminlte.sidebar_collapse' => true,
            'adminlte.sidebar_without_hover' => true,
        ]);

        $this->assertEquals(
            [
                'sidebar-expand-md',
                'sidebar-mini',
                'sidebar-collapse',
                'sidebar-without-hover',
            ],
            Sidebar::makeBodyClasses()
        );
    }

    public function testMakeBodyClassesOnTheTopnavLayout()
    {
        // The topnav layout has no sidebar at all.

        config([
            'adminlte.layout_topnav' => true,
            'adminlte.sidebar_expand' => 'md',
            'adminlte.sidebar_mini' => true,
            'adminlte.sidebar_collapse' => true,
            'adminlte.sidebar_without_hover' => true,
        ]);

        $this->assertEquals([], Sidebar::makeBodyClasses());

        // The topnav layout enabled by a view section behaves the same.

        config(['adminlte.layout_topnav' => false]);
        View::inject('layout_topnav', 'dummy-content');

        $this->assertEquals([], Sidebar::makeBodyClasses());
    }

    public function testMakeClasses()
    {
        // Without configuration, the default classes are used.

        config(['adminlte' => []]);

        $this->assertEquals(
            ['app-sidebar', 'bg-body-secondary shadow'],
            Sidebar::makeClasses()
        );

        // A custom set of classes replaces the default one.

        config(['adminlte.classes_sidebar' => 'bg-dark']);
        $this->assertEquals(['app-sidebar', 'bg-dark'], Sidebar::makeClasses());
    }

    public function testMakeClassesWithAnInvalidConfig()
    {
        // Only a non empty string is accepted as custom classes.

        foreach (['', null, false, ['bg-dark'], 10] as $cfg) {
            config(['adminlte.classes_sidebar' => $cfg]);

            $this->assertEquals(['app-sidebar'], Sidebar::makeClasses());
        }
    }

    public function testMakeNavClassesWithoutConfig()
    {
        config(['adminlte' => []]);

        // Without configuration, only the base classes of the menu are used.

        $this->assertEquals(
            ['nav', 'sidebar-menu', 'flex-column'],
            Sidebar::makeNavClasses()
        );
    }

    public function testMakeNavClassesWithTheStyleVariants()
    {
        config(['adminlte' => []]);

        $variants = [
            'sidebar_nav_compact' => 'nav-compact',
            'sidebar_nav_indent' => 'nav-indent',
            'sidebar_nav_pills' => 'nav-pills',
        ];

        foreach ($variants as $option => $class) {
            config(["adminlte.{$option}" => true]);

            $this->assertEquals(
                ['nav', 'sidebar-menu', 'flex-column', $class],
                Sidebar::makeNavClasses()
            );

            config(["adminlte.{$option}" => false]);

            $this->assertEquals(
                ['nav', 'sidebar-menu', 'flex-column'],
                Sidebar::makeNavClasses()
            );
        }
    }

    public function testMakeNavClassesWithEveryStyleVariant()
    {
        config([
            'adminlte.sidebar_nav_compact' => true,
            'adminlte.sidebar_nav_indent' => true,
            'adminlte.sidebar_nav_pills' => true,
        ]);

        // The variants are always added on the same order.

        $this->assertEquals(
            [
                'nav',
                'sidebar-menu',
                'flex-column',
                'nav-compact',
                'nav-indent',
                'nav-pills',
            ],
            Sidebar::makeNavClasses()
        );
    }

    public function testMakeNavClassesWithTheCustomClasses()
    {
        config([
            'adminlte.sidebar_nav_compact' => false,
            'adminlte.sidebar_nav_indent' => false,
            'adminlte.sidebar_nav_pills' => false,
            'adminlte.classes_sidebar_nav' => 'my-cls1 my-cls2',
        ]);

        // The custom classes always go after the built-in variants.

        $this->assertEquals(
            ['nav', 'sidebar-menu', 'flex-column', 'my-cls1 my-cls2'],
            Sidebar::makeNavClasses()
        );

        config(['adminlte.sidebar_nav_indent' => true]);

        $this->assertEquals(
            ['nav', 'sidebar-menu', 'flex-column', 'nav-indent', 'my-cls1 my-cls2'],
            Sidebar::makeNavClasses()
        );
    }

    public function testMakeNavClassesWithInvalidCustomClasses()
    {
        config(['adminlte' => []]);

        // Only a non empty string is accepted as custom classes.

        foreach (['', null, false, ['nav-compact'], 10] as $cfg) {
            config(['adminlte.classes_sidebar_nav' => $cfg]);

            $this->assertEquals(
                ['nav', 'sidebar-menu', 'flex-column'],
                Sidebar::makeNavClasses()
            );
        }
    }

    public function testMakeAttributesWithTheSidebarTheme()
    {
        config(['adminlte.sidebar_collapse_remember' => false]);

        foreach (['light', 'dark'] as $theme) {
            config(['adminlte.sidebar_theme' => $theme]);

            $this->assertEquals(
                ["data-bs-theme=\"{$theme}\""],
                Sidebar::makeAttributes()
            );
        }

        // An unsupported theme is ignored, so the sidebar inherits the color
        // mode of the page.

        foreach (['invalid', '', null, true] as $theme) {
            config(['adminlte.sidebar_theme' => $theme]);

            $this->assertEquals([], Sidebar::makeAttributes());
        }
    }

    public function testMakeAttributesWithoutConfig()
    {
        config(['adminlte' => []]);

        // The dark theme is the default one.

        $this->assertEquals(['data-bs-theme="dark"'], Sidebar::makeAttributes());
    }

    public function testMakeAttributesWithTheCollapseRememberOption()
    {
        config(['adminlte.sidebar_theme' => null]);

        config(['adminlte.sidebar_collapse_remember' => true]);

        $this->assertEquals(
            ['data-enable-persistence="true"'],
            Sidebar::makeAttributes()
        );

        config(['adminlte.sidebar_collapse_remember' => false]);
        $this->assertEquals([], Sidebar::makeAttributes());
    }

    public function testMakeAttributesWithTheBreakpointOption()
    {
        config([
            'adminlte.sidebar_theme' => null,
            'adminlte.sidebar_collapse_remember' => false,
        ]);

        // Any numeric value (integer, float or numeric string) is accepted.

        foreach ([768, 991.98, '1200'] as $breakpoint) {
            config(['adminlte.sidebar_breakpoint' => $breakpoint]);

            $this->assertEquals(
                ["data-sidebar-breakpoint=\"{$breakpoint}\""],
                Sidebar::makeAttributes()
            );
        }

        // A non numeric value is ignored, so the plugin keeps its own default.

        foreach ([null, '', 'invalid', true, false, ['768']] as $breakpoint) {
            config(['adminlte.sidebar_breakpoint' => $breakpoint]);

            $this->assertEquals([], Sidebar::makeAttributes());
        }
    }

    public function testMakeAttributesWithoutTheBreakpointOption()
    {
        config(['adminlte' => []]);

        // Without the option, no breakpoint attribute is added at all.

        $this->assertStringNotContainsString(
            'data-sidebar-breakpoint',
            implode(' ', Sidebar::makeAttributes())
        );
    }

    public function testMakeAttributesWithEveryOptionEnabled()
    {
        config([
            'adminlte.sidebar_theme' => 'light',
            'adminlte.sidebar_collapse_remember' => true,
            'adminlte.sidebar_breakpoint' => 768,
        ]);

        $this->assertEquals(
            [
                'data-bs-theme="light"',
                'data-enable-persistence="true"',
                'data-sidebar-breakpoint="768"',
            ],
            Sidebar::makeAttributes()
        );
    }
}

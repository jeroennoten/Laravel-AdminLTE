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

        // The breakpoint is matched case insensitively and without any
        // surrounding whitespace.

        config(['adminlte.sidebar_expand' => ' MD ']);

        $this->assertEquals(['sidebar-expand-md'], Sidebar::makeBodyClasses());

        // An unsupported breakpoint falls back to the default one. Emitting
        // no expand class at all would leave the sidebar without a column on
        // the layout grid, and the push menu plugin without a breakpoint.

        foreach (['xs', 'invalid', null, '', true, 992] as $breakpoint) {
            config(['adminlte.sidebar_expand' => $breakpoint]);

            $this->assertEquals(
                ['sidebar-expand-lg'],
                Sidebar::makeBodyClasses(),
                var_export($breakpoint, true)
            );
        }
    }

    public function testMakeBodyClassesWithTheMiniMode()
    {
        // Note the expand class is always part of the set, the sidebar layout
        // does not work without it.

        config(['adminlte.sidebar_mini' => true]);
        $this->assertEquals(['sidebar-expand-lg', 'sidebar-mini'], Sidebar::makeBodyClasses());

        config(['adminlte.sidebar_mini' => false]);
        $this->assertEquals(['sidebar-expand-lg'], Sidebar::makeBodyClasses());

        // A null value disables the mini mode too.

        config(['adminlte.sidebar_mini' => null]);
        $this->assertEquals(['sidebar-expand-lg'], Sidebar::makeBodyClasses());

        // And any truthy non string value enables it.

        config(['adminlte.sidebar_mini' => 1]);
        $this->assertEquals(['sidebar-expand-lg', 'sidebar-mini'], Sidebar::makeBodyClasses());
    }

    public function testMakeBodyClassesWithTheLegacyMiniTokens()
    {
        config(['adminlte.sidebar_mini' => 'xs']);

        // The legacy breakpoint tokens of the option mean 'enabled'.

        foreach (['xs', 'sm', 'md', 'lg', 'xl', 'xxl'] as $token) {
            config(['adminlte.sidebar_mini' => $token]);

            $this->assertEquals(
                ['sidebar-expand-lg', 'sidebar-mini'],
                Sidebar::makeBodyClasses(),
                "The legacy token '{$token}' did not enable the mini sidebar"
            );
        }

        // Any other string disables the mini mode.

        foreach (['invalid', '', 'true'] as $token) {
            config(['adminlte.sidebar_mini' => $token]);
            $this->assertEquals(['sidebar-expand-lg'], Sidebar::makeBodyClasses());
        }
    }

    public function testMakeBodyClassesWithTheCollapsedState()
    {
        config(['adminlte.sidebar_mini' => false]);

        config(['adminlte.sidebar_collapse' => true]);
        $this->assertEquals(['sidebar-expand-lg', 'sidebar-collapse'], Sidebar::makeBodyClasses());

        config(['adminlte.sidebar_collapse' => false]);
        $this->assertEquals(['sidebar-expand-lg'], Sidebar::makeBodyClasses());

        // The view section enables the collapsed state as well.

        View::inject('sidebar_collapse', 'dummy-content');
        $this->assertEquals(['sidebar-expand-lg', 'sidebar-collapse'], Sidebar::makeBodyClasses());

        View::flushSections();
        $this->assertEquals(['sidebar-expand-lg'], Sidebar::makeBodyClasses());
    }

    public function testMakeBodyClassesWithTheWithoutHoverOption()
    {
        config(['adminlte.sidebar_mini' => false]);

        config(['adminlte.sidebar_without_hover' => true]);

        $this->assertEquals(
            ['sidebar-expand-lg', 'sidebar-without-hover'],
            Sidebar::makeBodyClasses()
        );

        config(['adminlte.sidebar_without_hover' => false]);
        $this->assertEquals(['sidebar-expand-lg'], Sidebar::makeBodyClasses());
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

    public function testTheStyleVariantsAreBodyClasses()
    {
        // AdminLTE compounds the compact and the indent variants with the
        // sidebar body tokens on a single element and then reaches the sidebar
        // as a descendant, so those two belong to the body.

        $variants = [
            'sidebar_nav_compact' => 'nav-compact',
            'sidebar_nav_indent' => 'nav-indent',
        ];

        foreach ($variants as $option => $class) {
            config(['adminlte' => [$option => true]]);

            $this->assertContains($class, Sidebar::makeBodyClasses(), $class);
            $this->assertNotContains($class, Sidebar::makeNavClasses(), $class);

            config(['adminlte' => [$option => false]]);

            $this->assertNotContains($class, Sidebar::makeBodyClasses(), $class);
        }
    }

    public function testThePillsVariantIsAMenuClass()
    {
        // The pills variant is the plain Bootstrap one: no rule compounds it
        // with a layout token, and its two rules are descendant selectors. On
        // the body it would also paint the navbar links and the open user menu
        // toggler, so it belongs to the menu element.

        config(['adminlte' => ['sidebar_nav_pills' => true]]);

        $this->assertContains('nav-pills', Sidebar::makeNavClasses());
        $this->assertNotContains('nav-pills', Sidebar::makeBodyClasses());

        config(['adminlte' => ['sidebar_nav_pills' => false]]);

        $this->assertNotContains('nav-pills', Sidebar::makeNavClasses());
    }

    public function testEveryStyleVariantIsAddedOnTheSameOrder()
    {
        config([
            'adminlte.sidebar_nav_compact' => true,
            'adminlte.sidebar_nav_indent' => true,
        ]);

        $classes = Sidebar::makeBodyClasses();
        $variants = array_values(array_intersect(
            $classes,
            ['nav-compact', 'nav-indent']
        ));

        $this->assertEquals(
            ['nav-compact', 'nav-indent'],
            $variants
        );
    }

    public function testMakeNavClassesWithTheCustomClasses()
    {
        config([
            'adminlte.sidebar_nav_compact' => false,
            'adminlte.classes_sidebar_nav' => 'my-cls1 my-cls2',
        ]);

        $this->assertEquals(
            ['nav', 'sidebar-menu', 'flex-column', 'my-cls1 my-cls2'],
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

        // The theme is matched case insensitively and without surrounding
        // whitespace.

        config(['adminlte.sidebar_theme' => ' Light ']);

        $this->assertEquals(['data-bs-theme="light"'], Sidebar::makeAttributes());

        // Null, false and an empty value opt out of the attribute, so the
        // sidebar inherits the color mode of the page.

        foreach ([null, false, ''] as $theme) {
            config(['adminlte.sidebar_theme' => $theme]);

            $this->assertEquals([], Sidebar::makeAttributes());
        }

        // Any other value falls back to the documented default, otherwise a
        // typo would silently drop the theme of the sidebar.

        foreach (['invalid', true, 1, ['dark']] as $theme) {
            config(['adminlte.sidebar_theme' => $theme]);

            $this->assertEquals(
                ['data-bs-theme="dark"'],
                Sidebar::makeAttributes(),
                var_export($theme, true)
            );
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

        // The published width is the upper bound of the media query AdminLTE
        // ships for the resolved expand breakpoint, and not the configured
        // value: it is the very number the stylesheet hands to the plugin.

        $widths = [[768, '767.98'], [767.98, '767.98'], ['1200', '1199.98']];

        foreach ($widths as [$breakpoint, $expected]) {
            config(['adminlte.sidebar_breakpoint' => $breakpoint]);

            $this->assertEquals(
                ["data-sidebar-breakpoint=\"{$expected}\""],
                Sidebar::makeAttributes()
            );
        }

        // A width resolving to the breakpoint the plugin already assumes adds
        // no attribute, and neither does a non numeric value.

        foreach ([992, 991.98, null, '', 'invalid', true, false, ['768']] as $breakpoint) {
            config(['adminlte.sidebar_breakpoint' => $breakpoint]);

            $this->assertEquals([], Sidebar::makeAttributes());
        }
    }

    public function testMakeAttributesPublishTheExpandBreakpointWidth()
    {
        config([
            'adminlte.sidebar_theme' => null,
            'adminlte.sidebar_collapse_remember' => false,
            'adminlte.sidebar_breakpoint' => null,
        ]);

        // The push menu plugin only reads the width of the expand class back
        // from the stylesheet while that media query is active, and otherwise
        // assumes the 'lg' one. So, any other expand breakpoint has to publish
        // its width, or the script and the media queries would disagree.

        $widths = [
            'sm' => '575.98',
            'md' => '767.98',
            'xl' => '1199.98',
            'xxl' => '1399.98',
        ];

        foreach ($widths as $expand => $expected) {
            config(['adminlte.sidebar_expand' => $expand]);

            $this->assertEquals(
                ["data-sidebar-breakpoint=\"{$expected}\""],
                Sidebar::makeAttributes()
            );
        }

        // The 'lg' breakpoint is the one the plugin already assumes.

        config(['adminlte.sidebar_expand' => 'lg']);

        $this->assertEquals([], Sidebar::makeAttributes());
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
                'data-sidebar-breakpoint="767.98"',
            ],
            Sidebar::makeAttributes()
        );
    }

    public function testMakeNavAttributesWithoutConfig()
    {
        config(['adminlte' => []]);

        // The defaults of the treeview plugin need no attribute at all.

        $this->assertEquals([], Sidebar::makeNavAttributes());
    }

    public function testMakeNavAttributesWithTheAccordionOption()
    {
        config(['adminlte.sidebar_nav_accordion' => false]);

        $this->assertEquals(['data-accordion="false"'], Sidebar::makeNavAttributes());

        config(['adminlte.sidebar_nav_accordion' => true]);

        $this->assertEquals([], Sidebar::makeNavAttributes());
    }

    public function testTheNavAnimationSpeedAcceptsOnlyNumbers()
    {
        // A number is honored, and the zero speed disables the animation.

        foreach ([0, 150, '250', 12.5] as $speed) {
            config(['adminlte.sidebar_nav_animation_speed' => $speed]);

            $this->assertEquals(
                (int) $speed,
                Sidebar::navAnimationSpeed(),
                var_export($speed, true)
            );
        }

        // The plugin parses the attribute with 'Number()', so a value it can
        // not parse would animate the treeview for 'NaN' milliseconds. Such a
        // value falls back to the default speed instead.

        foreach (['fast', '', null, true, ['300']] as $speed) {
            config(['adminlte.sidebar_nav_animation_speed' => $speed]);

            $this->assertEquals(
                Sidebar::DEFAULT_NAV_ANIMATION_SPEED,
                Sidebar::navAnimationSpeed(),
                var_export($speed, true)
            );
        }

        // And a negative speed is clamped, the plugin would never finish the
        // animation otherwise.

        config(['adminlte.sidebar_nav_animation_speed' => -5]);

        $this->assertEquals(0, Sidebar::navAnimationSpeed());
    }

    public function testMakeNavAttributesWithTheAnimationSpeed()
    {
        // The default speed is the one of the plugin, so it is not declared.

        config(['adminlte.sidebar_nav_animation_speed' => 300]);

        $this->assertEquals([], Sidebar::makeNavAttributes());

        config(['adminlte.sidebar_nav_animation_speed' => 500]);

        $this->assertEquals(['data-animation-speed="500"'], Sidebar::makeNavAttributes());

        // An unparsable speed emits no attribute, since it falls back to the
        // default one.

        config(['adminlte.sidebar_nav_animation_speed' => 'fast']);

        $this->assertEquals([], Sidebar::makeNavAttributes());
    }
}

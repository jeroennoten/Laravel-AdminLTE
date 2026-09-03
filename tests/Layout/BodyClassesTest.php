<?php

use Illuminate\Support\Facades\View;
use JeroenNoten\LaravelAdminLte\Layout\BodyClasses;
use JeroenNoten\LaravelAdminLte\Layout\Sidebar;

class BodyClassesTest extends TestCase
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

        // Without configuration, the sidebar defaults are expected. Note
        // the fixed sidebar is part of them, since it is the shipped default.

        $this->assertEquals(
            ['layout-fixed', 'sidebar-expand-lg', 'sidebar-mini', 'bg-body-tertiary'],
            BodyClasses::make()
        );
    }

    public function testMakeWithTheFixedSidebar()
    {
        // Note the sidebar always contributes its expand class, the layout
        // grid gives it no column at all without one.

        config([
            'adminlte.sidebar_mini' => false,
            'adminlte.classes_body' => '',
        ]);

        config(['adminlte.layout_fixed_sidebar' => true]);
        $this->assertEquals(['layout-fixed', 'sidebar-expand-lg'], BodyClasses::make());

        config(['adminlte.layout_fixed_sidebar' => false]);
        $this->assertEquals(['sidebar-expand-lg'], BodyClasses::make());

        // The fixed sidebar is not compatible with the topnav layout.

        config([
            'adminlte.layout_fixed_sidebar' => true,
            'adminlte.layout_topnav' => true,
        ]);

        $this->assertEquals([], BodyClasses::make());

        // Neither when the topnav layout comes from a view section.

        config(['adminlte.layout_topnav' => false]);
        View::inject('layout_topnav', 'dummy-content');

        $this->assertEquals([], BodyClasses::make());
    }

    public function testMakeWithTheFixedSections()
    {
        config([
            'adminlte.layout_fixed_sidebar' => false,
            'adminlte.sidebar_mini' => false,
            'adminlte.classes_body' => '',
            'adminlte.layout_fixed_navbar' => true,
            'adminlte.layout_fixed_footer' => true,
        ]);

        $this->assertEquals(
            ['fixed-header', 'fixed-footer', 'sidebar-expand-lg'],
            BodyClasses::make()
        );

        // The fixed sections are compatible with the topnav layout.

        config(['adminlte.layout_topnav' => true]);

        $this->assertEquals(
            ['fixed-header', 'fixed-footer'],
            BodyClasses::make()
        );
    }

    public function testIsFixedNavbarEnabled()
    {
        config(['adminlte.layout_fixed_navbar' => true]);
        $this->assertTrue(BodyClasses::isFixedNavbarEnabled());

        config(['adminlte.layout_fixed_navbar' => false]);
        $this->assertFalse(BodyClasses::isFixedNavbarEnabled());

        config(['adminlte.layout_fixed_navbar' => null]);
        $this->assertFalse(BodyClasses::isFixedNavbarEnabled());

        // Any truthy value enables the fixed mode.

        config(['adminlte.layout_fixed_navbar' => 1]);
        $this->assertTrue(BodyClasses::isFixedNavbarEnabled());
    }

    public function testIsFixedFooterEnabled()
    {
        config(['adminlte.layout_fixed_footer' => true]);
        $this->assertTrue(BodyClasses::isFixedFooterEnabled());

        config(['adminlte.layout_fixed_footer' => false]);
        $this->assertFalse(BodyClasses::isFixedFooterEnabled());

        config(['adminlte.layout_fixed_footer' => null]);
        $this->assertFalse(BodyClasses::isFixedFooterEnabled());

        config(['adminlte.layout_fixed_footer' => 'yes']);
        $this->assertTrue(BodyClasses::isFixedFooterEnabled());
    }

    public function testTheFixedSectionsWithTheLegacyResponsiveConfig()
    {
        // AdminLTE v4 has no responsive fixed modes, so the legacy array
        // configuration is enabled when any of its values is enabled.

        config([
            'adminlte.layout_fixed_navbar' => ['xs' => true, 'lg' => false],
            'adminlte.layout_fixed_footer' => ['md' => true],
        ]);

        $this->assertTrue(BodyClasses::isFixedNavbarEnabled());
        $this->assertTrue(BodyClasses::isFixedFooterEnabled());

        // An array without enabled values keeps the sections unfixed.

        config([
            'adminlte.layout_fixed_navbar' => ['xs' => false, 'md' => false],
            'adminlte.layout_fixed_footer' => [],
        ]);

        $this->assertFalse(BodyClasses::isFixedNavbarEnabled());
        $this->assertFalse(BodyClasses::isFixedFooterEnabled());

        // Only a strict boolean value is accepted inside the array.

        config([
            'adminlte.layout_fixed_navbar' => ['xs' => 1],
            'adminlte.layout_fixed_footer' => ['md' => 'yes'],
        ]);

        $this->assertFalse(BodyClasses::isFixedNavbarEnabled());
        $this->assertFalse(BodyClasses::isFixedFooterEnabled());
    }

    public function testMakeWithTheCustomBodyClasses()
    {
        config([
            'adminlte.layout_fixed_sidebar' => false,
            'adminlte.sidebar_mini' => false,
        ]);

        config(['adminlte.classes_body' => 'custom-1 custom-2']);

        $this->assertEquals(
            ['sidebar-expand-lg', 'custom-1 custom-2'],
            BodyClasses::make()
        );

        // Only a non empty string is accepted.

        foreach (['', null, false, ['custom'], 10] as $cfg) {
            config(['adminlte.classes_body' => $cfg]);
            $this->assertEquals(['sidebar-expand-lg'], BodyClasses::make());
        }
    }

    public function testMakeMergesTheSidebarClasses()
    {
        config([
            'adminlte.layout_fixed_sidebar' => false,
            'adminlte.layout_fixed_navbar' => false,
            'adminlte.layout_fixed_footer' => false,
            'adminlte.classes_body' => '',
            'adminlte.sidebar_expand' => 'xl',
            'adminlte.sidebar_mini' => true,
            'adminlte.sidebar_collapse' => true,
        ]);

        $this->assertEquals(Sidebar::makeBodyClasses(), BodyClasses::make());
    }

    public function testMakeWithEveryOptionEnabled()
    {
        config([
            'adminlte.layout_topnav' => false,
            'adminlte.layout_fixed_sidebar' => true,
            'adminlte.layout_fixed_navbar' => true,
            'adminlte.layout_fixed_footer' => true,
            'adminlte.sidebar_expand' => 'lg',
            'adminlte.sidebar_mini' => true,
            'adminlte.sidebar_collapse' => true,
            'adminlte.sidebar_without_hover' => true,
            'adminlte.classes_body' => 'bg-body-tertiary',
        ]);

        $this->assertEquals(
            [
                'layout-fixed',
                'fixed-header',
                'fixed-footer',
                'sidebar-expand-lg',
                'sidebar-mini',
                'sidebar-collapse',
                'sidebar-without-hover',
                'bg-body-tertiary',
            ],
            array_values(BodyClasses::make())
        );
    }

    public function testMakeAvoidsDuplicatedClasses()
    {
        // A custom body class that the layout already adds is not repeated.

        config([
            'adminlte.layout_fixed_sidebar' => false,
            'adminlte.layout_fixed_navbar' => true,
            'adminlte.sidebar_expand' => 'lg',
            'adminlte.sidebar_mini' => true,
            'adminlte.classes_body' => 'sidebar-mini',
        ]);

        $classes = array_values(BodyClasses::make());

        $this->assertEquals(
            ['fixed-header', 'sidebar-expand-lg', 'sidebar-mini'],
            $classes
        );

        $this->assertEquals($classes, array_unique($classes));
    }

    public function testMakeWithTheCompactMode()
    {
        // Two AdminLTE rules compound the token with 'sidebar-mini' and
        // 'sidebar-collapse' on a single element, so it has to share the body
        // with them, otherwise the collapsed compact rail never applies.

        config([
            'adminlte.layout_compact' => true,
            'adminlte.sidebar_mini' => true,
            'adminlte.sidebar_collapse' => true,
        ]);

        $classes = BodyClasses::make();

        $this->assertContains('compact-mode', $classes);
        $this->assertContains('sidebar-mini', $classes);
        $this->assertContains('sidebar-collapse', $classes);
    }

    public function testTheCompactModeFollowsTheUsualTruthiness()
    {
        // The option is read like every other boolean one of the package, so
        // a truthy value enables it. It used to require an explicit 'true',
        // which silently ignored the values an environment variable yields.

        foreach ([true, 1, '1', 'yes'] as $value) {
            config(['adminlte.layout_compact' => $value]);

            $this->assertContains(
                'compact-mode',
                BodyClasses::make(),
                var_export($value, true)
            );
        }

        foreach ([false, null, 0, ''] as $value) {
            config(['adminlte.layout_compact' => $value]);

            $this->assertNotContains(
                'compact-mode',
                BodyClasses::make(),
                var_export($value, true)
            );
        }
    }
}

<?php

use JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper;

class LayoutHelperTest extends TestCase
{
    public function testEnableDisablePreloader()
    {
        // Test with config enabled.

        config(['adminlte.preloader.enabled' => true]);

        $this->assertTrue(LayoutHelper::isPreloaderEnabled());

        // Test with config disabled.

        config(['adminlte.preloader.enabled' => false]);

        $this->assertFalse(LayoutHelper::isPreloaderEnabled());
    }

    public function testMakeBodyData()
    {
        // Test without config.

        $data = LayoutHelper::makeBodyData();
        $this->assertEquals('', $data);

        // Test with default config values.

        config([
            'adminlte.sidebar_scrollbar_theme' => 'os-theme-light',
            'adminlte.sidebar_scrollbar_auto_hide' => 'l',
        ]);

        $data = LayoutHelper::makeBodyData();
        $this->assertEquals('', $data);

        // Test with non-default 'sidebar_scrollbar_theme' config value.

        config([
            'adminlte.sidebar_scrollbar_theme' => 'os-theme-dark',
            'adminlte.sidebar_scrollbar_auto_hide' => 'l',
        ]);

        $data = LayoutHelper::makeBodyData();
        $this->assertStringContainsString('data-scrollbar-theme=os-theme-dark', $data);

        // Test with non-default 'sidebar_scrollbar_auto_hide' config value.

        config([
            'adminlte.sidebar_scrollbar_theme' => 'os-theme-light',
            'adminlte.sidebar_scrollbar_auto_hide' => 'm',
        ]);

        $data = LayoutHelper::makeBodyData();
        $this->assertStringContainsString('data-scrollbar-auto-hide=m', $data);

        // Test with non-default config values.

        config([
            'adminlte.sidebar_scrollbar_theme' => 'os-theme-dark',
            'adminlte.sidebar_scrollbar_auto_hide' => 's',
        ]);

        $data = LayoutHelper::makeBodyData();
        $this->assertStringContainsString('data-scrollbar-theme=os-theme-dark', $data);
        $this->assertStringContainsString('data-scrollbar-auto-hide=s', $data);
    }

    public function testMakeBodyClassesWithouConfig()
    {
        // Test without config.

        $data = LayoutHelper::makeBodyClasses();
        $this->assertEquals('sidebar-mini', $data);
    }

    public function testMakeBodyClassesWithSidebarMiniConfig()
    {
        // Test config 'sidebar_mini' => null.

        config(['adminlte.sidebar_mini' => null]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('sidebar-mini', $data);
        $this->assertStringNotContainsString('sidebar-mini-md', $data);
        $this->assertStringNotContainsString('sidebar-mini-xs', $data);

        // Test config 'sidebar_mini' => 'lg'.

        config(['adminlte.sidebar_mini' => 'lg']);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('sidebar-mini', $data);
        $this->assertStringNotContainsString('sidebar-mini-md', $data);
        $this->assertStringNotContainsString('sidebar-mini-xs', $data);

        // Test config 'sidebar_mini' => 'md'.

        config(['adminlte.sidebar_mini' => 'md']);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('sidebar-mini-md', $data);
        $this->assertStringNotContainsString('sidebar-mini-xs', $data);
        $this->assertDoesNotMatchRegularExpression('/sidebar-mini[^-]/', $data);

        // Test config 'sidebar_mini' => 'xs'.

        config(['adminlte.sidebar_mini' => 'xs']);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('sidebar-mini-xs', $data);
        $this->assertStringNotContainsString('sidebar-mini-md', $data);
        $this->assertDoesNotMatchRegularExpression('/sidebar-mini[^-]/', $data);
    }

    public function testMakeBodyClassesWithSidebarCollapseConfig()
    {
        // Test config 'sidebar_collapse' => null.

        config(['adminlte.sidebar_collapse' => null]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('sidebar-collapse', $data);

        // Test config 'sidebar_collapse' => false.

        config(['adminlte.sidebar_collapse' => false]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('sidebar-collapse', $data);

        // Test config 'sidebar_collapse' => true.

        config(['adminlte.sidebar_collapse' => true]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('sidebar-collapse', $data);
    }

    public function testMakeBodyClassesWithLayoutTopnavConfig()
    {
        // Test config 'layout_topnav' => null.

        config(['adminlte.layout_topnav' => null]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('layout-top-nav', $data);

        // Test config 'layout_topnav' => false.

        config(['adminlte.layout_topnav' => false]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('layout-top-nav', $data);

        // Test config 'layout_topnav' => true.

        config(['adminlte.layout_topnav' => true]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('layout-top-nav', $data);
    }

    public function testMakeBodyClassesWithLayoutBoxedConfig()
    {
        // Test config 'layout_boxed' => null.

        config(['adminlte.layout_boxed' => null]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('layout-boxed', $data);

        // Test config 'layout_boxed' => false.

        config(['adminlte.layout_boxed' => false]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('layout-boxed', $data);

        // Test config 'layout_boxed' => true.

        config(['adminlte.layout_boxed' => true]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('layout-boxed', $data);
    }

    public function testMakeBodyClassesWithRightSidebarConfig()
    {
        // Test config 'right_sidebar_push' => false.

        config(['adminlte.right_sidebar_push' => false]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('control-sidebar-push', $data);

        // Test config 'right_sidebar_push' => true, 'right_sidebar' => true.

        config([
            'adminlte.right_sidebar' => true,
            'adminlte.right_sidebar_push' => true,
        ]);

        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('control-sidebar-push', $data);

        // Test config 'right_sidebar_push' => true, 'right_sidebar' => false.

        config([
            'adminlte.right_sidebar' => false,
            'adminlte.right_sidebar_push' => true,
        ]);

        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('control-sidebar-push', $data);
    }

    public function testMakeBodyClassesWithLayoutFixedSidebarConfig()
    {
        // Test config 'layout_fixed_sidebar' => null.

        config(['adminlte.layout_fixed_sidebar' => null]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('layout-fixed', $data);

        // Test config 'layout_fixed_sidebar' => true, 'layout_topnav' => true.

        config([
            'adminlte.layout_fixed_sidebar' => true,
            'adminlte.layout_topnav' => true,
        ]);

        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('layout-fixed', $data);

        // Test config 'layout_fixed_sidebar' => true, 'layout_topnav' => null.

        config([
            'adminlte.layout_fixed_sidebar' => true,
            'adminlte.layout_topnav' => null,
        ]);

        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('layout-fixed', $data);
    }

    public function testMakeBodyClassesWithLayoutFixedNavbarConfig()
    {
        // Test config 'layout_fixed_navbar' => null.

        config(['adminlte.layout_fixed_navbar' => null]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('layout-navbar-fixed', $data);

        // Test config 'layout_fixed_navbar' => true, 'layout_boxed' => true.

        config([
            'adminlte.layout_fixed_navbar' => true,
            'adminlte.layout_boxed' => true,
        ]);

        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('layout-navbar-fixed', $data);

        // Test config 'layout_fixed_navbar' => true, 'layout_boxed' => null.

        config([
            'adminlte.layout_fixed_navbar' => true,
            'adminlte.layout_boxed' => null,
        ]);

        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('layout-navbar-fixed', $data);

        // Test config 'layout_fixed_navbar' => ['xs' => true, 'lg' => false],
        // 'layout_boxed' => null.

        config([
            'adminlte.layout_fixed_navbar' => ['xs' => true, 'lg' => false],
            'adminlte.layout_boxed' => null,
        ]);

        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('layout-navbar-fixed', $data);
        $this->assertStringContainsString('layout-lg-navbar-not-fixed', $data);

        // Test config 'layout_fixed_navbar' => ['xs' => true, 'foo' => true],
        // 'layout_boxed' => null.

        config([
            'adminlte.layout_fixed_navbar' => ['xs' => true, 'foo' => true],
            'adminlte.layout_boxed' => null,
        ]);

        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('layout-navbar-fixed', $data);
        $this->assertStringNotContainsString('layout-foo-navbar-fixed', $data);
    }

    public function testMakeBodyClassesWithLayoutFixedFooterConfig()
    {
        // Test config 'layout_fixed_footer' => null.

        config(['adminlte.layout_fixed_footer' => null]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('layout-footer-fixed', $data);

        // Test config 'layout_fixed_footer' => true, 'layout_boxed' => true.

        config([
            'adminlte.layout_fixed_footer' => true,
            'adminlte.layout_boxed' => true,
        ]);

        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('layout-footer-fixed', $data);

        // Test config 'layout_fixed_footer' => true, 'layout_boxed' => null.

        config([
            'adminlte.layout_fixed_footer' => true,
            'adminlte.layout_boxed' => null,
        ]);

        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('layout-footer-fixed', $data);

        // Test config 'layout_fixed_footer' => ['md' => true, 'lg' => false],
        // 'layout_boxed' => null.

        config([
            'adminlte.layout_fixed_footer' => ['md' => true, 'lg' => false],
            'adminlte.layout_boxed' => null,
        ]);

        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('layout-md-footer-fixed', $data);
        $this->assertStringContainsString('layout-lg-footer-not-fixed', $data);

        // Test config 'layout_fixed_footer' => ['md' => true, 'foo' => false],
        // 'layout_boxed' => null.

        config([
            'adminlte.layout_fixed_footer' => ['md' => true, 'foo' => false],
            'adminlte.layout_boxed' => null,
        ]);

        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('layout-md-footer-fixed', $data);
        $this->assertStringNotContainsString('layout-foo-footer-not-fixed', $data);
    }

    public function testMakeBodyClassesWithClassesBodyConfig()
    {
        // Test config 'classes_body' => custom-body-class.

        config(['adminlte.classes_body' => 'custom-body-class']);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('custom-body-class', $data);

        // Test config 'classes_body' => 'custom-body-class-1 custom-body-class-2'.

        config(['adminlte.classes_body' => 'custom-body-class-1 custom-body-class-2']);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('custom-body-class-1', $data);
        $this->assertStringContainsString('custom-body-class-2', $data);
    }

    public function testMakeBodyClassesWithDarkModeConfig()
    {
        // Test config 'layout_dark_mode' => null.

        config(['adminlte.layout_dark_mode' => null]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('dark-mode', $data);

        // Test config 'layout_dark_mode' => true.

        config(['adminlte.layout_dark_mode' => true]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('dark-mode', $data);
    }
}

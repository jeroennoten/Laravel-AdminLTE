<?php

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use JeroenNoten\LaravelAdminLte\Events\ReadingDarkModePreference;
use JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper;

class LayoutHelperTest extends TestCase
{
    public function testMakeContentWrapperClasses()
    {
        // Test without config. On AdminLTE v4 the content wrapper is the
        // 'app-main' element.

        $data = LayoutHelper::makeContentWrapperClasses();
        $this->assertEquals('app-main', $data);

        // Test with custom classes on the configuration.

        config(['adminlte.classes_content_wrapper' => 'class1 class2']);

        $data = LayoutHelper::makeContentWrapperClasses();
        $this->assertStringContainsString('app-main', $data);
        $this->assertStringContainsString('class1', $data);
        $this->assertStringContainsString('class2', $data);

        // Test with cwrapper mode enabled.

        config([
            'adminlte.preloader.enabled' => true,
            'adminlte.preloader.mode' => 'cwrapper',
        ]);

        $data = LayoutHelper::makeContentWrapperClasses();
        $this->assertStringContainsString('app-main', $data);
        $this->assertStringContainsString('position-relative', $data);
    }

    public function testMakeBodyData()
    {
        // On AdminLTE v4 there are no body data attributes, the sidebar
        // scrollbar setup is done on the master layout instead.

        $this->assertEquals('', LayoutHelper::makeBodyData());
    }

    public function testMakeWrapperDataIsDeprecated()
    {
        // The color mode is applied on the html element now, so no data
        // attributes are added on the app-wrapper element.

        $this->assertEquals('', LayoutHelper::makeWrapperData());
    }

    public function testMakeBodyClassesWithoutConfig()
    {
        config(['adminlte' => []]);

        $data = LayoutHelper::makeBodyClasses();

        // Without configuration, the sidebar related defaults of the helper
        // are expected (the fixed and the mini sidebar are the defaults).

        $this->assertEquals(
            'layout-fixed sidebar-expand-lg sidebar-mini bg-body-tertiary', $data
        );
    }

    public function testMakeBodyClassesWithSidebarMiniConfig()
    {
        // Test config 'sidebar_mini' => true.

        config(['adminlte.sidebar_mini' => true]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('sidebar-mini', $data);

        // Test config 'sidebar_mini' => false.

        config(['adminlte.sidebar_mini' => false]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('sidebar-mini', $data);

        // Test the legacy tokens are still supported.

        foreach (['xs', 'md', 'lg'] as $token) {
            config(['adminlte.sidebar_mini' => $token]);
            $data = LayoutHelper::makeBodyClasses();
            $this->assertStringContainsString('sidebar-mini', $data);
        }
    }

    public function testMakeBodyClassesWithSidebarExpandConfig()
    {
        // Test the supported breakpoints.

        foreach (['sm', 'md', 'lg', 'xl', 'xxl'] as $bp) {
            config(['adminlte.sidebar_expand' => $bp]);
            $data = LayoutHelper::makeBodyClasses();
            $this->assertStringContainsString("sidebar-expand-{$bp}", $data);
        }

        // Test with an invalid breakpoint.

        config(['adminlte.sidebar_expand' => 'invalid']);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('sidebar-expand', $data);
    }

    public function testMakeBodyClassesWithSidebarCollapseConfig()
    {
        // Test config 'sidebar_collapse' => true.

        config(['adminlte.sidebar_collapse' => true]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('sidebar-collapse', $data);

        // Test config 'sidebar_collapse' => false.

        config(['adminlte.sidebar_collapse' => false]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('sidebar-collapse', $data);

        // Test when the section "sidebar_collapse" is defined.

        View::inject('sidebar_collapse', 'dummy-content');
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('sidebar-collapse', $data);

        View::flushSections();
    }

    public function testMakeBodyClassesWithSidebarWithoutHoverConfig()
    {
        config(['adminlte.sidebar_without_hover' => true]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('sidebar-without-hover', $data);

        config(['adminlte.sidebar_without_hover' => false]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('sidebar-without-hover', $data);
    }

    public function testMakeBodyClassesWithLayoutTopnavConfig()
    {
        // The topnav layout has no sidebar, so no sidebar related classes are
        // expected on the body element.

        config([
            'adminlte.layout_topnav' => true,
            'adminlte.sidebar_mini' => true,
            'adminlte.layout_fixed_sidebar' => true,
        ]);

        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('sidebar-mini', $data);
        $this->assertStringNotContainsString('sidebar-expand', $data);
        $this->assertStringNotContainsString('layout-fixed', $data);

        // Test when the section "layout_topnav" is defined.

        config(['adminlte.layout_topnav' => false]);
        View::inject('layout_topnav', 'dummy-content');
        $this->assertTrue(LayoutHelper::isLayoutTopnavEnabled());

        View::flushSections();
    }

    public function testMakeBodyClassesWithLayoutBoxedConfig()
    {
        // The boxed layout was removed on AdminLTE v4, so no class should be
        // added for it.

        config(['adminlte.layout_boxed' => true]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('layout-boxed', $data);
    }

    public function testMakeBodyClassesWithLayoutFixedSidebarConfig()
    {
        // Test config 'layout_fixed_sidebar' => true.

        config(['adminlte.layout_fixed_sidebar' => true]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('layout-fixed', $data);

        // Test config 'layout_fixed_sidebar' => false.

        config(['adminlte.layout_fixed_sidebar' => false]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('layout-fixed', $data);

        // The fixed sidebar is not compatible with the topnav layout.

        config([
            'adminlte.layout_fixed_sidebar' => true,
            'adminlte.layout_topnav' => true,
        ]);

        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('layout-fixed', $data);
    }

    public function testMakeBodyClassesWithLayoutFixedNavbarConfig()
    {
        // Test config 'layout_fixed_navbar' => true.

        config(['adminlte.layout_fixed_navbar' => true]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('fixed-header', $data);
        $this->assertTrue(LayoutHelper::isFixedNavbarEnabled());

        // Test config 'layout_fixed_navbar' => false.

        config(['adminlte.layout_fixed_navbar' => false]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('fixed-header', $data);

        // Test the legacy responsive configuration is still accepted.

        config(['adminlte.layout_fixed_navbar' => ['xs' => true, 'lg' => false]]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('fixed-header', $data);

        config(['adminlte.layout_fixed_navbar' => ['xs' => false]]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('fixed-header', $data);
    }

    public function testMakeBodyClassesWithLayoutFixedFooterConfig()
    {
        // Test config 'layout_fixed_footer' => true.

        config(['adminlte.layout_fixed_footer' => true]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('fixed-footer', $data);
        $this->assertTrue(LayoutHelper::isFixedFooterEnabled());

        // Test config 'layout_fixed_footer' => false.

        config(['adminlte.layout_fixed_footer' => false]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('fixed-footer', $data);

        // Test the legacy responsive configuration is still accepted.

        config(['adminlte.layout_fixed_footer' => ['md' => true]]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('fixed-footer', $data);
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

    public function testGetColorMode()
    {
        // Test the default configuration.

        config(['adminlte.color_mode.default' => 'auto']);
        $this->assertEquals('auto', LayoutHelper::getColorMode());

        // Test an explicit color mode.

        config(['adminlte.color_mode.default' => 'dark']);
        $this->assertEquals('dark', LayoutHelper::getColorMode());

        config(['adminlte.color_mode.default' => 'light']);
        $this->assertEquals('light', LayoutHelper::getColorMode());

        // Test an invalid color mode.

        config(['adminlte.color_mode.default' => 'invalid']);
        $this->assertEquals('auto', LayoutHelper::getColorMode());

        // Test the legacy 'layout_dark_mode' option.

        config([
            'adminlte.color_mode.default' => 'light',
            'adminlte.layout_dark_mode' => true,
        ]);

        $this->assertEquals('dark', LayoutHelper::getColorMode());

        // Test the legacy 'layout_theme_mode' option.

        config([
            'adminlte.layout_dark_mode' => null,
            'adminlte.layout_theme_mode' => 'dark',
        ]);

        $this->assertEquals('dark', LayoutHelper::getColorMode());
    }

    public function testIsRtlEnabled()
    {
        // Test the RTL mode explicitly enabled and disabled.

        config(['adminlte.rtl.enabled' => true]);
        $this->assertTrue(LayoutHelper::isRtlEnabled());
        $this->assertEquals('rtl', LayoutHelper::getHtmlDirection());

        config(['adminlte.rtl.enabled' => false]);
        $this->assertFalse(LayoutHelper::isRtlEnabled());
        $this->assertEquals('ltr', LayoutHelper::getHtmlDirection());

        // Test the automatic detection from the application locale.

        config([
            'adminlte.rtl.enabled' => null,
            'adminlte.rtl.locales' => ['ar', 'fa', 'uz-AF'],
        ]);

        app()->setLocale('en');
        $this->assertFalse(LayoutHelper::isRtlEnabled());

        app()->setLocale('ar');
        $this->assertTrue(LayoutHelper::isRtlEnabled());

        // The language part of a regional locale is also detected.

        app()->setLocale('ar_EG');
        $this->assertTrue(LayoutHelper::isRtlEnabled());

        // A full locale is matched too.

        app()->setLocale('uz-AF');
        $this->assertTrue(LayoutHelper::isRtlEnabled());

        app()->setLocale('en');
    }

    public function testIsRtlLocale()
    {
        config(['adminlte.rtl.locales' => ['ar', 'he']]);

        $this->assertTrue(LayoutHelper::isRtlLocale('ar'));
        $this->assertTrue(LayoutHelper::isRtlLocale('AR'));
        $this->assertTrue(LayoutHelper::isRtlLocale('he_IL'));
        $this->assertFalse(LayoutHelper::isRtlLocale('en'));
        $this->assertFalse(LayoutHelper::isRtlLocale('es-MX'));
    }

    public function testMakeHtmlData()
    {
        // Test without RTL and with the automatic color mode. The color mode
        // is resolved on the client side, so no attribute is expected.

        config([
            'adminlte.rtl.enabled' => false,
            'adminlte.color_mode.default' => 'auto',
        ]);

        $this->assertEquals('', LayoutHelper::makeHtmlData());

        // Test with an explicit color mode.

        config(['adminlte.color_mode.default' => 'dark']);
        $this->assertEquals('data-bs-theme="dark"', LayoutHelper::makeHtmlData());

        // Test with the RTL mode enabled.

        config([
            'adminlte.rtl.enabled' => true,
            'adminlte.color_mode.default' => 'auto',
        ]);

        $this->assertEquals('dir="rtl"', LayoutHelper::makeHtmlData());

        // Test with both the RTL mode and an explicit color mode.

        config(['adminlte.color_mode.default' => 'light']);

        $data = LayoutHelper::makeHtmlData();
        $this->assertStringContainsString('dir="rtl"', $data);
        $this->assertStringContainsString('data-bs-theme="light"', $data);
    }

    public function testMakeSidebarWrapperClasses()
    {
        config(['adminlte.classes_sidebar' => 'bg-body-secondary shadow']);

        $data = LayoutHelper::makeSidebarWrapperClasses();
        $this->assertStringContainsString('app-sidebar', $data);
        $this->assertStringContainsString('bg-body-secondary', $data);
        $this->assertStringContainsString('shadow', $data);

        // Test without custom classes.

        config(['adminlte.classes_sidebar' => '']);
        $this->assertEquals('app-sidebar', LayoutHelper::makeSidebarWrapperClasses());
    }

    public function testMakeSidebarData()
    {
        config(['adminlte.sidebar_theme' => 'dark']);
        $this->assertEquals('data-bs-theme="dark"', LayoutHelper::makeSidebarData());

        config(['adminlte.sidebar_theme' => 'light']);
        $this->assertEquals('data-bs-theme="light"', LayoutHelper::makeSidebarData());

        // An invalid or null theme inherits the color mode of the page.

        config(['adminlte.sidebar_theme' => null]);
        $this->assertEquals('', LayoutHelper::makeSidebarData());
    }

    public function testRightSidebarEnabledMethod()
    {
        // Test config 'right_sidebar' => true.

        config(['adminlte.right_sidebar' => true]);
        $this->assertTrue(LayoutHelper::isRightSidebarEnabled());

        // Test config 'right_sidebar' => false.

        config(['adminlte.right_sidebar' => false]);
        $this->assertFalse(LayoutHelper::isRightSidebarEnabled());

        // Test when section "right_sidebar" is defined.

        View::inject('right_sidebar', 'dummy-content');
        $this->assertTrue(LayoutHelper::isRightSidebarEnabled());

        // Test when section "right_sidebar" is not defined.

        View::flushSections();
        $this->assertFalse(LayoutHelper::isRightSidebarEnabled());
    }

    public function testMakeBodyClassesWithSidebarMiniLegacyTokens()
    {
        // All the legacy breakpoint tokens enable the mini sidebar.

        foreach (['xs', 'sm', 'md', 'lg', 'xl', 'xxl'] as $token) {
            config(['adminlte.sidebar_mini' => $token]);
            $data = LayoutHelper::makeBodyClasses();

            $this->assertStringContainsString(
                'sidebar-mini',
                $data,
                "The legacy token '{$token}' did not enable the mini sidebar"
            );
        }

        // An unknown token disables the mini sidebar.

        config(['adminlte.sidebar_mini' => 'invalid']);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('sidebar-mini', $data);

        // A null value disables the mini sidebar too.

        config(['adminlte.sidebar_mini' => null]);
        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('sidebar-mini', $data);
    }

    public function testMakeBodyClassesWithSidebarCollapseSection()
    {
        // The view section enables the collapsed sidebar even when the config
        // option is disabled.

        config(['adminlte.sidebar_collapse' => false]);
        View::inject('sidebar_collapse', 'dummy-content');

        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringContainsString('sidebar-collapse', $data);

        // Once the sections are flushed, the config option decides again.

        View::flushSections();

        $data = LayoutHelper::makeBodyClasses();
        $this->assertStringNotContainsString('sidebar-collapse', $data);
    }

    public function testMakeBodyClassesWithLayoutTopnavSection()
    {
        // The topnav layout may also be enabled through a view section. On
        // that case, no sidebar related classes are expected.

        config([
            'adminlte.layout_topnav' => false,
            'adminlte.sidebar_mini' => true,
            'adminlte.sidebar_collapse' => true,
            'adminlte.sidebar_without_hover' => true,
            'adminlte.layout_fixed_sidebar' => true,
        ]);

        View::inject('layout_topnav', 'dummy-content');

        $data = LayoutHelper::makeBodyClasses();

        $this->assertStringNotContainsString('sidebar-mini', $data);
        $this->assertStringNotContainsString('sidebar-expand', $data);
        $this->assertStringNotContainsString('sidebar-collapse', $data);
        $this->assertStringNotContainsString('sidebar-without-hover', $data);
        $this->assertStringNotContainsString('layout-fixed', $data);

        View::flushSections();
    }

    public function testMakeBodyClassesWithTopnavKeepsTheFixedSections()
    {
        // The fixed navbar and footer are compatible with the topnav layout.

        config([
            'adminlte.layout_topnav' => true,
            'adminlte.layout_fixed_navbar' => true,
            'adminlte.layout_fixed_footer' => true,
        ]);

        $data = LayoutHelper::makeBodyClasses();

        $this->assertStringContainsString('fixed-header', $data);
        $this->assertStringContainsString('fixed-footer', $data);
    }

    public function testMakeBodyClassesWithEmptyFixedSectionArrays()
    {
        // An empty array (or an array without enabled values) disables the
        // fixed mode of a layout section.

        config([
            'adminlte.layout_fixed_navbar' => [],
            'adminlte.layout_fixed_footer' => ['xs' => false, 'md' => false],
        ]);

        $data = LayoutHelper::makeBodyClasses();

        $this->assertStringNotContainsString('fixed-header', $data);
        $this->assertStringNotContainsString('fixed-footer', $data);
        $this->assertFalse(LayoutHelper::isFixedNavbarEnabled());
        $this->assertFalse(LayoutHelper::isFixedFooterEnabled());

        // Note only a strict boolean value enables the fixed mode.

        config(['adminlte.layout_fixed_navbar' => ['xs' => 1]]);

        $this->assertFalse(LayoutHelper::isFixedNavbarEnabled());
    }

    public function testMakeBodyClassesAvoidsDuplicatedClasses()
    {
        // A custom body class that is already added by the helper is not
        // repeated on the resulting set of classes.

        config([
            'adminlte.layout_fixed_sidebar' => false,
            'adminlte.sidebar_expand' => 'lg',
            'adminlte.sidebar_mini' => true,
            'adminlte.classes_body' => 'sidebar-mini',
        ]);

        $data = LayoutHelper::makeBodyClasses();
        $classes = explode(' ', $data);

        $this->assertEquals('sidebar-expand-lg sidebar-mini', $data);
        $this->assertEquals(count($classes), count(array_unique($classes)));
    }

    public function testMakeBodyClassesWithoutAnyConfiguration()
    {
        // A null configuration keeps the defaults of the helper.

        config([
            'adminlte.layout_fixed_sidebar' => null,
            'adminlte.sidebar_expand' => null,
            'adminlte.sidebar_mini' => null,
            'adminlte.classes_body' => null,
        ]);

        // Note an invalid 'sidebar_expand' value adds no class at all.

        $this->assertEquals('', LayoutHelper::makeBodyClasses());
    }

    public function testIsLayoutBoxedEnabledIsDeprecated()
    {
        // The boxed layout was removed on AdminLTE v4, but the helper method
        // is kept for backward compatibility.

        config(['adminlte.layout_boxed' => false]);
        $this->assertFalse(LayoutHelper::isLayoutBoxedEnabled());

        config(['adminlte.layout_boxed' => true]);
        $this->assertTrue(LayoutHelper::isLayoutBoxedEnabled());

        // The view section is also supported.

        config(['adminlte.layout_boxed' => false]);
        View::inject('layout_boxed', 'dummy-content');
        $this->assertTrue(LayoutHelper::isLayoutBoxedEnabled());

        View::flushSections();

        // In any case, no class is added to the body element.

        config(['adminlte.layout_boxed' => true]);
        $this->assertStringNotContainsString('boxed', LayoutHelper::makeBodyClasses());
    }

    public function testMakeHtmlDataForEveryColorMode()
    {
        config([
            'adminlte.rtl.enabled' => false,
            'adminlte.color_mode.remember' => true,
            'adminlte.layout_dark_mode' => null,
            'adminlte.layout_theme_mode' => null,
        ]);

        // The 'auto' mode is resolved on the client side.

        config(['adminlte.color_mode.default' => 'auto']);
        $this->assertEquals('', LayoutHelper::makeHtmlData());

        // The explicit modes are applied on the html element.

        foreach (['light', 'dark'] as $mode) {
            config(['adminlte.color_mode.default' => $mode]);

            $this->assertEquals(
                "data-bs-theme=\"{$mode}\"",
                LayoutHelper::makeHtmlData()
            );
        }

        // An invalid mode falls back to the 'auto' one.

        config(['adminlte.color_mode.default' => 'invalid']);
        $this->assertEquals('', LayoutHelper::makeHtmlData());
    }

    public function testMakeHtmlDataWhenTheColorModeIsNotRemembered()
    {
        // When the color mode is not remembered on the browser, the AdminLTE
        // color mode plugin must be disabled.

        config([
            'adminlte.rtl.enabled' => false,
            'adminlte.color_mode.default' => 'light',
            'adminlte.color_mode.remember' => false,
        ]);

        $data = LayoutHelper::makeHtmlData();

        $this->assertStringContainsString('data-lte-color-mode="off"', $data);
        $this->assertStringContainsString('data-bs-theme="light"', $data);

        // The automatic mode is the exception, it has to be resolved on the
        // client side, so the plugin must stay enabled for it.

        config(['adminlte.color_mode.default' => 'auto']);

        $this->assertEquals('', LayoutHelper::makeHtmlData());

        // The attribute is not added when the color mode is remembered.

        config(['adminlte.color_mode.remember' => true]);

        $this->assertStringNotContainsString(
            'data-lte-color-mode',
            LayoutHelper::makeHtmlData()
        );
    }

    public function testMakeHtmlDataWithAllTheAttributes()
    {
        config([
            'adminlte.rtl.enabled' => true,
            'adminlte.color_mode.default' => 'dark',
            'adminlte.color_mode.remember' => false,
        ]);

        $data = LayoutHelper::makeHtmlData();

        $this->assertStringContainsString('dir="rtl"', $data);
        $this->assertStringContainsString('data-bs-theme="dark"', $data);
        $this->assertStringContainsString('data-lte-color-mode="off"', $data);
    }

    public function testMakeHtmlDataWithTheLegacyDarkModeOptions()
    {
        config([
            'adminlte.rtl.enabled' => false,
            'adminlte.color_mode.default' => 'light',
            'adminlte.color_mode.remember' => true,
        ]);

        // The legacy 'layout_dark_mode' option forces the dark color mode.

        config(['adminlte.layout_dark_mode' => true]);
        $this->assertEquals('data-bs-theme="dark"', LayoutHelper::makeHtmlData());

        // The legacy 'layout_theme_mode' option takes precedence over it.

        config([
            'adminlte.layout_dark_mode' => true,
            'adminlte.layout_theme_mode' => 'light',
        ]);

        $this->assertEquals('data-bs-theme="light"', LayoutHelper::makeHtmlData());
        $this->assertEquals('light', LayoutHelper::getColorMode());

        // An invalid legacy theme mode is ignored.

        config([
            'adminlte.layout_dark_mode' => false,
            'adminlte.layout_theme_mode' => 'invalid',
        ]);

        $this->assertEquals('light', LayoutHelper::getColorMode());
    }

    public function testGetColorModeWithTheServerSidePreference()
    {
        config([
            'adminlte.layout_dark_mode' => null,
            'adminlte.layout_theme_mode' => null,
            'adminlte.color_mode.default' => 'light',
        ]);

        $this->assertEquals('light', LayoutHelper::getColorMode());

        // A listener of the 'ReadingDarkModePreference' event may enable the
        // dark mode on the server side.

        Event::listen(ReadingDarkModePreference::class, function ($event) {
            $event->darkMode->enable();
        });

        $this->assertTrue(LayoutHelper::isDarkModeEnabled());
        $this->assertEquals('dark', LayoutHelper::getColorMode());
        $this->assertEquals('data-bs-theme="dark"', LayoutHelper::makeHtmlData());
    }

    public function testIsDarkModeEnabledWithoutAnyPreference()
    {
        config(['adminlte.layout_dark_mode' => false]);
        Session::forget('adminlte_dark_mode');

        $this->assertFalse(LayoutHelper::isDarkModeEnabled());
    }

    public function testMakeSidebarDataWithAnInvalidTheme()
    {
        // An unsupported theme is ignored, so the sidebar inherits the color
        // mode of the page.

        config([
            'adminlte.sidebar_theme' => 'invalid',
            'adminlte.sidebar_collapse_remember' => false,
        ]);

        $this->assertEquals('', LayoutHelper::makeSidebarData());
    }

    public function testMakeSidebarDataWithTheCollapseRememberOption()
    {
        // The persistence attribute makes the PushMenu plugin to remember the
        // collapsed state of the sidebar.

        config([
            'adminlte.sidebar_theme' => null,
            'adminlte.sidebar_collapse_remember' => true,
        ]);

        $this->assertEquals(
            'data-enable-persistence="true"',
            LayoutHelper::makeSidebarData()
        );

        // The attribute is not added when the option is disabled.

        config(['adminlte.sidebar_collapse_remember' => false]);
        $this->assertEquals('', LayoutHelper::makeSidebarData());

        // Test the option combined with a sidebar theme.

        config([
            'adminlte.sidebar_theme' => 'dark',
            'adminlte.sidebar_collapse_remember' => true,
        ]);

        $data = LayoutHelper::makeSidebarData();

        $this->assertStringContainsString('data-bs-theme="dark"', $data);
        $this->assertStringContainsString('data-enable-persistence="true"', $data);
    }

    public function testMakeSidebarWrapperClassesWithAnInvalidConfig()
    {
        // A non string configuration is ignored.

        config(['adminlte.classes_sidebar' => ['bg-dark']]);
        $this->assertEquals('app-sidebar', LayoutHelper::makeSidebarWrapperClasses());

        config(['adminlte.classes_sidebar' => null]);
        $this->assertEquals('app-sidebar', LayoutHelper::makeSidebarWrapperClasses());
    }

    public function testMakeContentWrapperClassesWithAnInvalidConfig()
    {
        config(['adminlte.classes_content_wrapper' => ['p-3']]);
        $this->assertEquals('app-main', LayoutHelper::makeContentWrapperClasses());

        config(['adminlte.classes_content_wrapper' => null]);
        $this->assertEquals('app-main', LayoutHelper::makeContentWrapperClasses());
    }

    public function testIsRtlLocaleWithRegionalAndCasedLocales()
    {
        config(['adminlte.rtl.locales' => ['AR', 'uz_AF', 'he-IL']]);

        // The comparison is case insensitive.

        $this->assertTrue(LayoutHelper::isRtlLocale('ar'));
        $this->assertTrue(LayoutHelper::isRtlLocale('AR'));
        $this->assertTrue(LayoutHelper::isRtlLocale('Ar'));

        // The language part of a regional locale is checked too.

        $this->assertTrue(LayoutHelper::isRtlLocale('ar_EG'));
        $this->assertTrue(LayoutHelper::isRtlLocale('ar-eg'));

        // Both the underscore and the dash separators are supported on the
        // configured locales and on the checked one.

        $this->assertTrue(LayoutHelper::isRtlLocale('uz-AF'));
        $this->assertTrue(LayoutHelper::isRtlLocale('uz_af'));
        $this->assertTrue(LayoutHelper::isRtlLocale('he_IL'));

        // A regional locale of a not configured language is not RTL.

        $this->assertFalse(LayoutHelper::isRtlLocale('uz'));
        $this->assertFalse(LayoutHelper::isRtlLocale('en_US'));
    }

    public function testIsRtlLocaleWithInvalidValues()
    {
        config(['adminlte.rtl.locales' => ['ar']]);

        // A non string locale is never RTL.

        $this->assertFalse(LayoutHelper::isRtlLocale(null));
        $this->assertFalse(LayoutHelper::isRtlLocale(['ar']));

        // An invalid set of configured locales disables the detection.

        config(['adminlte.rtl.locales' => 'ar']);
        $this->assertFalse(LayoutHelper::isRtlLocale('ar'));

        config(['adminlte.rtl.locales' => []]);
        $this->assertFalse(LayoutHelper::isRtlLocale('ar'));
    }

    public function testIsRtlEnabledIgnoresTheLocaleWhenConfigured()
    {
        // An explicit configuration always takes precedence over the locale.

        config([
            'adminlte.rtl.enabled' => false,
            'adminlte.rtl.locales' => ['ar'],
        ]);

        app()->setLocale('ar');

        $this->assertFalse(LayoutHelper::isRtlEnabled());
        $this->assertEquals('ltr', LayoutHelper::getHtmlDirection());

        // And a non boolean configuration enables the locale detection.

        config(['adminlte.rtl.enabled' => 'yes']);

        $this->assertTrue(LayoutHelper::isRtlEnabled());

        app()->setLocale('en');
    }
}

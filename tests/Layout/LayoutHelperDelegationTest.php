<?php

use Illuminate\Support\Facades\View;
use JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper;
use JeroenNoten\LaravelAdminLte\Layout\BodyClasses;
use JeroenNoten\LaravelAdminLte\Layout\ColorMode;
use JeroenNoten\LaravelAdminLte\Layout\Direction;
use JeroenNoten\LaravelAdminLte\Layout\Layout;
use JeroenNoten\LaravelAdminLte\Layout\Sidebar;

/**
 * Checks that the LayoutHelper facade keeps returning exactly what its
 * collaborators produce. This is the regression net of the public API, so
 * every public method of the helper must be checked here.
 */
class LayoutHelperDelegationTest extends TestCase
{
    /**
     * The original package configuration, used to reset the configuration
     * between the scenarios of a test.
     *
     * @var array
     */
    protected $defaults;

    /**
     * The locales used to check the 'isRtlLocale' delegation.
     *
     * @var array
     */
    protected $locales = ['ar', 'AR', 'ar_EG', 'he-IL', 'en', 'uz-AF', '', 'xx'];

    /**
     * Setup this testing class.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->defaults = config('adminlte');
    }

    /**
     * Tear down this testing class.
     */
    public function tearDown(): void
    {
        View::flushSections();
        app()->setLocale('en');

        parent::tearDown();
    }

    /**
     * The set of configuration scenarios to check the delegation with.
     *
     * @return array
     */
    protected function scenarios()
    {
        return [
            'defaults' => [],

            'empty config' => ['adminlte' => []],

            'topnav layout' => [
                'adminlte.layout_topnav' => true,
                'adminlte.sidebar_mini' => true,
                'adminlte.sidebar_collapse' => true,
                'adminlte.layout_fixed_sidebar' => true,
            ],

            'boxed layout' => ['adminlte.layout_boxed' => true],

            'right sidebar' => ['adminlte.right_sidebar' => true],

            'fixed sections' => [
                'adminlte.layout_fixed_navbar' => true,
                'adminlte.layout_fixed_footer' => true,
            ],

            'legacy fixed sections' => [
                'adminlte.layout_fixed_navbar' => ['xs' => true, 'lg' => false],
                'adminlte.layout_fixed_footer' => ['md' => false],
            ],

            'sidebar tuning' => [
                'adminlte.sidebar_expand' => 'xxl',
                'adminlte.sidebar_mini' => 'md',
                'adminlte.sidebar_collapse' => true,
                'adminlte.sidebar_without_hover' => true,
                'adminlte.sidebar_theme' => 'light',
                'adminlte.sidebar_collapse_remember' => true,
                'adminlte.classes_sidebar' => 'bg-dark',
                'adminlte.sidebar_breakpoint' => 768,
            ],

            'sidebar nav tuning' => [
                'adminlte.sidebar_nav_compact' => true,
                'adminlte.sidebar_nav_indent' => true,
                'adminlte.sidebar_nav_pills' => true,
                'adminlte.classes_sidebar_nav' => 'my-nav-cls',
            ],

            'invalid sidebar tuning' => [
                'adminlte.sidebar_expand' => 'invalid',
                'adminlte.sidebar_mini' => 'invalid',
                'adminlte.sidebar_theme' => 'invalid',
                'adminlte.classes_sidebar' => null,
                'adminlte.sidebar_breakpoint' => 'invalid',
                'adminlte.classes_sidebar_nav' => ['nav-compact'],
            ],

            'dark color mode' => [
                'adminlte.color_mode.default' => 'dark',
                'adminlte.color_mode.remember' => false,
            ],

            'light color mode' => [
                'adminlte.color_mode.default' => 'light',
                'adminlte.color_mode.remember' => true,
            ],

            'auto color mode' => [
                'adminlte.color_mode.default' => 'auto',
                'adminlte.color_mode.remember' => false,
            ],

            'invalid color mode' => ['adminlte.color_mode.default' => 'invalid'],

            'legacy dark mode' => [
                'adminlte.color_mode.default' => 'light',
                'adminlte.layout_dark_mode' => true,
            ],

            'legacy theme mode' => [
                'adminlte.color_mode.default' => 'dark',
                'adminlte.layout_theme_mode' => 'light',
            ],

            'rtl enabled' => [
                'adminlte.rtl.enabled' => true,
                'adminlte.color_mode.default' => 'dark',
            ],

            'rtl disabled' => ['adminlte.rtl.enabled' => false],

            'rtl from the locale' => [
                'adminlte.rtl.enabled' => null,
                'adminlte.rtl.locales' => ['ar', 'he-IL'],
            ],

            'content wrapper preloader' => [
                'adminlte.classes_content_wrapper' => 'p-3',
                'adminlte.preloader.enabled' => true,
                'adminlte.preloader.mode' => 'cwrapper',
            ],

            'custom body classes' => ['adminlte.classes_body' => 'custom-class'],
        ];
    }

    /**
     * Applies a configuration scenario over the package defaults.
     *
     * @param  array  $scenario  The configuration of the scenario
     * @return void
     */
    protected function applyScenario($scenario)
    {
        config(['adminlte' => $this->defaults]);

        if (! empty($scenario)) {
            config($scenario);
        }
    }

    /**
     * Checks the delegation of every method of the helper on the current
     * configuration.
     *
     * @param  string  $name  The name of the current scenario
     * @return void
     */
    protected function assertDelegationHolds($name)
    {
        $msg = "Failed on the '{$name}' scenario";

        // The checks (boolean results).

        $this->assertSame(
            Layout::isTopnavEnabled(),
            LayoutHelper::isLayoutTopnavEnabled(),
            $msg
        );

        $this->assertSame(
            Layout::isBoxedEnabled(),
            LayoutHelper::isLayoutBoxedEnabled(),
            $msg
        );

        $this->assertSame(
            Layout::isRightSidebarEnabled(),
            LayoutHelper::isRightSidebarEnabled(),
            $msg
        );

        $this->assertSame(
            Direction::isRtlEnabled(),
            LayoutHelper::isRtlEnabled(),
            $msg
        );

        $this->assertSame(
            ColorMode::isDarkModeEnabled(),
            LayoutHelper::isDarkModeEnabled(),
            $msg
        );

        $this->assertSame(
            BodyClasses::isFixedNavbarEnabled(),
            LayoutHelper::isFixedNavbarEnabled(),
            $msg
        );

        $this->assertSame(
            BodyClasses::isFixedFooterEnabled(),
            LayoutHelper::isFixedFooterEnabled(),
            $msg
        );

        foreach ($this->locales as $locale) {
            $this->assertSame(
                Direction::isRtlLocale($locale),
                LayoutHelper::isRtlLocale($locale),
                "{$msg} (locale: {$locale})"
            );
        }

        // The getters and the makers (string results).

        $this->assertSame(
            Direction::get(),
            LayoutHelper::getHtmlDirection(),
            $msg
        );

        $this->assertSame(ColorMode::get(), LayoutHelper::getColorMode(), $msg);

        $htmlAttrs = array_merge(
            Direction::isRtlEnabled() ? ['dir="rtl"'] : [],
            ColorMode::makeHtmlAttributes()
        );

        $this->assertSame(
            trim(implode(' ', $htmlAttrs)),
            LayoutHelper::makeHtmlData(),
            $msg
        );

        $this->assertSame(
            trim(implode(' ', BodyClasses::make())),
            LayoutHelper::makeBodyClasses(),
            $msg
        );

        $this->assertSame(
            trim(implode(' ', Sidebar::makeClasses())),
            LayoutHelper::makeSidebarWrapperClasses(),
            $msg
        );

        $this->assertSame(
            trim(implode(' ', Sidebar::makeNavClasses())),
            LayoutHelper::makeSidebarNavClasses(),
            $msg
        );

        $this->assertSame(
            trim(implode(' ', Sidebar::makeAttributes())),
            LayoutHelper::makeSidebarData(),
            $msg
        );

        $this->assertSame(
            trim(implode(' ', Layout::makeWrapperClasses())),
            LayoutHelper::makeWrapperClasses(),
            $msg
        );

        $this->assertSame(
            trim(implode(' ', Layout::makeContentWrapperClasses())),
            LayoutHelper::makeContentWrapperClasses(),
            $msg
        );

        // The methods kept for backward compatibility.

        $this->assertSame('', LayoutHelper::makeBodyData(), $msg);
        $this->assertSame('', LayoutHelper::makeWrapperData(), $msg);
    }

    public function testEveryMethodDelegatesOnItsCollaborator()
    {
        foreach ($this->scenarios() as $name => $scenario) {
            $this->applyScenario($scenario);
            $this->assertDelegationHolds($name);
        }
    }

    public function testTheDelegationHoldsWithTheViewSections()
    {
        // The sections that may enable a layout option.

        $sections = [
            'layout_topnav', 'layout_boxed', 'right_sidebar', 'sidebar_collapse',
        ];

        foreach ($sections as $section) {
            $this->applyScenario([]);

            View::inject($section, 'dummy-content');
            $this->assertDelegationHolds("section: {$section}");

            View::flushSections();
        }
    }

    public function testTheDelegationHoldsWithTheRtlLocales()
    {
        $this->applyScenario([
            'adminlte.rtl.enabled' => null,
            'adminlte.rtl.locales' => ['ar', 'uz-AF'],
        ]);

        foreach (['en', 'ar', 'ar_EG', 'uz-AF', 'es'] as $locale) {
            app()->setLocale($locale);
            $this->assertDelegationHolds("locale: {$locale}");
        }

        app()->setLocale('en');
    }

    public function testEveryPublicMethodOfTheHelperIsChecked()
    {
        // This guard fails when a new public method is added to the facade
        // without checking its delegation on this test class.

        $checked = [
            'isLayoutTopnavEnabled', 'isLayoutBoxedEnabled',
            'isRightSidebarEnabled', 'isRtlEnabled', 'isRtlLocale',
            'getHtmlDirection', 'getColorMode', 'isDarkModeEnabled',
            'isFixedNavbarEnabled', 'isFixedFooterEnabled', 'makeHtmlData',
            'makeBodyClasses', 'makeBodyData', 'makeWrapperData',
            'makeSidebarWrapperClasses', 'makeSidebarNavClasses',
            'makeSidebarData', 'makeWrapperClasses',
            'makeContentWrapperClasses',
        ];

        $reflection = new ReflectionClass(LayoutHelper::class);
        $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
        $methods = array_map(fn ($method) => $method->getName(), $methods);

        sort($checked);
        sort($methods);

        $this->assertEquals($checked, $methods);
    }
}

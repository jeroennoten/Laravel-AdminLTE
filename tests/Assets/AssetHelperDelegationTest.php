<?php

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Assets\AdminLteVersion;
use JeroenNoten\LaravelAdminLte\Assets\AssetResolver;
use JeroenNoten\LaravelAdminLte\Helpers\AssetHelper;

/**
 * Checks that the AssetHelper facade keeps returning exactly what its
 * collaborators produce. This is the regression net of the public API, so
 * every public method of the helper must be checked here.
 */
class AssetHelperDelegationTest extends TestCase
{
    /**
     * The original package configuration, used to reset the configuration
     * between the scenarios of a test.
     *
     * @var array
     */
    protected $defaults;

    /**
     * The methods of the helper that always resolve an asset key.
     *
     * @var array
     */
    protected $directAssets = [
        'adminlteCss' => 'adminlte_css',
        'adminlteJs' => 'adminlte_js',
    ];

    /**
     * The methods of the helper that resolve an asset key only when the
     * related configuration option is enabled.
     *
     * @var array
     */
    protected $optionalAssets = [
        'bootstrapJs' => ['bootstrap_js', 'adminlte.assets.bootstrap_js'],
        'bootstrapIconsCss' => ['bootstrap_icons_css', 'adminlte.assets.bootstrap_icons'],
        'overlayScrollbarsCss' => ['overlayscrollbars_css', 'adminlte.assets.overlayscrollbars'],
        'overlayScrollbarsJs' => ['overlayscrollbars_js', 'adminlte.assets.overlayscrollbars'],
        'fontsCss' => ['fonts_css', 'adminlte.google_fonts.allowed'],
    ];

    /**
     * The locations used to check the 'applyVersion' delegation.
     *
     * @var array
     */
    protected $locations = [
        'https://cdn.jsdelivr.net/npm/admin-lte@{version}/dist/css/adminlte.min.css',
        'vendor/adminlte/dist/css/adminlte.min.css',
        '{version}',
        '',
    ];

    /**
     * Setup this testing class.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->defaults = config('adminlte');
        $this->clearPublishedAssets();
    }

    /**
     * Tear down this testing class.
     */
    public function tearDown(): void
    {
        $this->clearPublishedAssets();
        app()->setLocale('en');

        parent::tearDown();
    }

    /**
     * Removes the assets that may be published on the public folder.
     *
     * @return void
     */
    protected function clearPublishedAssets()
    {
        File::deleteDirectory(public_path('vendor/adminlte'));
        File::deleteDirectory(public_path('vendor/bootstrap'));
    }

    /**
     * Publishes a dummy file on the specified local asset path.
     *
     * @param  string  $assetKey  The local asset key (as on the config file)
     * @return string
     */
    protected function publishFakeAsset($assetKey)
    {
        $path = config("adminlte.assets.local.{$assetKey}");

        File::ensureDirectoryExists(File::dirname(public_path($path)));
        File::put(public_path($path), '/* dummy */');

        return $path;
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

            'cdn mode' => ['adminlte.assets.mode' => 'cdn'],

            'invalid mode' => ['adminlte.assets.mode' => 'invalid'],

            'local mode with fallback' => [
                'adminlte.assets.mode' => 'local',
                'adminlte.assets.cdn_fallback' => true,
            ],

            'local mode without fallback' => [
                'adminlte.assets.mode' => 'local',
                'adminlte.assets.cdn_fallback' => false,
            ],

            'rtl mode' => [
                'adminlte.assets.mode' => 'cdn',
                'adminlte.rtl.enabled' => true,
            ],

            'extended colors' => [
                'adminlte.assets.mode' => 'cdn',
                'adminlte.assets.extended_colors' => true,
            ],

            'extended colors with the v3 aliases' => [
                'adminlte.assets.mode' => 'cdn',
                'adminlte.assets.extended_colors' => true,
                'adminlte.assets.extended_colors_v3_aliases' => true,
            ],

            'extended colors on rtl' => [
                'adminlte.assets.mode' => 'cdn',
                'adminlte.assets.extended_colors' => true,
                'adminlte.rtl.enabled' => true,
            ],

            'disabled third party assets' => [
                'adminlte.assets.bootstrap_js' => false,
                'adminlte.assets.bootstrap_icons' => false,
                'adminlte.assets.overlayscrollbars' => false,
                'adminlte.google_fonts.allowed' => false,
            ],

            'configured version' => [
                'adminlte.assets.mode' => 'cdn',
                'adminlte.assets.adminlte_version' => '9.9.9',
            ],

            'without locations' => [
                'adminlte.assets.local.adminlte_css' => null,
                'adminlte.assets.cdn.adminlte_css' => null,
                'adminlte.assets.local.fonts_css' => '',
                'adminlte.assets.cdn.fonts_css' => '',
            ],
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

        $this->assertSame(AssetResolver::mode(), AssetHelper::mode(), $msg);

        $this->assertSame(
            AdminLteVersion::get(),
            AssetHelper::adminlteVersion(),
            $msg
        );

        foreach ($this->locations as $location) {
            $this->assertSame(
                AdminLteVersion::apply($location),
                AssetHelper::applyVersion($location),
                $msg
            );
        }

        // The 'resolve' method is the entry point of the resolver.

        foreach (['adminlte_css', 'adminlte_js', 'fonts_css', 'dummy_key'] as $key) {
            $this->assertSame(
                AssetResolver::resolve($key),
                AssetHelper::resolve($key),
                "{$msg} (key: {$key})"
            );
        }

        // The methods that always resolve an asset key.

        foreach ($this->directAssets as $method => $key) {
            $this->assertSame(
                AssetResolver::resolve($key),
                AssetHelper::{$method}(),
                "{$msg} (method: {$method})"
            );
        }

        // The methods that can be disabled by the configuration.

        foreach ($this->optionalAssets as $method => $data) {
            [$key, $option] = $data;

            $expected = config($option, true)
                ? AssetResolver::resolve($key)
                : null;

            $this->assertSame(
                $expected,
                AssetHelper::{$method}(),
                "{$msg} (method: {$method})"
            );
        }

        // The extended colors stylesheet, which also picks between the v4
        // palette and the v3 aliases.

        $colorsKey = config('adminlte.assets.extended_colors_v3_aliases', false)
            ? 'colors_v3_css'
            : 'colors_css';

        $expected = config('adminlte.assets.extended_colors', false)
            ? AssetResolver::resolve($colorsKey)
            : null;

        $this->assertSame($expected, AssetHelper::colorsCss(), $msg);
    }

    public function testEveryMethodDelegatesOnItsCollaborator()
    {
        foreach ($this->scenarios() as $name => $scenario) {
            $this->applyScenario($scenario);
            $this->assertDelegationHolds($name);
        }
    }

    public function testTheDelegationHoldsWithThePublishedAssets()
    {
        $this->applyScenario([
            'adminlte.assets.mode' => 'local',
            'adminlte.assets.cdn_fallback' => true,
        ]);

        // Publish some of the assets, so both the published and the missing
        // ones are checked.

        $cssPath = $this->publishFakeAsset('adminlte_css');
        $this->publishFakeAsset('bootstrap_js');

        $this->assertDelegationHolds('published assets');

        // The published file is really the resolved one.

        $this->assertEquals(asset($cssPath), AssetHelper::adminlteCss());
    }

    public function testTheDelegationHoldsWithTheRtlLocales()
    {
        $this->applyScenario([
            'adminlte.assets.mode' => 'cdn',
            'adminlte.assets.extended_colors' => true,
            'adminlte.rtl.enabled' => null,
            'adminlte.rtl.locales' => ['ar'],
        ]);

        foreach (['en', 'ar'] as $locale) {
            app()->setLocale($locale);
            $this->assertDelegationHolds("locale: {$locale}");
        }

        app()->setLocale('en');
    }

    public function testEveryPublicMethodOfTheHelperIsChecked()
    {
        // This guard fails when a new public method is added to the facade
        // without checking its delegation on this test class.

        $checked = array_merge(
            ['mode', 'adminlteVersion', 'applyVersion', 'colorsCss', 'resolve'],
            array_keys($this->directAssets),
            array_keys($this->optionalAssets)
        );

        $reflection = new ReflectionClass(AssetHelper::class);
        $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
        $methods = array_map(fn ($method) => $method->getName(), $methods);

        sort($checked);
        sort($methods);

        $this->assertEquals($checked, $methods);
    }
}

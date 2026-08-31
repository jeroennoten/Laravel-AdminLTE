<?php

use Composer\Autoload\ClassLoader;
use Composer\InstalledVersions;
use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Helpers\AssetHelper;

class AssetHelperTest extends TestCase
{
    /**
     * The set of asset methods that always resolve to a location, mapped to a
     * substring that identifies the resolved resource.
     *
     * @var array
     */
    protected $requiredAssets = [
        'adminlteCss' => 'adminlte.min.css',
        'adminlteJs' => 'adminlte.min.js',
    ];

    /**
     * The set of optional asset methods (they may be disabled by config),
     * mapped to the configuration option that enables them and a substring
     * that identifies the resolved resource.
     *
     * @var array
     */
    protected $optionalAssets = [
        'bootstrapJs' => ['adminlte.assets.bootstrap_js', 'bootstrap.bundle.min.js'],
        'bootstrapIconsCss' => ['adminlte.assets.bootstrap_icons', 'bootstrap-icons'],
        'overlayScrollbarsCss' => ['adminlte.assets.overlayscrollbars', 'overlayscrollbars.min.css'],
        'overlayScrollbarsJs' => ['adminlte.assets.overlayscrollbars', 'overlayscrollbars.browser'],
        'fontsCss' => ['adminlte.google_fonts.allowed', 'source-sans-3'],
    ];

    /**
     * Setup this testing class.
     */
    public function setUp(): void
    {
        parent::setUp();

        // The tests of this class check how the assets are resolved when they
        // are published or not, so we need a clean public folder.

        $this->clearPublishedAssets();
    }

    /**
     * Tear down this testing class.
     */
    public function tearDown(): void
    {
        $this->clearPublishedAssets();

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

    /*
    |--------------------------------------------------------------------------
    | Helper methods.
    |--------------------------------------------------------------------------
    */

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
     * Runs a callback with a faked composer installed packages dataset. The
     * original dataset is always restored, even when the callback fails.
     *
     * @param  callable  $mutator  A callback that mutates a dataset
     * @param  callable  $callback  The callback to run with the faked dataset
     * @return void
     */
    protected function withFakeInstalledVersions($mutator, $callback)
    {
        $ref = new ReflectionClass(InstalledVersions::class);
        $installedProp = $ref->getProperty('installed');
        $byVendorProp = $ref->getProperty('installedByVendor');

        // Force the datasets to be loaded, and keep the original values in
        // order to restore them later.

        $datasets = InstalledVersions::getAllRawData();
        $origInstalled = $installedProp->getValue();
        $origByVendor = $byVendorProp->getValue();

        // Fake the dataset of every registered composer autoloader.

        $fakeByVendor = [];

        foreach (ClassLoader::getRegisteredLoaders() as $vendorDir => $loader) {
            $vendorDir = strtr($vendorDir, '\\', '/');
            $dataFile = $vendorDir.'/composer/installed.php';

            if (is_file($dataFile)) {
                $fakeByVendor[$vendorDir] = $mutator(require $dataFile);
            }
        }

        $installedProp->setValue(null, $mutator($datasets[0] ?? []));
        $byVendorProp->setValue(null, $fakeByVendor);

        try {
            $callback();
        } finally {
            $installedProp->setValue(null, $origInstalled);
            $byVendorProp->setValue(null, $origByVendor);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Tests.
    |--------------------------------------------------------------------------
    */

    public function testMode()
    {
        config(['adminlte.assets.mode' => 'cdn']);
        $this->assertEquals('cdn', AssetHelper::mode());

        config(['adminlte.assets.mode' => 'local']);
        $this->assertEquals('local', AssetHelper::mode());

        // An invalid mode falls back to the local one.

        config(['adminlte.assets.mode' => 'dummy']);
        $this->assertEquals('local', AssetHelper::mode());
    }

    public function testResolveOnCdnMode()
    {
        config(['adminlte.assets.mode' => 'cdn']);

        $this->assertStringContainsString('cdn.jsdelivr.net', AssetHelper::adminlteCss());
        $this->assertStringContainsString('cdn.jsdelivr.net', AssetHelper::adminlteJs());
        $this->assertStringContainsString('bootstrap-icons', AssetHelper::bootstrapIconsCss());
    }

    public function testResolveFallsBackToTheCdn()
    {
        // The assets are not published on the testing application, so the CDN
        // location is expected as fallback.

        config([
            'adminlte.assets.mode' => 'local',
            'adminlte.assets.cdn_fallback' => true,
        ]);

        $this->assertStringContainsString('cdn.jsdelivr.net', AssetHelper::adminlteCss());

        // Without the fallback, the local path is used.

        config(['adminlte.assets.cdn_fallback' => false]);

        $this->assertStringNotContainsString('cdn.jsdelivr.net', AssetHelper::adminlteCss());
        $this->assertStringContainsString('adminlte.min.css', AssetHelper::adminlteCss());
    }

    public function testResolveTheRtlVariant()
    {
        config([
            'adminlte.assets.mode' => 'cdn',
            'adminlte.rtl.enabled' => true,
        ]);

        $this->assertStringContainsString('adminlte.rtl.min.css', AssetHelper::adminlteCss());

        // The scripts have no RTL variant.

        $this->assertStringNotContainsString('rtl', AssetHelper::adminlteJs());

        config(['adminlte.rtl.enabled' => false]);

        $this->assertStringNotContainsString('rtl', AssetHelper::adminlteCss());
    }

    public function testExtendedColors()
    {
        config(['adminlte.assets.mode' => 'cdn']);

        // The extended colors are optional.

        config(['adminlte.assets.extended_colors' => false]);
        $this->assertNull(AssetHelper::colorsCss());

        config(['adminlte.assets.extended_colors' => true]);
        $this->assertStringContainsString('adminlte-colors.min.css', AssetHelper::colorsCss());

        // The AdminLTE v3 color aliases can be used instead.

        config(['adminlte.assets.extended_colors_v3_aliases' => true]);
        $this->assertStringContainsString('adminlte-colors-v3.min.css', AssetHelper::colorsCss());

        // And they also provide a RTL variant.

        config(['adminlte.rtl.enabled' => true]);
        $this->assertStringContainsString('adminlte-colors-v3.rtl.min.css', AssetHelper::colorsCss());
    }

    public function testDisabledThirdPartyAssets()
    {
        config([
            'adminlte.assets.bootstrap_js' => false,
            'adminlte.assets.bootstrap_icons' => false,
            'adminlte.assets.overlayscrollbars' => false,
            'adminlte.google_fonts.allowed' => false,
        ]);

        $this->assertNull(AssetHelper::bootstrapJs());
        $this->assertNull(AssetHelper::bootstrapIconsCss());
        $this->assertNull(AssetHelper::overlayScrollbarsCss());
        $this->assertNull(AssetHelper::overlayScrollbarsJs());
        $this->assertNull(AssetHelper::fontsCss());
    }

    public function testCdnLocationsUseTheInstalledVersion()
    {
        config([
            'adminlte.assets.mode' => 'cdn',
            'adminlte.assets.adminlte_version' => null,
        ]);

        // The version placeholder must be resolved, never emitted as is.

        $url = AssetHelper::adminlteCss();

        $this->assertStringNotContainsString('{version}', $url);
        $this->assertStringContainsString('admin-lte@'.AssetHelper::adminlteVersion(), $url);
        $this->assertMatchesRegularExpression('/admin-lte@\d+\.\d+/', $url);

        // The detected version must be the one composer installed.

        $installed = \Composer\InstalledVersions::getPrettyVersion('almasaeed2010/adminlte');
        $this->assertEquals(ltrim($installed, 'v'), AssetHelper::adminlteVersion());
    }

    public function testResolveThePublishedAssets()
    {
        // Publish the AdminLTE assets into the testing application.

        $this->artisan('adminlte:install --only=assets --force');

        // Every configured local path of the AdminLTE distribution must exist.

        $paths = [
            config('adminlte.assets.local.adminlte_css'),
            config('adminlte.assets.local.adminlte_rtl_css'),
            config('adminlte.assets.local.adminlte_js'),
            config('adminlte.assets.local.colors_css'),
            config('adminlte.assets.local.colors_rtl_css'),
            config('adminlte.logo_img'),
        ];

        foreach ($paths as $path) {
            $this->assertFileExists(public_path($path), "Missing published asset: {$path}");
        }

        // Now the helper resolves the published files instead of the CDN.

        $this->assertStringNotContainsString('cdn.jsdelivr.net', AssetHelper::adminlteCss());
        $this->assertStringNotContainsString('cdn.jsdelivr.net', AssetHelper::adminlteJs());

        // The third party assets are not published, so they use the CDN.

        $this->assertStringContainsString('cdn.jsdelivr.net', AssetHelper::bootstrapJs());

        // Clean up the published files.

        File::deleteDirectory(public_path('vendor/adminlte'));
    }

    public function testResolveWithAnUnknownAssetKey()
    {
        // An asset key without a local path and without a CDN location can't
        // be resolved.

        config(['adminlte.assets.mode' => 'local']);
        $this->assertNull(AssetHelper::resolve('dummy_key'));

        config(['adminlte.assets.mode' => 'cdn']);
        $this->assertNull(AssetHelper::resolve('dummy_key'));
    }

    public function testEveryAssetIsResolvedOnCdnMode()
    {
        config([
            'adminlte.assets.mode' => 'cdn',
            'adminlte.assets.extended_colors' => true,
        ]);

        // The required assets always resolve to the CDN.

        foreach ($this->requiredAssets as $method => $token) {
            $url = AssetHelper::{$method}();

            $this->assertStringContainsString('cdn.jsdelivr.net', $url);
            $this->assertStringContainsString($token, $url);
        }

        // The optional assets resolve to the CDN when they are enabled.

        foreach ($this->optionalAssets as $method => $data) {
            [$cfgKey, $token] = $data;

            config([$cfgKey => true]);
            $url = AssetHelper::{$method}();

            $this->assertStringContainsString('cdn.jsdelivr.net', $url);
            $this->assertStringContainsString($token, $url);
        }

        // The extended colors stylesheet too.

        $this->assertStringContainsString('cdn.jsdelivr.net', AssetHelper::colorsCss());
    }

    public function testEveryAssetIsResolvedLocallyWhenTheFallbackIsDisabled()
    {
        config([
            'adminlte.assets.mode' => 'local',
            'adminlte.assets.cdn_fallback' => false,
            'adminlte.assets.extended_colors' => true,
        ]);

        foreach (array_keys($this->requiredAssets) as $method) {
            $this->assertStringNotContainsString(
                'cdn.jsdelivr.net',
                AssetHelper::{$method}()
            );
        }

        foreach ($this->optionalAssets as $method => $data) {
            [$cfgKey, $token] = $data;

            config([$cfgKey => true]);
            $url = AssetHelper::{$method}();

            $this->assertStringNotContainsString('cdn.jsdelivr.net', $url);
            $this->assertStringContainsString($token, $url);
        }

        $this->assertStringNotContainsString(
            'cdn.jsdelivr.net',
            AssetHelper::colorsCss()
        );
    }

    public function testEveryAssetFallsBackToTheCdnWhenNotPublished()
    {
        config([
            'adminlte.assets.mode' => 'local',
            'adminlte.assets.cdn_fallback' => true,
            'adminlte.assets.extended_colors' => true,
        ]);

        foreach (array_keys($this->requiredAssets) as $method) {
            $this->assertStringContainsString(
                'cdn.jsdelivr.net',
                AssetHelper::{$method}()
            );
        }

        foreach ($this->optionalAssets as $method => $data) {
            config([$data[0] => true]);

            $this->assertStringContainsString(
                'cdn.jsdelivr.net',
                AssetHelper::{$method}()
            );
        }

        $this->assertStringContainsString(
            'cdn.jsdelivr.net',
            AssetHelper::colorsCss()
        );
    }

    public function testResolveWithoutALocalPath()
    {
        // Without a local path, the CDN location is used even on the local
        // mode and with the CDN fallback disabled.

        config([
            'adminlte.assets.mode' => 'local',
            'adminlte.assets.cdn_fallback' => false,
            'adminlte.assets.local.adminlte_css' => null,
        ]);

        $this->assertStringContainsString('cdn.jsdelivr.net', AssetHelper::adminlteCss());

        // When there is no CDN location either, the asset is not resolvable.

        config(['adminlte.assets.cdn.adminlte_css' => null]);
        $this->assertNull(AssetHelper::adminlteCss());
    }

    public function testResolveWithoutACdnLocation()
    {
        // Without a CDN location, the local path is used even when the asset
        // is not published and the CDN fallback is enabled.

        config([
            'adminlte.assets.mode' => 'local',
            'adminlte.assets.cdn_fallback' => true,
            'adminlte.assets.cdn.adminlte_css' => null,
        ]);

        $this->assertStringContainsString('adminlte.min.css', AssetHelper::adminlteCss());

        // On the CDN mode, the local path is used as well.

        config(['adminlte.assets.mode' => 'cdn']);
        $this->assertStringContainsString('adminlte.min.css', AssetHelper::adminlteCss());
    }

    public function testAPublishedFileWinsOverTheCdn()
    {
        config([
            'adminlte.assets.mode' => 'local',
            'adminlte.assets.cdn_fallback' => true,
        ]);

        // Without the published file, the CDN location is used.

        $this->assertStringContainsString('cdn.jsdelivr.net', AssetHelper::adminlteCss());
        $this->assertStringContainsString('cdn.jsdelivr.net', AssetHelper::bootstrapJs());

        // Now publish the files and check they win over the CDN.

        $cssPath = $this->publishFakeAsset('adminlte_css');
        $jsPath = $this->publishFakeAsset('bootstrap_js');

        $this->assertEquals(asset($cssPath), AssetHelper::adminlteCss());
        $this->assertEquals(asset($jsPath), AssetHelper::bootstrapJs());

        // The other assets are still resolved from the CDN.

        $this->assertStringContainsString('cdn.jsdelivr.net', AssetHelper::adminlteJs());
    }

    public function testThePublishedFilesAreIgnoredOnCdnMode()
    {
        config(['adminlte.assets.mode' => 'cdn']);

        $this->publishFakeAsset('adminlte_css');

        $this->assertStringContainsString('cdn.jsdelivr.net', AssetHelper::adminlteCss());
    }

    public function testThePublishedRtlFileWinsOverTheCdn()
    {
        config([
            'adminlte.assets.mode' => 'local',
            'adminlte.assets.cdn_fallback' => true,
            'adminlte.rtl.enabled' => true,
        ]);

        // Publishing the LTR variant does not affect the RTL resolution.

        $this->publishFakeAsset('adminlte_css');
        $this->assertStringContainsString('cdn.jsdelivr.net', AssetHelper::adminlteCss());

        // Now publish the RTL variant.

        $rtlPath = $this->publishFakeAsset('adminlte_rtl_css');
        $this->assertEquals(asset($rtlPath), AssetHelper::adminlteCss());
    }

    public function testTheEnabledThirdPartyAssetsAreResolved()
    {
        config([
            'adminlte.assets.mode' => 'cdn',
            'adminlte.assets.bootstrap_js' => true,
            'adminlte.assets.bootstrap_icons' => true,
            'adminlte.assets.overlayscrollbars' => true,
            'adminlte.google_fonts.allowed' => true,
        ]);

        $this->assertNotNull(AssetHelper::bootstrapJs());
        $this->assertNotNull(AssetHelper::bootstrapIconsCss());
        $this->assertNotNull(AssetHelper::overlayScrollbarsCss());
        $this->assertNotNull(AssetHelper::overlayScrollbarsJs());
        $this->assertNotNull(AssetHelper::fontsCss());
    }

    public function testEachThirdPartyAssetIsDisabledIndependently()
    {
        config(['adminlte.assets.mode' => 'cdn']);

        // Disabling one asset does not disable the other ones.

        foreach ($this->optionalAssets as $method => $data) {
            config([
                'adminlte.assets.bootstrap_js' => true,
                'adminlte.assets.bootstrap_icons' => true,
                'adminlte.assets.overlayscrollbars' => true,
                'adminlte.google_fonts.allowed' => true,
            ]);

            config([$data[0] => false]);

            $this->assertNull(
                AssetHelper::{$method}(),
                "The asset resolved by {$method}() was not disabled"
            );
        }

        // The OverlayScrollbars option controls both of its assets.

        config(['adminlte.assets.overlayscrollbars' => false]);

        $this->assertNull(AssetHelper::overlayScrollbarsCss());
        $this->assertNull(AssetHelper::overlayScrollbarsJs());
    }

    public function testExtendedColorsCombinations()
    {
        config(['adminlte.assets.mode' => 'cdn', 'adminlte.rtl.enabled' => false]);

        // The extended colors are disabled by default.

        config([
            'adminlte.assets.extended_colors' => false,
            'adminlte.assets.extended_colors_v3_aliases' => false,
        ]);

        $this->assertNull(AssetHelper::colorsCss());

        // Enabled, without the v3 aliases.

        config(['adminlte.assets.extended_colors' => true]);

        $this->assertStringContainsString('adminlte-colors.min.css', AssetHelper::colorsCss());

        // Enabled, with the v3 aliases.

        config(['adminlte.assets.extended_colors_v3_aliases' => true]);

        $this->assertStringContainsString('adminlte-colors-v3.min.css', AssetHelper::colorsCss());

        // Disabled, with the v3 aliases enabled (the aliases option has no
        // effect when the extended colors are disabled).

        config(['adminlte.assets.extended_colors' => false]);

        $this->assertNull(AssetHelper::colorsCss());
    }

    public function testExtendedColorsRtlCombinations()
    {
        config([
            'adminlte.assets.mode' => 'cdn',
            'adminlte.assets.extended_colors' => true,
            'adminlte.rtl.enabled' => true,
        ]);

        // The RTL variant of the v4 palette.

        config(['adminlte.assets.extended_colors_v3_aliases' => false]);

        $this->assertStringContainsString('adminlte-colors.rtl.min.css', AssetHelper::colorsCss());

        // The RTL variant of the v3 aliases.

        config(['adminlte.assets.extended_colors_v3_aliases' => true]);

        $this->assertStringContainsString('adminlte-colors-v3.rtl.min.css', AssetHelper::colorsCss());

        // The LTR variants are used when the RTL mode is disabled.

        config(['adminlte.rtl.enabled' => false]);

        $this->assertStringContainsString('adminlte-colors-v3.min.css', AssetHelper::colorsCss());
        $this->assertStringNotContainsString('rtl', AssetHelper::colorsCss());
    }

    public function testOnlyTheRtlAwareAssetsHaveARtlVariant()
    {
        config([
            'adminlte.assets.mode' => 'cdn',
            'adminlte.rtl.enabled' => true,
        ]);

        // The stylesheets with a RTL variant.

        $this->assertStringContainsString('adminlte.rtl.min.css', AssetHelper::adminlteCss());

        // The other assets have no RTL variant.

        $this->assertStringNotContainsString('rtl', AssetHelper::adminlteJs());
        $this->assertStringNotContainsString('rtl', AssetHelper::bootstrapJs());
        $this->assertStringNotContainsString('rtl', AssetHelper::bootstrapIconsCss());
        $this->assertStringNotContainsString('rtl', AssetHelper::overlayScrollbarsCss());
        $this->assertStringNotContainsString('rtl', AssetHelper::overlayScrollbarsJs());
        $this->assertStringNotContainsString('rtl', AssetHelper::fontsCss());
    }

    public function testTheRtlModeIsResolvedFromTheLocale()
    {
        config([
            'adminlte.assets.mode' => 'cdn',
            'adminlte.rtl.enabled' => null,
            'adminlte.rtl.locales' => ['ar'],
        ]);

        app()->setLocale('en');
        $this->assertStringNotContainsString('rtl', AssetHelper::adminlteCss());

        app()->setLocale('ar');
        $this->assertStringContainsString('adminlte.rtl.min.css', AssetHelper::adminlteCss());

        app()->setLocale('en');
    }

    public function testAdminlteVersionWithAConfiguredVersion()
    {
        // A version configured by the user takes precedence.

        config(['adminlte.assets.adminlte_version' => '4.0.0-beta1']);

        $this->assertEquals('4.0.0-beta1', AssetHelper::adminlteVersion());

        config(['adminlte.assets.mode' => 'cdn']);

        $this->assertStringContainsString(
            'admin-lte@4.0.0-beta1',
            AssetHelper::adminlteCss()
        );
    }

    public function testAdminlteVersionIgnoresAnEmptyConfiguredVersion()
    {
        $installed = ltrim(
            InstalledVersions::getPrettyVersion('almasaeed2010/adminlte'),
            'v'
        );

        // An empty or invalid configured version is ignored, so the installed
        // version is detected instead.

        config(['adminlte.assets.adminlte_version' => '']);
        $this->assertEquals($installed, AssetHelper::adminlteVersion());

        config(['adminlte.assets.adminlte_version' => null]);
        $this->assertEquals($installed, AssetHelper::adminlteVersion());

        config(['adminlte.assets.adminlte_version' => false]);
        $this->assertEquals($installed, AssetHelper::adminlteVersion());
    }

    public function testAdminlteVersionStripsTheVersionPrefix()
    {
        config(['adminlte.assets.adminlte_version' => null]);

        $mutator = function ($data) {
            $data['versions']['almasaeed2010/adminlte']['pretty_version'] = 'v4.9.1';

            return $data;
        };

        $this->withFakeInstalledVersions($mutator, function () {
            $this->assertEquals('4.9.1', AssetHelper::adminlteVersion());
        });
    }

    public function testAdminlteVersionFallsBackOnADevelopmentVersion()
    {
        config([
            'adminlte.assets.adminlte_version' => null,
            'adminlte.assets.mode' => 'cdn',
        ]);

        // A development version is not resolvable on the CDN, so the fallback
        // version of the helper is expected.

        $mutator = function ($data) {
            $data['versions']['almasaeed2010/adminlte']['pretty_version'] = 'dev-master';

            return $data;
        };

        $this->withFakeInstalledVersions($mutator, function () {
            $this->assertEquals('4.8', AssetHelper::adminlteVersion());

            $this->assertStringContainsString(
                'admin-lte@4.8/',
                AssetHelper::adminlteCss()
            );
        });
    }

    public function testAdminlteVersionFallsBackWhenThePackageIsNotInstalled()
    {
        config(['adminlte.assets.adminlte_version' => null]);

        // The version can't be detected when the package is not installed.

        $mutator = function ($data) {
            unset($data['versions']['almasaeed2010/adminlte']);

            return $data;
        };

        $this->withFakeInstalledVersions($mutator, function () {
            $this->assertEquals('4.8', AssetHelper::adminlteVersion());
        });
    }

    public function testAdminlteVersionFallsBackOnAnUnknownVersion()
    {
        config(['adminlte.assets.adminlte_version' => null]);

        // The version can't be detected when the installed package has no
        // pretty version (for example, when it is a replaced package).

        $mutator = function ($data) {
            unset($data['versions']['almasaeed2010/adminlte']['pretty_version']);

            return $data;
        };

        $this->withFakeInstalledVersions($mutator, function () {
            $this->assertEquals('4.8', AssetHelper::adminlteVersion());
        });
    }

    public function testTheVersionPlaceholderIsResolvedOnEveryCdnLocation()
    {
        config([
            'adminlte.assets.mode' => 'cdn',
            'adminlte.assets.extended_colors' => true,
            'adminlte.assets.adminlte_version' => '9.9.9',
        ]);

        // The AdminLTE CDN locations use the placeholder.

        $withPlaceholder = [
            AssetHelper::adminlteCss(),
            AssetHelper::adminlteJs(),
            AssetHelper::colorsCss(),
        ];

        foreach ($withPlaceholder as $url) {
            $this->assertStringNotContainsString('{version}', $url);
            $this->assertStringContainsString('admin-lte@9.9.9', $url);
        }

        // The third party CDN locations are pinned, so they are not affected
        // by the AdminLTE version.

        $others = [
            AssetHelper::bootstrapJs(),
            AssetHelper::bootstrapIconsCss(),
            AssetHelper::overlayScrollbarsCss(),
            AssetHelper::overlayScrollbarsJs(),
            AssetHelper::fontsCss(),
        ];

        foreach ($others as $url) {
            $this->assertStringNotContainsString('{version}', $url);
            $this->assertStringNotContainsString('9.9.9', $url);
        }
    }

    public function testTheVersionPlaceholderIsNotResolvedOnLocalPaths()
    {
        // A local path with a placeholder is used as is, the placeholder is
        // only replaced on the CDN locations.

        config([
            'adminlte.assets.mode' => 'local',
            'adminlte.assets.cdn_fallback' => false,
            'adminlte.assets.local.adminlte_css' => 'vendor/adminlte/{version}/adminlte.css',
        ]);

        $this->assertEquals(
            asset('vendor/adminlte/{version}/adminlte.css'),
            AssetHelper::adminlteCss()
        );
    }
}

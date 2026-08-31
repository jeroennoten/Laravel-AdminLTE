<?php

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Assets\AssetResolver;

class AssetResolverTest extends TestCase
{
    /**
     * Setup this testing class.
     */
    public function setUp(): void
    {
        parent::setUp();

        // The resolver checks whether the local assets are published or not,
        // so we need a clean public folder.

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

    public function testMode()
    {
        config(['adminlte.assets.mode' => 'local']);
        $this->assertEquals('local', AssetResolver::mode());

        config(['adminlte.assets.mode' => 'cdn']);
        $this->assertEquals('cdn', AssetResolver::mode());

        // Any unsupported mode falls back to the local one.

        foreach (['dummy', '', null, true, ['cdn']] as $mode) {
            config(['adminlte.assets.mode' => $mode]);
            $this->assertEquals('local', AssetResolver::mode());
        }

        // And so does a missing configuration.

        config(['adminlte' => []]);
        $this->assertEquals('local', AssetResolver::mode());
    }

    public function testTheRtlAwareKeys()
    {
        $this->assertEquals(
            ['adminlte_css', 'colors_css', 'colors_v3_css'],
            AssetResolver::RTL_AWARE_KEYS
        );
    }

    public function testResolveOnCdnMode()
    {
        config([
            'adminlte.assets.mode' => 'cdn',
            'adminlte.assets.adminlte_version' => '9.9.9',
        ]);

        $this->assertEquals(
            'https://cdn.jsdelivr.net/npm/admin-lte@9.9.9/dist/css/adminlte.min.css',
            AssetResolver::resolve('adminlte_css')
        );

        // The published files are ignored on the CDN mode.

        $this->publishFakeAsset('adminlte_css');

        $this->assertStringContainsString(
            'cdn.jsdelivr.net',
            AssetResolver::resolve('adminlte_css')
        );
    }

    public function testResolveOnCdnModeWithoutACdnLocation()
    {
        config([
            'adminlte.assets.mode' => 'cdn',
            'adminlte.assets.cdn.adminlte_css' => null,
        ]);

        // The local path is the only option left, even when it is not
        // published yet.

        $local = config('adminlte.assets.local.adminlte_css');

        $this->assertEquals(asset($local), AssetResolver::resolve('adminlte_css'));
    }

    public function testResolveOnLocalModeWithAPublishedAsset()
    {
        config([
            'adminlte.assets.mode' => 'local',
            'adminlte.assets.cdn_fallback' => true,
        ]);

        $path = $this->publishFakeAsset('adminlte_css');

        $this->assertEquals(asset($path), AssetResolver::resolve('adminlte_css'));
    }

    public function testResolveOnLocalModeWithoutAPublishedAsset()
    {
        // The CDN location is used as fallback when the asset is not
        // published yet.

        config([
            'adminlte.assets.mode' => 'local',
            'adminlte.assets.cdn_fallback' => true,
        ]);

        $this->assertStringContainsString(
            'cdn.jsdelivr.net',
            AssetResolver::resolve('adminlte_css')
        );

        // Without the fallback, the local path is used anyway.

        config(['adminlte.assets.cdn_fallback' => false]);

        $local = config('adminlte.assets.local.adminlte_css');

        $this->assertEquals(asset($local), AssetResolver::resolve('adminlte_css'));
    }

    public function testResolveOnLocalModeWithoutACdnLocation()
    {
        // The local path is used when there is no CDN location to fallback
        // to, even when the asset is not published.

        config([
            'adminlte.assets.mode' => 'local',
            'adminlte.assets.cdn_fallback' => true,
            'adminlte.assets.cdn.adminlte_css' => null,
        ]);

        $local = config('adminlte.assets.local.adminlte_css');

        $this->assertEquals(asset($local), AssetResolver::resolve('adminlte_css'));
    }

    public function testResolveWithoutALocalPath()
    {
        // Without a local path, the CDN location is used on both modes.

        config([
            'adminlte.assets.mode' => 'local',
            'adminlte.assets.cdn_fallback' => false,
            'adminlte.assets.local.adminlte_css' => null,
        ]);

        $this->assertStringContainsString(
            'cdn.jsdelivr.net',
            AssetResolver::resolve('adminlte_css')
        );

        config(['adminlte.assets.mode' => 'cdn']);

        $this->assertStringContainsString(
            'cdn.jsdelivr.net',
            AssetResolver::resolve('adminlte_css')
        );
    }

    public function testResolveWithoutAnyLocation()
    {
        config([
            'adminlte.assets.local.adminlte_css' => null,
            'adminlte.assets.cdn.adminlte_css' => null,
        ]);

        foreach (['local', 'cdn'] as $mode) {
            config(['adminlte.assets.mode' => $mode]);
            $this->assertNull(AssetResolver::resolve('adminlte_css'));
        }
    }

    public function testResolveWithEmptyLocations()
    {
        // An empty location is not usable.

        config([
            'adminlte.assets.local.adminlte_css' => '',
            'adminlte.assets.cdn.adminlte_css' => '',
        ]);

        foreach (['local', 'cdn'] as $mode) {
            config(['adminlte.assets.mode' => $mode]);
            $this->assertNull(AssetResolver::resolve('adminlte_css'));
        }
    }

    public function testResolveWithAnUnknownKey()
    {
        foreach (['local', 'cdn'] as $mode) {
            config(['adminlte.assets.mode' => $mode]);
            $this->assertNull(AssetResolver::resolve('dummy_key'));
        }
    }

    public function testResolveAppliesTheVersionOnTheCdnLocation()
    {
        config([
            'adminlte.assets.mode' => 'cdn',
            'adminlte.assets.adminlte_version' => '9.9.9',
        ]);

        $url = AssetResolver::resolve('adminlte_js');

        $this->assertStringNotContainsString('{version}', $url);
        $this->assertStringContainsString('admin-lte@9.9.9', $url);
    }

    public function testResolveDoesNotApplyTheVersionOnTheLocalPath()
    {
        // The placeholder is only replaced on the CDN locations.

        config([
            'adminlte.assets.mode' => 'local',
            'adminlte.assets.cdn_fallback' => false,
            'adminlte.assets.adminlte_version' => '9.9.9',
            'adminlte.assets.local.adminlte_css' => 'vendor/adminlte/{version}/adminlte.css',
        ]);

        $this->assertEquals(
            asset('vendor/adminlte/{version}/adminlte.css'),
            AssetResolver::resolve('adminlte_css')
        );
    }

    public function testResolveTheRtlVariantOfTheAwareKeys()
    {
        config([
            'adminlte.assets.mode' => 'cdn',
            'adminlte.rtl.enabled' => true,
        ]);

        // Every RTL aware key resolves to its '_rtl_css' variant.

        $expected = [
            'adminlte_css' => 'adminlte.rtl.min.css',
            'colors_css' => 'adminlte-colors.rtl.min.css',
            'colors_v3_css' => 'adminlte-colors-v3.rtl.min.css',
        ];

        foreach ($expected as $key => $file) {
            $this->assertStringContainsString($file, AssetResolver::resolve($key));
        }

        // The other keys have no RTL variant.

        foreach (['adminlte_js', 'bootstrap_js', 'fonts_css'] as $key) {
            $this->assertStringNotContainsString(
                'rtl',
                AssetResolver::resolve($key)
            );
        }
    }

    public function testTheRtlVariantIsNotUsedOnTheLtrMode()
    {
        config([
            'adminlte.assets.mode' => 'cdn',
            'adminlte.rtl.enabled' => false,
        ]);

        foreach (AssetResolver::RTL_AWARE_KEYS as $key) {
            $this->assertStringNotContainsString(
                'rtl',
                AssetResolver::resolve($key)
            );
        }
    }

    public function testTheRtlVariantFollowsTheLocale()
    {
        config([
            'adminlte.assets.mode' => 'cdn',
            'adminlte.rtl.enabled' => null,
            'adminlte.rtl.locales' => ['ar'],
        ]);

        app()->setLocale('en');

        $this->assertStringNotContainsString(
            'rtl',
            AssetResolver::resolve('adminlte_css')
        );

        app()->setLocale('ar');

        $this->assertStringContainsString(
            'adminlte.rtl.min.css',
            AssetResolver::resolve('adminlte_css')
        );

        app()->setLocale('en');
    }

    public function testTheRtlVariantResolvesThePublishedFile()
    {
        config([
            'adminlte.assets.mode' => 'local',
            'adminlte.assets.cdn_fallback' => true,
            'adminlte.rtl.enabled' => true,
        ]);

        // Publishing the LTR variant does not affect the RTL resolution.

        $this->publishFakeAsset('adminlte_css');

        $this->assertStringContainsString(
            'cdn.jsdelivr.net',
            AssetResolver::resolve('adminlte_css')
        );

        // Now publish the RTL variant.

        $rtlPath = $this->publishFakeAsset('adminlte_rtl_css');

        $this->assertEquals(
            asset($rtlPath),
            AssetResolver::resolve('adminlte_css')
        );
    }
}

<?php

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Helpers\AssetHelper;

class AssetHelperTest extends TestCase
{
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
}

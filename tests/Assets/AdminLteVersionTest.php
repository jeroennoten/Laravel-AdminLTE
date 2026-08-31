<?php

use Composer\Autoload\ClassLoader;
use Composer\InstalledVersions;
use JeroenNoten\LaravelAdminLte\Assets\AdminLteVersion;

class AdminLteVersionTest extends TestCase
{
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

    /**
     * Gets the version of the AdminLTE distribution installed by composer.
     *
     * @return string
     */
    protected function getInstalledVersion()
    {
        return ltrim(
            InstalledVersions::getPrettyVersion(AdminLteVersion::PACKAGE),
            'v'
        );
    }

    public function testTheConstants()
    {
        $this->assertEquals('almasaeed2010/adminlte', AdminLteVersion::PACKAGE);
        $this->assertEquals('{version}', AdminLteVersion::PLACEHOLDER);
        $this->assertEquals('4.8', AdminLteVersion::FALLBACK);
    }

    public function testGetWithAConfiguredVersion()
    {
        // A version configured by the user always takes precedence.

        config(['adminlte.assets.adminlte_version' => '4.0.0-beta1']);

        $this->assertEquals('4.0.0-beta1', AdminLteVersion::get());
    }

    public function testGetIgnoresAnInvalidConfiguredVersion()
    {
        // Only a non empty string is accepted as a configured version, so the
        // installed one is detected on any other case.

        foreach (['', null, false, 4.8, ['4.8']] as $cfg) {
            config(['adminlte.assets.adminlte_version' => $cfg]);

            $this->assertEquals($this->getInstalledVersion(), AdminLteVersion::get());
        }
    }

    public function testGetDetectsTheInstalledVersion()
    {
        config(['adminlte.assets.adminlte_version' => null]);

        $this->assertEquals($this->getInstalledVersion(), AdminLteVersion::get());
        $this->assertMatchesRegularExpression('/^\d+\.\d+/', AdminLteVersion::get());
    }

    public function testGetStripsTheVersionPrefix()
    {
        config(['adminlte.assets.adminlte_version' => null]);

        $mutator = function ($data) {
            $data['versions'][AdminLteVersion::PACKAGE]['pretty_version'] = 'v4.9.1';

            return $data;
        };

        $this->withFakeInstalledVersions($mutator, function () {
            $this->assertEquals('4.9.1', AdminLteVersion::get());
        });
    }

    public function testGetFallsBackOnADevelopmentVersion()
    {
        config(['adminlte.assets.adminlte_version' => null]);

        // A development version is not resolvable on a CDN.

        foreach (['dev-master', 'dev-main', 'invalid'] as $version) {
            $mutator = function ($data) use ($version) {
                $data['versions'][AdminLteVersion::PACKAGE]['pretty_version'] = $version;

                return $data;
            };

            $this->withFakeInstalledVersions($mutator, function () {
                $this->assertEquals(
                    AdminLteVersion::FALLBACK,
                    AdminLteVersion::get()
                );
            });
        }
    }

    public function testGetFallsBackWhenThePackageIsNotInstalled()
    {
        config(['adminlte.assets.adminlte_version' => null]);

        $mutator = function ($data) {
            unset($data['versions'][AdminLteVersion::PACKAGE]);

            return $data;
        };

        $this->withFakeInstalledVersions($mutator, function () {
            $this->assertEquals(AdminLteVersion::FALLBACK, AdminLteVersion::get());
        });
    }

    public function testGetFallsBackOnAnUnknownVersion()
    {
        config(['adminlte.assets.adminlte_version' => null]);

        // The version can't be detected when the installed package has no
        // pretty version (for example, when it is a replaced package).

        $mutator = function ($data) {
            unset($data['versions'][AdminLteVersion::PACKAGE]['pretty_version']);

            return $data;
        };

        $this->withFakeInstalledVersions($mutator, function () {
            $this->assertEquals(AdminLteVersion::FALLBACK, AdminLteVersion::get());
        });
    }

    public function testApplyReplacesThePlaceholder()
    {
        config(['adminlte.assets.adminlte_version' => '9.9.9']);

        $this->assertEquals(
            'https://cdn.example.com/admin-lte@9.9.9/dist/css/adminlte.css',
            AdminLteVersion::apply(
                'https://cdn.example.com/admin-lte@{version}/dist/css/adminlte.css'
            )
        );

        // Every occurrence of the placeholder is replaced.

        $this->assertEquals(
            '9.9.9/9.9.9',
            AdminLteVersion::apply('{version}/{version}')
        );
    }

    public function testApplyWithoutThePlaceholder()
    {
        config(['adminlte.assets.adminlte_version' => '9.9.9']);

        // A location without the placeholder is returned untouched.

        $this->assertEquals(
            'vendor/adminlte/dist/css/adminlte.min.css',
            AdminLteVersion::apply('vendor/adminlte/dist/css/adminlte.min.css')
        );

        $this->assertEquals('', AdminLteVersion::apply(''));
    }

    public function testApplyWithInvalidLocations()
    {
        config(['adminlte.assets.adminlte_version' => '9.9.9']);

        // A non string location is returned as is.

        $this->assertNull(AdminLteVersion::apply(null));
        $this->assertFalse(AdminLteVersion::apply(false));
        $this->assertEquals(['{version}'], AdminLteVersion::apply(['{version}']));
    }
}

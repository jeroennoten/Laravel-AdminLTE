<?php

use JeroenNoten\LaravelAdminLte\Console\PackageResources\PluginsCatalog;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\PluginsResource;

class PluginsCatalogTest extends TestCase
{
    /**
     * The full set of plugin keys of the AdminLTE v3 era. Every one of them
     * must be recognized by the catalogue, either as an available plugin or
     * as a legacy one.
     *
     * @var array
     */
    protected $v3PluginKeys = [
        'bootstrap4DualListbox', 'bootstrapColorpicker', 'bootstrapSlider',
        'bootstrapSwitch', 'bsCustomFileInput', 'chartJs', 'datatables',
        'datatablesPlugins', 'daterangepicker', 'ekkoLightbox', 'fastclick',
        'filterizr', 'flagIconCss', 'flot', 'fullcalendar', 'icheckBootstrap',
        'inputmask', 'ionRangslider', 'jquery', 'jqueryKnob', 'jqueryMapael',
        'jqueryMousewheel', 'jqueryUi', 'jqueryUiTouchPunch', 'jqueryValidation',
        'jqvmap', 'jsgrid', 'moment', 'overlayScrollbars', 'paceProgress',
        'raphael', 'select2', 'sparklines', 'summernote',
        'tempusdominusBootstrap4', 'toastr', 'uplot',
    ];

    /**
     * Makes a catalogue instance.
     *
     * @return PluginsCatalog
     */
    protected function makeCatalog()
    {
        return new PluginsCatalog();
    }

    public function testGetReturnsAllThePlugins()
    {
        $plugins = $this->makeCatalog()->get();

        $this->assertIsArray($plugins);
        $this->assertNotEmpty($plugins);

        // The keys of the plugins that AdminLTE v4 recommends.

        $expected = [
            'apexcharts', 'chartJs', 'datatables', 'dropzone', 'easymde',
            'filepond', 'flatpickr', 'fullcalendar', 'glightbox', 'imask',
            'jsvectormap', 'noUiSlider', 'pickr', 'quill', 'select2',
            'sortablejs', 'sweetalert2', 'tabulator', 'tomSelect',
        ];

        $this->assertEquals($expected, array_keys($plugins));
    }

    public function testGetReturnsASinglePlugin()
    {
        $plugin = $this->makeCatalog()->get('flatpickr');

        $this->assertEquals('flatpickr', $plugin['package']);
        $this->assertEquals('flatpickr/dist', $plugin['source']);
        $this->assertEquals('flatpickr', $plugin['target']);
        $this->assertStringContainsString('Flatpickr', $plugin['name']);
    }

    public function testGetWithAnUnknownPluginKey()
    {
        $catalog = $this->makeCatalog();

        $this->assertEquals([], $catalog->get('dummy'));
        $this->assertEquals([], $catalog->get('summernote'));

        // An empty key returns the whole set of plugins.

        $this->assertEquals($catalog->get(), $catalog->get(''));
        $this->assertEquals($catalog->get(), $catalog->get(null));
        $this->assertEquals($catalog->get(), $catalog->get(0));
    }

    public function testEveryPluginHasTheRequiredData()
    {
        foreach ($this->makeCatalog()->get() as $key => $plugin) {
            $this->assertArrayHasKey('name', $plugin, "Missing name on {$key}");
            $this->assertArrayHasKey('package', $plugin, "Missing package on {$key}");
            $this->assertArrayHasKey('version', $plugin, "Missing version on {$key}");

            // A plugin defines either a single source or a set of resources.

            $hasSource = isset($plugin['source']) || isset($plugin['resources']);
            $this->assertTrue($hasSource, "Missing source on {$key}");

            // The resources of a plugin declare their own source and target.

            foreach ($plugin['resources'] ?? [] as $resource) {
                $this->assertArrayHasKey('source', $resource);
                $this->assertArrayHasKey('target', $resource);
            }
        }
    }

    public function testGetLegacyReplacementWithAReplacement()
    {
        $catalog = $this->makeCatalog();

        $this->assertEquals('quill', $catalog->getLegacyReplacement('summernote'));
        $this->assertEquals('easymde', $catalog->getLegacyReplacement('simplemde'));
        $this->assertEquals('flatpickr', $catalog->getLegacyReplacement('daterangepicker'));
        $this->assertEquals('imask', $catalog->getLegacyReplacement('inputmask'));
        $this->assertEquals('apexcharts', $catalog->getLegacyReplacement('flot'));
        $this->assertEquals('tabulator', $catalog->getLegacyReplacement('jsgrid'));
    }

    public function testGetLegacyReplacementWithoutAReplacement()
    {
        $catalog = $this->makeCatalog();

        // A legacy plugin covered natively by Bootstrap 5.3 or AdminLTE v4
        // reports a null replacement.

        $this->assertNull($catalog->getLegacyReplacement('icheckBootstrap'));
        $this->assertNull($catalog->getLegacyReplacement('jquery'));
        $this->assertNull($catalog->getLegacyReplacement('moment'));
        $this->assertNull($catalog->getLegacyReplacement('paceProgress'));
    }

    public function testGetLegacyReplacementWithAnUnknownKey()
    {
        $catalog = $this->makeCatalog();

        // A key that is not a legacy one reports false, which is different
        // from the null of a legacy plugin without replacement.

        $this->assertFalse($catalog->getLegacyReplacement('dummy'));
        $this->assertFalse($catalog->getLegacyReplacement(''));
        $this->assertFalse($catalog->getLegacyReplacement(null));

        // The keys of the available plugins are not legacy ones either.

        foreach (array_keys($catalog->get()) as $key) {
            $this->assertFalse(
                $catalog->getLegacyReplacement($key),
                "The available plugin '{$key}' is reported as legacy"
            );
        }
    }

    public function testEveryV3PluginKeyIsRecognized()
    {
        $catalog = $this->makeCatalog();

        foreach ($this->v3PluginKeys as $key) {
            $isAvailable = ! empty($catalog->get($key));
            $isLegacy = $catalog->getLegacyReplacement($key) !== false;

            $this->assertTrue(
                $isAvailable || $isLegacy,
                "The plugin key '{$key}' is neither available nor legacy."
            );

            // A key can not be available and legacy at the same time.

            $this->assertFalse(
                $isAvailable && $isLegacy,
                "The plugin key '{$key}' is both available and legacy."
            );
        }
    }

    public function testEveryLegacyReplacementPointsToAnAvailablePlugin()
    {
        $catalog = $this->makeCatalog();

        foreach ($this->v3PluginKeys as $key) {
            $replacement = $catalog->getLegacyReplacement($key);

            if (! is_string($replacement)) {
                continue;
            }

            $this->assertNotEmpty(
                $catalog->get($replacement),
                "The replacement '{$replacement}' of '{$key}' does not exist."
            );
        }
    }

    public function testTheResourceDelegatesOnTheCatalog()
    {
        $catalog = $this->makeCatalog();
        $resource = new PluginsResource();

        // The plugins resource must expose exactly the catalogue data.

        $this->assertEquals($catalog->get(), $resource->getSourceData());

        foreach (array_keys($catalog->get()) as $key) {
            $this->assertEquals(
                $catalog->get($key),
                $resource->getSourceData($key)
            );
        }

        $this->assertEquals([], $resource->getSourceData('dummy'));

        // And the same happens with the legacy plugin keys.

        foreach ($this->v3PluginKeys as $key) {
            $this->assertSame(
                $catalog->getLegacyReplacement($key),
                $resource->getLegacyPluginReplacement($key)
            );
        }

        $this->assertFalse($resource->getLegacyPluginReplacement('dummy'));
    }
}

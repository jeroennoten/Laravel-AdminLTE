<?php

class TranslationsTest extends TestCase
{
    /**
     * The locale used as the reference of the key set.
     *
     * @var string
     */
    protected $referenceLocale = 'en';

    /**
     * Gets the path of every shipped translation file, keyed by locale.
     *
     * @return array
     */
    protected function getTranslationFiles()
    {
        $files = [];

        foreach (glob(__DIR__.'/../resources/lang/*/adminlte.php') as $file) {
            $files[basename(dirname($file))] = $file;
        }

        return $files;
    }

    public function testEveryLocaleShipsTheSameKeySet()
    {
        $files = $this->getTranslationFiles();

        $this->assertArrayHasKey($this->referenceLocale, $files);

        $reference = array_keys(require $files[$this->referenceLocale]);
        sort($reference);

        foreach ($files as $locale => $file) {
            $keys = array_keys(require $file);
            sort($keys);

            $this->assertEquals(
                $reference,
                $keys,
                "The '{$locale}' locale does not match the key set of the ".
                "'{$this->referenceLocale}' one."
            );
        }
    }

    public function testEveryTranslationIsANonEmptyString()
    {
        foreach ($this->getTranslationFiles() as $locale => $file) {
            foreach (require $file as $key => $value) {
                $this->assertIsString($value, "{$locale}.{$key}");
                $this->assertNotSame('', trim($value), "{$locale}.{$key}");
            }
        }
    }

    public function testTheAccessibilityKeysAreTranslated()
    {
        // These keys are the accessible name of a control, so a missing
        // translation ships an English string on every page of the app.

        $keys = [
            'card_maximize', 'card_collapse', 'card_remove', 'card_disabled',
            'loading', 'progress', 'skip_to_content', 'skip_to_navigation',
            'main_navigation', 'no_matching_pages', 'notifications',
            'breadcrumb', 'timeline_time', 'close',
        ];

        $reference = require $this->getTranslationFiles()[$this->referenceLocale];

        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $reference);
        }
    }

    public function testTheTranslationsAreResolvedThroughThePackageNamespace()
    {
        app()->setLocale('en');

        $this->assertEquals(
            'Loading',
            __('adminlte::adminlte.loading')
        );

        app()->setLocale('de');

        $this->assertNotEquals(
            'adminlte::adminlte.card_remove',
            __('adminlte::adminlte.card_remove')
        );
    }
}

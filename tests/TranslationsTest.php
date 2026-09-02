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

    public function testTheLaoLocaleIsReachableByItsIsoCode()
    {
        // The 'la' folder shipped the Lao translations, but 'la' is the code
        // of Latin. The canonical folder is 'lo' now, and 'la' is kept as an
        // alias so an application configured with the old code keeps working.

        $files = $this->getTranslationFiles();

        $this->assertArrayHasKey('lo', $files);
        $this->assertArrayHasKey('la', $files);

        $this->assertEquals(require $files['lo'], require $files['la']);

        app()->setLocale('lo');
        $lao = __('adminlte::adminlte.sign_in');

        app()->setLocale('la');
        $alias = __('adminlte::adminlte.sign_in');

        $this->assertEquals($lao, $alias);
        $this->assertNotEquals('adminlte::adminlte.sign_in', $lao);

        app()->setLocale('en');
    }

    public function testEveryLocaleShipsEveryTranslationFile()
    {
        // A missing file falls back to the application fallback locale, so a
        // localized panel silently shows English strings.

        $expected = [];

        foreach (glob(__DIR__.'/../resources/lang/en/*.php') as $file) {
            $expected[] = basename($file);
        }

        sort($expected);
        $this->assertContains('iframe.php', $expected);
        $this->assertContains('menu.php', $expected);

        foreach (glob(__DIR__.'/../resources/lang/*', GLOB_ONLYDIR) as $dir) {
            $files = [];

            foreach (glob($dir.'/*.php') as $file) {
                $files[] = basename($file);
            }

            sort($files);

            $this->assertEquals(
                $expected,
                $files,
                "The '".basename($dir)."' locale does not ship every file."
            );
        }
    }

    public function testEveryLocaleShipsTheSameKeysOnEveryFile()
    {
        // A locale may carry extra keys of its own, but a missing one falls
        // back to the fallback locale and shows an English string.

        foreach (['menu.php', 'iframe.php'] as $file) {
            $reference = array_keys(require __DIR__.'/../resources/lang/en/'.$file);

            foreach (glob(__DIR__.'/../resources/lang/*', GLOB_ONLYDIR) as $dir) {
                $keys = array_keys(require $dir.'/'.$file);

                $this->assertEmpty(
                    array_diff($reference, $keys),
                    basename($dir).'/'.$file.' is missing: '.
                    implode(', ', array_diff($reference, $keys))
                );
            }
        }
    }
}

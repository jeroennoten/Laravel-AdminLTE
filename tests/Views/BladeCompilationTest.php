<?php

use Illuminate\Support\Facades\Blade;

class BladeCompilationTest extends TestCase
{
    /**
     * Gets the path of every blade view shipped by the package.
     *
     * @return array
     */
    protected function getViewFiles()
    {
        $base = realpath(__DIR__.'/../../resources/views');
        $files = [];

        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($base)
        );

        foreach ($it as $file) {
            if (substr($file->getFilename(), -10) === '.blade.php') {
                $files[] = $file->getPathname();
            }
        }

        sort($files);

        return $files;
    }

    public function testEveryShippedViewCompilesToValidPhp()
    {
        $files = $this->getViewFiles();

        $this->assertNotEmpty($files);

        foreach ($files as $file) {
            $compiled = Blade::compileString(file_get_contents($file));

            // A compilation error does not throw, it leaves invalid PHP
            // behind, so the result is parsed instead.

            $error = null;

            try {
                token_get_all($compiled, TOKEN_PARSE);
            } catch (\ParseError $e) {
                $error = $e->getMessage();
            }

            $this->assertNull(
                $error,
                'The compiled output of '.basename($file).' is not valid PHP: '.$error
            );
        }
    }

    public function testNoShippedViewMixesTheInlineAndTheBlockPhpDirectives()
    {
        // The block directive is matched lazily, so an inline '@php(...)'
        // followed by an '@endphp' swallows everything in between, and the
        // directives of that region are emitted as literal text.

        foreach ($this->getViewFiles() as $file) {
            $content = file_get_contents($file);

            if (! preg_match('/@php\s*\(/', $content)) {
                continue;
            }

            $lastInline = 0;

            if (preg_match_all('/@php\s*\(/', $content, $m, PREG_OFFSET_CAPTURE)) {
                $lastInline = end($m[0])[1];
            }

            $lastEndBlock = strrpos($content, '@endphp');

            $this->assertFalse(
                $lastEndBlock !== false && $lastEndBlock > $lastInline,
                'The view '.basename($file).' has an inline @php() directive '.
                'followed by an @endphp one, which breaks the compilation of '.
                'every directive in between. Use the block form instead.'
            );
        }
    }
}

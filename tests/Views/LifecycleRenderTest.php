<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

class LifecycleRenderTest extends TestCase
{
    /**
     * Tear down this testing class.
     */
    public function tearDown(): void
    {
        View::flushSections();

        parent::tearDown();
    }

    /**
     * Renders the package page layout.
     *
     * @return string
     */
    protected function renderPage()
    {
        View::flushSections();

        return View::make('adminlte::page')->render();
    }

    public function testTheLifecycleHelpersAreDefinedOnce()
    {
        $html = $this->renderPage();

        $this->assertEquals(1, substr_count($html, 'window._AdminLTE_Ready ='));
        $this->assertEquals(1, substr_count($html, 'window._AdminLTE_Once ='));
    }

    public function testNoInlineScriptBindsOnDomContentLoaded()
    {
        // A script bound on 'DOMContentLoaded' never runs again after an in
        // app navigation, since the document is already loaded when the
        // swapped body re-executes it.

        $html = $this->renderPage();

        // The only binding left is the one inside the helper itself, which is
        // what every other script goes through now.

        $this->assertEquals(
            1,
            substr_count($html, "addEventListener('DOMContentLoaded'")
        );
    }

    public function testTheHelpersPrecedeTheScriptStack()
    {
        View::startPush('js');
        echo '<script>window._AdminLTE_Ready(() => {});</script>';
        View::stopPush();

        $html = View::make('adminlte::page')->render();

        $this->assertLessThan(
            strpos($html, 'window._AdminLTE_Ready(() => {});'),
            strpos($html, 'window._AdminLTE_Ready =')
        );

        View::flushSections();
    }

    public function testTheLivewireBridgeIsEmittedByDefault()
    {
        $html = $this->renderPage();

        $this->assertStringContainsString("'livewire:navigated'", $html);
        $this->assertStringContainsString('adminlte.initialize()', $html);
    }

    public function testTheLivewireBridgeCanBeDisabled()
    {
        config(['adminlte.spa_navigation' => false]);

        $html = $this->renderPage();

        $this->assertStringNotContainsString("'livewire:navigated'", $html);

        // The helpers themselves stay, they are what the scripts call.

        $this->assertStringContainsString('window._AdminLTE_Ready =', $html);
    }

    public function testTheDelegatedListenersAreGuardedAgainstReRegistration()
    {
        // These live on the document, so they survive a body swap and would
        // pile up on every navigation.

        $html = Blade::render(
            '<x-adminlte-toast id="t">B</x-adminlte-toast>'.
            "\n@stack('js')"
        );

        $this->assertStringContainsString("_AdminLTE_Once('toast-trigger'", $html);
    }
}

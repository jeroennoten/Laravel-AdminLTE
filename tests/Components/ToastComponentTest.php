<?php

require_once __DIR__.'/ComponentTestHelpers.php';

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Lang;
use JeroenNoten\LaravelAdminLte\View\Components;

class ToastComponentTest extends TestCase
{
    use ComponentTestHelpers;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Blade::component(
            Components\Widget\Toast::class,
            'adminlte-toast'
        );
    }

    /**
     * Return the set of supported screen positions with the Bootstrap
     * utilities that are expected on the shared container.
     *
     * @return array
     */
    protected function getPositions()
    {
        return [
            'top-start' => 'top-0 start-0',
            'top-center' => 'top-0 start-50 translate-middle-x',
            'top-end' => 'top-0 end-0',
            'middle-start' => 'top-50 start-0 translate-middle-y',
            'middle-center' => 'top-50 start-50 translate-middle',
            'middle-end' => 'top-50 end-0 translate-middle-y',
            'bottom-start' => 'bottom-0 start-0',
            'bottom-center' => 'bottom-0 start-50 translate-middle-x',
            'bottom-end' => 'bottom-0 end-0',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | General component tests.
    |--------------------------------------------------------------------------
    */

    public function testComponentRenders()
    {
        $component = new Components\Widget\Toast();
        $view = $component->render();

        $this->assertEquals(
            'adminlte::components.widget.toast',
            $view->getName()
        );
    }

    public function testComponentRendersWithEveryAttribute()
    {
        $html = $this->renderComponent(
            '<x-adminlte-toast id="saved" theme="success" title="Saved"
                icon="bi bi-check-circle" time="just now" position="bottom-end"
                autohide :delay="5000">The record was saved.</x-adminlte-toast>'
        );

        $this->assertNotEmpty(trim($html));
        $this->assertV4Markup($html);
        $this->assertFreeOfJquery($html);

        $this->assertV4Markup($this->renderPushedAssets());
        $this->assertFreeOfJquery($this->renderPushedAssets());
    }

    public function testComponentRendersWithoutAnyAttribute()
    {
        $html = $this->renderComponent('<x-adminlte-toast/>');

        $this->assertNotEmpty(trim($html));
        $this->assertV4Markup($html);

        // The default position is used and no header is rendered.

        $this->assertStringContainsString('bottom-0 end-0', $html);
        $this->assertStringNotContainsString('toast-header', $html);
        $this->assertStringNotContainsString('data-bs-autohide', $html);
        $this->assertStringNotContainsString('data-bs-delay', $html);

        // Only the shared container carries an id attribute.

        $this->assertEquals(1, substr_count($html, 'id="'));
    }

    public function testRendersTheReferenceToastMarkup()
    {
        $html = $this->renderComponent(
            '<x-adminlte-toast id="t1" title="Bootstrap" icon="bi bi-circle"
                time="11 mins ago">Hello, world!</x-adminlte-toast>'
        );

        $this->assertStringContainsString('role="alert"', $html);
        $this->assertStringContainsString('aria-live="assertive"', $html);
        $this->assertStringContainsString('aria-atomic="true"', $html);
        $this->assertStringContainsString('<div class="toast-header">', $html);
        $this->assertStringContainsString(
            '<i class="bi bi-circle me-2" aria-hidden="true"></i>',
            $html
        );
        $this->assertStringContainsString(
            '<strong class="me-auto">Bootstrap</strong>',
            $html
        );
        $this->assertStringContainsString('<small>11 mins ago</small>', $html);
        $this->assertStringContainsString(
            '<div class="toast-body">Hello, world!</div>',
            $html
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Shared container tests.
    |--------------------------------------------------------------------------
    */

    public function testContainerIdAndClass()
    {
        // $id, $theme, $title, $icon, $time, $position

        foreach ($this->getPositions() as $position => $utilities) {
            $component = new Components\Widget\Toast(
                null, null, null, null, null, $position
            );

            $this->assertEquals($position, $component->position);

            $this->assertEquals(
                "adminlte-toast-container-{$position}",
                $component->makeContainerId()
            );

            $this->assertEquals(
                "toast-container position-fixed {$utilities} p-3",
                $component->makeContainerClass()
            );
        }
    }

    public function testUnknownPositionFallsBackToTheDefaultOne()
    {
        foreach ([null, '', 'nowhere', 66] as $position) {
            $component = new Components\Widget\Toast(
                null, null, null, null, null, $position
            );

            $this->assertEquals('bottom-end', $component->position);

            $this->assertEquals(
                'toast-container position-fixed bottom-0 end-0 p-3',
                $component->makeContainerClass()
            );
        }
    }

    public function testEveryPositionRendersItsContainer()
    {
        foreach ($this->getPositions() as $position => $utilities) {
            $html = $this->renderComponent(
                "<x-adminlte-toast position=\"{$position}\"/>"
            );

            $this->assertStringContainsString(
                "<div id=\"adminlte-toast-container-{$position}\" ".
                "class=\"toast-container position-fixed {$utilities} p-3\"></div>",
                $html
            );
        }
    }

    public function testContainerIsSharedByTheToastsOfTheSamePosition()
    {
        $html = $this->renderComponent(
            '<x-adminlte-toast id="t1" position="top-end">A</x-adminlte-toast>
             <x-adminlte-toast id="t2" position="top-end">B</x-adminlte-toast>'
        );

        $this->assertEquals(1, substr_count($html, 'class="toast-container'));
        $this->assertEquals(1, substr_count($html, 'id="adminlte-toast-container-top-end"'));
        $this->assertEquals(2, substr_count($html, 'data-adminlte-toast-container'));
        $this->assertEquals(2, substr_count($html, 'class="toast align-items-center"'));
    }

    public function testEachPositionGetsItsOwnContainer()
    {
        $html = $this->renderComponent(
            '<x-adminlte-toast id="t1" position="top-end">A</x-adminlte-toast>
             <x-adminlte-toast id="t2" position="bottom-start">B</x-adminlte-toast>'
        );

        $this->assertStringContainsString('id="adminlte-toast-container-top-end"', $html);
        $this->assertStringContainsString('id="adminlte-toast-container-bottom-start"', $html);
        $this->assertEquals(2, substr_count($html, 'class="toast-container'));
    }

    public function testToastPointsToItsContainer()
    {
        $html = $this->renderComponent(
            '<x-adminlte-toast id="t1" position="middle-center">A</x-adminlte-toast>'
        );

        $this->assertStringContainsString(
            'data-adminlte-toast-container="adminlte-toast-container-middle-center"',
            $html
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Theme tests.
    |--------------------------------------------------------------------------
    */

    public function testToastClassWithoutTheme()
    {
        $component = new Components\Widget\Toast(null, null, 'Title');

        $this->assertEquals('toast', $component->makeToastClass());
    }

    public function testToastClassWithTheme()
    {
        // The AdminLTE v4 stylesheet provides a '.toast-{color}' variant for
        // every Bootstrap theme color.

        $themes = [
            'primary', 'secondary', 'success', 'danger', 'warning', 'info',
            'light', 'dark',
        ];

        foreach ($themes as $theme) {
            $component = new Components\Widget\Toast(null, $theme, 'Title');

            $this->assertEquals("toast toast-{$theme}", $component->makeToastClass());
        }
    }

    public function testToastClassResolvesTheLegacyThemeColors()
    {
        config(['adminlte.assets.extended_colors_v3_aliases' => false]);

        $component = new Components\Widget\Toast(null, 'lightblue', 'Title');
        $this->assertEquals('toast toast-sky', $component->makeToastClass());

        $component = new Components\Widget\Toast(null, 'green', 'Title');
        $this->assertEquals('toast toast-success', $component->makeToastClass());

        // The legacy names are kept when the v3 alias stylesheet is in use.

        config(['adminlte.assets.extended_colors_v3_aliases' => true]);

        $component = new Components\Widget\Toast(null, 'lightblue', 'Title');
        $this->assertEquals('toast toast-lightblue', $component->makeToastClass());
    }

    public function testRendersTheThemeClass()
    {
        $html = $this->renderComponent(
            '<x-adminlte-toast id="t1" theme="success" title="Saved">Ok</x-adminlte-toast>'
        );

        $this->assertStringContainsString('class="toast toast-success"', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Header tests.
    |--------------------------------------------------------------------------
    */

    public function testHasHeader()
    {
        // $id, $theme, $title, $icon, $time

        $this->assertFalse((new Components\Widget\Toast())->hasHeader());
        $this->assertTrue((new Components\Widget\Toast(null, null, 'T'))->hasHeader());
        $this->assertTrue((new Components\Widget\Toast(null, null, null, 'i'))->hasHeader());
        $this->assertTrue((new Components\Widget\Toast(null, null, null, null, 'now'))->hasHeader());
    }

    public function testHeaderlessToastIsStillDismissable()
    {
        $html = $this->renderComponent('<x-adminlte-toast id="t1">Body</x-adminlte-toast>');

        // The Bootstrap v5 markup for the headerless toasts.

        $this->assertStringContainsString('class="toast align-items-center"', $html);
        $this->assertStringContainsString('<div class="d-flex">', $html);
        $this->assertStringContainsString('<div class="toast-body">Body</div>', $html);
        $this->assertStringContainsString('class="btn-close me-2 m-auto"', $html);
        $this->assertStringContainsString('data-bs-dismiss="toast"', $html);
        $this->assertStringNotContainsString('toast-header', $html);
    }

    public function testHeaderWithoutTitlePushesTheTimeToTheStart()
    {
        $html = $this->renderComponent(
            '<x-adminlte-toast id="t1" time="just now">Body</x-adminlte-toast>'
        );

        $this->assertStringContainsString('<small class="me-auto">just now</small>', $html);
        $this->assertStringNotContainsString('<strong', $html);
    }

    public function testCloseButtonUsesTheTranslationKey()
    {
        $html = $this->renderComponent(
            '<x-adminlte-toast id="t1" title="Saved">Body</x-adminlte-toast>'
        );

        $this->assertStringContainsString(
            'aria-label="'.__('adminlte::adminlte.close').'"',
            $html
        );

        // The label is not hardcoded, it follows the active locale.

        Lang::addLines(['adminlte.close' => 'Cerrar'], 'xx', 'adminlte');
        $this->app->setLocale('xx');

        $html = $this->renderComponent(
            '<x-adminlte-toast id="t1" title="Saved">Body</x-adminlte-toast>'
        );

        $this->assertStringContainsString('aria-label="Cerrar"', $html);
        $this->assertStringNotContainsString('aria-label="Close"', $html);

        // The headerless toast reuses the very same key.

        $html = $this->renderComponent('<x-adminlte-toast id="t2">Body</x-adminlte-toast>');

        $this->assertStringContainsString('aria-label="Cerrar"', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Autohide and delay tests.
    |--------------------------------------------------------------------------
    */

    public function testAutohideAndDelayAreNormalized()
    {
        // $id, $theme, $title, $icon, $time, $position, $autohide, $delay

        $component = new Components\Widget\Toast();
        $this->assertNull($component->autohide);
        $this->assertNull($component->delay);

        $args = [null, null, null, null, null, null];

        $component = new Components\Widget\Toast(...$args, ...[true, '5000']);
        $this->assertTrue($component->autohide);
        $this->assertEquals(5000, $component->delay);

        $component = new Components\Widget\Toast(...$args, ...[false, 0]);
        $this->assertFalse($component->autohide);
        $this->assertEquals(0, $component->delay);

        $component = new Components\Widget\Toast(...$args, ...['false', null]);
        $this->assertFalse($component->autohide);
    }

    public function testRendersTheAutohideAndDelayAttributes()
    {
        $html = $this->renderComponent(
            '<x-adminlte-toast id="t1" autohide :delay="8000">Body</x-adminlte-toast>'
        );

        $this->assertStringContainsString('data-bs-autohide="true"', $html);
        $this->assertStringContainsString('data-bs-delay="8000"', $html);

        $html = $this->renderComponent(
            '<x-adminlte-toast id="t1" :autohide="false">Body</x-adminlte-toast>'
        );

        $this->assertStringContainsString('data-bs-autohide="false"', $html);
        $this->assertStringNotContainsString('data-bs-delay', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Attributes and escaping tests.
    |--------------------------------------------------------------------------
    */

    public function testTitleAndTimeApplyTheHtmlEntityDecoder()
    {
        $component = new Components\Widget\Toast(
            null, null, 'R&amp;D', null, '5&nbsp;min'
        );

        $this->assertEquals('R&D', $component->title);
        $this->assertEquals(html_entity_decode('5&nbsp;min'), $component->time);
    }

    public function testContentIsEscapedOnTheMarkup()
    {
        $html = $this->renderComponent(
            '<x-adminlte-toast id="t1" title="&lt;b&gt;T&lt;/b&gt;"
                time="&lt;i&gt;now&lt;/i&gt;">&lt;script&gt;x&lt;/script&gt;</x-adminlte-toast>'
        );

        $this->assertStringNotContainsString('<b>T</b>', $html);
        $this->assertStringNotContainsString('<i>now</i>', $html);
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('&lt;b&gt;T&lt;/b&gt;', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
    }

    public function testExtraAttributesAreForwardedToTheToast()
    {
        $html = $this->renderComponent(
            '<x-adminlte-toast id="t1" theme="info" title="T" class="shadow-lg"
                data-cy="toast">Body</x-adminlte-toast>'
        );

        $this->assertStringContainsString('id="t1"', $html);
        $this->assertStringContainsString('data-cy="toast"', $html);

        // The extra classes are merged with the component ones.

        $this->assertMatchesRegularExpression('/class="[^"]*\btoast\b/', $html);
        $this->assertMatchesRegularExpression('/class="[^"]*\btoast-info\b/', $html);
        $this->assertMatchesRegularExpression('/class="[^"]*\bshadow-lg\b/', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Javascript tests.
    |--------------------------------------------------------------------------
    */

    public function testRegistersTheJavascriptHelperOnlyOnce()
    {
        $this->renderComponent(
            '<x-adminlte-toast id="t1">A</x-adminlte-toast>
             <x-adminlte-toast id="t2" position="top-end">B</x-adminlte-toast>'
        );

        $js = $this->renderPushedAssets();

        $this->assertEquals(1, substr_count($js, 'class _AdminLTE_Toast'));
    }

    public function testJavascriptHelperWiresTheToasts()
    {
        $this->renderComponent('<x-adminlte-toast id="t1">A</x-adminlte-toast>');

        $js = $this->renderPushedAssets();

        // The toasts are moved into the shared container of their position.

        $this->assertStringContainsString('data-adminlte-toast-container', $js);
        $this->assertStringContainsString('appendChild', $js);

        // Bootstrap provides no declarative trigger, so a delegated listener
        // wires the 'data-bs-toggle="toast"' controls.

        $this->assertStringContainsString('bootstrap.Toast.getOrCreateInstance', $js);
        $this->assertStringContainsString("closest('[data-bs-toggle=\"toast\"]')", $js);
        $this->assertStringContainsString("addEventListener('click'", $js);

        // The plain text fields are never written through innerHTML.

        $this->assertStringContainsString('textContent', $js);
        $this->assertStringNotContainsString('innerHTML', $js);

        // AdminLTE v4 dropped the jQuery dependency.

        $this->assertFreeOfJquery($js);
        $this->assertV4Markup($js);
    }

    public function testTheTriggerTargetAcceptsTheBootstrapSelectorConvention()
    {
        // The 'data-bs-target' attribute carries a leading '#', so the helper
        // has to strip it before looking the toast up by id.

        $this->renderComponent(
            '<x-adminlte-toast id="my-toast">Body</x-adminlte-toast>'
        );

        $js = $this->renderPushedAssets();

        $this->assertStringContainsString("replace(/^#/, '')", $js);
    }
}

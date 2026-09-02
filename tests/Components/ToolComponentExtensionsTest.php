<?php

use JeroenNoten\LaravelAdminLte\View\Components;

class ToolComponentExtensionsTest extends TestCase
{
    use ComponentTestHelpers;

    public function testModalRendersTheFooterByDefault()
    {
        $html = $this->renderComponent(
            '<x-adminlte-modal id="m" title="T">Body</x-adminlte-modal>'
        );

        $this->assertStringContainsString('modal-footer', $html);
    }

    public function testModalFooterCanBeDisabled()
    {
        $html = $this->renderComponent(
            '<x-adminlte-modal id="m" title="T" disable-footer>Body</x-adminlte-modal>'
        );

        $this->assertStringNotContainsString('modal-footer', $html);
        $this->assertStringContainsString('modal-body', $html);
    }

    public function testModalDialogClassReachesTheDialogElement()
    {
        $html = $this->renderComponent(
            '<x-adminlte-modal id="m" title="T" dialog-class="my-dlg">Body</x-adminlte-modal>'
        );

        $this->assertMatchesRegularExpression(
            '/class="[^"]*modal-dialog[^"]*my-dlg/',
            $html
        );
    }

    public function testModalSupportsTheFullscreenSizes()
    {
        foreach (['fullscreen', 'fullscreen-md-down'] as $size) {
            $html = $this->renderComponent(
                '<x-adminlte-modal id="m" size="'.$size.'">B</x-adminlte-modal>'
            );

            $this->assertStringContainsString("modal-{$size}", $html);
        }
    }

    public function testModalRejectsAnUnknownSize()
    {
        $html = $this->renderComponent(
            '<x-adminlte-modal id="m" size="huge">B</x-adminlte-modal>'
        );

        $this->assertStringNotContainsString('modal-huge', $html);
    }

    public function testModalLabelledByIsOmittedWithoutATitle()
    {
        // A dangling 'aria-labelledby' points at an empty element, which is
        // worse than declaring no accessible name at all.

        $html = $this->renderComponent(
            '<x-adminlte-modal id="m">Body</x-adminlte-modal>'
        );

        $this->assertStringNotContainsString('aria-labelledby', $html);

        $html = $this->renderComponent(
            '<x-adminlte-modal id="m" title="T">Body</x-adminlte-modal>'
        );

        $this->assertStringContainsString('aria-labelledby="m-title"', $html);
    }

    public function testModalHeaderColorModeFollowsThePaletteContrast()
    {
        // A dark background needs the dark color mode, otherwise the close
        // button of the header stays invisible.

        $component = new Components\Tool\Modal('m', null, null, null, 'primary');
        $this->assertStringContainsString(
            'data-bs-theme="dark"',
            $component->makeModalHeaderData()
        );

        $component = new Components\Tool\Modal('m', null, null, null, 'warning');
        $this->assertEquals('', $component->makeModalHeaderData());
    }

    public function testModalHeaderColorModeWorksWithTheV3Aliases()
    {
        config([
            'adminlte.assets.extended_colors' => true,
            'adminlte.assets.extended_colors_v3_aliases' => true,
        ]);

        // The v3 names are real colors here, so they have to be resolved too.

        $component = new Components\Tool\Modal('m', null, null, null, 'maroon');
        $this->assertStringContainsString(
            'data-bs-theme="dark"',
            $component->makeModalHeaderData()
        );

        $component = new Components\Tool\Modal('m', null, null, null, 'yellow');
        $this->assertEquals('', $component->makeModalHeaderData());
    }

    public function testDatatableKeepsTheDefaultTableWidth()
    {
        $html = $this->renderComponent(
            '<x-adminlte-datatable id="t" :heads="[\'A\']"/>'
        );

        $this->assertStringContainsString('width:100%', $html);
    }

    public function testDatatableUserStyleWinsOverTheDefault()
    {
        // The literal attribute used to be emitted first, and the duplicate
        // attribute rules of HTML dropped the user value silently.

        $html = $this->renderComponent(
            '<x-adminlte-datatable id="t" :heads="[\'A\']" style="table-layout:fixed"/>'
        );

        $this->assertStringContainsString('table-layout:fixed', $html);
    }

    public function testDatatableWrapperClassIsConfigurable()
    {
        $html = $this->renderComponent(
            '<x-adminlte-datatable id="t" :heads="[\'A\']" wrapper-class="my-wrap"/>'
        );

        $this->assertMatchesRegularExpression(
            '/<div class="[^"]*table-responsive[^"]*my-wrap/',
            $html
        );
    }

    public function testDatatableWrapperAcceptsExtraAttributes()
    {
        // The scroll container is the element a Livewire app has to exclude
        // from its re-renders.

        $html = $this->renderComponent(
            '<x-adminlte-datatable id="t" :heads="[\'A\']"
                :wrapper-attributes="[\'wire:ignore\' => \'\', \'data-x\' => \'y\']"/>'
        );

        $this->assertStringContainsString('wire:ignore', $html);
        $this->assertStringContainsString('data-x="y"', $html);
    }

    public function testDatatableButtonsUseTheModernLayoutOption()
    {
        // The 'dom' option is the deprecated Datatables 1.x API, the pinned
        // 2.x release configures the table through 'layout'.

        $component = new Components\Tool\Datatable('t', ['A'], null, null,
            null, null, null, null, null, null, null, true);

        $this->assertArrayHasKey('layout', $component->config);
        $this->assertArrayNotHasKey('dom', $component->config);
        $this->assertEquals('buttons', $component->config['layout']['topStart']);
        $this->assertEquals('search', $component->config['layout']['topEnd']);
    }

    public function testDatatableHonorsAnExplicitLegacyDom()
    {
        $component = new Components\Tool\Datatable('t', ['A'], null, null,
            null, null, null, null, null, null, null, true, ['dom' => 'Bfrtip']);

        $this->assertEquals('Bfrtip', $component->config['dom']);
        $this->assertArrayNotHasKey('layout', $component->config);
    }

    public function testDatatableHonorsAnExplicitLayout()
    {
        $layout = ['topStart' => 'info'];

        $component = new Components\Tool\Datatable('t', ['A'], null, null,
            null, null, null, null, null, null, null, true, ['layout' => $layout]);

        $this->assertEquals($layout, $component->config['layout']);
    }

    public function testDatatableWithoutButtonsConfiguresNoLayout()
    {
        $component = new Components\Tool\Datatable('t', ['A']);

        $this->assertArrayNotHasKey('layout', $component->config);
        $this->assertArrayNotHasKey('dom', $component->config);
    }

    public function testDatatableExportTooltipsAreTranslated()
    {
        app()->setLocale('de');

        $component = new Components\Tool\Datatable('t', ['A'], null, null,
            null, null, null, null, null, null, null, true);

        $titles = array_column($component->config['buttons']['buttons'], 'titleAttr');

        $this->assertContains(__('adminlte::adminlte.datatable_print'), $titles);
        $this->assertNotContains('Print', $titles);

        app()->setLocale('en');
    }
}

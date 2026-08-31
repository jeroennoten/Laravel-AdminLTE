<?php

require_once __DIR__.'/ComponentTestHelpers.php';

use JeroenNoten\LaravelAdminLte\View\Components;

class ToolComponentsTest extends TestCase
{
    use ComponentTestHelpers;

    /**
     * Return array with the available blade components.
     */
    protected function getComponents()
    {
        $base = 'adminlte::components.tool';

        return [
            "{$base}.datatable" => new Components\Tool\Datatable('id', []),
            "{$base}.modal" => new Components\Tool\Modal('id'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | General components tests.
    |--------------------------------------------------------------------------
    */

    public function testAllComponentsRender()
    {
        foreach ($this->getComponents() as $viewName => $component) {
            $view = $component->render();
            $this->assertEquals($view->getName(), $viewName);
        }
    }

    public function testAllComponentsRenderFreeOfLegacyMarkup()
    {
        $templates = [
            '<x-adminlte-datatable id="t" :heads="[\'h1\', \'h2\']" theme="primary"
                head-theme="dark" footer-theme="light" bordered hoverable striped
                compressed with-footer beautify with-buttons/>',
            '<x-adminlte-modal id="m" title="t" icon="bi bi-bell" size="lg"
                theme="primary" v-centered scrollable static-backdrop
                disable-animations>body</x-adminlte-modal>',
        ];

        foreach ($templates as $template) {
            $this->assertV4Markup($this->renderComponent($template));
        }

        // Only the datatable component may reference jQuery, and only behind
        // a feature guard.

        $this->assertV4Markup($this->renderPushedAssets());
    }

    /*
    |--------------------------------------------------------------------------
    | Datatable component tests.
    |--------------------------------------------------------------------------
    */

    public function testDatatableComponent()
    {
        // Test basic component.

        $component = new Components\Tool\Datatable('id', []);

        $tClass = $component->makeTableClass();
        $this->assertStringContainsString('table', $tClass);
    }

    public function testDatatableComponentWithAdvancedOptions()
    {
        // Test advanced component.
        // $id, $heads, $theme, $headTheme, $bordered, $hoverable, $striped,
        // $compressed, $withFooter, $footerTheme, $beautify, $withButtons,
        // $config

        $component = new Components\Tool\Datatable(
            'id', [], 'primary', null, true, true, true, true, null, null,
            null, true, null
        );

        $tClass = $component->makeTableClass();
        $this->assertStringContainsString('table-bordered', $tClass);
        $this->assertStringContainsString('table-hover', $tClass);
        $this->assertStringContainsString('table-striped', $tClass);
        $this->assertStringContainsString('table-sm', $tClass);
        $this->assertStringContainsString('table-primary', $tClass);

        $this->assertContains(
            ['extend' => 'pageLength', 'className' => 'btn-secondary'],
            $component->config['buttons']['buttons']
        );

        // Test advanced component with length change button disabled.
        // $id, $heads, $theme, $headTheme, $bordered, $hoverable, $striped,
        // $compressed, $withFooter, $footerTheme, $beautify, $withButtons,
        // $config

        $component = new Components\Tool\Datatable(
            'id', [], 'primary', null, true, true, true, true, null, null,
            null, true, ['lengthChange' => false]
        );

        $this->assertNotContains(
            ['extend' => 'pageLength', 'className' => 'btn-secondary'],
            $component->config['buttons']['buttons']
        );
    }

    public function testDatatableComponentRendersTheTableStructure()
    {
        $html = $this->renderComponent(
            '<x-adminlte-datatable id="tbl" :heads="[\'Name\', \'Email\']">'.
            '<tr><td>a</td><td>b</td></tr>'.
            '</x-adminlte-datatable>'
        );

        // The AdminLTE v4 tables are wrapped on a responsive container.

        $this->assertStringContainsString('<div class="table-responsive">', $html);
        $this->assertStringContainsString('<table id="tbl"', $html);
        $this->assertStringContainsString('class="table"', $html);
        $this->assertStringContainsString('<thead', $html);
        $this->assertStringContainsString('Name', $html);
        $this->assertStringContainsString('Email', $html);
        $this->assertStringContainsString('<tbody><tr><td>a</td><td>b</td></tr></tbody>', $html);

        // Without the 'with-footer' attribute there is no table footer.

        $this->assertStringNotContainsString('<tfoot', $html);

        $this->assertV4Markup($html);
    }

    public function testDatatableComponentRendersEveryAttribute()
    {
        // Test all the constructor arguments at once:
        // $id, $heads, $theme, $headTheme, $bordered, $hoverable, $striped,
        // $compressed, $withFooter, $footerTheme, $beautify, $withButtons,
        // $config.

        $html = $this->renderComponent(
            '<x-adminlte-datatable id="tbl" :heads="[\'Name\']" theme="primary"
                head-theme="dark" footer-theme="light" bordered hoverable
                striped compressed with-footer beautify with-buttons
                :config="[\'paging\' => false]"/>'
        );

        $this->assertStringContainsString('table-bordered', $html);
        $this->assertStringContainsString('table-hover', $html);
        $this->assertStringContainsString('table-striped', $html);
        $this->assertStringContainsString('table-sm', $html);
        $this->assertStringContainsString('table-primary', $html);
        $this->assertStringContainsString('class="table-dark"', $html);
        $this->assertStringContainsString('class="table-light"', $html);

        // The 'beautify' attribute pushes a style block for the table.

        $assets = $this->renderPushedAssets();

        $this->assertStringContainsString('#tbl tr td', $assets);
        $this->assertStringContainsString('vertical-align: middle', $assets);

        // The plugin configuration reaches the initialization script.

        $this->assertStringContainsString('"paging":false', $assets);

        $this->assertV4Markup($html);
    }

    public function testDatatableComponentRendersTheHeadsProperties()
    {
        // Each head can be a string or an array with the 'label', 'width',
        // 'classes' and 'no-export' properties.

        $heads = "[['label' => 'Name', 'width' => 40, 'classes' => 'text-center'], ".
                 "['label' => 'Secret', 'no-export' => true], 'Plain']";

        $html = $this->renderComponent(
            '<x-adminlte-datatable id="tbl" :heads="'.$heads.'" with-footer/>'
        );

        $this->assertStringContainsString('class="text-center"', $html);
        $this->assertStringContainsString('style="width:40%"', $html);
        $this->assertStringContainsString('dt-no-export', $html);
        $this->assertStringContainsString('Plain', $html);

        // The footer repeats the labels of the headers.

        $this->assertEquals(2, substr_count($html, 'Plain'));
    }

    public function testDatatableComponentPluginScriptIsGuarded()
    {
        $this->renderComponent('<x-adminlte-datatable id="tbl" :heads="[\'a\']"/>');

        $js = $this->renderPushedAssets();

        // The Datatables plugin is one of the few that still requires jQuery,
        // so the initialization must not throw when it is not available.

        $this->assertGuardedPluginUsage(
            $js,
            "typeof window.jQuery === 'undefined'",
            "window.jQuery('#tbl').DataTable"
        );

        $this->assertStringContainsString(
            "typeof window.jQuery.fn.DataTable === 'undefined'",
            $js
        );
    }

    public function testDatatableComponentPushesTheResponsiveExtensionStyle()
    {
        $this->renderComponent(
            '<x-adminlte-datatable id="tbl" :heads="[\'a\']" :config="[\'responsive\' => true]"/>'
        );

        $css = $this->renderPushedAssets();

        $this->assertStringContainsString('.dataTable .child .dtr-details', $css);

        // Note the AdminLTE v4 layouts are logical property based.

        $this->assertStringContainsString('float: inline-end', $css);
    }

    /*
    |--------------------------------------------------------------------------
    | Modal component tests.
    |--------------------------------------------------------------------------
    */

    public function testModalComponent()
    {
        // Test basic component.

        $component = new Components\Tool\Modal('id');

        $mClass = $component->makeModalClass();
        $this->assertStringContainsString('modal', $mClass);
        $this->assertStringContainsString('fade', $mClass);

        $mdClass = $component->makeModalDialogClass();
        $this->assertStringContainsString('modal-dialog', $mdClass);

        $mhClass = $component->makeModalHeaderClass();
        $this->assertStringContainsString('modal-header', $mhClass);

        $cbClass = $component->makeCloseButtonClass();
        $this->assertEquals('btn btn-secondary', $cbClass);
    }

    public function testModalComponentWithAdvancedOptions()
    {
        // Test with all constructor arguments:
        // $id, $title, $icon, $size, $theme, $vCentered, $scrollable,
        // $staticBackdrop, $disableAnimations.

        $component = new Components\Tool\Modal(
            'id', 'title', null, 'lg', 'info', true, true, true, true
        );

        $mClass = $component->makeModalClass();
        $this->assertStringNotContainsString('fade', $mClass);

        $mdClass = $component->makeModalDialogClass();
        $this->assertStringContainsString('modal-dialog-centered', $mdClass);
        $this->assertStringContainsString('modal-dialog-scrollable', $mdClass);
        $this->assertStringContainsString('modal-lg', $mdClass);

        $mhClass = $component->makeModalHeaderClass();
        $this->assertStringContainsString('text-bg-info', $mhClass);

        // The dismiss button of the modal footer is always neutral, the theme
        // colors are reserved for the affirmative actions.

        $cbClass = $component->makeCloseButtonClass();
        $this->assertEquals('btn btn-secondary', $cbClass);
    }

    public function testModalComponentRendersTheBootstrap5Structure()
    {
        $html = $this->renderComponent(
            '<x-adminlte-modal id="mdl" title="My title">The body</x-adminlte-modal>'
        );

        $this->assertStringContainsString('class="modal fade"', $html);
        $this->assertStringContainsString('id="mdl"', $html);
        $this->assertStringContainsString('tabindex="-1"', $html);
        $this->assertStringContainsString('aria-labelledby="mdl-title"', $html);
        $this->assertStringContainsString('class="modal-dialog"', $html);
        $this->assertStringContainsString('class="modal-content"', $html);
        $this->assertStringContainsString('class="modal-header"', $html);
        $this->assertStringContainsString('class="modal-title fs-5" id="mdl-title"', $html);
        $this->assertStringContainsString('<div class="modal-body">The body</div>', $html);
        $this->assertStringContainsString('class="modal-footer"', $html);

        // The header close button uses the Bootstrap 5 markup.

        $this->assertStringContainsString(
            '<button type="button" class="btn-close" data-bs-dismiss="modal"',
            $html
        );

        $this->assertV4Markup($html);
    }

    public function testModalComponentRendersEveryAttribute()
    {
        // Test all the constructor arguments at once:
        // $id, $title, $icon, $size, $theme, $vCentered, $scrollable,
        // $staticBackdrop, $disableAnimations.

        $html = $this->renderComponent(
            '<x-adminlte-modal id="mdl" title="My title" icon="bi bi-bell"
                size="xl" theme="primary" v-centered scrollable static-backdrop
                disable-animations/>'
        );

        $this->assertStringContainsString('class="modal"', $html);
        $this->assertStringNotContainsString('fade', $html);
        $this->assertStringContainsString('modal-dialog-centered', $html);
        $this->assertStringContainsString('modal-dialog-scrollable', $html);
        $this->assertStringContainsString('modal-xl', $html);
        $this->assertStringContainsString('modal-header text-bg-primary', $html);
        $this->assertStringContainsString('<i class="bi bi-bell me-2"></i>', $html);

        // The static backdrop uses the Bootstrap 5 data attributes.

        $this->assertStringContainsString('data-bs-backdrop="static"', $html);
        $this->assertStringContainsString('data-bs-keyboard="false"', $html);

        // The body is only rendered when the slot has content.

        $this->assertStringNotContainsString('modal-body', $html);

        $this->assertV4Markup($html);
    }

    public function testModalComponentDeclaresTheColorModeOfDarkHeaders()
    {
        // Bootstrap 5.3 resolves the close button color from the active color
        // mode, so a dark themed header must declare the dark color mode.

        $html = $this->renderComponent('<x-adminlte-modal id="mdl" theme="danger"/>');
        $this->assertStringContainsString('data-bs-theme="dark"', $html);

        // A light themed header keeps the color mode of the page.

        $html = $this->renderComponent('<x-adminlte-modal id="mdl" theme="warning"/>');
        $this->assertStringNotContainsString('data-bs-theme', $html);

        // The AdminLTE v3 color names are resolved before the lookup.

        $html = $this->renderComponent('<x-adminlte-modal id="mdl" theme="maroon"/>');
        $this->assertStringContainsString('text-bg-pink', $html);
        $this->assertStringContainsString('data-bs-theme="dark"', $html);
    }

    public function testModalComponentSizes()
    {
        foreach (['sm', 'lg', 'xl'] as $size) {
            $html = $this->renderComponent("<x-adminlte-modal id=\"mdl\" size=\"{$size}\"/>");

            $this->assertStringContainsString("modal-dialog modal-{$size}", $html);
        }

        // An unknown size is ignored.

        $html = $this->renderComponent('<x-adminlte-modal id="mdl" size="huge"/>');

        $this->assertStringContainsString('class="modal-dialog"', $html);
        $this->assertStringNotContainsString('modal-huge', $html);
    }

    public function testModalComponentFooterButtonIsASingleNeutralButton()
    {
        // Regression: the default dismiss button of the footer must be a plain
        // 'btn btn-secondary' (no theme color and no legacy 'btn-flat').

        $html = $this->renderComponent('<x-adminlte-modal id="mdl" theme="danger"/>');

        $this->assertEquals(
            1,
            preg_match_all('/<button[^>]*class="btn btn-secondary"/', $html)
        );

        $this->assertStringNotContainsString('btn-danger', $html);
        $this->assertStringNotContainsString('btn-flat', $html);

        // The footer button is replaced when the footer slot is provided.

        $html = $this->renderComponent(
            '<x-adminlte-modal id="mdl">'.
            '<x-slot name="footerSlot"><button id="ok">Ok</button></x-slot>'.
            '</x-adminlte-modal>'
        );

        $this->assertStringContainsString('<button id="ok">Ok</button>', $html);
        $this->assertStringNotContainsString('btn btn-secondary', $html);
    }
}

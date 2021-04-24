<?php

use JeroenNoten\LaravelAdminLte\Components;

class ComponentsTest extends TestCase
{
    /**
     * Get package providers.
     */
    protected function getPackageProviders($app)
    {
        // Register our service provider into the Laravel's application.

        return ['JeroenNoten\LaravelAdminLte\AdminLteServiceProvider'];
    }

    /**
     * Return array with the available blade components.
     */
    protected function getComponents()
    {
        $base = 'adminlte::components';

        return [

            // Base components.

            "{$base}.input-group-component"  => new Components\InputGroupComponent('name'),

            // Form components.

            "{$base}.button"       => new Components\Button(),
            "{$base}.date-range"   => new Components\DateRange('name'),
            "{$base}.input"        => new Components\Input('name'),
            "{$base}.input-color"  => new Components\InputColor('name'),
            "{$base}.input-date"   => new Components\InputDate('name'),
            "{$base}.input-file"   => new Components\InputFile('name'),
            "{$base}.input-slider" => new Components\InputSlider('name'),
            "{$base}.input-switch" => new Components\InputSwitch('name'),
            "{$base}.select"       => new Components\Select('name'),
            "{$base}.select2"      => new Components\Select2('name'),
            "{$base}.select-bs"    => new Components\SelectBs('name'),
            "{$base}.textarea"     => new Components\Textarea('name'),
            "{$base}.text-editor"  => new Components\TextEditor('name'),

            // Tool components.

            "{$base}.datatable"    => new Components\Datatable('id', []),
            "{$base}.modal"        => new Components\Modal('id'),

            // Widget components.

            "{$base}.alert"            => new Components\Alert(),
            "{$base}.callout"          => new Components\Callout(),
            "{$base}.card"             => new Components\Card(),
            "{$base}.info-box"         => new Components\InfoBox(),
            "{$base}.profile-col-item" => new Components\ProfileColItem(),
            "{$base}.profile-row-item" => new Components\ProfileRowItem(),
            "{$base}.profile-widget"   => new Components\ProfileWidget(),
            "{$base}.progress"         => new Components\Progress(),
            "{$base}.small-box"        => new Components\SmallBox(),
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

    public function testInputGroupComponent()
    {
        $component = new Components\InputGroupComponent(
            'name', null, null, 'lg', null, 'fgroup-class', 'igroup-class'
        );

        $iGroupClass = $component->makeInputGroupClass(true);
        $fGroupClass = $component->makeFormGroupClass();
        $iClass = $component->makeItemClass(true);

        $this->assertStringContainsString('input-group', $iGroupClass);
        $this->assertStringContainsString('input-group-lg', $iGroupClass);
        $this->assertStringContainsString('igroup-class', $iGroupClass);
        $this->assertStringContainsString('adminlte-invalid-igroup', $iGroupClass);
        $this->assertStringContainsString('form-group', $fGroupClass);
        $this->assertStringContainsString('fgroup-class', $fGroupClass);
        $this->assertStringContainsString('form-control', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    /*
    |--------------------------------------------------------------------------
    | Individual form components tests.
    |--------------------------------------------------------------------------
    */

    public function testInputDateComponent()
    {
        $component = new Components\InputDate('name');
        $iClass = $component->makeItemClass(true);

        $this->assertStringContainsString('datetimepicker', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testInputFileComponent()
    {
        $component = new Components\InputFile('name', null, null, 'sm');
        $iClass = $component->makeItemClass(true);

        $this->assertStringContainsString('custom-file-input', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testInputSliderComponent()
    {
        $component = new Components\InputSlider(
            'name', null, null, 'lg', null, null, 'igroup-class'
        );

        $iGroupClass = $component->makeInputGroupClass(true);
        $iClass = $component->makeItemClass(true);

        $this->assertStringContainsString('input-group', $iGroupClass);
        $this->assertStringContainsString('input-group-lg', $iGroupClass);
        $this->assertStringContainsString('igroup-class', $iGroupClass);
        $this->assertStringContainsString('adminlte-invalid-islgroup', $iGroupClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testInputSwitchComponent()
    {
        $component = new Components\InputSwitch(
            'name', null, null, 'lg', null, null, 'igroup-class'
        );

        $iGroupClass = $component->makeInputGroupClass(true);
        $iClass = $component->makeItemClass(true);

        $this->assertStringContainsString('input-group', $iGroupClass);
        $this->assertStringContainsString('input-group-lg', $iGroupClass);
        $this->assertStringContainsString('igroup-class', $iGroupClass);
        $this->assertStringContainsString('adminlte-invalid-iswgroup', $iGroupClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testSelectComponent()
    {
        $component = new Components\Select('name');
        $iClass = $component->makeItemClass(true);

        $this->assertStringContainsString('form-control', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testSelect2Component()
    {
        $component = new Components\Select2('name');
        $iClass = $component->makeItemClass(true);

        $this->assertStringContainsString('form-control', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testSelectBsComponent()
    {
        $component = new Components\SelectBs('name', null, null, 'lg');
        $iClass = $component->makeItemClass(true);

        $this->assertStringContainsString('form-control', $iClass);
        $this->assertStringContainsString('form-control-lg', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testTextEditorComponent()
    {
        $component = new Components\TextEditor(
            'name', null, null, 'lg', null, null, 'igroup-class'
        );

        $iGroupClass = $component->makeInputGroupClass(true);

        $this->assertStringContainsString('input-group', $iGroupClass);
        $this->assertStringContainsString('input-group-lg', $iGroupClass);
        $this->assertStringContainsString('igroup-class', $iGroupClass);
        $this->assertStringContainsString('adminlte-invalid-itegroup', $iGroupClass);
    }

    /*
    |--------------------------------------------------------------------------
    | Individual tool components tests.
    |--------------------------------------------------------------------------
    */

    public function testDatatableComponent()
    {
        // Test basic component.
        $component = new Components\Datatable('id', []);

        $tClass = $component->makeTableClass();
        $this->assertStringContainsString('table', $tClass);

        // Test advanced component.
        // $id, $heads, $theme, $headTheme, $bordered, $hoverable, $striped,
        // $compressed, $withFooter, $footerTheme, $beautify, $withButtons,
        // $config
        $component = new Components\Datatable(
            'id', [], 'primary', null, true, true, true, true, null, null,
            null, true, null
        );

        $tClass = $component->makeTableClass();
        $this->assertStringContainsString('table-bordered', $tClass);
        $this->assertStringContainsString('table-hover', $tClass);
        $this->assertStringContainsString('table-striped', $tClass);
        $this->assertStringContainsString('table-sm', $tClass);
        $this->assertStringContainsString('table-primary', $tClass);
    }

    public function testModalComponent()
    {
        // Test basic component.

        $component = new Components\Modal('id');

        $mClass = $component->makeModalClass();
        $this->assertStringContainsString('modal', $mClass);
        $this->assertStringContainsString('fade', $mClass);

        $mdClass = $component->makeModalDialogClass();
        $this->assertStringContainsString('modal-dialog', $mdClass);

        $mhClass = $component->makeModalHeaderClass();
        $this->assertStringContainsString('modal-header', $mhClass);

        $cbClass = $component->makeCloseButtonClass();
        $this->assertStringContainsString('bg-secondary', $cbClass);

        // Test with all constructor arguments:
        // $id, $title, $icon, $size, $theme, $vCentered, $scrollable,
        // $staticBackdrop, $disableAnimations.

        $component = new Components\Modal(
            'id', 'title', null, 'lg', 'info', true, true, true, true
        );

        $mClass = $component->makeModalClass();
        $this->assertStringNotContainsString('fade', $mClass);

        $mdClass = $component->makeModalDialogClass();
        $this->assertStringContainsString('modal-dialog-centered', $mdClass);
        $this->assertStringContainsString('modal-dialog-scrollable', $mdClass);
        $this->assertStringContainsString('modal-lg', $mdClass);

        $mhClass = $component->makeModalHeaderClass();
        $this->assertStringContainsString('bg-info', $mhClass);

        $cbClass = $component->makeCloseButtonClass();
        $this->assertStringContainsString('bg-info', $cbClass);
    }

    /*
    |--------------------------------------------------------------------------
    | Individual widget components tests.
    |--------------------------------------------------------------------------
    */

    public function testAlertComponent()
    {
        // Test without theme.

        $component = new Components\Alert(null, null, null);

        $aClass = $component->makeAlertClass();

        $this->assertStringContainsString('alert', $aClass);
        $this->assertStringContainsString('border', $aClass);

        // Test with theme.

        $component = new Components\Alert('danger', null, null, true);

        $aClass = $component->makeAlertClass();

        $this->assertStringContainsString('alert', $aClass);
        $this->assertStringContainsString('alert-danger', $aClass);
        $this->assertStringContainsString('alert-dismissable', $aClass);
    }

    public function testCalloutComponent()
    {
        $component = new Components\Callout('danger');

        $cClass = $component->makeCalloutClass();

        $this->assertStringContainsString('callout', $cClass);
        $this->assertStringContainsString('callout-danger', $cClass);
    }

    public function testCardComponent()
    {
        // Test basic component.

        $component = new Components\Card('title', null, 'info');

        $cClass = $component->makeCardClass();
        $this->assertStringContainsString('card', $cClass);
        $this->assertStringContainsString('card-info', $cClass);

        $ctClass = $component->makeCardTitleClass();
        $this->assertStringContainsString('card-title', $ctClass);

        // Test collapsed with full theme:
        // $title, $icon, $theme, $themeMode, $disabled, $collapsible,
        // $removable, $maximizable.

        $component = new Components\Card(
            'title', null, 'success', 'full', null, 'collapsed'
        );

        $cClass = $component->makeCardClass();
        $this->assertStringContainsString('bg-gradient-success', $cClass);
        $this->assertStringContainsString('collapsed-card', $cClass);

        // Test outline theme:
        // $title, $icon, $theme, $themeMode, $disabled, $collapsible,
        // $removable, $maximizable.

        $component = new Components\Card('title', null, 'teal', 'outline');

        $cClass = $component->makeCardClass();
        $this->assertStringContainsString('card-teal', $cClass);
        $this->assertStringContainsString('card-outline', $cClass);

        $ctClass = $component->makeCardTitleClass();
        $this->assertStringContainsString('text-teal', $ctClass);
    }

    public function testInfoBoxComponent()
    {
        $component = new Components\InfoBox(
            'title', 'text', null, null, 'danger', 'primary'
        );

        $bClass = $component->makeBoxClass();
        $this->assertStringContainsString('info-box', $bClass);
        $this->assertStringContainsString('bg-danger', $bClass);

        $iClass = $component->makeIconClass();
        $this->assertStringContainsString('info-box-icon', $iClass);
        $this->assertStringContainsString('bg-primary', $iClass);
    }

    public function testProfileColItemComponent()
    {
        $component = new Components\ProfileColItem(
            'title', 'text', null, null, 'b-theme'
        );

        $twClass = $component->makeTextWrapperClass();
        $this->assertStringContainsString('badge', $twClass);
        $this->assertStringContainsString('bg-b-theme', $twClass);
    }

    public function testProfileRowItemComponent()
    {
        $component = new Components\ProfileRowItem(
            'title', 'text', null, null, 'b-theme'
        );

        $twClass = $component->makeTextWrapperClass();
        $this->assertStringContainsString('badge', $twClass);
        $this->assertStringContainsString('bg-b-theme', $twClass);
    }

    public function testProfileWidgetComponent()
    {
        // Test without cover.

        $component = new Components\ProfileWidget(
            'name', 'description', null, 'danger', null, 'h-class', 'f-class',
            'layout-foo'
        );

        $this->assertEquals('modern', $component->layoutType);

        $cClass = $component->makeCardClass();
        $this->assertStringContainsString('card', $cClass);
        $this->assertStringContainsString('card-widget', $cClass);
        $this->assertStringContainsString('widget-user', $cClass);

        $hClass = $component->makeHeaderClass();
        $this->assertStringContainsString('widget-user-header', $hClass);
        $this->assertStringContainsString('bg-gradient-danger', $hClass);
        $this->assertStringContainsString('h-class', $hClass);

        $fClass = $component->makeFooterClass();
        $this->assertStringContainsString('card-footer', $fClass);
        $this->assertStringContainsString('f-class', $fClass);

        $hStyle = $component->makeHeaderStyle();
        $this->assertTrue(empty($hStyle));

        // Test with cover and classic layout.

        $component = new Components\ProfileWidget(
            'name', 'description', null, 'danger', 'img.png', null, null,
            'classic'
        );

        $cClass = $component->makeCardClass();
        $this->assertStringContainsString('widget-user-2', $cClass);

        $hClass = $component->makeHeaderClass();
        $this->assertStringNotContainsString('bg-gradient-danger', $hClass);

        $hStyle = $component->makeHeaderStyle();
        $this->assertStringContainsString("background: url('img.png')", $hStyle);
    }

    public function testProgressComponent()
    {
        // Test basic component.

        $component = new Components\Progress();

        $pClass = $component->makeProgressClass();
        $this->assertStringContainsString('progress', $pClass);

        $pbClass = $component->makeProgressBarClass();
        $this->assertStringContainsString('progress-bar', $pbClass);
        $this->assertStringContainsString('bg-info', $pbClass);

        $pbStyle = $component->makeProgressBarStyle();
        $this->assertStringContainsString('width:0%', $pbStyle);

        // Test with all constructor arguments:
        // $value, $theme, $size, $striped, $vertical, $animated, $withLabel.

        $component = new Components\Progress(
            75, 'danger', 'sm', true, true, true, true
        );

        $pClass = $component->makeProgressClass();
        $this->assertStringContainsString('progress', $pClass);
        $this->assertStringContainsString('progress-sm', $pClass);
        $this->assertStringContainsString('vertical', $pClass);

        $pbClass = $component->makeProgressBarClass();
        $this->assertStringContainsString('progress-bar', $pbClass);
        $this->assertStringContainsString('bg-danger', $pbClass);
        $this->assertStringContainsString('progress-bar-striped', $pbClass);
        $this->assertStringContainsString('progress-bar-animated', $pbClass);

        $pbStyle = $component->makeProgressBarStyle();
        $this->assertStringContainsString('height:75%', $pbStyle);
    }

    public function testSmallBoxComponent()
    {
        $component = new Components\SmallBox('title', 'text', null, 'danger');

        $bClass = $component->makeBoxClass();
        $this->assertStringContainsString('small-box', $bClass);
        $this->assertStringContainsString('bg-danger', $bClass);

        $oClass = $component->makeOverlayClass();
        $this->assertStringContainsString('overlay', $oClass);
        $this->assertStringContainsString('d-none', $oClass);
    }
}

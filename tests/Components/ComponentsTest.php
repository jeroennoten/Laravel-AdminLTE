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
        $form = "{$base}.form";
        $tool = "{$base}.tool";
        $widget = "{$base}.widget";

        return [

            // Base components.

            "{$form}.input-group-component" => new Components\Form\InputGroupComponent('name'),

            // Form components.

            "{$form}.button"       => new Components\Form\Button(),
            "{$form}.date-range"   => new Components\Form\DateRange('name'),
            "{$form}.input"        => new Components\Form\Input('name'),
            "{$form}.input-color"  => new Components\Form\InputColor('name'),
            "{$form}.input-date"   => new Components\Form\InputDate('name'),
            "{$form}.input-file"   => new Components\Form\InputFile('name'),
            "{$form}.input-slider" => new Components\Form\InputSlider('name'),
            "{$form}.input-switch" => new Components\Form\InputSwitch('name'),
            "{$form}.select"       => new Components\Form\Select('name'),
            "{$form}.select2"      => new Components\Form\Select2('name'),
            "{$form}.select-bs"    => new Components\Form\SelectBs('name'),
            "{$form}.textarea"     => new Components\Form\Textarea('name'),
            "{$form}.text-editor"  => new Components\Form\TextEditor('name'),

            // Tool components.

            "{$tool}.datatable" => new Components\Tool\Datatable('id', []),
            "{$tool}.modal"     => new Components\Tool\Modal('id'),

            // Widget components.

            "{$widget}.alert"            => new Components\Widget\Alert(),
            "{$widget}.callout"          => new Components\Widget\Callout(),
            "{$widget}.card"             => new Components\Widget\Card(),
            "{$widget}.info-box"         => new Components\Widget\InfoBox(),
            "{$widget}.profile-col-item" => new Components\Widget\ProfileColItem(),
            "{$widget}.profile-row-item" => new Components\Widget\ProfileRowItem(),
            "{$widget}.profile-widget"   => new Components\Widget\ProfileWidget(),
            "{$widget}.progress"         => new Components\Widget\Progress(),
            "{$widget}.small-box"        => new Components\Widget\SmallBox(),
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
        $component = new Components\Form\InputGroupComponent(
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
        $component = new Components\Form\InputDate('name');
        $iClass = $component->makeItemClass(true);

        $this->assertStringContainsString('datetimepicker', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testInputFileComponent()
    {
        $component = new Components\Form\InputFile('name', null, null, 'sm');
        $iClass = $component->makeItemClass(true);

        $this->assertStringContainsString('custom-file-input', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testInputSliderComponent()
    {
        $component = new Components\Form\InputSlider(
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
        $component = new Components\Form\InputSwitch(
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
        $component = new Components\Form\Select('name');
        $iClass = $component->makeItemClass(true);

        $this->assertStringContainsString('form-control', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testSelect2Component()
    {
        $component = new Components\Form\Select2('name');
        $iClass = $component->makeItemClass(true);

        $this->assertStringContainsString('form-control', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testSelectBsComponent()
    {
        $component = new Components\Form\SelectBs('name', null, null, 'lg');
        $iClass = $component->makeItemClass(true);

        $this->assertStringContainsString('form-control', $iClass);
        $this->assertStringContainsString('form-control-lg', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testTextEditorComponent()
    {
        $component = new Components\Form\TextEditor(
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
        $component = new Components\Tool\Datatable('id', []);

        $tClass = $component->makeTableClass();
        $this->assertStringContainsString('table', $tClass);

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
    }

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
        $this->assertStringContainsString('bg-secondary', $cbClass);

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

        $component = new Components\Widget\Alert(null, null, null);

        $aClass = $component->makeAlertClass();

        $this->assertStringContainsString('alert', $aClass);
        $this->assertStringContainsString('border', $aClass);

        // Test with theme.

        $component = new Components\Widget\Alert('danger', null, null, true);

        $aClass = $component->makeAlertClass();

        $this->assertStringContainsString('alert', $aClass);
        $this->assertStringContainsString('alert-danger', $aClass);
        $this->assertStringContainsString('alert-dismissable', $aClass);
    }

    public function testCalloutComponent()
    {
        $component = new Components\Widget\Callout('danger');

        $cClass = $component->makeCalloutClass();

        $this->assertStringContainsString('callout', $cClass);
        $this->assertStringContainsString('callout-danger', $cClass);
    }

    public function testCardComponent()
    {
        // Test basic component.

        $component = new Components\Widget\Card('title', null, 'info');

        $cClass = $component->makeCardClass();
        $this->assertStringContainsString('card', $cClass);
        $this->assertStringContainsString('card-info', $cClass);

        $ctClass = $component->makeCardTitleClass();
        $this->assertStringContainsString('card-title', $ctClass);

        // Test collapsed with full theme:
        // $title, $icon, $theme, $themeMode, $disabled, $collapsible,
        // $removable, $maximizable.

        $component = new Components\Widget\Card(
            'title', null, 'success', 'full', null, 'collapsed'
        );

        $cClass = $component->makeCardClass();
        $this->assertStringContainsString('bg-gradient-success', $cClass);
        $this->assertStringContainsString('collapsed-card', $cClass);

        // Test outline theme:
        // $title, $icon, $theme, $themeMode, $disabled, $collapsible,
        // $removable, $maximizable.

        $component = new Components\Widget\Card(
            'title', null, 'teal', 'outline'
        );

        $cClass = $component->makeCardClass();
        $this->assertStringContainsString('card-teal', $cClass);
        $this->assertStringContainsString('card-outline', $cClass);

        $ctClass = $component->makeCardTitleClass();
        $this->assertStringContainsString('text-teal', $ctClass);
    }

    public function testInfoBoxComponent()
    {
        $component = new Components\Widget\InfoBox(
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
        $component = new Components\Widget\ProfileColItem(
            'title', 'text', null, null, 'b-theme'
        );

        $twClass = $component->makeTextWrapperClass();
        $this->assertStringContainsString('badge', $twClass);
        $this->assertStringContainsString('bg-b-theme', $twClass);
    }

    public function testProfileRowItemComponent()
    {
        $component = new Components\Widget\ProfileRowItem(
            'title', 'text', null, null, 'b-theme'
        );

        $twClass = $component->makeTextWrapperClass();
        $this->assertStringContainsString('badge', $twClass);
        $this->assertStringContainsString('bg-b-theme', $twClass);
    }

    public function testProfileWidgetComponent()
    {
        // Test without cover.

        $component = new Components\Widget\ProfileWidget(
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

        $component = new Components\Widget\ProfileWidget(
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

        $component = new Components\Widget\Progress();

        $pClass = $component->makeProgressClass();
        $this->assertStringContainsString('progress', $pClass);

        $pbClass = $component->makeProgressBarClass();
        $this->assertStringContainsString('progress-bar', $pbClass);
        $this->assertStringContainsString('bg-info', $pbClass);

        $pbStyle = $component->makeProgressBarStyle();
        $this->assertStringContainsString('width:0%', $pbStyle);

        // Test with all constructor arguments:
        // $value, $theme, $size, $striped, $vertical, $animated, $withLabel.

        $component = new Components\Widget\Progress(
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
        $component = new Components\Widget\SmallBox(
            'title', 'text', null, 'danger'
        );

        $bClass = $component->makeBoxClass();
        $this->assertStringContainsString('small-box', $bClass);
        $this->assertStringContainsString('bg-danger', $bClass);

        $oClass = $component->makeOverlayClass();
        $this->assertStringContainsString('overlay', $oClass);
        $this->assertStringContainsString('d-none', $oClass);
    }
}

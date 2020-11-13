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
            "{$base}.select-bs"  => new Components\SelectBs('name'),
            "{$base}.textarea"     => new Components\Textarea('name'),
            "{$base}.text-editor"  => new Components\TextEditor('name'),

            // Widget components.

            "{$base}.alert"               => new Components\Alert(),
            "{$base}.callout"             => new Components\Callout(),
            "{$base}.card"                => new Components\Card(null, 'title'),
            "{$base}.datatable"           => new Components\Datatable(null, 'id', null, null, null, '[]'),
            "{$base}.info-box"            => new Components\InfoBox(null, null, null, 'title', 'text'),
            "{$base}.modal"               => new Components\Modal('title', null, 'id'),
            "{$base}.profile-flat"        => new Components\ProfileFlat(null, 'img', 'name', 'desc'),
            "{$base}.profile-flat-item"   => new Components\ProfileFlatItem(null, 'title', 'text'),
            "{$base}.profile-widget"      => new Components\ProfileWidget(null, 'img', 'name', 'desc'),
            "{$base}.profile-widget-item" => new Components\ProfileWidgetItem(null, 'title', 'text'),
            "{$base}.progress"            => new Components\Progress(),
            "{$base}.small-box"           => new Components\SmallBox(null, null, 'title', null, 'text'),
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
            'name', null, 'lg', null, 'top-class'
        );

        $iGroupClass = $component->makeInputGroupClass();
        $fGroupClass = $component->makeFormGroupClass();
        $iClass = $component->makeItemClass(true);

        $this->assertStringContainsString('input-group', $iGroupClass);
        $this->assertStringContainsString('input-group-lg', $iGroupClass);
        $this->assertStringContainsString('form-group', $fGroupClass);
        $this->assertStringContainsString('top-class', $fGroupClass);
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
        $component = new Components\InputFile('name', null, 'sm');
        $iClass = $component->makeItemClass(true);

        $this->assertStringContainsString('custom-file-input', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testInputSwitchComponent()
    {
        $component = new Components\InputSwitch('name');
        $iClass = $component->makeItemClass(true);

        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testSelectComponent()
    {
        $component = new Components\Select('name');
        $iClass = $component->makeItemClass(true);

        $this->assertStringContainsString('form-control', $iClass);
        $this->assertStringContainsString('w-100', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testSelect2Component()
    {
        $component = new Components\Select2('name');
        $iClass = $component->makeItemClass(true);

        $this->assertStringContainsString('form-control', $iClass);
        $this->assertStringContainsString('w-100', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testSelectBsComponent()
    {
        $component = new Components\SelectBs('name', null, 'lg');
        $iClass = $component->makeItemClass(true);

        $this->assertStringContainsString('form-control', $iClass);
        $this->assertStringContainsString('form-control-lg', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    /*
    |--------------------------------------------------------------------------
    | Individual widget components tests.
    |--------------------------------------------------------------------------
    */

    public function testAlertComponent()
    {
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

    public function testDatatableComponent()
    {
        $component = new Components\Datatable(
            null, 'id', true, true, true, '[]'
        );

        $this->assertIsString($component->border());
        $this->assertIsString($component->hover());
        $this->assertIsString($component->condense());
    }

    public function testInfoBoxComponent()
    {
        $component = new Components\InfoBox(
            null, null, null, 'title', 'text'
        );

        $this->assertIsString($component->background());
        $this->assertIsString($component->foreground());

        $component = new Components\InfoBox(
            null, null, null, 'title', 'text', true, true
        );

        $this->assertIsString($component->background());
        $this->assertIsString($component->foreground());
    }

    public function testModalComponent()
    {
        $component = new Components\Modal('title', null, 'id');

        $this->assertIsString($component->modalsize());
        $this->assertIsNumeric($component->zindex());
    }

    public function testProfileFlatComponent()
    {
        $component = new Components\ProfileFlat(null, 'img', 'name', 'desc');

        $this->assertIsString($component->background());
    }

    public function testProfileWidgetComponent()
    {
        $component = new Components\ProfileWidget(null, 'img', 'name', 'desc');

        $this->assertIsString($component->background());
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
        $component = new Components\SmallBox(null, null, 'title', null, 'text');

        $this->assertIsString($component->urlTextLine());
    }
}

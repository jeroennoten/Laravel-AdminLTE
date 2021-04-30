<?php

use JeroenNoten\LaravelAdminLte\Components;

class FormComponentsTest extends TestCase
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
        $base = 'adminlte::components.form';

        return [
            "{$base}.input-group-component" => new Components\Form\InputGroupComponent('name'),
            "{$base}.button"       => new Components\Form\Button(),
            "{$base}.date-range"   => new Components\Form\DateRange('name'),
            "{$base}.input"        => new Components\Form\Input('name'),
            "{$base}.input-color"  => new Components\Form\InputColor('name'),
            "{$base}.input-date"   => new Components\Form\InputDate('name'),
            "{$base}.input-file"   => new Components\Form\InputFile('name'),
            "{$base}.input-slider" => new Components\Form\InputSlider('name'),
            "{$base}.input-switch" => new Components\Form\InputSwitch('name'),
            "{$base}.select"       => new Components\Form\Select('name'),
            "{$base}.select2"      => new Components\Form\Select2('name'),
            "{$base}.select-bs"    => new Components\Form\SelectBs('name'),
            "{$base}.textarea"     => new Components\Form\Textarea('name'),
            "{$base}.text-editor"  => new Components\Form\TextEditor('name'),
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

    /*
    |--------------------------------------------------------------------------
    | Individual form components tests.
    |--------------------------------------------------------------------------
    */

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
}

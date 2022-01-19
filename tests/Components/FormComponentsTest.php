<?php

use Illuminate\Support\MessageBag;
use JeroenNoten\LaravelAdminLte\View\Components;

class FormComponentsTest extends TestCase
{
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
            "{$base}.options"      => new Components\Form\Options(['o1, o2']),
        ];
    }

    /**
     * Add an error on the session's error bag for the provided $key.
     */
    protected function addErrorOnSessionFor($key)
    {
        $msgBag = new MessageBag();
        $msgBag->add($key, 'error');
        session()->put('errors', $msgBag);
    }

    /**
     * Flash an input with value into the current laravel request.
     *
     * @param  string  $key  The input key
     * @param  mixed  $val  The input value
     */
    protected function addInputOnCurrentRequest($key, $val)
    {
        session()->flashInput([$key => $val]);
        request()->setLaravelsession(session());
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

        $this->addErrorOnSessionFor('name');

        $iGroupClass = $component->makeInputGroupClass();
        $fGroupClass = $component->makeFormGroupClass();
        $iClass = $component->makeItemClass();

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

        $this->addErrorOnSessionFor('name');

        $iClass = $component->makeItemClass();

        $this->assertStringContainsString('datetimepicker', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testInputDateComponentOldSupport()
    {
        // Test component with old support disabled.

        $component = new Components\Form\InputDate('name');
        $oVal = $component->getOldValue('name', 'default');

        $this->assertEquals('default', $oVal);

        // Test component with old support enabled.

        $component = new Components\Form\InputDate(
            'name', null, null, null, null, null, null, null, null, null, true
        );

        $this->addInputOnCurrentRequest('name', 'foo');
        $oVal = $component->getOldValue('name', 'default');

        $this->assertEquals('foo', $oVal);
    }

    public function testInputFileComponent()
    {
        $component = new Components\Form\InputFile('name', null, null, 'sm');
        $this->addErrorOnSessionFor('name');

        $iClass = $component->makeItemClass();

        $this->assertStringContainsString('custom-file-input', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testInputSliderComponent()
    {
        $component = new Components\Form\InputSlider(
            'name', null, null, 'lg', null, null, 'igroup-class'
        );

        $this->addErrorOnSessionFor('name');

        $iGroupClass = $component->makeInputGroupClass();
        $iClass = $component->makeItemClass();

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

        $this->addErrorOnSessionFor('name');

        $iGroupClass = $component->makeInputGroupClass();
        $iClass = $component->makeItemClass();

        $this->assertStringContainsString('input-group', $iGroupClass);
        $this->assertStringContainsString('input-group-lg', $iGroupClass);
        $this->assertStringContainsString('igroup-class', $iGroupClass);
        $this->assertStringContainsString('adminlte-invalid-iswgroup', $iGroupClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testOptionsComponent()
    {
        $options = ['m' => 'Male', 'f' => 'Female', 'o' => 'Other'];
        $component = new Components\Form\Options($options, 'f', 'o');

        // Test selected / disabled options.

        $this->assertFalse($component->isSelected('m'));
        $this->assertTrue($component->isSelected('f'));
        $this->assertFalse($component->isDisabled('m'));
        $this->assertTrue($component->isDisabled('o'));

        // Test rendered HTML.

        $html = $component->resolveView()->with($component->data());
        $format = '%A<option%avalue="m"%A>%AMale%A</option>%A';
        $format .= '<option%avalue="f"%Aselected%A>%AFemale%A</option>%A';
        $format .= '<option%avalue="o"%Adisabled%A>%AOther%A</option>%A';

        $this->assertStringMatchesFormat($format, $html);

        // Test rendered HTML with empty option (no label).

        $component = new Components\Form\Options($options, 'f', 'o', null, true);

        $html = $component->resolveView()->with($component->data());
        $format = '%A<option%Avalue%A>%A</option>%A';
        $format .= '%A<option%avalue="m"%A>%AMale%A</option>%A';
        $format .= '<option%avalue="f"%Aselected%A>%AFemale%A</option>%A';
        $format .= '<option%avalue="o"%Adisabled%A>%AOther%A</option>%A';

        $this->assertStringMatchesFormat($format, $html);

        // Test rendered HTML with empty option (and label).

        $component = new Components\Form\Options($options, 'f', 'o', null, 'Label');

        $html = $component->resolveView()->with($component->data());
        $format = '%A<option%Avalue%A>%ALabel%A</option>%A';
        $format .= '%A<option%avalue="m"%A>%AMale%A</option>%A';
        $format .= '<option%avalue="f"%Aselected%A>%AFemale%A</option>%A';
        $format .= '<option%avalue="o"%Adisabled%A>%AOther%A</option>%A';

        $this->assertStringMatchesFormat($format, $html);

        // Test rendered HTML with placeholder.

        $component = new Components\Form\Options($options, 'f', 'o', null, null, 'Placeholder');

        $html = $component->resolveView()->with($component->data());
        $format = '%A<option%Aclass="d-none"%Avalue%A>%APlaceholder%A</option>%A';
        $format .= '%A<option%avalue="m"%A>%AMale%A</option>%A';
        $format .= '<option%avalue="f"%Aselected%A>%AFemale%A</option>%A';
        $format .= '<option%avalue="o"%Adisabled%A>%AOther%A</option>%A';

        $this->assertStringMatchesFormat($format, $html);
    }

    public function testSelectComponent()
    {
        $component = new Components\Form\Select('name');

        $this->addErrorOnSessionFor('name');

        $iClass = $component->makeItemClass();

        $this->assertStringContainsString('form-control', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testSelectComponentOldSupport()
    {
        // Test component with old support disabled.

        $component = new Components\Form\Select('name');
        $oVal = $component->getOldValue('name', 'default');

        $this->assertEquals('default', $oVal);

        // Test component with old support enabled.

        $component = new Components\Form\Select(
            'name', null, null, null, null, null, null, null, null, true
        );

        $this->addInputOnCurrentRequest('name', 'foo');
        $oVal = $component->getOldValue('name', 'default');

        $this->assertEquals('foo', $oVal);
    }

    public function testSelect2Component()
    {
        $component = new Components\Form\Select2('name');

        $this->addErrorOnSessionFor('name');

        $iClass = $component->makeItemClass();

        $this->assertStringContainsString('form-control', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testSelect2ComponentOldSupport()
    {
        // Test component with old support disabled.

        $component = new Components\Form\Select2('name');
        $oVal = $component->getOldValue('name', 'default');

        $this->assertEquals('default', $oVal);

        // Test component with old support enabled.

        $component = new Components\Form\Select2(
            'name', null, null, null, null, null, null, null, null, null, true
        );

        $this->addInputOnCurrentRequest('name', 'foo');
        $oVal = $component->getOldValue('name', 'default');

        $this->assertEquals('foo', $oVal);
    }

    public function testSelectBsComponent()
    {
        $component = new Components\Form\SelectBs('name', null, null, 'lg');

        $this->addErrorOnSessionFor('name');

        $iClass = $component->makeItemClass();

        $this->assertStringContainsString('form-control', $iClass);
        $this->assertStringContainsString('form-control-lg', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testTextEditorComponent()
    {
        $component = new Components\Form\TextEditor(
            'name', null, null, 'lg', null, null, 'igroup-class'
        );

        $this->addErrorOnSessionFor('name');

        $iGroupClass = $component->makeInputGroupClass();

        $this->assertStringContainsString('input-group', $iGroupClass);
        $this->assertStringContainsString('input-group-lg', $iGroupClass);
        $this->assertStringContainsString('igroup-class', $iGroupClass);
        $this->assertStringContainsString('adminlte-invalid-itegroup', $iGroupClass);
    }
}

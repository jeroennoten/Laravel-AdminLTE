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

            "{$base}.date-range"   => new Components\DateRange('id'),
            "{$base}.input"        => new Components\Input('name'),
            "{$base}.input-color"  => new Components\InputColor('id'),
            "{$base}.input-date"   => new Components\InputDate('id'),
            "{$base}.input-file"   => new Components\InputFile(),
            "{$base}.input-slider" => new Components\InputSlider('id'),
            "{$base}.input-switch" => new Components\InputSwitch(),
            "{$base}.input-tag"    => new Components\InputTag(),
            "{$base}.option"       => new Components\Option(),
            "{$base}.select"       => new Components\Select('id'),
            "{$base}.select2"      => new Components\Select2('id'),
            "{$base}.select-icon"  => new Components\SelectIcon('id'),
            "{$base}.submit"       => new Components\Submit(),
            "{$base}.textarea"     => new Components\Textarea('name'),
            "{$base}.text-editor"  => new Components\TextEditor('id'),

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
            "{$base}.progress"            => new Components\Progress(null, null, 50),
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

    public function testDateRangeComponent()
    {
        for ($i = 0; $i <= 5; $i++) {
            $component = new Components\DateRange('id', null, null, null, $i);
            $this->assertIsString($component->initiator());
        }
    }

    public function testTextEditorComponent()
    {
        $component = new Components\TextEditor(
            'id', null, null, null, null, null, null, null,
            null, null, ['Foo Font', 'Bar Font']
        );

        $fonts = $component->fontarray();
        $this->assertIsArray($fonts);
        $this->assertContains('Foo Font', $fonts);
        $this->assertContains('Bar Font', $fonts);
    }

    /*
    |--------------------------------------------------------------------------
    | Individual widget components tests.
    |--------------------------------------------------------------------------
    */

    public function testAlertComponent()
    {
        $types = ['info', 'warning', 'success', 'danger', 'unknown'];

        foreach ($types as $type) {
            $component = new Components\Alert($type);
            $this->assertIsString($component->icon());
        }
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
        $component = new Components\Progress('sm', null, 50);

        $this->assertIsString($component->barsize());
    }

    public function testSmallBoxComponent()
    {
        $component = new Components\SmallBox(null, null, 'title', null, 'text');

        $this->assertIsString($component->urlTextLine());
    }
}

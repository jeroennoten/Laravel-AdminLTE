<?php

require_once __DIR__.'/ComponentTestHelpers.php';

use Illuminate\Support\MessageBag;
use JeroenNoten\LaravelAdminLte\View\Components;

class FormComponentsTest extends TestCase
{
    use ComponentTestHelpers;

    /**
     * Return array with a template exercising every attribute of each one of
     * the available form components. Note some of the attributes are only
     * accepted for backward compatibility and fully ignored.
     *
     * @return array
     */
    protected function getFullyAttributedTemplates()
    {
        // The set of attributes shared by every input group based component.

        $base = 'name="fname" id="fid" label="A label" igroup-size="lg"
            label-class="l-cls" fgroup-class="f-cls" igroup-class="i-cls"
            error-key="ekey"';

        return [
            'button' => '<x-adminlte-button label="Send" type="submit"
                theme="primary" icon="bi bi-send"/>',
            'input-group-component' => "<x-adminlte-input {$base} disable-feedback/>",
            'input' => "<x-adminlte-input {$base} enable-old-support/>",
            'input-color' => "<x-adminlte-input-color {$base} enable-old-support
                :config=\"['format' => 'hex']\"/>",
            'input-date' => "<x-adminlte-input-date {$base} enable-old-support
                :config=\"['format' => 'YYYY-MM-DD', 'sideBySide' => true]\"/>",
            'input-file' => "<x-adminlte-input-file {$base} placeholder=\"Choose\"
                legend=\"Browse\"/>",
            'input-file-krajee' => "<x-adminlte-input-file-krajee {$base}
                preset-mode=\"avatar\" :config=\"['showUpload' => true]\"/>",
            'input-slider' => "<x-adminlte-input-slider {$base} color=\"red\"
                enable-old-support :config=\"['min' => 1, 'max' => 9]\"/>",
            'input-switch' => "<x-adminlte-input-switch {$base} is-checked
                enable-old-support :config=\"['onColor' => 'success']\"/>",
            'date-range' => "<x-adminlte-date-range {$base} enable-old-support
                enable-default-ranges=\"Today\" :config=\"['timePicker' => true]\"/>",
            'select' => "<x-adminlte-select {$base} enable-old-support>
                <option>a</option></x-adminlte-select>",
            'select2' => "<x-adminlte-select2 {$base} enable-old-support
                :config=\"['tags' => true]\"><option>a</option></x-adminlte-select2>",
            'select-bs' => "<x-adminlte-select-bs {$base} enable-old-support
                :config=\"['title' => 'Pick one']\"><option>a</option></x-adminlte-select-bs>",
            'textarea' => "<x-adminlte-textarea {$base} enable-old-support>
                Content</x-adminlte-textarea>",
            'text-editor' => "<x-adminlte-text-editor {$base} enable-old-support
                :config=\"['height' => 300]\"/>",
            'options' => '<x-adminlte-options :options="[\'a\' => \'A\']"
                selected="a" disabled="a" strict empty-option="Empty"
                placeholder="Pick"/>',
        ];
    }

    /**
     * Return array with the available blade components.
     *
     * @return array
     */
    protected function getComponents()
    {
        $base = 'adminlte::components.form';

        return [
            "{$base}.input-group-component" => new Components\Form\InputGroupComponent('name'),
            "{$base}.button" => new Components\Form\Button(),
            "{$base}.date-range" => new Components\Form\DateRange('name'),
            "{$base}.input" => new Components\Form\Input('name'),
            "{$base}.input-color" => new Components\Form\InputColor('name'),
            "{$base}.input-date" => new Components\Form\InputDate('name'),
            "{$base}.input-file" => new Components\Form\InputFile('name'),
            "{$base}.input-file-krajee" => new Components\Form\InputFileKrajee('name'),
            "{$base}.input-slider" => new Components\Form\InputSlider('name'),
            "{$base}.input-switch" => new Components\Form\InputSwitch('name'),
            "{$base}.select" => new Components\Form\Select('name'),
            "{$base}.select2" => new Components\Form\Select2('name'),
            "{$base}.select-bs" => new Components\Form\SelectBs('name'),
            "{$base}.textarea" => new Components\Form\Textarea('name'),
            "{$base}.text-editor" => new Components\Form\TextEditor('name'),
            "{$base}.options" => new Components\Form\Options(['o1, o2']),
        ];
    }

    /**
     * Add an error on the session's error bag for the provided $key.
     *
     * @param  string  $key  The key for which to add an error
     * @return void
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
     * @return void
     */
    protected function addInputOnCurrentRequest($key, $val)
    {
        session()->flashInput([$key => $val]);
        request()->setLaravelsession(session()->driver());
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

    public function testAllComponentsRenderWithEveryAttribute()
    {
        // Every attribute of every form component (including the ones that are
        // only accepted for backward compatibility) must render without
        // breaking and without any of the legacy AdminLTE v3 / Bootstrap 4
        // markup.

        foreach ($this->getFullyAttributedTemplates() as $name => $template) {
            $html = $this->renderComponent($template);

            $this->assertNotEmpty(trim($html), "The {$name} component is empty.");
            $this->assertV4Markup($html);
            $this->assertV4Markup($this->renderPushedAssets());

            // The rendered markup is always jQuery free, only the scripts of
            // the components wrapping a jQuery plugin may reference it.

            $this->assertFreeOfJquery($html);
        }
    }

    public function testOnlyTheJqueryBasedPluginsReferenceJquery()
    {
        // AdminLTE v4 dropped the jQuery dependency. The Select2 and the
        // Krajee file input plugins are the only form plugins that still
        // require it, and their scripts are feature guarded.

        $jqueryBased = ['select2', 'input-file-krajee'];

        foreach ($this->getFullyAttributedTemplates() as $name => $template) {
            $this->renderComponent($template);
            $assets = $this->renderPushedAssets();

            if (in_array($name, $jqueryBased, true)) {
                $this->assertStringContainsString('window.jQuery', $assets);

                continue;
            }

            $this->assertFreeOfJquery($assets);
        }
    }

    public function testAllComponentsRenderWithTheMinimalSetOfAttributes()
    {
        $templates = [
            '<x-adminlte-button/>',
            '<x-adminlte-input name="n"/>',
            '<x-adminlte-input-color name="n"/>',
            '<x-adminlte-input-date name="n"/>',
            '<x-adminlte-input-file name="n"/>',
            '<x-adminlte-input-file-krajee name="n"/>',
            '<x-adminlte-input-slider name="n"/>',
            '<x-adminlte-input-switch name="n"/>',
            '<x-adminlte-date-range name="n"/>',
            '<x-adminlte-select name="n"/>',
            '<x-adminlte-select2 name="n"/>',
            '<x-adminlte-select-bs name="n"/>',
            '<x-adminlte-textarea name="n"/>',
            '<x-adminlte-text-editor name="n"/>',
            '<x-adminlte-options :options="[]"/>',
        ];

        foreach ($templates as $template) {
            $html = $this->renderComponent($template);

            $this->assertV4Markup($html);
            $this->assertFreeOfJquery($html);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Input group component tests.
    |--------------------------------------------------------------------------
    */

    public function testInvalidInputGroupComponentBySessionErrorsBag()
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
        $this->assertStringContainsString('mb-3', $fGroupClass);
        $this->assertStringContainsString('fgroup-class', $fGroupClass);
        $this->assertStringContainsString('form-control', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testInvalidInputGroupComponentByErrorsBagSetup()
    {
        // Note we configure a specific error key called 'nameErrorKey'.

        $component = new Components\Form\InputGroupComponent(
            'name', null, null, 'sm', null, 'fgroup-class',
            'igroup-class', null, 'nameErrorKey'
        );

        // Setup an internal errors bag for the component.

        $msgBag = new MessageBag();
        $msgBag->add('nameErrorKey', 'error');
        $component->setErrorsBag($msgBag);

        // Test the component.

        $iGroupClass = $component->makeInputGroupClass();
        $fGroupClass = $component->makeFormGroupClass();
        $iClass = $component->makeItemClass();

        $this->assertStringContainsString('input-group', $iGroupClass);
        $this->assertStringContainsString('input-group-sm', $iGroupClass);
        $this->assertStringContainsString('igroup-class', $iGroupClass);
        $this->assertStringContainsString('adminlte-invalid-igroup', $iGroupClass);
        $this->assertStringContainsString('mb-3', $fGroupClass);
        $this->assertStringContainsString('fgroup-class', $fGroupClass);
    }

    public function testInputGroupComponentRendersTheBootstrap5Structure()
    {
        $html = $this->renderComponent(
            '<x-adminlte-input name="username" label="Username" igroup-size="lg"
                label-class="text-danger" fgroup-class="w-50" igroup-class="shadow">'.
            '<x-slot name="prependSlot"><span class="input-group-text">@</span></x-slot>'.
            '<x-slot name="appendSlot"><span class="input-group-text">!</span></x-slot>'.
            '<x-slot name="bottomSlot"><small id="hint">A hint</small></x-slot>'.
            '</x-adminlte-input>'
        );

        // Bootstrap 5 dropped the 'form-group' class in favour of the spacing
        // utilities and requires the 'form-label' class on the labels.

        $this->assertStringContainsString('<div class="mb-3 w-50">', $html);
        $this->assertStringContainsString(
            '<label for="username" class="form-label text-danger">',
            $html
        );

        $this->assertStringContainsString(
            '<div class="input-group input-group-lg shadow">',
            $html
        );

        // Bootstrap 5 dropped the 'input-group-prepend' and the
        // 'input-group-append' wrappers, the addons are now direct children of
        // the 'input-group' element.

        $this->assertStringContainsString('<span class="input-group-text">@</span>', $html);
        $this->assertStringContainsString('<span class="input-group-text">!</span>', $html);
        $this->assertStringContainsString('<small id="hint">A hint</small>', $html);

        $this->assertStringContainsString('<input id="username" name="username"', $html);
        $this->assertStringContainsString('class="form-control"', $html);

        $this->assertV4Markup($html);
    }

    public function testInputGroupComponentRendersTheInvalidFeedback()
    {
        $this->setValidationErrorsFor('username');

        $html = $this->renderComponent('<x-adminlte-input name="username"/>');

        $this->assertStringContainsString('adminlte-invalid-igroup', $html);
        $this->assertStringContainsString('class="form-control is-invalid"', $html);
        $this->assertStringContainsString('class="invalid-feedback d-block"', $html);
        $this->assertStringContainsString('role="alert"', $html);
        $this->assertStringContainsString('The username value is invalid.', $html);

        $this->assertV4Markup($html);
    }

    public function testInputGroupComponentDisableFeedbackAttribute()
    {
        $this->setValidationErrorsFor('username');

        $html = $this->renderComponent(
            '<x-adminlte-input name="username" disable-feedback/>'
        );

        $this->assertStringNotContainsString('is-invalid', $html);
        $this->assertStringNotContainsString('invalid-feedback', $html);
    }

    public function testInputGroupComponentErrorKeyAttribute()
    {
        // The error key is generated from the name property, but it can be
        // overwritten through the 'error-key' attribute.

        $this->setValidationErrorsFor(['files', 'person.2.name', 'custom']);

        $keys = [
            'files[]' => 'files',
            'person[2][name]' => 'person.2.name',
        ];

        foreach ($keys as $name => $errorKey) {
            $component = new Components\Form\InputGroupComponent($name);
            $this->assertEquals($errorKey, $component->errorKey);
            $this->assertTrue($component->isInvalid());
        }

        $component = new Components\Form\InputGroupComponent(
            'other', null, null, null, null, null, null, null, 'custom'
        );

        $this->assertEquals('custom', $component->errorKey);
        $this->assertTrue($component->isInvalid());
    }

    public function testInputGroupComponentIgnoresAnInvalidSize()
    {
        $component = new Components\Form\InputGroupComponent('name', null, null, 'xl');

        $this->assertEquals('input-group', $component->makeInputGroupClass());
    }

    public function testFormGroupKeepsASingleBottomMargin()
    {
        // Regression: the Bootstrap spacing utilities are declared with
        // '!important' and with the same specificity, so a caller provided
        // margin must replace the default one instead of being appended to it.

        $component = new Components\Form\InputGroupComponent('name');
        $this->assertEquals('mb-3', $component->makeFormGroupClass());

        foreach (['mb-0', 'mb-5', 'my-2', 'mb-auto'] as $margin) {
            $component = new Components\Form\InputGroupComponent(
                'name', null, null, null, null, $margin
            );

            $this->assertEquals($margin, $component->makeFormGroupClass());

            $html = $this->renderComponent(
                "<x-adminlte-input name=\"n\" fgroup-class=\"{$margin}\"/>"
            );

            $this->assertStringContainsString("<div class=\"{$margin}\">", $html);
            $this->assertEquals(1, preg_match_all('/\bm[by]-(auto|[0-5])\b/', $html));
        }

        // A class that is not a margin utility does not disable the default.

        $component = new Components\Form\InputGroupComponent(
            'name', null, null, null, null, 'w-50'
        );

        $this->assertEquals('mb-3 w-50', $component->makeFormGroupClass());
    }

    /*
    |--------------------------------------------------------------------------
    | Button component tests.
    |--------------------------------------------------------------------------
    */

    public function testButtonComponentRendersEveryAttribute()
    {
        // Test all the constructor arguments at once:
        // $label, $type, $theme, $icon.

        $html = $this->renderComponent(
            '<x-adminlte-button label="Send" type="submit" theme="primary"
                icon="bi bi-send" class="w-100" id="btn"/>'
        );

        $this->assertStringContainsString('<button type="submit"', $html);
        $this->assertStringContainsString('class="btn btn-primary w-100"', $html);
        $this->assertStringContainsString('id="btn"', $html);
        $this->assertStringContainsString('<i class="bi bi-send"></i>', $html);
        $this->assertStringContainsString('Send', $html);

        $this->assertV4Markup($html);
    }

    public function testButtonComponentDefaults()
    {
        $html = $this->renderComponent('<x-adminlte-button/>');

        // The default type is 'button' and the legacy 'default' theme is
        // mapped to the Bootstrap 5 'secondary' theme.

        $this->assertStringContainsString('<button type="button"', $html);
        $this->assertStringContainsString('class="btn btn-secondary"', $html);
        $this->assertStringNotContainsString('<i class', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Date range component tests.
    |--------------------------------------------------------------------------
    */

    public function testDateRangeComponent()
    {
        $component = new Components\Form\DateRange('name');

        $this->addErrorOnSessionFor('name');

        $iClass = $component->makeItemClass();

        $this->assertStringContainsString('form-control', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testDateRangeComponentOldSupport()
    {
        // Test component with old support disabled.

        $component = new Components\Form\DateRange('name');
        $oVal = $component->getOldValue('name', 'default');

        $this->assertEquals('default', $oVal);

        // Test component with old support enabled.

        $component = new Components\Form\DateRange(
            'name', null, null, null, null, null, null,
            null, null, null, null, true
        );

        $this->addInputOnCurrentRequest('name', 'foo');
        $oVal = $component->getOldValue('name', 'default');

        $this->assertEquals('foo', $oVal);
    }

    public function testDateRangeComponentTranslatesTheLegacyConfiguration()
    {
        // On AdminLTE v4 the jQuery 'DateRangePicker' plugin was replaced by
        // the vanilla javascript 'Flatpickr' plugin, so the legacy properties
        // are translated when possible and dropped otherwise.

        $config = [
            'singleDatePicker' => true,
            'timePicker' => true,
            'timePicker24Hour' => true,
            'minYear' => '2020-01-01',
            'maxYear' => '2030-01-01',
            'locale' => ['format' => 'Y-m-d', 'separator' => ' to '],
            'startDate' => '2024-01-01',
            'endDate' => '2024-01-31',
            'ranges' => ['Today' => []],
            'autoApply' => true,
            'opens' => 'left',
            'buttonClasses' => 'btn',
        ];

        $component = new Components\Form\DateRange(
            'name', null, null, null, null, null, null, null, null, $config
        );

        $pluginCfg = $component->makePluginConfig();

        $this->assertEquals('single', $pluginCfg['mode']);
        $this->assertTrue($pluginCfg['enableTime']);
        $this->assertTrue($pluginCfg['time_24hr']);
        $this->assertEquals('2020-01-01', $pluginCfg['minDate']);
        $this->assertEquals('2030-01-01', $pluginCfg['maxDate']);
        $this->assertEquals('Y-m-d', $pluginCfg['dateFormat']);
        $this->assertEquals([' to '], array_values($pluginCfg['locale']));
        $this->assertEquals(['2024-01-01', '2024-01-31'], $pluginCfg['defaultDate']);
        $this->assertTrue($pluginCfg['allowInput']);

        // The meaningless legacy properties are dropped.

        foreach (['ranges', 'autoApply', 'opens', 'buttonClasses'] as $key) {
            $this->assertArrayNotHasKey($key, $pluginCfg);
        }
    }

    public function testDateRangeComponentWidensTheLegacyYearBounds()
    {
        // The legacy 'minYear' and 'maxYear' properties carry a year, while
        // their 'Flatpickr' counterparts expect a date, so a bare year has to
        // be widened to the boundary day it stands for.

        $component = new Components\Form\DateRange(
            'name', null, null, null, null, null, null, null, null,
            ['minYear' => 2010, 'maxYear' => '2030']
        );

        $pluginCfg = $component->makePluginConfig();

        $this->assertEquals('2010-01-01', $pluginCfg['minDate']);
        $this->assertEquals('2030-12-31', $pluginCfg['maxDate']);

        // A value that is already a date is passed through untouched.

        $component = new Components\Form\DateRange(
            'name', null, null, null, null, null, null, null, null,
            ['minYear' => '2010-06-15']
        );

        $this->assertEquals('2010-06-15', $component->makePluginConfig()['minDate']);
    }

    public function testDateRangeComponentDefaultRanges()
    {
        // The predefined ranges are only used to preselect the initial range.

        $ranges = [
            'Today', 'Yesterday', 'Last 7 Days', 'Last 30 Days', 'This Month',
            'Last Month',
        ];

        foreach ($ranges as $range) {
            $component = new Components\Form\DateRange(
                'name', null, null, null, null, null, null, null, null, [], $range
            );

            $pluginCfg = $component->makePluginConfig();

            $this->assertCount(2, $pluginCfg['defaultDate']);
            $this->assertMatchesRegularExpression(
                '/^\d{4}-\d{2}-\d{2}$/',
                $pluginCfg['defaultDate'][0]
            );
        }

        // An unknown or boolean range does not preselect anything.

        foreach (['Unknown range', true] as $range) {
            $component = new Components\Form\DateRange(
                'name', null, null, null, null, null, null, null, null, [], $range
            );

            $this->assertArrayNotHasKey('defaultDate', $component->makePluginConfig());
        }

        // An invalid configuration is discarded.

        $component = new Components\Form\DateRange(
            'name', null, null, null, null, null, null, null, null, 'invalid'
        );

        $this->assertEquals([], $component->config);
    }

    public function testDateRangeComponentRendersAGuardedPluginScript()
    {
        $html = $this->renderComponent(
            '<x-adminlte-date-range name="range" label="Range"/>'
        );

        $this->assertStringContainsString('<input id="range" name="range"', $html);
        $this->assertStringContainsString('class="form-control"', $html);

        $js = $this->renderPushedAssets();

        // The plugin is vanilla javascript and the initialization must not
        // throw when the plugin is not loaded.

        $this->assertGuardedPluginUsage(
            $js,
            "typeof window.flatpickr === 'undefined'",
            'window.flatpickr(el, usrCfg)'
        );

        $this->assertStringContainsString('_AdminLTE_DateRange', $js);
        $this->assertFreeOfJquery($js);
    }

    /*
    |--------------------------------------------------------------------------
    | Input color component tests.
    |--------------------------------------------------------------------------
    */

    public function testInputColorComponent()
    {
        $component = new Components\Form\InputColor('name');

        $this->addErrorOnSessionFor('name');

        $iClass = $component->makeItemClass();

        $this->assertStringContainsString('form-control', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testInputColorComponentOldSupport()
    {
        // Test component with old support disabled.

        $component = new Components\Form\InputColor('name');
        $oVal = $component->getOldValue('name', 'default');

        $this->assertEquals('default', $oVal);

        // Test component with old support enabled.

        $component = new Components\Form\InputColor(
            'name', null, null, null, null, null, null, null, null, null, true
        );

        $this->addInputOnCurrentRequest('name', 'foo');
        $oVal = $component->getOldValue('name', 'default');

        $this->assertEquals('foo', $oVal);
    }

    public function testInputColorComponentRendersTheNativeControl()
    {
        // Bootstrap 5 provides a native color control, so the legacy
        // 'Bootstrap Colorpicker' jQuery plugin is not required anymore.

        $html = $this->renderComponent(
            '<x-adminlte-input-color name="color" label="Color" value="#FF8800"/>'
        );

        $this->assertStringContainsString('<input id="color" name="color"', $html);
        $this->assertStringContainsString('value="#ff8800"', $html);
        $this->assertStringContainsString('class="form-control form-control-color"', $html);
        $this->assertStringContainsString('type="color"', $html);

        $this->assertV4Markup($html);
        $this->assertFreeOfJquery($this->renderPushedAssets());
    }

    public function testInputColorComponentKeepsTheSwatchWidth()
    {
        // Regression: the input group rules of Bootstrap beat the 3rem swatch
        // width of '.form-control-color', so the component restores it.

        $this->renderComponent('<x-adminlte-input-color name="color"/>');

        $css = $this->renderPushedAssets();

        $this->assertStringContainsString('.input-group > .form-control-color', $css);
        $this->assertStringContainsString('width: 3rem', $css);
        $this->assertStringContainsString('flex: 0 0 auto', $css);
    }

    public function testInputColorComponentNormalizesTheValue()
    {
        $component = new Components\Form\InputColor('color');

        // Only a lowercase hexadecimal notation is accepted by the native
        // control, any other value falls back to a neutral default.

        $this->assertEquals('#ff8800', $component->makeColorValue('#FF8800'));
        $this->assertEquals('#123abc', $component->makeColorValue('#123abc'));
        $this->assertEquals('#000000', $component->makeColorValue('red'));
        $this->assertEquals('#000000', $component->makeColorValue('#fff'));
        $this->assertEquals('#000000', $component->makeColorValue(null));
        $this->assertEquals('#000000', $component->makeColorValue(123));
    }

    public function testInputColorComponentIgnoresThePluginConfiguration()
    {
        // The 'config' attribute is only accepted for backward compatibility.

        $config = ['format' => 'rgb', 'useAlpha' => true];

        $component = new Components\Form\InputColor(
            'color', null, null, null, null, null, null, null, null, $config
        );

        $this->assertEquals($config, $component->config);

        $html = $this->renderComponent(
            '<x-adminlte-input-color name="color" :config="[\'format\' => \'rgb\']"/>'
        );

        $this->assertStringNotContainsString('format', $html);
        $this->assertStringNotContainsString('format', $this->renderPushedAssets());
    }

    /*
    |--------------------------------------------------------------------------
    | Input date component tests.
    |--------------------------------------------------------------------------
    */

    public function testInputDateComponent()
    {
        $component = new Components\Form\InputDate('name');

        $this->addErrorOnSessionFor('name');

        $iClass = $component->makeItemClass();

        $this->assertStringContainsString('form-control', $iClass);
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

    public function testInputDateComponentDefaultsToBootstrapIcons()
    {
        // The default icons are kept for backward compatibility, but they are
        // Bootstrap Icons now (they are not used by the 'Flatpickr' plugin).

        $component = new Components\Form\InputDate('name');

        foreach ($component->config['icons'] as $icon) {
            $this->assertStringStartsWith('bi bi-', $icon);
        }

        $this->assertTrue($component->config['buttons']['showClose']);

        // The defaults can be overwritten through the configuration.

        $component = new Components\Form\InputDate(
            'name', null, null, null, null, null, null, null, null,
            ['icons' => ['date' => 'custom'], 'buttons' => ['showClose' => false]]
        );

        $this->assertEquals(['date' => 'custom'], $component->config['icons']);
        $this->assertFalse($component->config['buttons']['showClose']);
    }

    public function testInputDateComponentTranslatesTheLegacyConfiguration()
    {
        // On AdminLTE v4 the 'Tempus Dominus' plugin was replaced by the
        // vanilla javascript 'Flatpickr' plugin.

        $config = [
            'format' => 'Y-m-d',
            'defaultDate' => '2024-01-01',
            'minDate' => '2020-01-01',
            'maxDate' => '2030-01-01',
            'disabledDates' => ['2024-01-02'],
            'enabledDates' => ['2024-01-03'],
            'inline' => true,
            'sideBySide' => true,
            'useCurrent' => false,
            'viewMode' => 'days',
        ];

        $component = new Components\Form\InputDate(
            'name', null, null, null, null, null, null, null, null, $config
        );

        $pluginCfg = $component->makePluginConfig();

        $this->assertEquals('Y-m-d', $pluginCfg['dateFormat']);
        $this->assertEquals('2024-01-01', $pluginCfg['defaultDate']);
        $this->assertEquals('2020-01-01', $pluginCfg['minDate']);
        $this->assertEquals('2030-01-01', $pluginCfg['maxDate']);
        $this->assertEquals(['2024-01-02'], $pluginCfg['disable']);
        $this->assertEquals(['2024-01-03'], $pluginCfg['enable']);
        $this->assertTrue($pluginCfg['inline']);
        $this->assertTrue($pluginCfg['allowInput']);

        // The meaningless legacy properties are dropped, including the ones
        // that are kept on the component configuration.

        foreach (['icons', 'buttons', 'sideBySide', 'useCurrent', 'viewMode'] as $key) {
            $this->assertArrayNotHasKey($key, $pluginCfg);
        }

        // An invalid configuration is discarded.

        $component = new Components\Form\InputDate(
            'name', null, null, null, null, null, null, null, null, 'invalid'
        );

        $this->assertEquals(['icons', 'buttons'], array_keys($component->config));
    }

    public function testInputDateComponentRendersAGuardedPluginScript()
    {
        $html = $this->renderComponent(
            '<x-adminlte-input-date name="date" label="Date" value="2024-01-01"/>'
        );

        $this->assertStringContainsString('<input id="date" name="date"', $html);
        $this->assertStringContainsString('value="2024-01-01"', $html);
        $this->assertStringContainsString('class="form-control"', $html);

        $js = $this->renderPushedAssets();

        $this->assertGuardedPluginUsage(
            $js,
            "typeof window.flatpickr === 'undefined'",
            'window.flatpickr(el, usrCfg)'
        );

        $this->assertStringContainsString('_AdminLTE_InputDate', $js);
        $this->assertFreeOfJquery($js);
    }

    /*
    |--------------------------------------------------------------------------
    | Input file component tests.
    |--------------------------------------------------------------------------
    */

    public function testInputFileComponent()
    {
        $component = new Components\Form\InputFile('name', null, null, 'sm');
        $this->addErrorOnSessionFor('name');

        $iClass = $component->makeItemClass();

        $this->assertStringContainsString('form-control', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testInputFileComponentRendersTheNativeControl()
    {
        // Bootstrap 5 dropped the 'custom-file' structure and the
        // 'form-control-file' class, a file input is now a 'form-control'.
        // The native browse button can not be relabeled, so the legend is
        // rendered as an input group addon instead.

        $html = $this->renderComponent(
            '<x-adminlte-input-file name="doc" label="Document" legend="Browse"
                placeholder="Choose a file"/>'
        );

        $this->assertStringContainsString('<input type="file" id="doc" name="doc"', $html);
        $this->assertStringContainsString('class="form-control"', $html);
        $this->assertStringContainsString(
            '<label class="input-group-text" for="doc">Browse</label>',
            $html
        );

        // The placeholder is only accepted for backward compatibility.

        $this->assertStringNotContainsString('Choose a file', $html);

        $this->assertV4Markup($html);
        $this->assertFreeOfJquery($html);
    }

    /*
    |--------------------------------------------------------------------------
    | Input file Krajee component tests.
    |--------------------------------------------------------------------------
    */

    public function testInputFileKrajeeComponentBasic()
    {
        app()->setLocale('es');
        $component = new Components\Form\InputFileKrajee('name');

        // Test default values for the plugin configuration.

        $this->assertEquals('bs5', $component->config['theme']);
        $this->assertEquals('es', $component->config['language']);

        $this->assertStringNotContainsString(
            'input-group',
            $component->config['inputGroupClass']
        );

        // Test invalid feedback classes.

        $ifClass = $component->makeInvalidFeedbackClass();

        $this->assertStringContainsString('invalid-feedback', $ifClass);
        $this->assertStringContainsString('d-block', $ifClass);
    }

    public function testInputFileKrajeeComponentAdvanced()
    {
        app()->setLocale('en');

        $cfg = [
            'inputGroupClass' => 'ig-class-1',
            'theme' => 'theme-foo',
            'language' => 'br',
        ];

        $component = new Components\Form\InputFileKrajee(
            'name', null, null, 'lg', null, null, 'ig-class-2', null, null, $cfg
        );

        // Test default values for the plugin configuration.

        $this->assertEquals('theme-foo', $component->config['theme']);
        $this->assertEquals('br', $component->config['language']);

        $pluginIGroupClasses = explode(
            ' ',
            $component->config['inputGroupClass']
        );

        $this->assertContains('ig-class-1', $pluginIGroupClasses);
        $this->assertContains('ig-class-2', $pluginIGroupClasses);
        $this->assertContains('input-group-lg', $pluginIGroupClasses);
        $this->assertNotContains('input-group', $pluginIGroupClasses);
    }

    public function testInputFileKrajeeComponentWithPresets()
    {
        // Test avatar preset mode.

        $component = new Components\Form\InputFileKrajee(
            'name', null, null, null, null, null, null,
            null, null, null, 'avatar'
        );

        $ifClass = $component->makeInvalidFeedbackClass();

        $this->assertEquals('avatar', $component->presetMode);
        $this->assertStringContainsString('invalid-feedback', $ifClass);
        $this->assertStringContainsString('d-block', $ifClass);
        $this->assertStringContainsString('text-center', $ifClass);

        // Test minimalist preset mode.

        $component = new Components\Form\InputFileKrajee(
            'name', null, null, null, null, null, null,
            null, null, null, 'minimalist'
        );

        $ifClass = $component->makeInvalidFeedbackClass();

        $this->assertEquals('minimalist', $component->presetMode);
        $this->assertStringContainsString('invalid-feedback', $ifClass);
        $this->assertStringContainsString('d-block', $ifClass);
        $this->assertStringNotContainsString('text-center', $ifClass);
    }

    public function testInputFileKrajeeComponentRendersItsOwnFormGroup()
    {
        // This component does not extend the input group layout, the plugin
        // generates the 'input-group' structure on its own.

        $html = $this->renderComponent(
            '<x-adminlte-input-file-krajee name="doc" label="Document"
                fgroup-class="w-50" label-class="text-danger"/>'
        );

        $this->assertStringContainsString('<div class="mb-3 w-50">', $html);
        $this->assertStringContainsString(
            '<label for="doc" class="form-label text-danger">',
            $html
        );

        $this->assertStringContainsString('<input type="file" id="doc" name="doc"', $html);
        $this->assertStringContainsString('class="form-control"', $html);
        $this->assertStringNotContainsString('<div class="input-group', $html);

        $this->assertV4Markup($html);
        $this->assertFreeOfJquery($html);
    }

    public function testInputFileKrajeeComponentRendersAGuardedPluginScript()
    {
        $this->renderComponent('<x-adminlte-input-file-krajee name="doc"/>');

        $js = $this->renderPushedAssets();

        // The Krajee file input plugin still requires jQuery, so both the
        // library and the plugin availability must be checked first.

        $this->assertGuardedPluginUsage(
            $js,
            'if (window.jQuery) {',
            "$('#doc').fileinput"
        );

        $this->assertStringContainsString(
            "typeof $.fn.fileinput === 'undefined'",
            $js
        );

        // The plugin is forced to generate Bootstrap 5 markup.

        $this->assertStringContainsString('"bsVersion":"5"', $js);
        $this->assertStringContainsString('"theme":"bs5"', $js);
    }

    public function testInputFileKrajeeComponentRendersTheInvalidFeedback()
    {
        $this->setValidationErrorsFor('doc');

        $html = $this->renderComponent('<x-adminlte-input-file-krajee name="doc"/>');

        $this->assertStringContainsString('class="invalid-feedback d-block"', $html);
        $this->assertStringContainsString('The doc value is invalid.', $html);

        // The plugin markup is patched on the client side.

        $js = $this->renderPushedAssets();

        $this->assertStringContainsString('adminlte-invalid-krajee-preview', $js);
        $this->assertStringContainsString('.file-caption-name', $js);
    }

    /*
    |--------------------------------------------------------------------------
    | Input slider component tests.
    |--------------------------------------------------------------------------
    */

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

    public function testInputSliderComponentOldSupport()
    {
        // Test component with old support disabled.

        $component = new Components\Form\InputSlider('name');
        $oVal = $component->getOldValue('name', 'default');

        $this->assertEquals('default', $oVal);

        // Test component with old support enabled.

        $component = new Components\Form\InputSlider(
            'name', null, null, null, null, null, null,
            null, null, null, null, true
        );

        $this->addInputOnCurrentRequest('name', 'foo');
        $oVal = $component->getOldValue('name', 'default');

        $this->assertEquals('foo', $oVal);
    }

    public function testInputSliderComponentTranslatesTheLegacyConfiguration()
    {
        // On AdminLTE v4 the jQuery 'bootstrap-slider' plugin was replaced by
        // the vanilla javascript 'noUiSlider' plugin.

        $config = [
            'min' => 5,
            'max' => 50,
            'step' => 5,
            'value' => 25,
            'orientation' => 'vertical',
            'reversed' => true,
            'tooltip' => 'always',
            'precision' => 2,
            'ticks' => [1, 2],
            'handle' => 'round',
        ];

        $component = new Components\Form\InputSlider(
            'name', null, null, null, null, null, null, null, null, $config
        );

        $pluginCfg = $component->makePluginConfig();

        $this->assertEquals(['min' => 5.0, 'max' => 50.0], $pluginCfg['range']);
        $this->assertEquals(5.0, $pluginCfg['step']);
        $this->assertEquals([25.0], $pluginCfg['start']);
        $this->assertEquals('lower', $pluginCfg['connect']);
        $this->assertEquals('vertical', $pluginCfg['orientation']);
        $this->assertEquals('rtl', $pluginCfg['direction']);
        $this->assertTrue($pluginCfg['tooltips']);

        // The meaningless legacy properties are dropped.

        foreach (['precision', 'ticks', 'handle', 'value', 'id'] as $key) {
            $this->assertArrayNotHasKey($key, $pluginCfg);
        }

        // The legacy 'tooltip' => 'hide' value disables the tooltips.

        $component = new Components\Form\InputSlider(
            'name', null, null, null, null, null, null, null, null,
            ['tooltip' => 'hide']
        );

        $this->assertFalse($component->makePluginConfig()['tooltips']);
    }

    public function testInputSliderComponentDualHandleSupport()
    {
        // The legacy 'range' property enabled a dual handle slider, while the
        // 'noUiSlider' property with the same name holds the min/max values.

        $component = new Components\Form\InputSlider(
            'name', null, null, null, null, null, null, null, null,
            ['range' => true, 'min' => 0, 'max' => 100]
        );

        $pluginCfg = $component->makePluginConfig();

        $this->assertEquals([0.0, 100.0], $pluginCfg['start']);
        $this->assertEquals([false, true, false], $pluginCfg['connect']);
        $this->assertEquals(['min' => 0.0, 'max' => 100.0], $pluginCfg['range']);

        // An array valued 'range' is forwarded as is.

        $component = new Components\Form\InputSlider(
            'name', null, null, null, null, null, null, null, null,
            ['range' => ['min' => 10, 'max' => 20]]
        );

        $pluginCfg = $component->makePluginConfig();

        $this->assertEquals(['min' => 10, 'max' => 20], $pluginCfg['range']);
        $this->assertEquals([10.0], $pluginCfg['start']);

        // A comma separated value defines the position of every handle.

        $component = new Components\Form\InputSlider(
            'name', null, null, null, null, null, null, null, null,
            ['value' => '10,20']
        );

        $this->assertEquals([10.0, 20.0], $component->makeStartValue());
    }

    public function testInputSliderComponentRendersAGuardedPluginScript()
    {
        $html = $this->renderComponent(
            '<x-adminlte-input-slider name="slider" label="Slider" color="red"/>'
        );

        // The plugin renders into a plain DOM element, so the submitted value
        // is held by a hidden input.

        $this->assertStringContainsString(
            '<input type="hidden" id="slider" name="slider"',
            $html
        );

        $this->assertStringContainsString(
            '<div id="slider-slider" class="adminlte-slider flex-fill align-self-center" >',
            $html
        );

        $assets = $this->renderPushedAssets();

        $this->assertGuardedPluginUsage(
            $assets,
            "typeof window.noUiSlider === 'undefined'",
            'window.noUiSlider.create(target, usrCfg)'
        );

        // The color attribute is applied through a dedicated style block.

        $this->assertStringContainsString('#slider-slider .noUi-connect', $assets);
        $this->assertStringContainsString('background: red', $assets);

        $this->assertV4Markup($html);
        $this->assertFreeOfJquery($assets);
    }

    public function testInputSliderComponentSupportsTheHtmlAttributes()
    {
        // The min, max, step, value and disabled attributes are alternatives
        // to the related plugin configuration properties.

        $this->renderComponent(
            '<x-adminlte-input-slider name="slider" min="2" max="8" step="2"
                value="4" disabled/>'
        );

        $js = $this->renderPushedAssets();

        $this->assertStringContainsString('usrCfg.range.min = Number( "2" )', $js);
        $this->assertStringContainsString('usrCfg.range.max = Number( "8" )', $js);
        $this->assertStringContainsString('usrCfg.step = Number( "2" )', $js);
        $this->assertStringContainsString('usrCfg.start = attrValue', $js);
        $this->assertStringContainsString("target.setAttribute('disabled', true)", $js);
    }

    public function testInputSliderComponentVerticalOrientation()
    {
        $component = new Components\Form\InputSlider(
            'name', null, null, null, null, null, null, null, null,
            ['orientation' => 'vertical']
        );

        $this->assertStringContainsString(
            'adminlte-slider-vertical',
            $component->makeSliderClass()
        );

        // The slider id can be overwritten through the configuration.

        $component = new Components\Form\InputSlider(
            'name', null, null, null, null, null, null, null, null,
            ['id' => 'custom-slider']
        );

        $this->assertEquals('custom-slider', $component->config['id']);
    }

    /*
    |--------------------------------------------------------------------------
    | Input switch component tests.
    |--------------------------------------------------------------------------
    */

    public function testInputSwitchComponentCheckedState()
    {
        // Test the state property isn't defined when is-checked attribute
        // isn't provided.

        $component = new Components\Form\InputSwitch('name');

        $this->assertArrayNotHasKey('state', $component->config);

        // Test the state property is true when is-checked attribute has a
        // truthy value.

        foreach ([true, 1, 'true'] as $v) {
            $component = new Components\Form\InputSwitch(
                'name', null, null, null, null, null, null, null, null, null, $v
            );

            $this->assertTrue($component->config['state']);
        }

        // Test the state property is false when is-checked attribute has a
        // falsy value.

        foreach ([false, 0, ''] as $v) {
            $component = new Components\Form\InputSwitch(
                'name', null, null, null, null, null, null, null, null, null, $v
            );

            $this->assertFalse($component->config['state']);
        }
    }

    public function testInputSwitchComponentWithErrorStyle()
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

    public function testInputSwitchComponentOldSupport()
    {
        // Test component with old support disabled.

        $component = new Components\Form\InputSwitch('name');
        $oVal = $component->getOldValue('name', 'default');

        $this->assertEquals('default', $oVal);

        // Test component with old support enabled.

        $component = new Components\Form\InputSwitch(
            'name', null, null, null, null, null, null, null, null, null, null, true
        );

        $this->addInputOnCurrentRequest('name', 'foo');
        $oVal = $component->getOldValue('name', 'default');

        $this->assertEquals('foo', $oVal);
    }

    public function testInputSwitchComponentWrapperIsANativeBootstrapSwitch()
    {
        // Regression: the Bootstrap 5.3 switch pairs a left padding on the
        // wrapper with a negative margin on the input, so the wrapper must be
        // exactly a 'form-check form-switch' element.

        $component = new Components\Form\InputSwitch('name');

        $this->assertEquals(
            'form-check form-switch',
            $component->makeSwitchWrapperClass()
        );

        $html = $this->renderComponent('<x-adminlte-input-switch name="active"/>');

        $this->assertStringContainsString('<div class="form-check form-switch">', $html);
        $this->assertStringContainsString(
            '<input type="checkbox" role="switch" id="active" name="active"',
            $html
        );

        $this->assertStringContainsString('class="form-check-input"', $html);
        $this->assertStringContainsString('value="true"', $html);

        // The legacy 'Bootstrap Switch' plugin is not required anymore.

        $this->assertV4Markup($html);
        $this->assertFreeOfJquery($html);
        $this->assertFreeOfJquery($this->renderPushedAssets());
    }

    public function testInputSwitchComponentHonoursTheLegacyConfiguration()
    {
        // Only a few of the legacy plugin properties are still honoured.

        $html = $this->renderComponent(
            '<x-adminlte-input-switch name="active" is-checked
                :config="[\'disabled\' => true, \'readonly\' => true,
                    \'onColor\' => \'success\', \'labelText\' => \'Enabled\']"/>'
        );

        $this->assertStringContainsString('checked', $html);
        $this->assertStringContainsString('disabled', $html);
        $this->assertStringContainsString('readonly', $html);
        $this->assertStringContainsString(
            '<label class="form-check-label mb-0" for="active">',
            $html
        );

        $this->assertStringContainsString('Enabled', $html);

        // The checked state color is applied through a dedicated style block.

        $css = $this->renderPushedAssets();

        $this->assertStringContainsString('#active.form-check-input:checked', $css);
        $this->assertStringContainsString('var(--bs-success)', $css);
    }

    public function testInputSwitchComponentIgnoresTheMeaninglessConfiguration()
    {
        // The remaining legacy properties are accepted and silently ignored.

        $config = [
            'size' => 'large', 'animate' => false, 'handleWidth' => 100,
            'inverse' => true, 'offColor' => 'danger', 'offText' => 'Off',
            'wrapperClass' => 'legacy-wrapper',
        ];

        $component = new Components\Form\InputSwitch(
            'name', null, null, null, null, null, null, null, null, $config
        );

        $this->assertNull($component->getSwitchColor());
        $this->assertNull($component->getSwitchLabel());
        $this->assertEquals('form-check form-switch', $component->makeSwitchWrapperClass());

        $html = $this->renderComponent(
            '<x-adminlte-input-switch name="active"
                :config="[\'wrapperClass\' => \'legacy-wrapper\', \'offText\' => \'Off\']"/>'
        );

        $this->assertStringNotContainsString('legacy-wrapper', $html);
        $this->assertStringNotContainsString('Off', $html);
    }

    public function testInputSwitchComponentLabelAndColorSources()
    {
        // The label falls back to the legacy 'onText' property.

        $component = new Components\Form\InputSwitch(
            'name', null, null, null, null, null, null, null, null,
            ['onText' => 'On &amp; running']
        );

        $this->assertEquals('On & running', $component->getSwitchLabel());

        // The 'labelText' property takes precedence.

        $component = new Components\Form\InputSwitch(
            'name', null, null, null, null, null, null, null, null,
            ['labelText' => 'Label', 'onText' => 'On']
        );

        $this->assertEquals('Label', $component->getSwitchLabel());

        // Only the Bootstrap theme names are accepted as the switch color.

        foreach (['primary', 'secondary', 'success', 'info', 'warning', 'danger', 'light', 'dark'] as $color) {
            $component = new Components\Form\InputSwitch(
                'name', null, null, null, null, null, null, null, null,
                ['onColor' => $color]
            );

            $this->assertEquals($color, $component->getSwitchColor());
        }

        $component = new Components\Form\InputSwitch(
            'name', null, null, null, null, null, null, null, null,
            ['onColor' => 'lightblue']
        );

        $this->assertNull($component->getSwitchColor());
    }

    public function testInputSwitchComponentChecksTheOldValueOnValidationErrors()
    {
        // When the old value support is enabled and the form was submitted
        // with errors, the previously submitted state takes precedence.

        $component = new Components\Form\InputSwitch(
            'name', null, null, null, null, null, null, null, null,
            ['state' => true], null, true
        );

        $msgBag = new MessageBag();
        $msgBag->add('name', 'error');
        $component->setErrorsBag($msgBag);

        $this->assertFalse($component->isChecked());

        $this->addInputOnCurrentRequest('name', 'true');
        $this->assertTrue($component->isChecked());
    }

    /*
    |--------------------------------------------------------------------------
    | Options component tests.
    |--------------------------------------------------------------------------
    */

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

    public function testOptionsComponentRendersEveryAttribute()
    {
        // Test all the constructor arguments at once:
        // $options, $selected, $disabled, $strict, $emptyOption, $placeholder.

        $html = $this->renderComponent(
            '<x-adminlte-options :options="[\'a\' => \'A\', \'b\' => \'B\']"
                :selected="[\'a\']" :disabled="[\'b\']" strict
                empty-option="None"/>'
        );

        $this->assertStringContainsString('None', $html);
        $this->assertStringContainsString('value="a"', $html);
        $this->assertStringContainsString('selected', $html);
        $this->assertStringContainsString('disabled', $html);

        $this->assertV4Markup($html);
    }

    public function testOptionsComponentStrictComparison()
    {
        $options = [0 => 'Zero', 1 => 'One'];

        // Without the strict attribute, a loose comparison is used, so the
        // string '1' matches the integer key 1.

        $component = new Components\Form\Options($options, '1');
        $this->assertTrue($component->isSelected(1));

        // The strict attribute enables a type safe comparison.

        $component = new Components\Form\Options($options, '1', null, true);
        $this->assertFalse($component->isSelected(1));

        $component = new Components\Form\Options($options, [1], null, true);
        $this->assertTrue($component->isSelected(1));

        // The selected and disabled attributes accept a scalar value.

        $component = new Components\Form\Options($options, 1, 0);
        $this->assertTrue($component->isSelected(1));
        $this->assertTrue($component->isDisabled(0));
    }

    public function testOptionsComponentEmptyOptionTakesPrecedenceOverPlaceholder()
    {
        $html = $this->renderComponent(
            '<x-adminlte-options :options="[\'a\' => \'A\']" empty-option="None"
                placeholder="Pick one"/>'
        );

        $this->assertStringContainsString('None', $html);
        $this->assertStringNotContainsString('Pick one', $html);
        $this->assertStringNotContainsString('d-none', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Select component tests.
    |--------------------------------------------------------------------------
    */

    public function testSelectComponent()
    {
        $component = new Components\Form\Select('name');

        $this->addErrorOnSessionFor('name');

        $iClass = $component->makeItemClass();

        $this->assertStringContainsString('form-select', $iClass);
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

    public function testSelectComponentRendersAFormSelectElement()
    {
        // Bootstrap 5 replaced the legacy 'custom-select' class of the select
        // elements with the 'form-select' one.

        $html = $this->renderComponent(
            '<x-adminlte-select name="gender" label="Gender" multiple>'.
            '<option value="m">Male</option>'.
            '</x-adminlte-select>'
        );

        $this->assertStringContainsString('<select id="gender" name="gender"', $html);
        $this->assertStringContainsString('class="form-select"', $html);
        $this->assertStringContainsString('multiple', $html);
        $this->assertStringContainsString('<option value="m">Male</option>', $html);

        $this->assertV4Markup($html);
        $this->assertFreeOfJquery($html);

        // Without validation errors there is no javascript at all, only the
        // shared style block of the input group layout.

        $this->assertStringNotContainsString('<script', $this->renderPushedAssets());
    }

    public function testSelectComponentRestoresTheOldValuesWithVanillaJavascript()
    {
        $this->setValidationErrorsFor('gender');
        $this->addInputOnCurrentRequest('gender', 'm');

        $this->renderComponent(
            '<x-adminlte-select name="gender" enable-old-support>'.
            '<option value="m">Male</option>'.
            '</x-adminlte-select>'
        );

        $js = $this->renderPushedAssets();

        $this->assertStringContainsString('Array.from(el.options)', $js);
        $this->assertStringContainsString('opt.selected', $js);
        $this->assertFreeOfJquery($js);
    }

    /*
    |--------------------------------------------------------------------------
    | Select2 component tests.
    |--------------------------------------------------------------------------
    */

    public function testSelect2Component()
    {
        $component = new Components\Form\Select2('name');

        $this->addErrorOnSessionFor('name');

        $iClass = $component->makeItemClass();

        $this->assertStringContainsString('form-select', $iClass);
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

    public function testSelect2ComponentDefaultsToThePluginDefaultTheme()
    {
        // The AdminLTE v4 compatibility stylesheet styles the plugin 'default'
        // theme, so it is used unless another one is explicitly configured.

        $component = new Components\Form\Select2('name');
        $this->assertEquals('default', $component->config['theme']);

        $component = new Components\Form\Select2(
            'name', null, null, null, null, null, null, null, null,
            ['theme' => 'bootstrap-5']
        );

        $this->assertEquals('bootstrap-5', $component->config['theme']);

        // An invalid configuration is discarded.

        $component = new Components\Form\Select2(
            'name', null, null, null, null, null, null, null, null, 'invalid'
        );

        $this->assertEquals(['theme' => 'default'], $component->config);
    }

    public function testSelect2ComponentRendersAGuardedPluginScript()
    {
        $html = $this->renderComponent(
            '<x-adminlte-select2 name="gender" label="Gender"/>'
        );

        $this->assertStringContainsString('<select id="gender" name="gender"', $html);
        $this->assertStringContainsString('class="form-select"', $html);

        $assets = $this->renderPushedAssets();

        // The Select2 plugin still requires jQuery, so both the library and
        // the plugin availability must be checked first.

        $this->assertGuardedPluginUsage(
            $assets,
            'if (window.jQuery) {',
            "$('#gender').select2"
        );

        $this->assertStringContainsString("typeof $.fn.select2 === 'undefined'", $assets);

        // The plugin container behaves as a Bootstrap 5 input group item.

        $this->assertStringContainsString('.input-group > .select2-container', $assets);

        $this->assertV4Markup($html);
        $this->assertFreeOfJquery($html);
    }

    /*
    |--------------------------------------------------------------------------
    | SelectBs component tests.
    |--------------------------------------------------------------------------
    */

    public function testSelectBsComponent()
    {
        $component = new Components\Form\SelectBs('name', null, null, 'lg');

        $this->addErrorOnSessionFor('name');

        $iClass = $component->makeItemClass();

        $this->assertStringContainsString('form-select', $iClass);
        $this->assertStringContainsString('form-select-lg', $iClass);
        $this->assertStringContainsString('is-invalid', $iClass);
    }

    public function testSelectBsComponentOldSupport()
    {
        // Test component with old support disabled.

        $component = new Components\Form\SelectBs('name');
        $oVal = $component->getOldValue('name', 'default');

        $this->assertEquals('default', $oVal);

        // Test component with old support enabled.

        $component = new Components\Form\SelectBs(
            'name', null, null, null, null, null, null, null, null, null, true
        );

        $this->addInputOnCurrentRequest('name', 'foo');
        $oVal = $component->getOldValue('name', 'default');

        $this->assertEquals('foo', $oVal);
    }

    public function testSelectBsComponentTranslatesTheLegacyConfiguration()
    {
        // On AdminLTE v4 the jQuery 'bootstrap-select' plugin was replaced by
        // the vanilla javascript 'Tom Select' plugin.

        $config = [
            'title' => 'Pick one',
            'maxOptions' => 5,
            'maxItems' => 2,
            'liveSearch' => true,
            'actionsBox' => true,
            'style' => 'btn-primary',
            'size' => 4,
        ];

        $component = new Components\Form\SelectBs(
            'name', null, null, null, null, null, null, null, null, $config
        );

        $this->assertEquals('Pick one', $component->config['placeholder']);
        $this->assertEquals(5, $component->config['maxOptions']);
        $this->assertEquals(2, $component->config['maxItems']);

        // The meaningless legacy properties are dropped.

        foreach (['title', 'liveSearch', 'actionsBox', 'style', 'size'] as $key) {
            $this->assertArrayNotHasKey($key, $component->config);
        }

        // The 'noneSelectedText' property is also mapped to the placeholder.

        $component = new Components\Form\SelectBs(
            'name', null, null, null, null, null, null, null, null,
            ['noneSelectedText' => 'Nothing']
        );

        $this->assertEquals('Nothing', $component->config['placeholder']);

        // An invalid configuration is discarded.

        $component = new Components\Form\SelectBs(
            'name', null, null, null, null, null, null, null, null, 'invalid'
        );

        $this->assertEquals([], $component->config);
    }

    public function testSelectBsComponentDegradesToANativeSelect()
    {
        $html = $this->renderComponent(
            '<x-adminlte-select-bs name="gender" label="Gender" igroup-size="sm"/>'
        );

        // Without the plugin, the element stays a native Bootstrap 5 select.

        $this->assertStringContainsString('<select id="gender" name="gender"', $html);
        $this->assertStringContainsString('class="form-select form-select-sm"', $html);

        $js = $this->renderPushedAssets();

        $this->assertGuardedPluginUsage(
            $js,
            "typeof window.TomSelect === 'undefined'",
            'new window.TomSelect(el, usrCfg)'
        );

        $this->assertV4Markup($html);
        $this->assertFreeOfJquery($js);
    }

    /*
    |--------------------------------------------------------------------------
    | Text editor component tests.
    |--------------------------------------------------------------------------
    */

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

    public function testTextEditorComponentOldSupport()
    {
        // Test component with old support disabled.

        $component = new Components\Form\TextEditor('name');
        $oVal = $component->getOldValue('name', 'default');

        $this->assertEquals('default', $oVal);

        // Test component with old support enabled.

        $component = new Components\Form\TextEditor(
            'name', null, null, null, null, null, null, null, null, null, true
        );

        $this->addInputOnCurrentRequest('name', 'foo');
        $oVal = $component->getOldValue('name', 'default');

        $this->assertEquals('foo', $oVal);
    }

    public function testTextEditorComponentTranslatesTheLegacyConfiguration()
    {
        // On AdminLTE v4 the jQuery 'Summernote' plugin was replaced by the
        // vanilla javascript 'Quill' plugin.

        $config = [
            'height' => 250,
            'airMode' => true,
            'toolbar' => [['style', ['bold']]],
            'lang' => 'es-ES',
            'placeholder' => 'Write here',
        ];

        $component = new Components\Form\TextEditor(
            'name', null, null, null, null, null, null, null, null, $config
        );

        $pluginCfg = $component->makePluginConfig();

        $this->assertEquals('snow', $pluginCfg['theme']);
        $this->assertEquals('Write here', $pluginCfg['placeholder']);
        $this->assertArrayHasKey('toolbar', $pluginCfg['modules']);

        // The meaningless legacy properties are dropped.

        foreach (['height', 'airMode', 'toolbar', 'lang', 'width'] as $key) {
            $this->assertArrayNotHasKey($key, $pluginCfg);
        }

        // The legacy 'height' property is honoured through a style block.

        $this->assertEquals('250px', $component->makeEditorHeight());
        $this->assertEquals('name-editor', $component->makeEditorId());

        // A custom 'modules' configuration replaces the default toolbar.

        $component = new Components\Form\TextEditor(
            'name', null, null, null, null, null, null, null, null,
            ['modules' => ['toolbar' => false], 'theme' => 'bubble']
        );

        $pluginCfg = $component->makePluginConfig();

        $this->assertEquals('bubble', $pluginCfg['theme']);
        $this->assertFalse($pluginCfg['modules']['toolbar']);
    }

    public function testTextEditorComponentHeightAttribute()
    {
        // A numeric height is interpreted as pixels, a string is used as is.

        $heights = [250 => '250px', '20rem' => '20rem', '50%' => '50%'];

        foreach ($heights as $height => $expected) {
            $component = new Components\Form\TextEditor(
                'name', null, null, null, null, null, null, null, null,
                ['height' => $height]
            );

            $this->assertEquals($expected, $component->makeEditorHeight());
        }

        // Without a height, no style block is generated.

        $component = new Components\Form\TextEditor('name');
        $this->assertNull($component->makeEditorHeight());
    }

    public function testTextEditorComponentRendersAGuardedPluginScript()
    {
        $html = $this->renderComponent(
            '<x-adminlte-text-editor name="body" label="Body"
                :config="[\'height\' => 300]">Hello</x-adminlte-text-editor>'
        );

        // The underlying textarea is hidden, it only holds the value that will
        // be submitted with the form.

        $this->assertStringContainsString('<textarea id="body" name="body"', $html);
        $this->assertStringContainsString('class="d-none"', $html);
        $this->assertStringContainsString(
            '<div class="adminlte-text-editor form-control p-0">',
            $html
        );

        $this->assertStringContainsString('<div id="body-editor"></div>', $html);

        $assets = $this->renderPushedAssets();

        $this->assertGuardedPluginUsage(
            $assets,
            "typeof window.Quill === 'undefined'",
            'new window.Quill(target, usrCfg)'
        );

        $this->assertStringContainsString('#body-editor .ql-editor', $assets);
        $this->assertStringContainsString('min-height: 300px', $assets);

        $this->assertV4Markup($html);
        $this->assertFreeOfJquery($assets);
    }

    public function testTextEditorComponentSupportsTheHtmlAttributes()
    {
        $this->renderComponent(
            '<x-adminlte-text-editor name="body" placeholder="Write here" readonly/>'
        );

        $js = $this->renderPushedAssets();

        $this->assertStringContainsString('usrCfg.placeholder', $js);
        $this->assertStringContainsString('Write here', $js);
        $this->assertStringContainsString('usrCfg.readOnly = true', $js);
    }

    /*
    |--------------------------------------------------------------------------
    | Textarea component tests.
    |--------------------------------------------------------------------------
    */

    public function testTextareaComponentRendering()
    {
        $html = $this->renderComponent(
            '<x-adminlte-textarea name="bio" label="Bio" rows="5">'.
            'Some content'.
            '</x-adminlte-textarea>'
        );

        $this->assertStringContainsString('<textarea id="bio" name="bio"', $html);
        $this->assertStringContainsString('class="form-control"', $html);
        $this->assertStringContainsString('rows="5"', $html);
        $this->assertStringContainsString('Some content', $html);

        $this->assertV4Markup($html);
        $this->assertFreeOfJquery($html);
    }

    public function testTextareaComponentOldSupport()
    {
        // Test component with old support disabled.

        $component = new Components\Form\Textarea('name');
        $this->assertEquals('default', $component->getOldValue('name', 'default'));

        // Test component with old support enabled.

        $component = new Components\Form\Textarea(
            'name', null, null, null, null, null, null, null, null, true
        );

        $this->addInputOnCurrentRequest('name', 'foo');
        $this->assertEquals('foo', $component->getOldValue('name', 'default'));

        // The old value replaces the slot content on the rendered markup.

        $this->addInputOnCurrentRequest('bio', 'The old bio');

        $html = $this->renderComponent(
            '<x-adminlte-textarea name="bio" enable-old-support>'.
            'The slot content'.
            '</x-adminlte-textarea>'
        );

        $this->assertStringContainsString('The old bio', $html);
        $this->assertStringNotContainsString('The slot content', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Input component tests.
    |--------------------------------------------------------------------------
    */

    public function testInputComponentRendering()
    {
        $html = $this->renderComponent(
            '<x-adminlte-input name="email" label="Email" type="email"
                placeholder="me@example.com" value="a@b.c" required/>'
        );

        $this->assertStringContainsString('<input id="email" name="email"', $html);
        $this->assertStringContainsString('value="a@b.c"', $html);
        $this->assertStringContainsString('type="email"', $html);
        $this->assertStringContainsString('placeholder="me@example.com"', $html);
        $this->assertStringContainsString('required', $html);
        $this->assertStringContainsString('class="form-control"', $html);

        $this->assertV4Markup($html);
        $this->assertFreeOfJquery($html);
    }

    public function testInputComponentOldSupport()
    {
        // Test component with old support disabled.

        $component = new Components\Form\Input('name');
        $this->assertEquals('default', $component->getOldValue('name', 'default'));

        // Test component with old support enabled.

        $component = new Components\Form\Input(
            'name', null, null, null, null, null, null, null, null, true
        );

        $this->addInputOnCurrentRequest('name', 'foo');
        $this->assertEquals('foo', $component->getOldValue('name', 'default'));

        // The old value replaces the value attribute on the rendered markup.

        $this->addInputOnCurrentRequest('email', 'old@value.com');

        $html = $this->renderComponent(
            '<x-adminlte-input name="email" value="new@value.com" enable-old-support/>'
        );

        $this->assertStringContainsString('value="old@value.com"', $html);
        $this->assertStringNotContainsString('new@value.com', $html);
    }

    public function testDateRangeTranslatesTheLegacyConfiguration()
    {
        $component = new Components\Form\DateRange('r', null, null, null, null, null, null, null, null, [
            'locale' => ['format' => 'd/m/Y', 'separator' => ' to '],
            'timePicker' => true,
            'timePicker24Hour' => true,
            'singleDatePicker' => true,
            'minDate' => '2026-01-01',
        ]);

        $config = $component->makePluginConfig();

        $this->assertEquals('d/m/Y', $config['dateFormat']);
        $this->assertEquals(' to ', $config['locale']['rangeSeparator']);
        $this->assertEquals('single', $config['mode']);
        $this->assertTrue($config['enableTime']);
        $this->assertTrue($config['time_24hr']);
        $this->assertEquals('2026-01-01', $config['minDate']);
        $this->assertTrue($config['allowInput']);
    }

    public function testInputSliderTranslatesTheLegacyConfiguration()
    {
        $component = new Components\Form\InputSlider('s', null, null, null, null, null, null, null, null, [
            'min' => 5,
            'max' => 50,
            'step' => 5,
            'value' => [10, 20],
            'orientation' => 'vertical',
            'reversed' => true,
            'tooltip' => 'hide',
        ]);

        $config = $component->makePluginConfig();

        $this->assertEquals(['min' => 5.0, 'max' => 50.0], $config['range']);
        $this->assertEquals(5.0, $config['step']);
        $this->assertEquals([false, true, false], $config['connect']);
        $this->assertEquals('vertical', $config['orientation']);
        $this->assertEquals('rtl', $config['direction']);
        $this->assertFalse($config['tooltips']);
    }

    public function testTheValidationStateIsWiredToTheFeedbackBlock()
    {
        $component = new Components\Form\Input('fname', 'fid');

        // Without errors the control declares no validation state.

        $attrs = $component->makeItemAttributes();

        $this->assertArrayNotHasKey('aria-invalid', $attrs);
        $this->assertArrayNotHasKey('aria-describedby', $attrs);

        // With errors it announces the state and points at the feedback block.

        $this->addErrorOnSessionFor('fname');
        $attrs = $component->makeItemAttributes();

        $this->assertEquals('true', $attrs['aria-invalid']);
        $this->assertEquals('fid-error', $attrs['aria-describedby']);
        $this->assertEquals('fid-error', $component->makeInvalidFeedbackId());
    }

    public function testTheItemAttributesKeepTheItemClassAndTheExtras()
    {
        $component = new Components\Form\Input('fname', 'fid');

        $attrs = $component->makeItemAttributes(['type' => 'color']);

        $this->assertEquals($component->makeItemClass(), $attrs['class']);
        $this->assertEquals('color', $attrs['type']);
    }

    public function testTheValidationStateReachesEveryInputGroupComponent()
    {
        $this->addErrorOnSessionFor('fname');

        $components = [
            new Components\Form\Input('fname', 'fid'),
            new Components\Form\Textarea('fname', 'fid'),
            new Components\Form\Select('fname', 'fid'),
            new Components\Form\SelectBs('fname', 'fid'),
            new Components\Form\Select2('fname', 'fid'),
            new Components\Form\InputColor('fname', 'fid'),
            new Components\Form\InputDate('fname', 'fid'),
            new Components\Form\InputFile('fname', 'fid'),
            new Components\Form\InputSwitch('fname', 'fid'),
            new Components\Form\DateRange('fname', 'fid'),
            new Components\Form\TextEditor('fname', 'fid'),
        ];

        foreach ($components as $component) {
            $attrs = $component->makeItemAttributes();
            $name = get_class($component);

            $this->assertEquals('true', $attrs['aria-invalid'] ?? null, $name);
            $this->assertEquals('fid-error', $attrs['aria-describedby'] ?? null, $name);
        }
    }

    public function testTheSliderWiresItsPluginElementToTheFeedbackBlock()
    {
        $this->addErrorOnSessionFor('fname');

        $component = new Components\Form\InputSlider('fname', 'fid');
        $attrs = (string) $component->makeSliderAttributes();

        $this->assertStringContainsString('aria-invalid="true"', $attrs);
        $this->assertStringContainsString('aria-describedby="fid-error"', $attrs);
    }

    public function testTheSliderExposesTheAttributesOfItsPluginElement()
    {
        $html = $this->renderComponent(
            '<x-adminlte-input-slider name="fname" id="fid"
                :slider-attributes="[\'wire:ignore\' => \'\', \'data-x\' => \'y\']"/>'
        );

        $this->assertStringContainsString('wire:ignore', $html);
        $this->assertStringContainsString('data-x="y"', $html);
    }

    public function testTheButtonRendersItsSlotContent()
    {
        $html = $this->renderComponent(
            '<x-adminlte-button label="Save"><span class="badge">3</span></x-adminlte-button>'
        );

        $this->assertStringContainsString('Save', $html);
        $this->assertStringContainsString('<span class="badge">3</span>', $html);
    }

    public function testTheSliderAttributesAreSeparatedFromTheClass()
    {
        // The attribute bag carries no leading space of its own, so without
        // one the markup collapses into class="…"wire:ignore="".

        $html = $this->renderComponent(
            '<x-adminlte-input-slider name="fname" id="fid"
                :slider-attributes="[\'wire:ignore\' => \'\']"/>'
        );

        $this->assertStringNotContainsString('"wire:ignore', $html);
        $this->assertMatchesRegularExpression(
            '/class="adminlte-slider[^"]*"\s+wire:ignore/',
            $html
        );
    }

    public function testABareEmptyOptionAndPlaceholderCarryNoLabel()
    {
        // The bare attribute reaches the component as a boolean, and the
        // entity decoder used to turn it into the literal '1'.

        foreach (['empty-option', 'placeholder'] as $attribute) {
            $html = $this->renderComponent(
                '<x-adminlte-options :options="[\'a\' => \'A\']" '.$attribute.'/>'
            );

            preg_match('/<option[^>]*value>\s*([^<]*)</', $html, $matches);

            $this->assertSame('', trim($matches[1] ?? 'no match'), $attribute);
        }
    }

    public function testAStringEmptyOptionAndPlaceholderKeepTheirLabel()
    {
        foreach (['empty-option', 'placeholder'] as $attribute) {
            $html = $this->renderComponent(
                '<x-adminlte-options :options="[\'a\' => \'A\']" '.$attribute.'="Pick one"/>'
            );

            $this->assertStringContainsString('Pick one', $html, $attribute);
        }
    }

    public function testTheTextEditorRendersEveryInheritedSlot()
    {
        $html = $this->renderComponent(
            '<x-adminlte-text-editor name="f" id="f">'.
            '<x-slot name="prependSlot"><span>PRE</span></x-slot>'.
            '<x-slot name="appendSlot"><span>APP</span></x-slot>'.
            '<x-slot name="bottomSlot"><span>BOT</span></x-slot>'.
            '</x-adminlte-text-editor>'
        );

        foreach (['PRE', 'APP', 'BOT'] as $marker) {
            $this->assertStringContainsString($marker, $html, $marker);
        }
    }

    public function testTheKrajeeInputDiscardsTheInheritedSlots()
    {
        // The component does not extend the base layout, because the plugin
        // builds its own input group. The documentation warns about it.

        $html = $this->renderComponent(
            '<x-adminlte-input-file-krajee name="f" id="f">'.
            '<x-slot name="prependSlot"><span>PRE</span></x-slot>'.
            '<x-slot name="appendSlot"><span>APP</span></x-slot>'.
            '<x-slot name="bottomSlot"><span>BOT</span></x-slot>'.
            '</x-adminlte-input-file-krajee>'
        );

        foreach (['PRE', 'APP', 'BOT'] as $marker) {
            $this->assertStringNotContainsString($marker, $html, $marker);
        }
    }
}

<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Form;

use Illuminate\View\ComponentAttributeBag;

class InputSlider extends InputGroupComponent
{
    use Traits\OldValueSupportTrait;

    /**
     * The class added to the "input-group" element when the input group has
     * associated errors.
     *
     * @var string
     */
    protected $invalidGroupClass = 'adminlte-invalid-islgroup';

    /**
     * The set of legacy 'bootstrap-slider' configuration properties that became
     * meaningless on AdminLTE v4, where the vanilla Javascript 'noUiSlider'
     * plugin is used instead. They are accepted for backward compatibility and
     * dropped before the configuration is handed over to the plugin.
     *
     * Note the legacy 'range' property is handled apart, since the same name
     * is used by the 'noUiSlider' plugin with a different meaning.
     *
     * @var array
     */
    protected $legacyCfgNoop = [
        'id', 'min', 'max', 'step', 'value', 'precision', 'orientation',
        'enabled', 'reversed', 'tooltip', 'tooltip_split',
        'tooltip_position', 'handle', 'selection', 'natural_arrow_keys',
        'ticks', 'ticks_positions', 'ticks_labels', 'ticks_snap_bounds',
        'ticks_tooltip', 'scale', 'focus', 'labelledby', 'rangeHighlights',
        'lock_to_ticks', 'formatter', 'rtl',
    ];

    /**
     * The slider configuration parameters. Array with 'key => value' pairs,
     * where the key should be an existing configuration property of the
     * 'noUiSlider' plugin. The legacy 'bootstrap-slider' properties are still
     * accepted, they are translated when possible and ignored otherwise.
     *
     * @var array
     */
    public $config;

    /**
     * The slider color. One of the available html colors.
     *
     * @var string
     */
    public $color;

    /**
     * The attributes of the DOM element that holds the slider.
     *
     * @var array
     */
    public $sliderAttributes = [];

    /**
     * Create a new component instance.
     * Note this component requires the 'NoUiSlider' plugin.
     *
     * @return void
     */
    public function __construct(
        $name, $id = null, $label = null, $igroupSize = null, $labelClass = null,
        $fgroupClass = null, $igroupClass = null, $disableFeedback = null,
        $errorKey = null, $config = [], $color = null, $enableOldSupport = null,
        $sliderAttributes = []
    ) {
        parent::__construct(
            $name, $id, $label, $igroupSize, $labelClass, $fgroupClass,
            $igroupClass, $disableFeedback, $errorKey
        );

        $this->config = is_array($config) ? $config : [];
        $this->enableOldSupport = isset($enableOldSupport);
        $this->color = $color;

        // Set a default plugin 'id' option. This is the id of the DOM element
        // that holds the slider, it's not the id of the underlying input.

        $this->config['id'] = $this->config['id'] ?? "{$this->id}-slider";

        $this->sliderAttributes = is_array($sliderAttributes)
            ? $sliderAttributes
            : [];
    }

    /**
     * Get the configuration that will be handed over to the 'noUiSlider'
     * plugin. The legacy properties are translated when possible and dropped
     * when they became meaningless.
     *
     * @return array
     */
    public function makePluginConfig()
    {
        $pluginCfg = $this->makeForwardedConfig();

        $pluginCfg += $this->makeRangeConfig();
        $pluginCfg += $this->makeHandlesConfig($pluginCfg);
        $pluginCfg += $this->makeOrientationConfig();
        $pluginCfg += $this->makeTooltipsConfig();

        return $pluginCfg;
    }

    /**
     * Gets the configuration properties that are forwarded to the plugin as
     * they are provided.
     *
     * @return array
     */
    protected function makeForwardedConfig()
    {
        $pluginCfg = [];

        foreach ($this->config as $key => $value) {
            // The 'range' key is ambiguous: on the legacy plugin it was a
            // boolean enabling a dual handle slider, while on 'noUiSlider' it
            // holds the min/max definition. Only the latter is forwarded.

            if ($key === 'range') {
                continue;
            }

            if (! in_array($key, $this->legacyCfgNoop, true)) {
                $pluginCfg[$key] = $value;
            }
        }

        if (is_array($this->config['range'] ?? null)) {
            $pluginCfg['range'] = $this->config['range'];
        }

        return $pluginCfg;
    }

    /**
     * Translates the legacy 'min', 'max' and 'step' properties into the range
     * definition required by the plugin.
     *
     * @return array
     */
    protected function makeRangeConfig()
    {
        $cfg = [
            'range' => [
                'min' => (float) ($this->config['min'] ?? 0),
                'max' => (float) ($this->config['max'] ?? 10),
            ],
        ];

        if (isset($this->config['step'])) {
            $cfg['step'] = (float) $this->config['step'];
        }

        return $cfg;
    }

    /**
     * Translates the legacy 'value' and 'range' (dual handle) properties into
     * the handles definition required by the plugin. Note the plugin expects
     * an array of booleans when there is more than one handle.
     *
     * @param  array  $pluginCfg  The configuration resolved so far
     * @return array
     */
    protected function makeHandlesConfig($pluginCfg)
    {
        $start = $pluginCfg['start'] ?? $this->makeStartValue();

        return [
            'start' => $start,
            'connect' => count((array) $start) > 1 ? [false, true, false] : 'lower',
        ];
    }

    /**
     * Translates the legacy 'orientation', 'reversed' and 'rtl' properties.
     *
     * @return array
     */
    protected function makeOrientationConfig()
    {
        $cfg = [];

        if (isset($this->config['orientation'])) {
            $cfg['orientation'] = $this->config['orientation'];
        }

        if (! empty($this->config['reversed']) || ! empty($this->config['rtl'])) {
            $cfg['direction'] = 'rtl';
        }

        return $cfg;
    }

    /**
     * Translates the legacy 'tooltip' property.
     *
     * @return array
     */
    protected function makeTooltipsConfig()
    {
        if (! isset($this->config['tooltip'])) {
            return [];
        }

        return ['tooltips' => $this->config['tooltip'] !== 'hide'];
    }

    /**
     * Get the initial value (or values) of the slider handles.
     *
     * @return array
     */
    public function makeStartValue()
    {
        $value = $this->getOldValue($this->errorKey, $this->config['value'] ?? null);
        $handles = $this->normalizeHandles($value);

        if (empty($handles)) {
            return $this->makeDefaultHandles();
        }

        return array_map('floatval', $handles);
    }

    /**
     * Normalizes the provided value into the set of slider handles. Note a
     * string holds the handles separated by commas.
     *
     * @param  mixed  $value  The value to normalize
     * @return array
     */
    protected function normalizeHandles($value)
    {
        if (is_string($value)) {
            $value = explode(',', $value);
        }

        $handles = array_map('trim', array_map('strval', (array) $value));

        return array_values(array_filter($handles, function ($item) {
            return $item !== '';
        }));
    }

    /**
     * Makes the handles to use when no value is provided. The legacy 'range'
     * property enables a slider with two handles.
     *
     * @return array
     */
    protected function makeDefaultHandles()
    {
        $range = is_array($this->config['range'] ?? null) ? $this->config['range'] : [];

        $min = (float) ($this->config['min'] ?? $range['min'] ?? 0);
        $max = (float) ($this->config['max'] ?? $range['max'] ?? 10);

        return ($this->config['range'] ?? null) === true ? [$min, $max] : [$min];
    }

    /**
     * Makes the attribute bag of the DOM element that holds the slider. The
     * plugin mutates that element, so a Livewire app needs to mark it with
     * 'wire:ignore' in order to survive a re-render.
     *
     * @return \Illuminate\View\ComponentAttributeBag
     */
    public function makeSliderAttributes()
    {
        $attrs = $this->sliderAttributes;

        if ($this->isInvalid()) {
            $attrs['aria-invalid'] = 'true';
            $attrs['aria-describedby'] = $this->makeInvalidFeedbackId();
        }

        return new ComponentAttributeBag($attrs);
    }

    /**
     * Make the class attribute for the DOM element that holds the slider.
     *
     * @return string
     */
    public function makeSliderClass()
    {
        $classes = ['adminlte-slider', 'flex-fill', 'align-self-center'];

        if (($this->config['orientation'] ?? null) === 'vertical') {
            $classes[] = 'adminlte-slider-vertical';
        }

        return implode(' ', $classes);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.form.input-slider');
    }
}

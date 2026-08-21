<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Form;

class InputSlider extends InputGroupComponent
{
    use Traits\OldValueSupportTrait;

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
     * Create a new component instance.
     * Note this component requires the 'NoUiSlider' plugin.
     *
     * @return void
     */
    public function __construct(
        $name, $id = null, $label = null, $igroupSize = null, $labelClass = null,
        $fgroupClass = null, $igroupClass = null, $disableFeedback = null,
        $errorKey = null, $config = [], $color = null, $enableOldSupport = null
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
        $pluginCfg = [];

        foreach ($this->config as $key => $value) {
            // The 'range' key is ambiguous: on the legacy plugin it was a
            // boolean enabling a dual handle slider, while on 'noUiSlider' it
            // holds the min/max definition. Only the latter is forwarded.

            if ($key === 'range') {
                if (is_array($value)) {
                    $pluginCfg['range'] = $value;
                }

                continue;
            }

            if (! in_array($key, $this->legacyCfgNoop, true)) {
                $pluginCfg[$key] = $value;
            }
        }

        // Translate the legacy 'min' and 'max' properties into the 'range'
        // option required by the plugin.

        $pluginCfg['range'] = $pluginCfg['range'] ?? [
            'min' => (float) ($this->config['min'] ?? 0),
            'max' => (float) ($this->config['max'] ?? 10),
        ];

        // Translate the legacy 'step' property.

        if (isset($this->config['step'])) {
            $pluginCfg['step'] = $pluginCfg['step'] ?? (float) $this->config['step'];
        }

        // Translate the legacy 'value' property into the 'start' option.

        $pluginCfg['start'] = $pluginCfg['start'] ?? $this->makeStartValue();

        // Translate the legacy 'range' (dual handle) property into the
        // 'connect' option. Note the plugin expects an array of booleans when
        // there is more than one handle.

        if (! isset($pluginCfg['connect'])) {
            $pluginCfg['connect'] = count((array) $pluginCfg['start']) > 1
                ? [false, true, false]
                : 'lower';
        }

        // Translate the legacy 'orientation' and 'reversed' properties.

        if (isset($this->config['orientation'])) {
            $pluginCfg['orientation'] = $pluginCfg['orientation']
                ?? $this->config['orientation'];
        }

        if (! empty($this->config['reversed']) || ! empty($this->config['rtl'])) {
            $pluginCfg['direction'] = $pluginCfg['direction'] ?? 'rtl';
        }

        // Translate the legacy 'tooltip' property.

        if (isset($this->config['tooltip'])) {
            $pluginCfg['tooltips'] = $pluginCfg['tooltips']
                ?? ($this->config['tooltip'] !== 'hide');
        }

        return $pluginCfg;
    }

    /**
     * Get the initial value (or values) of the slider handles.
     *
     * @return array
     */
    public function makeStartValue()
    {
        $value = $this->getOldValue($this->errorKey, $this->config['value'] ?? null);

        if (is_string($value)) {
            $value = explode(',', $value);
        }

        $value = array_values(array_filter(
            array_map('trim', array_map('strval', (array) $value)),
            function ($item) {
                return $item !== '';
            }
        ));

        if (empty($value)) {
            $range = is_array($this->config['range'] ?? null)
                ? $this->config['range']
                : [];

            $min = (float) ($this->config['min'] ?? $range['min'] ?? 0);
            $max = (float) ($this->config['max'] ?? $range['max'] ?? 10);
            $isDual = ($this->config['range'] ?? null) === true;

            return $isDual ? [$min, $max] : [$min];
        }

        return array_map('floatval', $value);
    }

    /**
     * Make the class attribute for the "input-group" element. Note we overwrite
     * the method of the parent class.
     *
     * @return string
     */
    public function makeInputGroupClass()
    {
        $classes = ['input-group'];

        if (isset($this->size) && in_array($this->size, ['sm', 'lg'])) {
            $classes[] = "input-group-{$this->size}";
        }

        if ($this->isInvalid()) {
            $classes[] = 'adminlte-invalid-islgroup';
        }

        if (isset($this->igroupClass)) {
            $classes[] = $this->igroupClass;
        }

        return implode(' ', $classes);
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

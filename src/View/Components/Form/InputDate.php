<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Form;

class InputDate extends InputGroupComponent
{
    use Traits\OldValueSupportTrait;

    /**
     * The default set of icons for the date picker configuration.
     *
     * @var array
     */
    protected $icons = [
        'time' => 'bi bi-clock',
        'date' => 'bi bi-calendar',
        'up' => 'bi bi-arrow-up',
        'down' => 'bi bi-arrow-down',
        'previous' => 'bi bi-chevron-left',
        'next' => 'bi bi-chevron-right',
        'today' => 'bi bi-calendar-check',
        'clear' => 'bi bi-trash',
        'close' => 'bi bi-x-lg',
    ];

    /**
     * The default set of buttons for the date picker configuration.
     *
     * @var array
     */
    protected $buttons = [
        'showClose' => true,
    ];

    /**
     * The set of legacy 'Tempus Dominus' configuration properties that became
     * meaningless on AdminLTE v4, where the vanilla Javascript 'Flatpickr'
     * plugin is used instead. They are accepted for backward compatibility and
     * dropped before the configuration is handed over to the plugin.
     *
     * @var array
     */
    protected $legacyCfgNoop = [
        'icons', 'buttons', 'collapse', 'sideBySide', 'toolbarPlacement',
        'widgetPositioning', 'widgetParent', 'useCurrent', 'calendarWeeks',
        'viewMode', 'keepOpen', 'focusOnShow', 'debug', 'allowInputToggle',
        'extraFormats', 'keepInvalid', 'ignoreReadonly', 'tooltips',
        'useStrict', 'daysOfWeekDisabled', 'stepping', 'timeZone',
    ];

    /**
     * The set of legacy 'Tempus Dominus' configuration properties that have a
     * 'Flatpickr' counterpart with a different name.
     *
     * @var array
     */
    protected $legacyCfgMap = [
        'format' => 'dateFormat',
        'defaultDate' => 'defaultDate',
        'minDate' => 'minDate',
        'maxDate' => 'maxDate',
        'disabledDates' => 'disable',
        'enabledDates' => 'enable',
        'inline' => 'inline',
    ];

    /**
     * The date picker configuration parameters. Array with 'key => value'
     * pairs, where the key should be an existing configuration property of
     * the 'Flatpickr' plugin.
     *
     * Note the legacy 'Tempus Dominus' properties are still accepted. The
     * 'icons' and 'buttons' properties are kept (with Bootstrap Icons based
     * defaults) for backward compatibility, but they are not used by the
     * 'Flatpickr' plugin.
     *
     * @var array
     */
    public $config;

    /**
     * Create a new component instance.
     * Note this component requires the 'Flatpickr' plugin.
     *
     * @return void
     */
    public function __construct(
        $name, $id = null, $label = null, $igroupSize = null, $labelClass = null,
        $fgroupClass = null, $igroupClass = null, $disableFeedback = null,
        $errorKey = null, $config = [], $enableOldSupport = null
    ) {
        parent::__construct(
            $name, $id, $label, $igroupSize, $labelClass, $fgroupClass,
            $igroupClass, $disableFeedback, $errorKey
        );

        $this->enableOldSupport = isset($enableOldSupport);
        $this->config = is_array($config) ? $config : [];

        // Setup the default plugin icons option.

        $this->config['icons'] = $this->config['icons'] ?? $this->icons;

        // Setup the default plugin buttons option.

        $this->config['buttons'] = $this->config['buttons'] ?? $this->buttons;
    }

    /**
     * Get the configuration that will be handed over to the 'Flatpickr'
     * plugin. The legacy properties are translated when possible and dropped
     * when they became meaningless.
     *
     * @return array
     */
    public function makePluginConfig()
    {
        $pluginCfg = [];

        foreach ($this->config as $key => $value) {
            if (isset($this->legacyCfgMap[$key])) {
                $pluginCfg[$this->legacyCfgMap[$key]] = $value;
            } elseif (! in_array($key, $this->legacyCfgNoop, true)) {
                $pluginCfg[$key] = $value;
            }
        }

        // The 'allowInput' option lets the user type the date manually, which
        // matches the behavior of the legacy plugin.

        $pluginCfg['allowInput'] = $pluginCfg['allowInput'] ?? true;

        return $pluginCfg;
    }

    /**
     * Make the class attribute for the input group item. Note we overwrite
     * the method of the parent class.
     *
     * @return string
     */
    public function makeItemClass()
    {
        $classes = ['form-control'];

        if ($this->isInvalid()) {
            $classes[] = 'is-invalid';
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
        return view('adminlte::components.form.input-date');
    }
}

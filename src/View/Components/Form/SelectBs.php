<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Form;

class SelectBs extends InputGroupComponent
{
    use Traits\OldValueSupportTrait;

    /**
     * The set of legacy 'bootstrap-select' configuration properties that can
     * be translated into a Tom Select configuration property. Any other legacy
     * property will be accepted and ignored.
     *
     * @var array
     */
    protected $legacyCfgMap = [
        'title' => 'placeholder',
        'noneSelectedText' => 'placeholder',
        'maxOptions' => 'maxOptions',
        'maxItems' => 'maxItems',
    ];

    /**
     * The set of legacy 'bootstrap-select' configuration properties that became
     * meaningless on AdminLTE v4 (Bootstrap 5). They are accepted for backward
     * compatibility and silently dropped.
     *
     * @var array
     */
    protected $legacyCfgNoop = [
        'style', 'styleBase', 'container', 'dropupAuto', 'header', 'hideDisabled',
        'iconBase', 'liveSearch', 'liveSearchNormalize', 'liveSearchPlaceholder',
        'liveSearchStyle', 'mobile', 'multipleSeparator', 'selectedTextFormat',
        'selectOnTab', 'showContent', 'showIcon', 'showSubtext', 'showTick',
        'size', 'tickIcon', 'width', 'windowPadding', 'virtualScroll',
        'actionsBox', 'countSelectedText', 'deselectAllText', 'selectAllText',
        'doneButton', 'doneButtonText', 'dropdownAlignRight', 'noneResultsText',
    ];

    /**
     * The underlying plugin configuration parameters. Array with 'key => value'
     * pairs. On AdminLTE v4 the legacy jQuery 'bootstrap-select' plugin was
     * replaced by the vanilla Javascript 'Tom Select' plugin (the 'TomSelect'
     * key of the plugins configuration), so the keys should be existing Tom
     * Select settings. The legacy 'bootstrap-select' properties are still
     * accepted, they are translated when possible and ignored otherwise.
     *
     * @var array
     */
    public $config;

    /**
     * Create a new component instance.
     * Note this component requires the 'TomSelect' plugin. When the plugin is
     * not available, the component gracefully degrades to a native Bootstrap 5
     * 'form-select' element.
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

        $this->config = $this->normalizeConfig(is_array($config) ? $config : []);
        $this->enableOldSupport = isset($enableOldSupport);
    }

    /**
     * Normalize the provided plugin configuration. Legacy 'bootstrap-select'
     * properties are translated to their Tom Select counterpart when such a
     * counterpart exists, and dropped when they became meaningless.
     *
     * @param  array  $config  The user provided plugin configuration
     * @return array
     */
    protected function normalizeConfig($config)
    {
        $normalized = [];

        foreach ($config as $key => $value) {
            if (isset($this->legacyCfgMap[$key])) {
                $normalized[$this->legacyCfgMap[$key]] = $value;
            } elseif (! in_array($key, $this->legacyCfgNoop, true)) {
                $normalized[$key] = $value;
            }
        }

        return $normalized;
    }

    /**
     * Make the class attribute for the input group item. Note we overwrite
     * the method of the parent class. Bootstrap 5 requires the "form-select"
     * class on the select elements.
     *
     * @return string
     */
    public function makeItemClass()
    {
        $classes = ['form-select'];

        if ($this->isInvalid()) {
            $classes[] = 'is-invalid';
        }

        if (isset($this->size) && in_array($this->size, ['sm', 'lg'])) {
            $classes[] = "form-select-{$this->size}";
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
        return view('adminlte::components.form.select-bs');
    }
}

<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Form;

use Illuminate\Support\Carbon;

class DateRange extends InputGroupComponent
{
    use Traits\OldValueSupportTrait;

    /**
     * The set of legacy 'DateRangePicker' configuration properties that became
     * meaningless on AdminLTE v4, where the vanilla Javascript 'Flatpickr'
     * plugin is used instead. They are accepted for backward compatibility and
     * dropped before the configuration is handed over to the plugin.
     *
     * @var array
     */
    protected $legacyCfgNoop = [
        'ranges', 'autoUpdateInput', 'autoApply', 'alwaysShowCalendars',
        'linkedCalendars', 'showCustomRangeLabel', 'showDropdowns',
        'showWeekNumbers', 'showISOWeekNumbers', 'opens', 'drops', 'parentEl',
        'buttonClasses', 'applyButtonClasses', 'cancelButtonClasses',
        'isInvalidDate', 'isCustomDate', 'maxSpan', 'dateLimit',
        'timePickerIncrement', 'timePickerSeconds', 'singleDatePicker',
        'startDate', 'endDate', 'timePicker', 'timePicker24Hour', 'locale',
    ];

    /**
     * The set of legacy 'DateRangePicker' configuration properties that have a
     * 'Flatpickr' counterpart with a different name.
     *
     * @var array
     */
    protected $legacyCfgMap = [
        'minDate' => 'minDate',
        'maxDate' => 'maxDate',
        'minYear' => 'minDate',
        'maxYear' => 'maxDate',
    ];

    /**
     * The date range picker configuration parameters. Array with
     * 'key => value' pairs, where the key should be an existing configuration
     * property of the 'Flatpickr' plugin. The legacy 'DateRangePicker'
     * properties are still accepted, they are translated when possible and
     * ignored otherwise.
     *
     * @var array
     */
    public $config;

    /**
     * Enables a default set of ranges option. The string value, if any, will
     * be used as the initial date range. The available values are: 'Today',
     * 'Yesterday', 'Last 7 Days', 'Last 30 Days', 'This Month' or 'Last Month'.
     *
     * Note the 'Flatpickr' plugin does not provide a predefined ranges menu,
     * so on AdminLTE v4 this property is only used to preselect the initial
     * date range.
     *
     * @var bool|string
     */
    public $enableDefaultRanges;

    /**
     * Create a new component instance.
     * Note this component requires the 'Flatpickr' plugin.
     *
     * @return void
     */
    public function __construct(
        $name, $id = null, $label = null, $igroupSize = null, $labelClass = null,
        $fgroupClass = null, $igroupClass = null, $disableFeedback = null,
        $errorKey = null, $config = [], $enableDefaultRanges = null,
        $enableOldSupport = null
    ) {
        parent::__construct(
            $name, $id, $label, $igroupSize, $labelClass, $fgroupClass,
            $igroupClass, $disableFeedback, $errorKey
        );

        $this->config = is_array($config) ? $config : [];
        $this->enableDefaultRanges = $enableDefaultRanges;
        $this->enableOldSupport = isset($enableOldSupport);
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
        $pluginCfg = $this->makeForwardedConfig();

        $pluginCfg += $this->makeModeConfig();
        $pluginCfg += $this->makeTimeConfig();
        $pluginCfg += $this->makeLocaleConfig($pluginCfg);
        $pluginCfg += $this->makeDefaultDateConfig();

        // The 'allowInput' option lets the user type the range manually, which
        // matches the behavior of the legacy plugin.

        $pluginCfg += ['allowInput' => true];

        return $pluginCfg;
    }

    /**
     * Gets the configuration properties that are forwarded to the plugin,
     * translating the ones that only changed their name.
     *
     * @return array
     */
    protected function makeForwardedConfig()
    {
        $pluginCfg = [];

        foreach ($this->config as $key => $value) {
            if (isset($this->legacyCfgMap[$key])) {
                $pluginCfg[$this->legacyCfgMap[$key]] = $value;
            } elseif (! in_array($key, $this->legacyCfgNoop, true)) {
                $pluginCfg[$key] = $value;
            }
        }

        return $pluginCfg;
    }

    /**
     * Setups the range mode. The legacy 'singleDatePicker' property is
     * honoured, so a single date can still be selected.
     *
     * @return array
     */
    protected function makeModeConfig()
    {
        return [
            'mode' => empty($this->config['singleDatePicker']) ? 'range' : 'single',
        ];
    }

    /**
     * Translates the legacy time picker related properties.
     *
     * @return array
     */
    protected function makeTimeConfig()
    {
        $cfg = [];

        if (! empty($this->config['timePicker'])) {
            $cfg['enableTime'] = true;
        }

        if (isset($this->config['timePicker24Hour'])) {
            $cfg['time_24hr'] = (bool) $this->config['timePicker24Hour'];
        }

        return $cfg;
    }

    /**
     * Translates the legacy locale related properties.
     *
     * @param  array  $pluginCfg  The configuration resolved so far
     * @return array
     */
    protected function makeLocaleConfig($pluginCfg)
    {
        $locale = $this->config['locale'] ?? [];

        if (! is_array($locale)) {
            return [];
        }

        $cfg = [];

        if (isset($locale['format'])) {
            $cfg['dateFormat'] = $locale['format'];
        }

        if (isset($locale['separator'])) {
            $current = is_array($pluginCfg['locale'] ?? null) ? $pluginCfg['locale'] : [];
            $cfg['locale'] = array_merge($current, ['rangeSeparator' => $locale['separator']]);
        }

        return $cfg;
    }

    /**
     * Setups the initial date range, when available.
     *
     * @return array
     */
    protected function makeDefaultDateConfig()
    {
        $defaultDate = $this->makeDefaultDate();

        return empty($defaultDate) ? [] : ['defaultDate' => $defaultDate];
    }

    /**
     * Get the initial date range for the plugin. The legacy 'startDate' and
     * 'endDate' properties take precedence over the 'enableDefaultRanges'
     * property.
     *
     * @return array
     */
    protected function makeDefaultDate()
    {
        $startDate = $this->config['startDate'] ?? null;
        $endDate = $this->config['endDate'] ?? null;

        if (isset($startDate) || isset($endDate)) {
            return array_values(array_filter([$startDate, $endDate]));
        }

        return $this->getDefaultRange($this->enableDefaultRanges);
    }

    /**
     * Get the dates (as ISO 8601 strings) of one of the predefined ranges.
     *
     * @param  mixed  $range  The name of the predefined range
     * @return array
     */
    protected function getDefaultRange($range)
    {
        if (! is_string($range)) {
            return [];
        }

        $resolvers = $this->getDefaultRangeResolvers();

        if (! isset($resolvers[$range])) {
            return [];
        }

        $dates = $resolvers[$range](Carbon::now());

        return array_map(function ($date) {
            return $date->format('Y-m-d');
        }, $dates);
    }

    /**
     * Gets the resolver of each supported default range. Every resolver takes
     * the current date and returns the start and the end of its range.
     *
     * @return array
     */
    protected function getDefaultRangeResolvers()
    {
        return [
            'Today' => function ($now) {
                return [$now->copy()->startOfDay(), $now->copy()->endOfDay()];
            },
            'Yesterday' => function ($now) {
                $ref = $now->copy()->subDay();

                return [$ref->copy()->startOfDay(), $ref->copy()->endOfDay()];
            },
            'Last 7 Days' => function ($now) {
                return [$now->copy()->subDays(6), $now->copy()];
            },
            'Last 30 Days' => function ($now) {
                return [$now->copy()->subDays(29), $now->copy()];
            },
            'This Month' => function ($now) {
                return [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];
            },
            'Last Month' => function ($now) {
                $ref = $now->copy()->subMonthNoOverflow();

                return [$ref->copy()->startOfMonth(), $ref->copy()->endOfMonth()];
            },
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.form.date-range');
    }
}

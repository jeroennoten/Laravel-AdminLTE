<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Form;

class TextEditor extends InputGroupComponent
{
    use Traits\OldValueSupportTrait;

    /**
     * The class added to the "input-group" element when the input group has
     * associated errors.
     *
     * @var string
     */
    protected $invalidGroupClass = 'adminlte-invalid-itegroup';

    /**
     * The default toolbar handed over to the 'Quill' plugin.
     *
     * @var array
     */
    protected $defaultToolbar = [
        ['bold', 'italic', 'underline', 'strike'],
        [['list' => 'ordered'], ['list' => 'bullet']],
        [['header' => [1, 2, 3, 4, 5, 6, false]]],
        [['color' => []], ['background' => []]],
        [['align' => []]],
        ['link', 'blockquote', 'code-block'],
        ['clean'],
    ];

    /**
     * The set of legacy 'Summernote' configuration properties that became
     * meaningless on AdminLTE v4, where the vanilla Javascript 'Quill' plugin
     * is used instead. They are accepted for backward compatibility and
     * dropped before the configuration is handed over to the plugin.
     *
     * @var array
     */
    protected $legacyCfgNoop = [
        'width', 'height', 'minHeight', 'maxHeight', 'focus', 'airMode',
        'toolbar', 'popover', 'lang', 'dialogsInBody', 'dialogsFade',
        'disableDragAndDrop', 'shortcuts', 'tabsize', 'styleTags', 'fontNames',
        'fontNamesIgnoreCheck', 'fontSizes', 'colors', 'colorsName',
        'lineHeights', 'tableClassName', 'insertTableMaxSize', 'callbacks',
        'codeviewFilter', 'codeviewIframeFilter', 'spellCheck', 'disableResize',
        'disableResizeEditor', 'followingToolbar', 'toolbarPosition',
    ];

    /**
     * The text editor configuration parameters. Array with 'key => value'
     * pairs, where the key should be an existing configuration property of the
     * 'Quill' plugin ('theme', 'modules', 'formats', 'placeholder',
     * 'readOnly', 'bounds', 'debug', etc.).
     *
     * The legacy 'Summernote' properties are still accepted. The 'height'
     * property is honoured (it setups the editor height) and the remaining
     * ones are ignored.
     *
     * @var array
     */
    public $config;

    /**
     * Create a new component instance.
     * Note this component requires the 'Quill' plugin.
     * TODO: the append/prepend addon slots are not supported.
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

        $this->config = is_array($config) ? $config : [];
        $this->enableOldSupport = isset($enableOldSupport);

        // Setup the default plugin width option. Kept for backward
        // compatibility, the property is not used by the 'Quill' plugin.

        $this->config['width'] = $this->config['width'] ?? 'inherit';
    }

    /**
     * Get the configuration that will be handed over to the 'Quill' plugin.
     * The legacy properties are dropped when they became meaningless.
     *
     * @return array
     */
    public function makePluginConfig()
    {
        $pluginCfg = [];

        foreach ($this->config as $key => $value) {
            if (! in_array($key, $this->legacyCfgNoop, true)) {
                $pluginCfg[$key] = $value;
            }
        }

        // Setup the default plugin theme.

        $pluginCfg['theme'] = $pluginCfg['theme'] ?? 'snow';

        // Setup the default plugin toolbar.

        if (! isset($pluginCfg['modules'])) {
            $pluginCfg['modules'] = ['toolbar' => $this->defaultToolbar];
        }

        return $pluginCfg;
    }

    /**
     * Get the id of the DOM element that will hold the editor.
     *
     * @return string
     */
    public function makeEditorId()
    {
        return "{$this->id}-editor";
    }

    /**
     * Get the height of the editor, when configured through the legacy
     * 'height' plugin property.
     *
     * @return string|null
     */
    public function makeEditorHeight()
    {
        $height = $this->config['height'] ?? null;

        if (is_numeric($height)) {
            return "{$height}px";
        }

        return is_string($height) ? $height : null;
    }

    /**
     * Make the class attribute for the input group item. Note we overwrite
     * the method of the parent class. The underlying textarea is hidden, it
     * only holds the value that will be submitted with the form.
     *
     * @return string
     */
    public function makeItemClass()
    {
        return 'd-none';
    }

    /**
     * Make the class attribute for the DOM element that holds the editor.
     *
     * @return string
     */
    public function makeEditorClass()
    {
        $classes = ['adminlte-text-editor', 'form-control', 'p-0'];

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
        return view('adminlte::components.form.text-editor');
    }
}

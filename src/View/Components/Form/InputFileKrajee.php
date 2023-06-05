<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Form;

use Illuminate\Support\Str;

class InputFileKrajee extends InputGroupComponent
{
    /**
     * The Krajee file input plugin configuration parameters. Array with
     * 'key => value' pairs, where the key should be an existing configuration
     * property of the Krajee file input plugin.
     *
     * @var array
     */
    public $config;

    /**
     * The plugin preset mode. Used to make specific plugin configuration for
     * some particular scenarios. The current supported set of values are:
     * 'avatar', 'minimalist'.
     *
     * @var string
     */
    public $presetMode;

    /**
     * Create a new component instance.
     * Note this component requires the Krajee 'bootstrap-fileinput' plugin.
     *
     * @return void
     */
    public function __construct(
        $name, $id = null, $label = null, $igroupSize = null, $labelClass = null,
        $fgroupClass = null, $igroupClass = null, $disableFeedback = null,
        $errorKey = null, $config = [], $presetMode = null
    ) {
        parent::__construct(
            $name, $id, $label, $igroupSize, $labelClass, $fgroupClass,
            $igroupClass, $disableFeedback, $errorKey
        );

        $this->config = is_array($config) ? $config : [];
        $this->presetMode = $presetMode;

        // Make some default configuration for the underlying plugin.

        $this->makePluginDefaultCfg();

        // Make the plugin 'inputGroupClass' configuration.

        $this->makePluginInputGroupClassCfg();

        // Get the preset mode config and merge with the current plugin config.

        $this->config = array_merge($this->config, $this->getPresetModeCfg());
    }

    /**
     * Make the class attribute for the invalid feedback block.
     *
     * @return string
     */
    public function makeInvalidFeedbackClass()
    {
        $classes = ['invalid-feedback', 'd-block'];

        if ($this->presetMode == 'avatar') {
            $classes[] = 'text-center';
        }

        return implode(' ', $classes);
    }

    /**
     * Make some default configuration for the plugin, when it's not provided
     * by the config property.
     *
     * @return void
     */
    protected function makePluginDefaultCfg()
    {
        // By default, force the plugin theme to 'Font Awesome 5'. Note this
        // requires the theme files provided by the plugin to be imported.

        if (! isset($this->config['theme'])) {
            $this->config['theme'] = 'fa5';
        }

        // By default, force the plugin language to the configured application
        // locale. However, note you will still need to import the locale
        // files provided by the plugin.

        if (! isset($this->config['language'])) {
            $this->config['language'] = config('app.locale');
        }
    }

    /**
     * Make the plugin 'inputGroupClass' configuration. These classes will be
     * appended to the 'input-group' DOM element that is internally generated
     * by the plugin.
     *
     * @return void
     */
    protected function makePluginInputGroupClassCfg()
    {
        // Use the parent method to create the input group classes, but
        // remove the 'input-group' CSS class to avoid duplication, since it's
        // already added by the underlying plugin.

        $inputGroupClasses = Str::of($this->makeInputGroupClass())
            ->replaceFirst('input-group', '')
            ->trim();

        if (empty($this->config['inputGroupClass'])) {
            $this->config['inputGroupClass'] = $inputGroupClasses;
        } else {
            $this->config['inputGroupClass'] .= " {$inputGroupClasses}";
        }
    }

    /**
     * Get the preset mode configuration.
     *
     * @return array
     */
    protected function getPresetModeCfg()
    {
        $modeCfg = [];

        // Check for valid preset mode and generate the related plugin config.

        switch ($this->presetMode) {
            case 'avatar':
                $modeCfg = $this->makeAvatarCfg();
                break;

            case 'minimalist':
                $modeCfg = $this->makeMinimalistCfg();
                break;

            default:
                break;
        }

        // Return the preset mode config.

        return $modeCfg;
    }

    /**
     * Generates the plugin configuration for an avatar image upload mode.
     *
     * @return array
     */
    protected function makeAvatarCfg()
    {
        // Setup the additional classes for the upload preview zone.

        $previewZoneClasses = ['bg-light', 'd-flex', 'justify-content-center'];

        // Setup the allowed image extensions.

        $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'tif', 'tiff'];

        // Return the configuration for the avatar mode.

        return [
            'showUpload' => false,
            'showClose' => false,
            'showCaption' => false,
            'showCancel' => false,
            'browseOnZoneClick' => true,
            'allowedFileExtensions' => $allowedImageExtensions,
            'maxFileCount' => 1,
            'previewClass' => implode(' ', $previewZoneClasses),
            'browseLabel' => '',
            'removeLabel' => '',
        ];
    }

    /**
     * Generates the plugin configuration for a minimalist style.
     *
     * @return array
     */
    protected function makeMinimalistCfg()
    {
        // Return the configuration for the avatar mode.

        return [
            'showPreview' => false,
            'browseLabel' => '',
            'removeLabel' => '',
            'uploadLabel' => '',
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.form.input-file-krajee');
    }
}

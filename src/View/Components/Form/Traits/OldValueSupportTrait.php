<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Form\Traits;

trait OldValueSupportTrait
{
    /**
     * Whether to enable the retrievement of the submitted value in case of
     * validation errors. If enabled, the submitted value will be automatically
     * shown when there is a validation error.
     *
     * @var bool
     */
    public $enableOldSupport;

    /**
     * Gets the previous submitted value for an input item. When the old value
     * support is disabled or the old value can't be found, the specified
     * default value is returned.
     *
     * @param  string  $errorKey  The key to use for look up the old value
     * @param  mixed  $default  Default value to use when there isn't old value
     * @return mixed
     */
    public function getOldValue($errorKey, $default = null)
    {
        return $this->enableOldSupport ? old($errorKey, $default) : $default;
    }
}
